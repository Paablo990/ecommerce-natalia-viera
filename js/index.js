const PRODUCTOS = [
  {
    id: 1,
    img: 'assets/products/producto1.jpg',
    titulo: 'Producto 1 mega nashe',
    descripcion:
      'Lorem, ipsum dolor sit amet consectetur adipisicing elit Eaque placeat  labore...',
    precio: 700,
  },
  {
    id: 1,
    img: 'assets/products/producto1.jpg',
    titulo: 'Producto 1 mega nashe',
    descripcion:
      'Lorem, ipsum dolor sit amet consectetur adipisicing elit Eaque placeat  labore...',
    precio: 700,
  },
  {
    id: 1,
    img: 'assets/products/producto1.jpg',
    titulo: 'Producto 1 mega nashe',
    descripcion:
      'Lorem, ipsum dolor sit amet consectetur adipisicing elit Eaque placeat  labore...',
    precio: 700,
  },
  {
    id: 1,
    img: 'assets/products/producto1.jpg',
    titulo: 'Producto 1 mega nashe',
    descripcion:
      'Lorem, ipsum dolor sit amet consectetur adipisicing elit Eaque placeat  labore...',
    precio: 700,
  },
  {
    id: 1,
    img: 'assets/products/producto1.jpg',
    titulo: 'Producto 1 mega nashe',
    descripcion:
      'Lorem, ipsum dolor sit amet consectetur adipisicing elit Eaque placeat  labore...',
    precio: 700,
  },
  {
    id: 1,
    img: 'assets/products/producto1.jpg',
    titulo: 'Producto 1 mega nashe',
    descripcion:
      'Lorem, ipsum dolor sit amet consectetur adipisicing elit Eaque placeat  labore...',
    precio: 700,
  },
];

const renderProducto = ({ id, img, titulo, descripcion, precio }) => {
  return `<li class="product">
        <a href="./producto.html?id=${id}">
          <img src="${img}" alt="" loading="lazy" class="product__img"/>
        </a>
        <h3 class="product__title">${titulo}</h3>
        <p class="product__description">
          ${descripcion}
        </p>
        <div class="product__wrapper">
          <span>$${precio}</span>
          <button type="button" class="product__button">Agregar</button>
        </div>
      </li>`;
};

$(() => {
  // MENU HAMBURGUESA RESPONSIVE
  const $menuBoton = $('#menu-boton');
  const $responsiveNavBar = $('#responsive-navbar');

  $menuBoton.on('click', () => {
    if ($responsiveNavBar.hasClass('cerrado'))
      $responsiveNavBar.removeClass('cerrado');
    else $responsiveNavBar.addClass('cerrado');
  });

  // CARGAR ULTIMOS PRODUCTOS
  const $listaUltimosProductos = $('#last-products');
  PRODUCTOS.map(producto => {
    $listaUltimosProductos.append(renderProducto(producto));
  });
  // CARGAR ULTIMAS OFERTAS
  const $listaUltimasOfertas = $('#last-offers');
  PRODUCTOS.map(producto => {
    $listaUltimasOfertas.append(renderProducto(producto));
  });
});
