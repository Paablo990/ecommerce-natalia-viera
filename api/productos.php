<?php

define('MAX_PRODUCTS_REQUEST', 100);

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $id = $_GET["id"] ?? false;

  if ($id == false) {
    return print("Todos los productos...");
  }

  return print("La id del producto es -> $id...");
}
