<?php

class Database
{
  private mysqli $db;

  public function __construct()
  {
    $this->db = new mysqli("localhost", "root", "12345678", "SISdb");
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  //  METODOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function queryWithoutParams(
    string $query
  ): array {
    if ($this->db->errno) {
      throw new mysqli_sql_exception("Ocurrio un error al conectar a la BD.");
    }

    $statement = $this->db->prepare($query);
    $statement->execute();

    $resultado = $statement->get_result();

    if ($resultado == null) {
      $statement->close();
      return [];
    }

    $filas = [];

    while ($fila = $resultado->fetch_assoc()) {
      $filas[] = $fila;
    }

    $statement->close();

    return $filas;
  }

  public function queryWithParams(
    string $query,
    array $query_params,
    string $query_types
  ): array {
    if ($this->db->errno) {
      throw new mysqli_sql_exception("Ocurrio un error al conectar a la BD.");
    }

    $statement = $this->db->prepare($query);
    $statement->bind_param($query_types, ...$query_params);
    $statement->execute();

    $resultado = $statement->get_result();

    if ($resultado == null) {
      $statement->close();
      return [];
    }

    $filas = [];

    while ($fila = $resultado->fetch_assoc()) {
      $filas[] = $fila;
    }

    $statement->close();

    return $filas;
  }

  public function insertRow(
    string $query,
    array $query_params,
    string $query_types
  ): ?int {
    if ($this->db->errno) {
      throw new mysqli_sql_exception("Ocurrio un error al conectar a la BD.");
    }

    $statement = $this->db->prepare($query);
    $statement->bind_param($query_types, ...$query_params);
    $statement->execute();

    $id = $statement->insert_id;

    $statement->close();

    return $id;
  }

  public function updateOrDeleteRow(
    string $query,
    array $query_params,
    string $query_types
  ): void {
    if ($this->db->errno) {
      throw new mysqli_sql_exception("Ocurrio un error al conectar a la BD.");
    }

    $statement = $this->db->prepare($query);
    $statement->bind_param($query_types, ...$query_params);
    $statement->execute();

    $statement->close();
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  //  METODOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function close(): void
  {
    $this->db->close();
  }
}
