<?php

class Database
{
  // constantes
  private const DB_HOST = "localhost";
  private const DB_USER = "santiago";
  private const DB_PASS = "123";
  private const DB_NAME = "test_natalia";

  // atributos
  private mysqli $conn;

  // constructor
  public function __construct()
  {
    $this->conn = new mysqli(self::DB_HOST, self::DB_USER, self::DB_PASS, self::DB_NAME);
  }

  // metodos
  public function close(): void
  {
    $this->conn->close();
  }

  public function query(string $query, ?array $data): array
  {
    if ($this->conn->connect_errno) {
      throw new mysqli_sql_exception($this->conn->connect_error);
    }

    $statement = $this->conn->prepare($query);

    if (count($data) > 0) {
      $types = "";
      $params = [];

      foreach ($data as $param) {
        $params[] = explode("|", $param)[0];
        $types .= explode("|", $param)[1];
      }

      $statement->bind_param($types, ...$params);
    }

    $statement->execute();
    $result = $statement->get_result();

    $results = array();

    while ($row = $result->fetch_assoc()) {
      $results[] = $row;
    }

    $statement->close();

    if ($result == null || count($results) == 0) {
      throw new Exception("No hay coincidencias.");
    }

    return $results;
  }

  public function insert(string $query, array $data): int
  {
    if ($this->conn->connect_errno) {
      throw new Exception($this->conn->connect_error);
    }

    $statement = $this->conn->prepare($query);

    $types = "";
    $params = [];

    foreach ($data as $param) {
      $params[] = explode("|", $param)[0];
      $types .= explode("|", $param)[1];
    }

    $statement->bind_param($types, ...$params);
    $statement->execute();

    $id = $statement->insert_id;

    $statement->close();

    return $id;
  }
}
