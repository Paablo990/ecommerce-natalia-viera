import { navbar } from './componentes/navbar-administracion.js';
import { API_URL } from './utils/config.js';

$(async () => {
  await navbar(['vendedor', 'jefe']);

  await cargarTabla();

  $('#filtrar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;
    const { value: id } = form[0];

    $('#resultado').html('');
    $('#resultado').css({ color: 'black' });

    form[0].value = '';

    $('#tabla-pedidos').html(`Cargando...`);

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/pedidos/ver-pedidos.php`, {
        method: 'get',
        cache: 'no-cache'
      })
    ).json();

    if (codigo >= 400) {
      $('#tabla-pedidos').html(`Ocurrio un error en la carga...`);
      return;
    }

    if (resultado.length === 0) {
      $('#tabla-pedidos').html(`No hay pedidos`);
      return;
    }

    if (id) {
      $('#tabla-pedidos').html(
        resultado
          .filter(pedido => pedido.id_pedido === +id)
          .map(pedido => renderRowPedido(pedido))
          .join('') || 'No existe ese pedido'
      );
    } else {
      $('#tabla-pedidos').html(
        resultado.map(pedido => renderRowPedido(pedido)).join('')
      );
    }

    $('#detalles-pedido').html('<li>No hay pedido seleccionado</li>');
    $('#detalles-pedido').css({ color: 'black' });
    $('#total').html(`...`);

    $('*[data-action="aceptar"]').on('click', aceptar);
    $('*[data-action="mostrar"]').on('click', mostrar);
  });
});

async function cargarTabla() {
  $('#resultado').html('');
  $('#resultado').css({ color: 'black' });

  $('#tabla-pedidos').html(`Cargando...`);

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/pedidos/ver-pedidos.php`, {
      method: 'get',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) {
    $('#tabla-pedidos').html(`Ocurrio un error en la carga...`);
    return;
  }

  if (resultado.length === 0) {
    $('#tabla-pedidos').html(`No hay pedidos`);
    return;
  }

  $('#tabla-pedidos').html(
    resultado.map(pedido => renderRowPedido(pedido)).join('')
  );

  $('#detalles-pedido').html('<li>No hay pedido seleccionado</li>');
  $('#detalles-pedido').css({ color: 'black' });
  $('#total').html(`...`);

  $('*[data-action="aceptar"]').on('click', aceptar);
  $('*[data-action="mostrar"]').on('click', mostrar);
}

async function aceptar(e) {
  const { target } = e;
  const id = target.getAttribute('data-id');

  $('#resultado').html('');
  $('#resultado').css({ color: 'black' });

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/pedidos/cambiar-etapa.php?id=${id}`, {
      method: 'get',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) {
    $('#resultado').html(resultado);
    $('#resultado').css({ color: 'red' });

    return;
  }

  await cargarTabla();

  $('#resultado').html(resultado);
  $('#resultado').css({ color: 'green' });
}

async function mostrar(e) {
  const { target } = e;
  const id = target.getAttribute('data-id');

  $('#detalles-pedido').html('<li>Cargando...</li>');
  $('#detalles-pedido').css({ color: 'black' });

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/pedidos/ver-pedido.php?id=${id}`, {
      method: 'get',
      cache: 'no-cache'
    })
  ).json();

  $('#detalles-pedido').html('<li>Cargando...</li>');
  $('#detalles-pedido').css({ color: 'black' });

  if (codigo >= 400) {
    $('#detalles-pedido').html(
      '<li>Ocurrio un problema cargando el pedido...</li>'
    );
    $('#detalles-pedido').css({ color: 'red' });
    $('#total').html(`...`);

    return;
  }

  if (resultado.length === 0) {
    $('#detalles-pedido').html('<li>No hay productos</li>');
    $('#detalles-pedido').css({ color: 'black' });
    $('#total').html(`...`);

    return;
  }

  let total = 0;

  $('#detalles-pedido').html(
    resultado.map(item => {
      const { precio, tipo, descuento, cantidad } = item;
      total += (precio - (precio * descuento) / 100) * cantidad;
      return tipo === 'Producto' ? renderProducto(item) : renderPaquete(item);
    })
  );

  $('#total').html(`$${total}`);
}

function renderRowPedido(pedido) {
  const {
    id_pedido,
    id_cliente,
    tarjeta,
    monto,
    cantidad,
    fecha_solicitud,
    estado
  } = pedido;

  const estados = [
    'Por aprobar',
    'Pago pendiente',
    'Pago realizado',
    'En camino',
    'Finalizado'
  ];

  return `
    <tr>
      <td>${id_pedido}</td>
      <td>${id_cliente}</td>
      <td>${tarjeta}</td>
      <td>${cantidad}</td>
      <td>$${monto}</td>
      <td>${fecha_solicitud.substring(0, 10)}</td>
      <td>${estados[estado]}</td>
      <td class="p-0 bg-natalia-blue-400">
        <button class="cta w-full"  data-id="${id_pedido}" data-action="aceptar">
          Aceptar
        </button>
      </td>
      <td class="p-0 bg-natalia-blue-400">
        <button class="cta w-full" data-id="${id_pedido}" data-action="mostrar">
          Mostrar
        </button>
      </td>
    </tr>;
  `;
}

function renderProducto(producto) {
  const {
    id_producto: id,
    nombre,
    ruta,
    precio,
    cantidad,
    descuento
  } = producto;

  return `
  <li class="flex max-h-32 p-2 border border-gray-300 relative">
  <div class="flex">
    <a class="w-32" href="./producto.html?id=${id}">
      <img
        class="h-full object-cover"
        src="./img/${ruta}"
        alt="${ruta}"
      />
    </a>
    <div>
      <h3 class="max-w-[200px] sm:max-w-xs break-words font-bold">
        ${nombre}
      </h3>
      <p class="font-semibold relative w-min mt-auto">
        $${precio - (precio * descuento) / 100}
        <span
          class="absolute text-xs -top-2 -right-3 text-red-500 line-through"
          >${descuento > 0 ? `$${(precio * descuento) / 100}` : ``}</span
        >
      </p>
      <p class="text-gray-600 text-sm">Cantidad: x${cantidad}</p>
    </div>
  </div>
</li>
  `;
}

function renderPaquete(paquete) {
  const {
    id_paquete: id,
    cantidad_productos,
    nombre,
    ruta,
    descuento,
    precio,
    cantidad
  } = paquete;

  return `
  <li class="flex max-h-32 p-2 border border-gray-300 relative">
  <p
  class="absolute -left-2 -top-2 bg-natalia-blue-400 text-white uppercase font-black tracking-widest text-xs p-1"
>
  Items: ${cantidad_productos}
</p>
  <div class="flex">
    <a class="w-32" href="./paquete.html?id=${id}">
      <img
        class="h-full object-cover"
        src="./img/${ruta}"
        alt="${ruta}"
      />
    </a>
    <div>
      <h3 class="max-w-[200px] sm:max-w-xs break-words font-bold">
        ${nombre}
      </h3>
      <p class="font-semibold relative w-min mt-auto">
        $${precio - (precio * descuento) / 100}
        <span
          class="absolute text-xs -top-2 -right-3 text-red-500 line-through"
          >${descuento > 0 ? `$${(precio * descuento) / 100}` : ``}</span
        >
      </p>
      <p class="text-gray-600 text-sm">Cantidad: x${cantidad}</p>
    </div>
  </div>
</li>
  `;
}
