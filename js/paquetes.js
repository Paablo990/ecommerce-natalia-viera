import { navbar } from './componentes/navbar.js';
import { API_URL } from './utils/config.js';

$(async () => {
  await navbar(false);

  await cargarPaquetes();

  $('#buscar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;
    const { value: query } = form[0];

    $('#paquetes').html('<li>Cargando...</li>');

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/paquetes/ver-paquetes.php?query=${query}`, {
        method: 'get',
        cache: 'no-cache'
      })
    ).json();

    if (codigo >= 400) {
      $('#paquetes').html('<li>Error al cargar los paquetes...</li>');
      return;
    }

    if (resultado.length === 0) {
      $('#paquetes').html('<li>No hay paquetes...</li>');
      return;
    }

    $('#paquetes').html(
      resultado.map(paquete => renderPaquete(paquete)).join('')
    );
  });
});

async function cargarPaquetes() {
  $('#paquetes').html('<li>Cargando...</li>');

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/paquetes/ver-paquetes.php`, {
      method: 'get',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) {
    $('#paquetes').html('<li>Error al cargar los paquetes...</li>');
    return;
  }

  if (resultado.length === 0) {
    $('#paquetes').html('<li>No hay paquetes...</li>');
    return;
  }

  $('#paquetes').html(
    resultado.map(paquete => renderPaquete(paquete)).join('')
  );
}

function renderPaquete(paquete) {
  const {
    id,
    cantidad_productos,
    nombre,
    ruta,
    descripcion,
    precio,
    descuento
  } = paquete;

  return `
  <li class="flex relative flex-col border border-gray-300 p-2 gap-2">
  <p
    class="absolute -left-2 -top-2 bg-natalia-blue-400 text-white uppercase font-black tracking-widest text-xs p-1"
  >
    Items: ${cantidad_productos}
  </p>
  <a class="h-1/2" href="./paquete.html?id=${id}">
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
</li>`;
}
