<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>Registro de Usuario</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #D8D8D8;
        }
        .form-container {
            max-width: 550px;
            margin: auto; 
            padding: 20px;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 0 100px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="p-3 m-0 border-0 bd-example m-0 border-0">
 
<div class="container mt-5 pt-5">
        <div class="form-container">
            <form method="post" action="" onsubmit="return validarApellidos()">
                <div class="mb-3">
                    <label for="correo" style="font-size: 30px;" class="form-label">Correo electrónico</label>
                    <p style="font-size: 15px;">introduce un correo eléctronico en el cual se mandará un código de verificación</p>
                    <input type="email" name="correo" class="form-control" id="correo" aria-describedby="emailHelp" required>
                </div>

    <div style="display: flex; justify-content: center;">
        <a class="btn btn-primary" href="Login.php">Regresar</a>
        <div style="flex: 0 1 70%"></div> 
        <a href="Reset_Password2.php" type="submit" class="btn btn-primary" >Confirmar</a>
    </div>



    
</body>
</html>
