<?php $__env->startSection('content'); ?>

<div class="container position-absolute top-50 start-50 translate-middle">
    <h1 class="text-center text-light mb-4">Bejelentkezés</h1>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="m-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-dark p-4 rounded shadow">
        <form action="<?php echo e(route('bejelentkezes')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail cím</label>
                <input type="email" class="form-control" id="email" name="email" maxlength="255" placeholder="Írd be az e-mail címedet">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Jelszó</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Írd be a jelszavad">
                    <button class="btn btn-outline-secondary" type="button" id="password-visibility-toggle">
                        <i class="bi bi-eye-slash" id="password-visibility-icon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Belépés</button>
        </form>
        <div class="text-center mt-3">
            <a href="<?php echo e(route('password.request')); ?>" class="text-light text-decoration-none">Elfelejtett jelszó?</a>
        </div>
    </div>
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

<?php echo $__env->make('zene.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/zene/login.blade.php ENDPATH**/ ?>