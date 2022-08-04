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
  productos.map(producto => {
    $listaUltimosProductos.append(renderProducto(producto));
  });

  // CARGAR ULTIMAS OFERTAS
  const $listaUltimasOfertas = $('#last-offers');
  productos.map(producto => {
    $listaUltimasOfertas.append(renderProducto(producto));
  });
});

async function fetchProductos() {
  const response = await fetch(`/js/productos.json`);
  return await response.json();
}
