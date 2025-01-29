<!doctype html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CSV Felvitel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body{
            background: #0d0d0d;
            color: whitesmoke;
            font-family: "Roboto", sans-serif;
            font-weight: 700;
            font-style: normal;

        }
        .btn{
            transition: 0.25s; !important;
            font-family: "Roboto", sans-serif;
            font-weight: 700;
            font-style: normal;
        }

        .btn-primary{
            background-color: #1E3E62; !important;
            border: 1px solid #1E3E62; !important;

        }

        .btn-primary:hover{
            background-color: #0B192C; !important;
            border: 1px solid #0B192C; !important;
        }

        #csv{
            background-color: #1E3E62;
            border-radius: 5px;
            border: 1px solid white;
            color: whitesmoke;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <h1 style="text-align: center">CSV</h1>
        <form action="<?php echo e(route('form.submit')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <textarea style="margin: 0 auto; display: block" name="csv" id="csv" rows="10" cols="50"></textarea>
    </div>
    <div class="row" style="margin-top: 1rem">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary" style="margin: 0 auto; display: block">Küldés</button>
        </div>
        <div class="col-md-4"></div>
        </form>
    </div>
</div>
</body>
</html><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/csvfelvetel.blade.php ENDPATH**/ ?>