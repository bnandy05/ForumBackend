<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php if(isset($allat)): ?>
    <p>Állat: <?php echo e($allat); ?></p>
    <p>Számítás: <?php echo e($szamitas); ?></p>
    <?php endif; ?>

    <?php if(isset($szin)): ?>
    <p>A megadott szín <?php echo e($szin); ?></p>
    <?php endif; ?>

    <?php if(isset($valtozat)): ?>
    <?php if($valtozat=="es"): ?>
    <img src="https://cenex.hu/image/cache/Konzol/kontroller-feher-1200x1200.jpg" alt="">
    <?php elseif($valtozat=="nelkuli"): ?>
    <img src="https://s13emagst.akamaized.net/products/40736/40735883/images/res_d6d0e620f9770f30e2bb11809f335576.jpg" alt="">
    <?php else: ?>
    <p>nincs ilyen konti ocsi</p>
    <?php endif; ?>
    <?php endif; ?>
</body>
</html><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/xboxteszt.blade.php ENDPATH**/ ?>