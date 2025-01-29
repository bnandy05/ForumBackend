<!doctype html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista</title>
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
        table {
            border-collapse: collapse;
            width: 100%;
            border-radius: 10px;
        }

        th, td {
            border: 3px solid #0d0d0d;
            padding: 8px;
            text-align: center;
            background: #1E3E62;
        }

        th {
            background-color: #0B192C;
        }
        </style>
</head>
<body>
<div class="container">
    <?php if(count($gep)!=0): ?>
    <table>
        <tr>
            <th>Gyártó</th>
            <th>Típus</th>
            <th>RAM</th>
            <th>SSD</th>
            <th>Monitor</th>
            <th></th>
        </tr>
        <?php for($i = 0; $i < count($gep); $i++): ?>
            <tr>
                <td><?php echo e($gep[$i]->gyarto); ?></td>
                <td><?php echo e($gep[$i]->tipus); ?></td>
                <td><?php echo e($gep[$i]->ram); ?> GB</td>
                <td><?php echo e($gep[$i]->ssd); ?> GB</td>
                <?php if($gep[$i]->monitor==1): ?>
                    <td>✓</td>
                <?php else: ?>
                    <td>X</td>
                <?php endif; ?>
                <td><a class="btn btn-danger" href='torles?id=<?php echo e($gep[$i]->id); ?>' onclick="return confirm('Biztosan törölni szeretné?')">Törlés</a></td>
            </tr>
        <?php endfor; ?>
    </table>
    <?php else: ?>
        <h1 style="text-align: center">Nincs Találat! :(</h1>
    <?php endif; ?>
    <a class="btn btn-primary" style="margin: 0 auto; display: block; margin-top: 1rem; width: 7rem" href="feltoltes">Új tárgy</a>
</div>
</body>
</html>

<script>
        window.history.pushState({}, document.title, "");
</script><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/lista.blade.php ENDPATH**/ ?>