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

$(() => {
  // MENU HAMBURGUESA RESPONSIVE
  const $menuBoton = $('#menu-boton');
  const $window = $(window);

  $menuBoton.on('click', () => {
    if ($menuBoton.hasClass('open')) $menuBoton.removeClass('open');
    else $menuBoton.addClass('open');
  });

  $window.on('resize', () => {
    if ($window.width() < 620) return;
    if ($menuBoton.hasClass('open')) $menuBoton.removeClass('open');
  });

  // CARGAR ULTIMOS PRODUCTOS
  const $listaUltimosProductos = $('#last-products');
  PRODUCTOS.map(producto => {
    $listaUltimosProductos.append(
      `<li class="product">
      <a href="/">
      <img src="${producto.img}" alt="" />
          </a>
          <h3>${producto.titulo}</h3>
          <p>
            ${producto.descripcion}
          </p>
          <div>
            <span>$${producto.precio}</span>
            <button type="button">Agregar</button>
          </div>
      </li>`
    );
  });

  // CARGAR ULTIMAS OFERTAS
  const $listaUltimasOfertas = $('#last-offers');
  PRODUCTOS.map(producto => {
    $listaUltimasOfertas.append(
      `<li class="product">
        <a href="/">
          <img src="${producto.img}" alt="" />
          <h3>${producto.titulo}</h3>
          <p>
            ${producto.descripcion}
          </p>
          <div>
            <span>$${producto.precio}</span>
            <button type="button">Agregar</button>
          </div>
        </a>
      </li>`
    );
  });
});
