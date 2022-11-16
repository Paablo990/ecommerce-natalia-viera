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

  const productoPrincipal = await fetchProducto(id);

  if (!productoPrincipal) {
    window.location.replace('./');
    return;
  }

  renderProductoPrincipal(productoPrincipal);

  const productosRelacionados = await fetchProductosRelacionados(
    productoPrincipal.categoria
  );

  productosRelacionados.forEach(producto => {
    $('#relacionados').append(renderProductoRelacionado(producto));
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

    const producto = new FormData(form);
    producto.append('producto', id);
    producto.append('usuario', idUsuario);

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/carritos/agregar-producto-a-carrito.php`, {
        method: 'post',
        body: producto,
        cache: 'no-cache'
      })
    ).json();

    $('#resultado').html(resultado);
    $('#resultado').css({ color: codigo >= 400 ? 'red' : 'green' });
  });
});

async function fetchProducto(id) {
  const { resultado, codigo } = await (
    await fetch(`${API_URL}/productos/ver-producto.php?id=${id}`, {
      method: 'get',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) return null;
  return resultado;
}

async function fetchProductosRelacionados(categoria) {
  const { resultado, codigo } = await (
    await fetch(
      `${API_URL}/productos/ver-productos.php?categoria=${categoria}`,
      { method: 'get', cache: 'no-cache' }
    )
  ).json();

  if (codigo >= 400) return [];
  return mezclar([...resultado]).slice(0, 6);
}

function renderProductoPrincipal(producto) {
  const { nombre, imagen, precio, descuento, stock, descripcion, categoria } =
    producto;

  $('#categoria').html(categoria);
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
}

function renderProductoRelacionado(producto) {
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

function mezclar(lista) {
  return lista.sort(() => Math.random() - 0.5);
}
