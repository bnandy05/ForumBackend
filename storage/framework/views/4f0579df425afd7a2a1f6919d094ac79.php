<?php $__env->startSection('content'); ?>

    <div class="container position-absolute top-50 start-50 translate-middle">
        <h1 class="text-center">Regisztráció</h1>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('regisztralas')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="name" class="form-label">Név</label>
                <input type="text" class="form-control" id="name" name="name" maxlength=255>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail cím</label>
                <input type="email" class="form-control" id="email" name="email" maxlength=255>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password">
                    <button class="btn btn-outline-secondary" type="button" id="password-visibility-toggle">
                        <i class="bi bi-eye-slash" id="password-visibility-icon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Regisztráció</button>
        </form>
        
    
    </div>



<script>
    const passwordInput = document.getElementById('password');
    const passwordVisibilityToggle = document.getElementById('password-visibility-toggle');
    const passwordVisibilityIcon = document.getElementById('password-visibility-icon');

    passwordVisibilityToggle.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordVisibilityIcon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            passwordInput.type = 'password';
            passwordVisibilityIcon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    });
</script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('zene.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/zene/regisztracio.blade.php ENDPATH**/ ?>