<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>

<body>
    <p>Saliendo...</p>
    <script>
        window.localStorage.removeItem('accessToken');
        window.localStorage.removeItem('refreshToken');
        window.localStorage.removeItem('user_id');

        setTimeout(function() {
            window.location.href = 'login.php';
        }, 1000);
    </script>
</body>

</html>