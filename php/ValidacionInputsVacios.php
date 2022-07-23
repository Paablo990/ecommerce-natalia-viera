<?php

$_INPUTS = json_decode(file_get_contents('php://input'), true);

$inputsVacios = [];

foreach ($_INPUTS as $id => $valor) {
  if ($valor == '') $inputsVacios[$id] = "VACIO";
}

echo json_encode($inputsVacios);