<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foglalások</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Foglalások</h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nap</th>
            <th>Foglaltság</th>
            <th>Foglaló</th>
            <th>Művelet</th>
        </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $foglalas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e(["Hétfő", "Kedd", "Szerda", "Csütörtök", "Péntek", "Szombat", "Vasárnap"][$index]); ?></td>
                <td><?php echo e($foglalas->foglalt); ?></td>
                <td><?php echo e($foglalas->foglalo ?? ''); ?></td>
                <td>
                    <?php if($foglalas->foglalt === 'nem'): ?>
                        <form id="foglalasForm<?php echo e($foglalas->id); ?>" method="GET" class="d-inline">
                            <input type="text" id="nev<?php echo e($foglalas->id); ?>" name="nev" placeholder="Név" required>
                            <button type="button" onclick="submitForm(<?php echo e($foglalas->id); ?>)" class="btn btn-success">Foglalás</button>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(url('/foglalastorles/' . $foglalas->id)); ?>" class="btn btn-danger">Töröl</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function submitForm(id) {
        const nevInput = document.getElementById(`nev${id}`).value;
        if (nevInput) {
            const url = `/foglalas/${id}/${nevInput}`;
            window.location.href = url;
        } else {
            alert('Kérlek, add meg a neved!');
        }
    }
</script>
</body>
</html>
<?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/foglalas.blade.php ENDPATH**/ ?>