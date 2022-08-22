<?php

define("OBLIGATORY_FIELDS", ["nombre", "precio", "descuento", "descripcion", "imagenes"]);

function validateEmptyProductFields(array $fields): void
{
  foreach (OBLIGATORY_FIELDS as $obligatoryField) {
    if (!isset($fields[$obligatoryField]))
      throw new Exception("El campo '$obligatoryField' es obligatorio.");

    if (empty($fields[$obligatoryField]) && $obligatoryField != "descuento")
      throw new Exception("El campo '$obligatoryField' es obligatorio.");
  }

  if (empty($fields["descuento"]) && $fields["descuento"] != 0) {
  }

  foreach ($fields["imagenes"] as $route) {
    if (empty($route))
      throw new Exception("El campo 'imagenes' es obligatorio.");
  }
}
