import { navbar } from './componentes/navbar.js';
import { API_URL } from './utils/config.js';

$(async () => {
  await navbar(true);

  await cargarUltimosProductos();
  await cargarUltimosPaquetes();
  await cargarProductosOferta();
  await cargarPaquetesOferta();
});

// load functions

async function cargarUltimosProductos() {
  $('#ultimos-productos').html('<li>Cargando...</li>');

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/productos/ver-productos.php`, {
      method: 'get',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) {
    $('#ultimos-productos').html(
      '<li>Ocurrio un error al cargar los productos...</li>'
    );

    return;
  }

  $('#ultimos-productos').html(
    resultado
      .slice(0, 6)
      .map(producto => renderProducto(producto))
      .join('')
  );
}

async function cargarUltimosPaquetes() {
  $('#ultimos-paquetes').html('<li>Cargando...</li>');

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/paquetes/ver-paquetes.php`, {
      method: 'get',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) {
    $('#ultimos-paquetes').html(
      '<li>Ocurrio un error al cargar los paquetes...</li>'
    );

    return;
  }

  $('#ultimos-paquetes').html(
    resultado
      .slice(0, 6)
      .map(paquete => renderPaquete(paquete))
      .join('')
  );
}

async function cargarProductosOferta() {
  $('#productos-oferta').html('<li>Cargando...</li>');

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/productos/ver-productos.php?oferta=true`, {
      method: 'get',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) {
    $('#productos-oferta').html(
      '<li>Ocurrio un error al cargar los productos...</li>'
    );

    return;
  }

  $('#productos-oferta').html(
    resultado
      .slice(0, 6)
      .map(producto => renderProducto(producto))
      .join('')
  );
}

async function cargarPaquetesOferta() {
  $('#paquetes-oferta').html('<li>Cargando...</li>');

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/paquetes/ver-paquetes.php?oferta=true`, {
      method: 'get',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) {
    $('#paquetes-oferta').html(
      '<li>Ocurrio un error al cargar los paquetes...</li>'
    );

    return;
  }

  $('#paquetes-oferta').html(
    resultado
      .slice(0, 6)
      .map(paquete => renderPaquete(paquete))
      .join('')
  );
}

// render functions

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

// utils

function mezclar(lista) {
  return lista.sort(() => Math.random() - 0.5);
}
