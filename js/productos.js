import { navbar } from './componentes/navbar.js';
import { API_URL } from './utils/config.js';

$(async () => {
  await navbar(false);

  await cargarProductos();

  $('#buscar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;
    const { value: query } = form[0];

    const categoria = $('#categoria').val();

    $('#productos').html('<li>Cargando...</li>');

    const { resultado, codigo } = await (
      await fetch(
        `${API_URL}/productos/ver-productos.php?query=${query}&categoria=${categoria}`,
        {
          method: 'get'
        }
      )
    ).json();

    if (codigo >= 400) {
      $('#productos').html('<li>Error al cargar los productos...</li>');
      return;
    }

    if (resultado.length === 0) {
      $('#productos').html('<li>No hay productos...</li>');
      return;
    }

    $('#productos').html(
      resultado.map(producto => renderProducto(producto)).join('')
    );
  });

  $('#categoria').on('change', async () => await cargarProductos());
});

async function cargarProductos() {
  const categoria = $('#categoria').val();

  $('#productos').html('<li>Cargando...</li>');

  const { resultado, codigo } = await (
    await fetch(
      `${API_URL}/productos/ver-productos.php?categoria=${categoria}`,
      {
        method: 'get'
      }
    )
  ).json();

  if (codigo >= 400) {
    $('#productos').html('<li>Error al cargar los productos...</li>');
    return;
  }

  if (resultado.length === 0) {
    $('#productos').html('<li>No hay productos...</li>');
    return;
  }

  $('#productos').html(
    resultado.map(producto => renderProducto(producto)).join('')
  );
}

function renderProducto(producto) {
  const { id, nombre, ruta, descripcion, precio, descuento } = producto;

  return `  
  <li class="flex flex-col border border-gray-300 p-2 gap-2">
  <a class="h-1/2" href="./producto.html?id=${id}">
    <img
      class="h-full mx-auto object-cover"
      src="./img/${ruta}"
      alt="${nombre}"
    />
  </a>
  <h4 class="text-lg font-bold">${nombre}</h4>
  <p class="text-sm mt-auto">
    ${
      descripcion.length > 65
        ? `${descripcion.slice(0, 65)}...`
        : `${descripcion}...`
    }
  </p>
  <p class="font-semibold relative w-min mt-auto">
    $${precio - (precio * descuento) / 100}
    <span
      class="absolute text-xs -top-2 -right-3 text-red-500 line-through"
      >${descuento > 0 ? `$${(precio * descuento) / 100}` : ``}</span
    >
  </p>
</li>
`;
}
