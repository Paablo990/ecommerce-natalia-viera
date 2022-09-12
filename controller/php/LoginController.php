<?php

require_once __DIR__ . "/../../model/entidades/database/Database.php";
require_once __DIR__ . "/../../model/entidades/administradores/RepoAdministradores.php";

$db = new Database();
$ra = new RepoAdministradores();

$correo = $_POST['correo'];
$contra = $_POST['contra'];

$query = "SELECT contra, id_usuario FROM USUARIOS WHERE correo=?";

$resultado = $db->queryWithParams(
  $query,
  [$correo],
  "s"
)[0] ?? null;

if ($resultado == null) {
  header('Location: ../../view/login.html');
}
$id = $resultado["id_usuario"];
$contra_hash = $resultado["contra"];

if (!password_verify($contra, $contra_hash)) {
  header('Location: ../../view/login.html');
}

$rango = $ra->rolAdministradorConId($id);

if ($rango == "Cliente") {
  header('Location: ../../view/home.html');
} else {
  header('Location: ../../view/roles/administradores.html');
}
