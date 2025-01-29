<!doctype html>
<html lang="hu" data-bs-theme="dark">

<head>
    <title>Zene Megosztó</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        
    body {
        background-color: #2e2e2e !important; /* Dark gray background */
        color: #8a2be2  !important;
    }

    .navbar {
        background-color: #3a3a3a !important;
        border-bottom: 2px solid #8a2be2 !important;
    }

    .navbar-brand,
    .nav-link {
        color: #d3d3d3 !important;
    }

    .nav-link.active {
        color: #8a2be2 !important;
        font-weight: bold !important;
    }

    .navbar-toggler {
        border-color: #b19cd9 !important;
    }

    .navbar-toggler-icon {
        background-image: linear-gradient(#b19cd9, #8a2be2) !important;
    }

    .btn-outline-light {
        border-color: #b19cd9 !important;
        color: #d3d3d3 !important;
    }

    .btn-outline-light:hover {
        background-color: #8a2be2 !important;
        color: #fff !important;
    }

    .btn-outline-danger {
        border-color: #ff4d4d !important;
        color: #ff4d4d !important;
    }

    .btn-outline-danger:hover {
        background-color: #ff4d4d !important;
        color: #fff !important;
    }

    /* Input fields */
    input, select, textarea {
        background-color: #333 !important; /* Dark background for input */
        border: 2px solid #8a2be2 !important; /* Purple border */
        color: white !important; /* White text */
        padding: 10px !important;
        border-radius: 5px !important;
        width: 100% !important;
        margin-bottom: 10px !important;
    }

    input:focus, select:focus, textarea:focus {
        outline: none !important;
        box-shadow: 0 0 5px rgba(138, 43, 226, 0.7) !important;
    }

    /* Button styling */
    button, .btn {
        background-color: #8a2be2 !important;
        border: 1px solid #8a2be2 !important;
        color: white !important;
        padding: 10px 20px !important;
        border-radius: 5px !important;
        cursor: pointer !important;
        font-size: 16px !important;
        text-align: center !important;
    }

    button:hover, .btn:hover {
        background-color: #6a1b9a !important;
        border-color: #6a1b9a !important;
    }

    #edit_leiras {
        height: 150px !important;
    }
    
    .zenecover{
        width: 100%; 
        height: 26rem;
        margin: 0 auto;
        display: block;
    }

    .profilzenecover{
        height:18rem;
        width: 18rem;
    }

    .szurke{
        color:grey;
    }

.input-group > button {
    margin-left: 1rem;
    margin-right: 1rem;
}
</style>


</head>

<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(Request::routeIs('fooldal') ? 'active' : ''); ?>" href="<?php echo e(route('fooldal')); ?>">Főoldal</a>
                    </li>
                    <?php if(auth()->guard()->check()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(Request::routeIs('feltoltesoldal') ? 'active' : ''); ?>" href="<?php echo e(route('feltoltesoldal')); ?>">Feltöltés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(Request::routeIs('zenek') ? 'active' : ''); ?>" href="<?php echo e(route('zenek')); ?>">Zenék</a>
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
                            <i class="bi bi-door-open"></i>
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

</html>
<?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/zene/layout/layout.blade.php ENDPATH**/ ?>