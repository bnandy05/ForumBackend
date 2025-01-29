<?php $__env->startSection('content'); ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Jelszó Változtatás</h4>
                    </div>

                    <div class="card-body">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if(session('status')): ?>
                            <div class="alert alert-success">
                                <?php echo e(session('status')); ?>

                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(url('/zene/jelszo-valtoztatas')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Jelenlegi Jelszó</label>
                                <div class="input-group">
                                    <input id="current_password" type="password" class="form-control"
                                        name="current_password" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('current_password')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">Új Jelszó</label>
                                <div class="input-group">
                                    <input id="new_password" type="password" class="form-control" name="new_password"
                                        required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('new_password')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Új Jelszó Megerősítése</label>
                                <div class="input-group">
                                    <input id="new_password_confirmation" type="password" class="form-control"
                                        name="new_password_confirmation" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('new_password_confirmation')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-key"></i> Jelszó Módosítása
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const button = input.nextElementSibling;
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>

<?php echo $__env->make('zene.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/zene/jelszo-valtoztatas.blade.php ENDPATH**/ ?>