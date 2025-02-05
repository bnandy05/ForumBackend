<!DOCTYPE html>
<html lang="hu" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sanctum Teszt - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Sanctum Teszt Dashboard</h3>
                        <form method="POST" action="<?php echo e(route('sanctum.logout')); ?>" class="m-0">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-danger">Kijelentkezés</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <strong>Sikeres bejelentkezés!</strong>
                            <p class="mb-0">Felhasználó: <?php echo e(auth()->user()->name); ?></p>
                            <p class="mb-0">Email: <?php echo e(auth()->user()->email); ?></p>
                        </div>
                        
                        <div class="mt-4">
                            <h4>Felhasználói adatok:</h4>
                            <table class="table">
                                <tr>
                                    <th>ID:</th>
                                    <td><?php echo e(Auth::user()->id); ?></td>
                                </tr>
                                <tr>
                                    <th>Név:</th>
                                    <td><?php echo e(Auth::user()->name); ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo e(Auth::user()->email); ?></td>
                                </tr>
                                <tr>
                                    <th>Sanctum Token:</th>
                                    <td style="word-break: break-all;"><?php echo e(session('api_token')); ?></td>
                                </tr>
                                <tr>
                                    <th>Sanctum valami:</th>
                                    <td style="word-break: break-all;"><?php echo e(session('user')); ?></td>
                                </tr>
                                <tr>
                                    <th>Remember token:</th>
                                    <td><?php echo e(Auth::user()->remember_token); ?></td>
                                </tr>    
                                <tr>
                                    <th>Regisztráció dátuma:</th>
                                    <td><?php echo e(Auth::user()->created_at); ?></td>
                                </tr>
                                <tr>
                                    <th>Utolsó módosítás:</th>
                                    <td><?php echo e(Auth::user()->updated_at); ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <h4>API Token:</h4>
                            <div class="p-3 rounded" style="background-color: #424242;">
                                <code><?php echo e(session('api_token')); ?></code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/sanctum-test/dashboard.blade.php ENDPATH**/ ?>