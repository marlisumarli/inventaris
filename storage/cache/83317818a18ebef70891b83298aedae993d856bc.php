
<?php $__env->startSection('content'); ?>
    <div class="mb-4">
        <h3>SCHEDULE</h3>
    </div>
    <div class="row">
        <?php $__currentLoopData = $hanger_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hanger_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php ($dateNow = new DateTime('now', new DateTimeZone('Asia/Jakarta'))); ?>


            <div class="container col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="card rounded-3 shadow-lg">
                    <div class="card-header">
                        <h5 class="card-title"># <?php echo e(strtoupper($hanger_type->getId())); ?></h5>
                    </div>

                    <?php if($supply_schedule->findById(strtolower($dateNow->format('YF').'-'.$hanger_type->getId())) !== null): ?>
                        <?php ($schedule = $supply_schedule->findById(strtolower($dateNow->format('YF').'-'.$hanger_type->getId()))->getMonth()); ?>
                        <div class="card-body d-flex text-center p-3">
                            <div class="mx-auto">
                                <span>Schedule saat ini</span><br>
                                <span class="badge text-bg-success"><?php echo e(DateTime::createFromFormat('!m', $schedule)->format('F')); ?></span>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a class="small" href="/admin/schedule/<?php echo e($hanger_type->getId()); ?>/create">
                                Lihat Jadwal
                            </a>
                        </div>

                    <?php else: ?>
                        <div class="card-body d-flex text-center p-3">
                            <div class="mx-auto">

                                Bulan sekarang belum dibuat
                                <span class="badge text-bg-warning"><?php echo e($dateNow->format('F')); ?></span>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a class="small" href="/admin/schedule/<?php echo e($hanger_type->getId()); ?>/create">
                                Buat Jadwal
                            </a>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Admin/Layout/main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hanger-management-pt-indospray\app\View/Admin/Schedule/index.blade.php ENDPATH**/ ?>