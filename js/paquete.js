import { navbar } from './componentes/navbar.js';
import { estaEnSesion, obtenerDatosDeSesion } from './utils/validar-sesion.js';
import { API_URL } from './utils/config.js';

$(async () => {
  await navbar(false);

  const parametros = window.location.search.substring(1).split('&');
  const id =
    parametros
      ?.find(parametro => parametro.split('=')[0] === 'id')
      ?.split('=')[1] || '';

  if (!id || isNaN(id)) {
    window.location.replace('./');
    return;
  }

  const paquetePrincipal = await fetchPaquete(id);

  if (!paquetePrincipal) {
    window.location.replace('./');
    return;
  }

  renderPaquetePrincipal(paquetePrincipal);

  const paquetesRelacionados = await fetchPaquetesRelacionados();

  paquetesRelacionados.forEach(paquete => {
    $('#relacionados').append(renderPaqueteRelacionado(paquete));
  });

  $('#agregar-carrito').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    if (!estaEnSesion()) {
      window.location.replace('./inicio-de-sesion.html');
      return;
    }

    const { rol, id: idUsuario } = (await obtenerDatosDeSesion()) ?? {};

    if (rol === undefined) {
      localStorage.clear();
      window.location.replace('./inicio-de-sesion.html');
      return;
    }

    $('#resultado').html('Enviando...');
    $('#resultado').css({ color: 'black' });

    const paquete = new FormData(form);
    paquete.append('paquete', id);
    paquete.append('usuario', idUsuario);

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/carritos/agregar-paquete-a-carrito.php`, {
        method: 'post',
        body: paquete
      })
    ).json();

    $('#resultado').html(resultado);
    $('#resultado').css({ color: codigo >= 400 ? 'red' : 'green' });
  });
});

async function fetchPaquete(id) {
  const { resultado, codigo } = await (
    await fetch(`${API_URL}/paquetes/ver-paquete.php?id=${id}`, {
      method: 'get'
    })
  ).json();

  if (codigo >= 400) return null;
  return resultado;
}

async function fetchPaquetesRelacionados() {
  const { resultado, codigo } = await (
    await fetch(`${API_URL}/paquetes/ver-paquetes.php`, {
      method: 'get'
    })
  ).json();

  if (codigo >= 400) return [];
  return mezclar([...resultado]).slice(0, 6);
}

function renderPaquetePrincipal(paquete) {
  const { nombre, imagen, precio, descuento, stock, descripcion, productos } =
    paquete;

  $('#nombre').html(nombre);
  $('#precio').html(`<p class="font-semibold relative w-min mt-auto">
  $${precio - (precio * descuento) / 100}
  <span
    class="absolute text-xs -top-2 -right-3 text-red-500 line-through"
    >${descuento > 0 ? `$${(precio * descuento) / 100}` : ``}</span
  >
</p>`);
  $('#stock').html(stock);
  $('#descripcion').html(descripcion);
  $('#breve-descripcion').html(
    `    ${
      descripcion.length > 65
        ? `${descripcion.slice(0, 255)}...`
        : `${descripcion}...`
    }`
  );
  $('#imagen')[0].src = `./img/${imagen}`;
  $('#imagen')[0].alt = `${imagen}`;

  $('#productos').html(
    productos.map(producto => renderProducto(producto)).join('')
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

function renderPaqueteRelacionado(paquete) {
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

function mezclar(lista) {
  return lista.sort(() => Math.random() - 0.5);
}
