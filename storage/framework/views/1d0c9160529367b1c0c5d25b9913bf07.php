<?php $__env->startSection('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Profil adatok</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Név</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="name-input" value="<?php echo e($user['name']); ?>" readonly>
                            <button class="btn btn-outline-secondary" type="button" id="name-btn">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email cím</label>
                        <p class="form-control"><?php echo e($user['email']); ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Regisztráció dátuma</label>
                        <p class="form-control"><?php echo e($user['date']); ?></p>
                    </div>
                    <div class="text-center">
                        <a href="<?php echo e(route('valtoztatas')); ?>" class="btn btn-primary">
                            Jelszó módosítása
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Zenéim</h3>
                </div>
                <div class="card-body" id="zenecontainer" style="display: block;">
                <?php $__currentLoopData = $zenek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zene): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?php echo e(asset('uploads/zene/images/' . $zene->imagefilename)); ?>" class="img-fluid rounded-start profilzenecover" alt="<?php echo e($zene->title); ?>">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo e($zene->title); ?></h5>
                                    <p class="card-text">
                                        <strong>Előadó:</strong> <?php echo e($zene->artist); ?><br>
                                        <strong>Műfaj:</strong> <?php echo e($zene->mufaj ? ucfirst($zene->mufaj->genre) : 'Ismeretlen'); ?><br>
                                        <small><?php echo e($zene->description); ?></small>
                                    </p>
                                    <audio controls style="width: 100%;">
                                        <source src="<?php echo e(asset('uploads/zene/audio/' . $zene->audiofilename)); ?>" type="audio/mpeg">
                                        A böngésződ nem támogatja a lejátszót.
                                    </audio>
                                    <div class="mt-3">
                                        <a href="<?php echo e(route('zene.edit', $zene->id)); ?>" class="btn btn-warning btn-sm">Módosítás</a>
                                        <form action="<?php echo e(route('zene.delete', $zene->id)); ?>" method="POST" style="display: inline-block;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Biztosan törölni szeretnéd?')">Törlés</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name-input');
    const nameBtn = document.getElementById('name-btn');
    const nameError = document.getElementById('name-error');
    let isEditing = false;
    let originalName = '';

    nameBtn.addEventListener('click', function() {
        if (!isEditing) {
            // Enter edit mode
            startEditing();
        } else {
            // Save changes
            saveChanges();
        }
    });

    nameInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            if (isEditing) {
                saveChanges();
            }
        }
    });

    function startEditing() {
        isEditing = true;
        originalName = nameInput.value;
        nameInput.removeAttribute('readonly');
        nameInput.focus();
        nameBtn.innerHTML = '<i class="bi bi-check-lg"></i>';
        nameBtn.classList.remove('btn-outline-secondary');
        nameBtn.classList.add('btn-outline-success');
        
        // Add cancel button
        const cancelBtn = document.createElement('button');
        cancelBtn.className = 'btn btn-outline-danger';
        cancelBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
        cancelBtn.onclick = cancelEditing;
        nameBtn.parentNode.insertBefore(cancelBtn, nameBtn.nextSibling);
    }

    function saveChanges() {
        const newName = nameInput.value.trim();
        
        if (newName === '') {
            showError('A név nem lehet üres!');
            return;
        }

        if (newName === originalName) {
            cancelEditing();
            return;
        }

        fetch('/zene/profil/nevvaltoztatas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                name: newName
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                nameInput.value = data.name;
                exitEditMode();
                showError('');
            } else {
                showError(data.error);
            }
        })
        .catch(error => {
            showError('Hiba történt a mentés során!');
            console.error('Error:', error);
        });
    }

    function cancelEditing() {
        nameInput.value = originalName;
        exitEditMode();
        showError('');
    }

    function exitEditMode() {
        isEditing = false;
        nameInput.setAttribute('readonly', 'readonly');
        nameBtn.innerHTML = '<i class="bi bi-pencil-fill"></i>';
        nameBtn.classList.remove('btn-outline-success');
        nameBtn.classList.add('btn-outline-secondary');
        
        // Remove cancel button
        const cancelBtn = nameBtn.nextSibling;
        if (cancelBtn && cancelBtn.classList.contains('btn-outline-danger')) {
            cancelBtn.remove();
        }
    }

    function showError(message) {
        nameError.textContent = message;
        if (message) {
            nameInput.classList.add('is-invalid');
        } else {
            nameInput.classList.remove('is-invalid');
        }
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('zene.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/zene/profil.blade.php ENDPATH**/ ?>