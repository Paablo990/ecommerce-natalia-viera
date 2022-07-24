<?php

include_once('DBController.php');
include_once('Validaciones.php');

$correo = $_POST['correo'];
$contra = $_POST['contra'];

// TODO: sacar esto de aca (validar en js)
if (inputsVacios(array($correo, $contra))) return header('Location: ../login.html');

$rango = existeUsuarioEnDB($correo, $contra);

// TODO: sacar esto de aca (validar en js)
if ($rango == 'ERROR') return header('Location: ../login.html');

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
    header('Location: ../prueba/cliente.html');
    break;
}