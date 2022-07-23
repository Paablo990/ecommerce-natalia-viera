<?php

include_once('DBController.php');

$correo = $_POST['correo'];
$contra = $_POST['contra'];

$rango = existeUsuarioEnDB($correo, $contra);

// TODO: sacar esto de aca (validar en js)
if ($rango == 'NO EXISTE') header('Location: ../login.html');

switch ($rango) {
  case 'JEFE':
    header('Location: ../prueba/jefe.html');
    break;
  case 'VENDEDOR':
    header('Location: ../prueba/vendedor.html');
    break;
  case 'COMPRADOR':
    header('Location: ../prueba/comprador.html');
    break;
  default:
    header('Location: ../index.html');
    break;
}