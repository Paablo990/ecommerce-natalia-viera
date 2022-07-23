<?php

include_once('DBController.php');

$correo = $_POST['correo'];
$contra = $_POST['contra'];

// TODO: sacar esto de aca (validar en js)
if (existeUsuarioEnDB($correo, $contra) != "ERROR") return header('Location: ../registro.html');

crearUsuarioEnDB($correo, $contra);
header('Location: ../index.html');