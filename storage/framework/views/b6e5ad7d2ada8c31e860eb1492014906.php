<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buliding</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS for window colors */
        .window-occupied {
            background-color: red;
        }
        .window-available {
            background-color: green;
        }
        .window {
            width: 100%;
            height: 50px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
<div class="container building mt-4">

<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?>

    <?php
        $roomsByFloor = $data->groupBy('emelet');
    ?>

    <?php $__currentLoopData = $roomsByFloor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floorRooms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="floor row">
        <?php $__currentLoopData = $floorRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($floorRooms->count() == 2 && $index == 0): ?> 
                <div class="col-md-3">
                    <div class="window 
                        <?php echo e($room->allapot === 'Foglalt' ? 'window-occupied' : 'window-available'); ?>">
                    </div>
                </div>
            <?php elseif($floorRooms->count() == 2 && $index == 1): ?>
                <div class="col-md-6"></div>
                <div class="col-md-3">
                    <div class="window 
                        <?php echo e($room->allapot === 'Foglalt' ? 'window-occupied' : 'window-available'); ?>">
                    </div>
                </div>
            <?php else: ?>
                <div class="col-md-3">
                    <div class="window 
                        <?php echo e($room->allapot === 'Foglalt' ? 'window-occupied' : 'window-available'); ?>">
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


    <div class="controls">
        <div class="form-group">
            <label for="floorSelect">Emelet</label>
            <select class="form-control" id="floorSelect" onchange="updateApartmentOptions()">
                <option value="0">FSZ</option>
                <option value="1">1. emelet</option>
                <option value="2">2. emelet</option>
                <option value="3">3. emelet</option>
                <option value="4">4. emelet</option>
                <option value="5">5. emelet</option>
            </select>
        </div>

        <div class="form-group">
            <label for="apartmentSelect">Lakás</label>
            <select class="form-control" id="apartmentSelect" onchange="updateFloorOptions()">
                <option value="4">1</option>
                <option value="3" disabled>2</option>
                <option value="2" disabled>3</option>
                <option value="1">4</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-3">
                <button class="btn btn-primary" onclick="uploadClick()" >Foglalás</button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-secondary" onclick="deleteClick()">Törlés</button>
            </div>
        </div>
    </div>

    <script>
        function updateApartmentOptions() {
            const floorSelect = document.getElementById("floorSelect");
            const apartmentSelect = document.getElementById("apartmentSelect");

            for (let i = 0; i < apartmentSelect.options.length; i++) {
                apartmentSelect.options[i].disabled = false;
            }

            if (floorSelect.value === "0" || floorSelect.value === "5") {
                apartmentSelect.options[1].disabled = true;
                apartmentSelect.options[2].disabled = true;
            }
        }

        function updateApartmentOptions() {
            const floorSelect = document.getElementById("floorSelect");
            const apartmentSelect = document.getElementById("apartmentSelect");

            for (let i = 0; i < apartmentSelect.options.length; i++) {
                apartmentSelect.options[i].disabled = false;
            }

            if (floorSelect.value === "0" || floorSelect.value === "5") {
                apartmentSelect.options[0].selected = true;
                apartmentSelect.options[1].disabled = true;
                apartmentSelect.options[2].disabled = true;
            }
        }

        function uploadClick()
        {
            const floor = document.getElementById("floorSelect").value;
            const room = document.getElementById("apartmentSelect").value;
            window.location.replace("/szalloda/foglal/"+floor+"/"+room);
        }
        function deleteClick()
        {
            const floor = document.getElementById("floorSelect").value;
            const room = document.getElementById("apartmentSelect").value;
            window.location.replace("/szalloda/torol/"+floor+"/"+room);
        }
    </script>
</body>
</html>
<?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/szalloda.blade.php ENDPATH**/ ?>