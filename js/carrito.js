import { navbar } from './componentes/navbar.js';
import { obtenerDatosDeSesion } from './utils/validar-sesion.js';
import { API_URL } from './utils/config.js';

let id;

let total = 0;

$(async () => {
  await navbar(false);

  const { id: id_usuario } = await obtenerDatosDeSesion();
  id = id_usuario;

  await cargarCarrito();

  $('#abrir-dialogo').on('click', () => {
    $('#dialog')[0].showModal();
    $('#compra')[0].reset();
  });

  $('#cerrar-dialogo').on('click', () => {
    $('#dialog')[0].close();
    $('#compra')[0].reset();
  });

  $('#compra').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado').html('');
    $('#resultado').css({ color: 'black' });

    const compra = new FormData(form);
    compra.append('usuario', id);
    compra.append('total', total);

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/pedidos/crear-pedido.php`, {
        method: 'post',
        body: compra
      })
    ).json();

    if (codigo >= 400) {
      $('#resultado').html(resultado);
      $('#resultado').css({ color: 'red' });

      return;
    }

    $('#dialog')[0].close();
    $('#compra')[0].reset();

    await cargarCarrito();
  });
});

async function cargarCarrito() {
  $('#lista-productos').html('<li>Cargando...</li>');
  $('#lista-productos').css({ color: 'black' });

  $('#abrir-dialogo').attr('disabled', '');

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/carritos/ver-carrito.php?id=${id}`)
  ).json();

  if (codigo >= 400) {
    $('#lista-productos').html(
      '<li>Ocurrio un problema cargando el carrito...</li>'
    );
    $('#lista-productos').css({ color: 'red' });

    $('#total').html(`...`);

    $('#abrir-dialogo').attr('disabled', '');

    return;
  }

  if (resultado.length === 0) {
    $('#lista-productos').html('<li>No hay productos</li>');
    $('#lista-productos').css({ color: 'black' });

    $('#total').html(`...`);

    $('#abrir-dialogo').attr('disabled', '');

    return;
  }

  total = 0;

  $('#lista-productos').html(
    resultado.map(item => {
      const { precio, tipo, descuento, cantidad } = item;
      total += (precio - (precio * descuento) / 100) * cantidad;
      return tipo === 'Producto' ? renderProducto(item) : renderPaquete(item);
    })
  );

  $('#total').html(`$${total}`);
  $('#abrir-dialogo').removeAttr('disabled');

  $('*[data-action="borrar-pr"]').on('click', borrarProducto);
  $('*[data-action="borrar-pa"]').on('click', borrarPaquete);
}

async function borrarProducto(e) {
  const { target } = e;
  const idProducto = target.getAttribute('data-id');

  const { resultado, codigo } = await (
    await fetch(
      `${API_URL}/carritos/quitar-producto-de-carrito.php?id_p=${idProducto}&id_u=${id}`,
      {
        method: 'delete'
      }
    )
  ).json();

  await cargarCarrito();
}

async function borrarPaquete(e) {
  const { target } = e;
  const idPaquete = target.getAttribute('data-id');

  const { resultado, codigo } = await (
    await fetch(
      `${API_URL}/carritos/quitar-paquete-de-carrito.php?id_p=${idPaquete}&id_u=${id}`,
      {
        method: 'delete'
      }
    )
  ).json();

  await cargarCarrito();
}

function renderProducto(producto) {
  const {
    id_producto: id,
    nombre,
    ruta,
    descripcion,
    precio,
    cantidad
  } = producto;

  return `
    <li>
      <button type="button" data-id="${id}" data-action="borrar-pr">
        X
      </button>
      <div>
        <a href="./producto.html?id=${id}">
          <img src="./img/${ruta}" alt="${ruta}" />
        </a>
        <h3>${nombre}</h3>
        <p>$${precio}</p>
        <p>Cantidad: x${cantidad}</p>
      </div>
    </li>
  `;
}

function renderPaquete(paquete) {
  const {
    id_paquete: id,
    nombre,
    ruta,
    descripcion,
    precio,
    cantidad
  } = paquete;

  return `
    <li>
      <button type="button" data-id="${id}" data-action="borrar-pa">
        X
      </button>
      <p>PAQUETE</p>
      <a href="./paquete.html?id=${id}">
        <img src="./img/${ruta}" alt="${ruta}" />
      </a>
      <h3>${nombre}</h3>
      <p>$${precio}</p>
      <p>Cantidad: x${cantidad}</p>
    </li>
  `;
}
