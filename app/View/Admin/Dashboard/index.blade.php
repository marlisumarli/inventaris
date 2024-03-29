@extends('Admin/Layout/main')
@section('content')
    <div class="mb-4">
        <h4>Dashboard</h4>
    </div>

    <div class="d-flex justify-content-center">
        <div class="col-lg-5 col-md-12 col-sm-12 mb-3">

            <div class="card rounded-3">
                <div class="card-body py-2 d-flex shadow-lg">
                    <div class="avatar my-auto"
                         data-label="{{$full_name}}"></div>
                    <div class="mx-3">
                        <span class="fw-bold">Selamat Datang, {{$session->getFullName()}} 👋</span>
                        <br>
                        <a class="btn-link text-dark text-decoration-none" href="/admin/user/logout"
                           id="logout">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        @foreach($hanger_types as $hanger_type)

            @if($supply_schedule->findById(strtolower($dateNow->format('YF').'-'.$hanger_type->getId())) !== null)

                @php
                    $schedule = $supply_schedule->findById(strtolower($dateNow->format('YF').'-'.$hanger_type->getId()));

                        $result = ['m1' => [], 'm2' => [], 'm3' => [], 'm4' => [], 'm5' => []];

                           foreach($schedule_weeks->findScheduleSupplyId($schedule->getId()) as $sch_week){
                               if ($sch_week->getMId() == 'M1'){
                                   $result['m1'][] = $sch_week->getMId();
                               }elseif ($sch_week->getMId() == 'M2'){
                                   $result['m2'][] = $sch_week->getMId();
                               }elseif ($sch_week->getMId() == 'M3'){
                                   $result['m3'][] = $sch_week->getMId();
                               }elseif ($sch_week->getMId() == 'M4'){
                                   $result['m4'][] = $sch_week->getMId();
                               }elseif ($sch_week->getMId() == 'M5'){
                                   $result['m5'][] = $sch_week->getMId();
                               }
                           }
                @endphp

                <div class="col-lg-6 col-md-12 mb-3">
                    <div class="card shadow-lg mb-2">
                        <div class="card-header d-flex">
                            <span class="card-title">Laporan {{strtoupper($hanger_type->getId())}} Bulan Ini</span>
                            <a href="/admin/supply-data/{{$hanger_type->getId()}}/{{$schedule->getId()}}/export"
                               class="text-success btn-sm py-0 ms-auto">
                                Export To Excel
                            </a>
                        </div>
                        <div class="card-body overflow-scroll">
                            <table class="table table-bordered text-center">
                                <thead>
                                <tr>
                                    <th colspan="{{count($result['m1'])}}" scope="col">M1</th>
                                    <th colspan="{{count($result['m2'])}}" scope="col">M2</th>
                                    <th colspan="{{count($result['m3'])}}" scope="col">M3</th>
                                    <th colspan="{{count($result['m4'])}}" scope="col">M4</th>
                                    <th colspan="{{count($result['m5'])}}" scope="col">M5</th>
                                </tr>
                                </thead>

                                <tbody class="table-group-divider">
                                <tr>

                                    @foreach($schedule_weeks->findScheduleSupplyId($schedule->getId()) as $sch_week)
                                        @php($dateTime = new DateTime($sch_week->getDate()))
                                        <td>
                                            <div class="card border-0">
                                                <div class="card-body p-0">
                                                    <span>{{$dateTime->format('d/m/Y')}}</span>
                                                </div>
                                                <span class="position-absolute top-100 start-100 translate-middle rounded-circle">

                                                    @if($dateNow->format('Y-m-d') >= $sch_week->getDate() && $sch_week->getIsDone() == null)
                                                        <i class="fa-solid fa-question text-warning"></i>
                                                    @elseif($dateNow->format('Y-m-d') <= $sch_week->getDate() && $sch_week->getIsDone() == null)
                                                        <i class="fa-regular fa-clock text-secondary"></i>
                                                    @else
                                                        <i class="fa-solid fa-check text-success"></i>

                                                    @endif

                                                </span>
                                            </div>
                                        </td>

                                    @endforeach

                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @endif
        @endforeach
    </div>
@endsection