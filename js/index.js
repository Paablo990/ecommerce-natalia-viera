const renderProducto = ({ id, img, titulo, descripcion, precio }) => `
<li class="product">
  <a href="./producto.html?id=${id}">
    <img src="../assets/products/${img}" alt="" loading="lazy"  class="product__img"/>
  </a>
  <div>
    <h3 class="product__title">${titulo}</h3>
    <p class="product__description">${descripcion}</p>
    <div class="product__wrapper">
      <span>$${precio}</span>
      <button type="button" class="product__button">Agregar</button>
    </div>
  <div/>
</li>
`;

const fetchProductos = async () => {
  const response = await fetch('../js/productos.json');
  return response.json();
};

$(async () => {
  // MENU HAMBURGUESA RESPONSIVE
  const $menuBoton = $('#menu-boton');
  const $responsiveNavBar = $('#responsive-navbar');

  $menuBoton.on('click', () => {
    if ($responsiveNavBar.hasClass('cerrado')) {
      $responsiveNavBar.removeClass('cerrado');
    } else {
      $responsiveNavBar.addClass('cerrado');
    }
  });

  const productos = await fetchProductos();

  // CARGAR ULTIMOS PRODUCTOS
  const $listaUltimosProductos = $('#last-products');
  productos.forEach(producto => {
    $listaUltimosProductos.append(renderProducto(producto));
  });

  // CARGAR ULTIMAS OFERTAS
  const $listaUltimasOfertas = $('#last-offers');
  productos.forEach(producto => {
    $listaUltimasOfertas.append(renderProducto(producto));
  });
});
