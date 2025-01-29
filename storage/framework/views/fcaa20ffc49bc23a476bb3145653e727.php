<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termék hozzáadása</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .required::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="text-center mb-4">Termék hozzáadása</h2>

    <?php if(session('success')): ?>
    <div style="color: green">
        <?php echo e(session('success')); ?>

    </div>
    <?php elseif(session('error')): ?>
    <div style="color: red">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('feltoltes')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label for="name" class="form-label required">Termék neve:</label>
            <input type="text" id="name" name="name" class="form-control" maxlength="50" required>
            <div class="invalid-feedback">
                Kérlek add meg a termék nevét (max. 50 karakter).
            </div>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label required">Ár:</label>
            <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required>
            <div class="invalid-feedback">
                Kérlek add meg az árat!
            </div>
        </div>

        <div class="mb-3">
            <label for="discount_price" class="form-label">Akciós ár:</label>
            <input type="number" id="discount_price" name="discount_price" class="form-control" step="0.01" min="0">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label required">Leírás:</label>
            <textarea id="description" name="description" class="form-control" maxlength="255" required></textarea>
            <div class="form-text">Max. 255 karakter.</div>
            <div class="invalid-feedback">
                Kérlek add meg a leírást (max. 255 karakter).
            </div>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label required">Termékkép feltöltése:</label>
            <input type="file" id="image" name="file" class="form-control" accept="image/*" required>
            <div class="invalid-feedback">
                Kérlek tölts fel egy képet a termékről!
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Beküldés</button>
    </form>
</div>

</body>
</html>
<?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/webshopfeltolt.blade.php ENDPATH**/ ?>