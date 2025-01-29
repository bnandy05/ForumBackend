<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($data->nev); ?> részletei</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="text-center">
<div class="container">
    <img src="/uploads/<?php echo e($data->url); ?>" style="width:35%;" alt="">
    <h1><?php echo e($data->nev); ?></h1>
    <h2>
    <?php if($data->akciosar!=null): ?>
                        <s><?php echo e($data->ar); ?> Ft</s>
                        <br>
                        <?php echo e($data->akciosar); ?> Ft
                    <?php else: ?>
                        <?php echo e($data->ar); ?> Ft
    <?php endif; ?>
    </h2>
    <h5><?php echo e($data->leiras); ?></h5>
</div>
</body>
</html><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/webshopreszlet.blade.php ENDPATH**/ ?>