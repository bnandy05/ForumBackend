<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
    <div class="row">
    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $adat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4 mb-4">
            <div class="card" style="width: 18rem; height: 25rem;">
                <img src="/uploads/<?php echo e($adat->url); ?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($adat->nev); ?></h5>
                    <h5 class="card-text">
                        <?php if($adat->akciosar != null): ?>
                            <s><?php echo e($adat->ar); ?> Ft</s>
                            <br>
                            <?php echo e($adat->akciosar); ?> Ft
                        <?php else: ?>
                            <?php echo e($adat->ar); ?> Ft
                        <?php endif; ?>
                    </h5>
                    <p class="card-text"><?php echo e($adat->leiras); ?></p>
                    <a href="/webaruhaz/reszletek/<?php echo e($adat->id); ?>" class="btn btn-primary">Részletek</a>
                </div>
            </div>
        </div>
        <?php if(($index + 1) % 3 == 0): ?>
            </div><div class="row">
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="row">
        <div class="col-md-12 text-center">
            <a href="/termek/feltoltes" class="btn btn-success">Új feltöltés</a>
        </div>
    </div>
</div>

</div>
</body>
</html><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/webaruhaz.blade.php ENDPATH**/ ?>