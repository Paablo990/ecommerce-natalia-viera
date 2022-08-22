<?php

require_once "database.php";

class ProductosDB
{
  // constantes
  private const QUERY_ALL_PRODUCTS = "SELECT `pr`.`id_producto` `id`, `pr`.`nombre`, `pr`.`precio`, `pr`.`descuento`, `pr`.`descripcion`, `im`.`ruta_imagen` `img` FROM Producto `pr` INNER JOIN ImagenProducto `im` ON `pr`.`id_producto`=`im`.`id_producto` GROUP BY `pr`.`id_producto` LIMIT ?,?";

  private const QUERY_PRODUCT_INFO_BY_ID = "SELECT `id_producto` `id`, `nombre`, `precio`, `descuento`, `descripcion` FROM Producto WHERE `id_producto`=?";

  private const QUERY_PRODUCT_IMAGES_BY_ID = "SELECT `ruta_imagen` `ruta` FROM ImagenProducto WHERE `id_producto`=?";

  private const INSERT_PRODUCT = "INSERT INTO Producto (`nombre`, `precio`, `descuento`, `descripcion`) VALUES (?, ?, ?, ?)";

  private const INSERT_PRODUCT_IMAGE = "INSERT INTO ImagenProducto (`id_producto`, `ruta_imagen`) VALUES (?, ?)";

  // atributos
  private Database $db;
  private array $products;

  // constructor
  public function __construct()
  {
    $this->db = new Database();
    $this->productos = [];
  }

  // metodos
  public function getAll(int $limit, int $offset): array
  {
    $this->products = $this->db->query(self::QUERY_ALL_PRODUCTS, [
      $offset . "|i",
      $limit . "|i"
    ]);
    $this->db->close();

    return $this->products;
  }

  public function getByID(string $id): array
  {
    $product = $this->db->query(self::QUERY_PRODUCT_INFO_BY_ID, [
      $id . "|i"
    ]);
    $images = $this->db->query(self::QUERY_PRODUCT_IMAGES_BY_ID, [
      $id . "|i"
    ]);

    $product[0]["imagenes"] = $images;

    $this->db->close();

    return $product;
  }

  public function insert(array $product, array $images): void
  {
    $params = [
      $product["nombre"] . "|s",
      $product["precio"] . "|i",
      $product["descuento"] . "|i",
      $product["descripcion"] . "|s"
    ];

    $id = $this->db->insert(self::INSERT_PRODUCT, $params);

    foreach ($images as $image) {
      $params = [
        $id . "|i",
        $image . "|s"
      ];
      $this->db->insert(self::INSERT_PRODUCT_IMAGE, $params);
    }
  }

  public function deleteProducto()
  {
  }

  public function updateProducto()
  {
  }
}
