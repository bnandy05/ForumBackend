<?php $__env->startSection('content'); ?>
    <div class="container position-absolute top-50 start-50 translate-middle">
        <h1 class="text-center">Elfelejtett jelszó</h1>

        <?php if(session('status')): ?>
            <div class="alert alert-success" role="alert">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div>
            <form method="POST" action="<?php echo e(route('password.email')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="email" class="form-label">Email cím</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo e(old('email')); ?>" required>
                    <div class="form-text">Add meg az email címed, és küldünk egy új jelszót.</div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Új jelszó kérése
                </button>
            </form>
            <a href="<?php echo e(route('login')); ?>" class="mt-3 d-inline-block">Vissza a bejelentkezéshez</a>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('zene.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/zene/elfelejtett-jelszo.blade.php ENDPATH**/ ?>