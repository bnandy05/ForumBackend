<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <title>A szavazás eredménye</title>
    <style>
        table {
            border-collapse: collapse;
        }
        th, td {
            border-collapse: collapse;
            padding: 5px;
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <h1>A szavazás eredménye</h1>
    <div id="tablazat"></div>
</body>
</html>

<script>
    function HTTPRequest(url, callback)
    {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (callback != null) callback(this.responseText)
            };
        };
        xhttp.open("GET", url, true);
        xhttp.send();
    }

    function Frissit()
    {
        HTTPRequest('/szavazat-tablazat', function (response) {
            document.getElementById('tablazat').innerHTML = response;
        });
    }

    Frissit();

    setInterval(Frissit, 1000);

</script>

<?php /**PATH /var/www/vhosts/moriczcloud.hu/berenandor.moriczcloud.hu/resources/views/szavazas.blade.php ENDPATH**/ ?>