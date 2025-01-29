

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-center">
        <form action="<?php echo e(route('feltoltes')); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate style="width: 50%">
            <?php echo csrf_field(); ?>
            <h1 class="mb-4 text-center">Zene Feltöltése</h1>

            <!-- Cím és Előadó -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Cím</label>
                    <input type="text" class="form-control" id="title" name="title" maxlength="50" required>
                    <div class="invalid-feedback">Kérlek add meg a címét!</div>
                </div>
                <div class="col-md-6">
                    <label for="artist" class="form-label">Előadó</label>
                    <input type="text" class="form-control" id="artist" name="artist" maxlength="50" required>
                    <div class="invalid-feedback">Kérlek add meg az előadót!</div>
                </div>
            </div>

            <!-- Leírás és Műfaj -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="description" class="form-label">Leírás</label>
                    <input type="text" class="form-control" id="description" name="description" maxlength="255">
                </div>
                <div class="col-md-6">
                    <label for="genreid" class="form-label">Műfaj</label>
                    <select class="form-select" id="genreid" name="genreid" required>
                        <option value="" selected>Válassz...</option>
                        <?php $__currentLoopData = $genres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $genre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($genre->id); ?>"><?php echo e(ucfirst($genre->genre)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="invalid-feedback">Kérlek válassz egy műfajt!</div>
                </div>
            </div>

            <!-- Zenei fájl és Borítókép -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="audiofile" class="form-label">Zenei Fájl</label>
                    <input type="file" class="form-control" id="audiofile" name="audiofile" accept="audio/*" required>
                    <div class="invalid-feedback">Kérlek tölts fel egy zenei fájlt!</div>
                </div>
                <div class="col-md-6">
                    <label for="imagefile" class="form-label">Borítókép</label>
                    <input type="file" class="form-control" id="imagefile" name="imagefile" accept="image/*" required>
                    <div class="invalid-feedback">Kérlek tölts fel egy borítóképet!</div>
                </div>
            </div>

            <!-- Feltöltés gomb -->
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Feltöltés</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('zene.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/zene/feltoltes.blade.php ENDPATH**/ ?>