<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e($title); ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <?php if(request()->has('dark')): ?>
        <link rel="stylesheet" href="/css/style_dark.css">
    <?php endif; ?>

    
</head>
<body>
    <h1 id="blog_title"><?php echo e($title); ?></h1>
    <h2 id="blog_author"><?php echo e($author); ?></h2>
    <h3 id="blog_date"><?php echo e($date); ?></h3>
    <p id="blog_content"><?php echo e($content); ?></p>
    <img id="blog_photo" src="<?php echo e($url); ?>" alt="">
    <a href="/blog/<?php echo e($faj); ?>">Világos</a>
    <a href="/blog/<?php echo e($faj); ?>/dark">Sötét</a>
</body>
</html><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/blog.blade.php ENDPATH**/ ?>