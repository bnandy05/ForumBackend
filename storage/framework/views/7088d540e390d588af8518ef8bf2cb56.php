

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="text-center my-4" style="color: white;">Zenék Listája</h1>
    <div class="row">
        <?php $__currentLoopData = $zenek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zene): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm kartya">
                <img src="<?php echo e(asset('uploads/zene/images/' . $zene->imagefilename)); ?>" class="card-img-top zenecover" alt="<?php echo e($zene->title); ?>">
                <div class="card-body">
                    <h5 class="card-title" style="color: white;"><?php echo e($zene->title); ?></h5>
                    <p class="card-text" style="color: white;">
                        <span><strong>Feltöltő:</strong> <?php echo e($zene->user->name); ?><br></span>
                        <strong>Előadó:</strong> <?php echo e($zene->artist); ?><br>
                        <strong>Műfaj:</strong> <?php echo e(ucfirst($zene->mufaj->genre)); ?><br>
                        <strong>Leírás:</strong><small> <?php echo e($zene->description); ?></small>
                    </p>
                    <audio controls style="width: 100%;" class="lejatszo">
                        <source src="<?php echo e(asset('uploads/zene/audio/' . $zene->audiofilename)); ?>" type="audio/mpeg">
                        A böngésződ nem támogatja a lejátszót.
                    </audio>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('zene.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/zene/zenek.blade.php ENDPATH**/ ?>