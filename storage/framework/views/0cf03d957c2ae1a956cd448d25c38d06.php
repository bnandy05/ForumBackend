<!doctype html>
<html lang="hu" data-bs-theme="dark">

<head>
    <title>Téli-Projekt</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        #edit_leiras {
            height: 150px;
        }
    </style>

</head>
<body>

    

    <header>
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark mb-4">
            <div class="container">
                <a class="navbar-brand" href="<?php echo e(route('fooldal')); ?>">Téli-Projekt</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(Request::routeIs('fooldal') ? 'active' : ''); ?>" href="<?php echo e(route('fooldal')); ?>">Főoldal</a>
                        </li>
                        <?php if(auth()->guard()->check()): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::routeIs('feltoltesoldal') ? 'active' : ''); ?>" href="<?php echo e(route('feltoltesoldal')); ?>">Feltöltés</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::routeIs('musorok') ? 'active' : ''); ?>" href="<?php echo e(route('musorok')); ?>">Műsorok</a>
                            </li>
                        <?php endif; ?>
                        <?php if(auth()->guard()->guest()): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::routeIs('login') ? 'active' : ''); ?>" href="<?php echo e(route('login')); ?>">Bejelentkezés</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::routeIs('regisztracio') ? 'active' : ''); ?>" href="<?php echo e(route('regisztracio')); ?>">Regisztráció</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <?php if(auth()->guard()->check()): ?>
                        <div class="d-flex align-items-center">
                            <a href="<?php echo e(route('profil')); ?>" class="btn btn-outline-light me-2" data-bs-title="Profil" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                <i class="bi bi-person-circle"></i>
                            </a>
                            <form action="<?php echo e(route('kijelentkezes')); ?>" method="GET" class="m-0">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-outline-danger" title="Kijelentkezés" data-bs-title="Kijelentkezés" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    
    
    
    

    <?php echo $__env->yieldContent('content'); ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/teliprojekt/layout/layout.blade.php ENDPATH**/ ?>