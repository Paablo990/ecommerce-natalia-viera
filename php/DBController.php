<?php

define('URL_FILE', 'data_base.txt');

function existeUsuarioEnDB($correo, $contra)
{
  $fo = fopen(URL_FILE, "r");
  $db_usuarios = explode('#', fread($fo, filesize(URL_FILE)));
  foreach ($db_usuarios as $db_usuario) {
    $datos = explode(':', $db_usuario);

    $db_correo = $datos[0];
    $db_contra = $datos[1];
    $db_rango = $datos[2];

    if ($db_correo == $correo && $db_contra == $contra) {
      fclose($fo);
      return $db_rango;
    }
  }

  fclose($fo);
  return 'ERROR';
}

function crearUsuarioEnDB($correo, $contra)
{
  $fo = fopen(URL_FILE, "a");
  fwrite($fo, "#" . $correo . ":" . $contra . ":CLIENTE");
}