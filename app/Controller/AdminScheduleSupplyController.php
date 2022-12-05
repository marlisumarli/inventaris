<?php

namespace Subjig\Report\Controller;

use Subjig\Report\App\Util;
use Subjig\Report\App\View;
use Subjig\Report\Config\Database;
use Subjig\Report\HTTP\Request\ScheduleRequest;
use Subjig\Report\HTTP\Request\SupplyRequest;
use Subjig\Report\Repository\HangerTypeRepository;
use Subjig\Report\Repository\PeriodRepository;
use Subjig\Report\Repository\ScheduleWeekRepository;
use Subjig\Report\Repository\SessionRepository;
use Subjig\Report\Repository\SupplyRepository;
use Subjig\Report\Repository\SupplyScheduleRepository;
use Subjig\Report\Repository\UserRepository;
use Subjig\Report\Service\ScheduleWeekService;
use Subjig\Report\Service\SessionService;
use Subjig\Report\Service\SupplyScheduleService;
use Subjig\Report\Service\SupplyService;

class AdminScheduleSupplyController
{
    private ScheduleWeekService $scheduleWeekService;
    private SupplyScheduleService $scheduleSupplyService;
    private ScheduleWeekRepository $scheduleWeekRepository;
    private SupplyScheduleRepository $scheduleSupplyRepository;
    private HangerTypeRepository $hangerTypeRepository;
    private SupplyRepository $supplyRepository;
    private SupplyService $supplyService;
    private SessionService $sessionService;
    private PeriodRepository $periodRepository;

    public function __construct()
    {
        $connection = Database::getConnection();

        $userRepository = new UserRepository($connection);
        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);

        $this->scheduleSupplyRepository = new SupplyScheduleRepository($connection);
        $this->scheduleSupplyService = new SupplyScheduleService($this->scheduleSupplyRepository);

        $this->scheduleWeekRepository = new ScheduleWeekRepository($connection);
        $this->scheduleWeekService = new ScheduleWeekService($this->scheduleWeekRepository);

        $this->hangerTypeRepository = new HangerTypeRepository($connection);

        $this->periodRepository = new PeriodRepository($connection);

        $this->supplyRepository = new SupplyRepository($connection);
        $this->supplyService = new SupplyService($this->supplyRepository);

    }

    public function index()
    {
        $model = [
            'full_name' => Util::nameSplitter($this->sessionService->current()->getFullName()),
            'Schedule' => 'active bg-warning',
            'Title' => 'Admin | Schedule',
            'hanger_types' => $this->hangerTypeRepository->findAll(),
            'supply_schedule' => $this->scheduleSupplyRepository,
        ];
        View::render('Admin/ScheduleSupply/index', compact('model'));
    }

    public function create(string $type)
    {
        $model = [
            'Schedule' => 'active bg-warning',
            'Title' => "Admin | Schedule $type",
            'full_name' => Util::nameSplitter($this->sessionService->current()->getFullName()),
            'periods' => $this->periodRepository->findAll(),
            'schedules' => $this->scheduleSupplyRepository->findAll($type),
            'schedule_weeks' => $this->scheduleWeekRepository,
            'type' => $type,
            'search' => $_GET['search'] ?? ''
        ];
        View::render('Admin/ScheduleSupply/create', compact('model'));

    }

    public function postCreate(string $type)
    {
        if (isset($_POST['submit'])) {
            $requestSSS = new ScheduleRequest();
            $requestSSS->hangerTypeId = $type;
            $responseSSS = $this->scheduleSupplyService->requestCreate($requestSSS);

            $i = 1;
            while ($i <= count($_POST)) {
                if (isset($_POST["date-m$i"])) {
                    for ($j = 0; $j < count($_POST["date-m$i"]); $j++) {
                        $requestSSW = new ScheduleRequest();
                        $requestSSW->supplyScheduleId = $responseSSS->supplySchedule->getId();
                        $requestSSW->scheduleDate = $_POST["date-m$i"][ $j ];
                        $requestSSW->hangerTypeId = $type;
                        $requestSSW->mId = "M$i";
                        $responseSSW = $this->scheduleWeekService->requestCreate($requestSSW);

                        $requestS = new SupplyRequest();
                        $requestS->hangerTypeId = $type;
                        $requestS->scheduleSupplyId = $responseSSW->scheduleWeek->getId();
                        $this->supplyService->requestCreate($requestS);
                    }
                }
                $i++;
            }

            $model = [
                'success' => "/admin/schedule/$type/create"
            ];
            View::render('Admin/ScheduleSupply/create', compact('model'));
        }
    }

    public function delete(string $id)
    {
        $type = $this->scheduleSupplyRepository->findById($id)->getHangerTypeId();
        $this->scheduleSupplyRepository->deleteById($id);
        $model = [
            'success' => "/admin/schedule/$type/create"
        ];
        View::render('Admin/ScheduleSupply/delete', compact('model'));
    }
}