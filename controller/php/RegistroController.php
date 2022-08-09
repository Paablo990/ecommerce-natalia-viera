<?php

include_once('DBController.php');
include_once('Validaciones.php');

$correo = $_POST['correo'];
$contra = $_POST['contra'];

// TODO: validar en js
if (inputsVacios(array($correo, $contra))) return header('Location: ../registro.html');

// TODO: sacar esto de aca (validar en js)
if (existeUsuarioEnDB($correo, $contra) != "ERROR") return header('Location: ../registro.html');

crearUsuarioEnDB($correo, $contra);
header('Location: ../prueba/cliente.html');