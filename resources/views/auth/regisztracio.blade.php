<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Regisztráció</h1>
    <form action="{{route('regisztralas')}}" method="post">
        @csrf
        <p>Név</p>
        <input type="text" maxlength="255" name="name">
        <p>E-Mail cím</p>
        <input type="email" name="email" maxlength="255">
        <p>Jelszó</p>
        <input type="password" name="password">
        <br>
        <input type="submit" value="Regisztráció">
    </form>
</body>
</html>