import { navbar } from './componentes/navbar-administracion.js';
import { API_URL } from './utils/config.js';

let productos = [];
let paqueteAux = {};

$(async () => {
  await navbar(['comprador', 'jefe']);

  $('#tabla-seleccionar').html('No hay productos seleccionados...');

  await cargarTabla();

  $('#filtrar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;
    const { value: id } = form[0];

    $('#resultado-borrar').html('');
    $('#resultado-borrar').css({ color: 'black' });

    form[0].value = '';

    $('#tabla-buscar').html(`Cargando...`);

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/paquetes/ver-paquetes.php`, { method: 'get' })
    ).json();

    if (codigo >= 400) {
      $('#tabla-buscar').html(`Ocurrio un error en la carga...`);
      return;
    }

    if (id) {
      $('#tabla-buscar').html(
        resultado
          .filter(paquete => paquete.id === +id)
          .map(paquete => renderRowPaquete(paquete))
          .join('') || 'No existe ese paquete'
      );

      $('*[data-action="borrar"]').on('click', borrar);
    } else {
      $('#tabla-buscar').html(
        resultado.map(paquete => renderRowPaquete(paquete)).join('')
      );

      $('*[data-action="borrar"]').on('click', borrar);
    }
  });

  $('#seleccionar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado-seleccionar').html('');
    $('#resultado-seleccionar').css({ color: 'black' });

    const id = form[0].value;
    const producto = productos.find(p => p.id === id);

    if (producto) {
      $('#resultado-seleccionar').html('Ese producto ya esta seleccionado');
      $('#resultado-seleccionar').css({ color: 'red' });

      return;
    }

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/productos/ver-producto.php?id=${id}`, {
        method: 'get'
      })
    ).json();

    if (codigo >= 400) {
      $('#resultado-seleccionar').html(resultado);
      $('#resultado-seleccionar').css({ color: 'red' });

      return;
    }

    form[0].value = '';

    productos.push(resultado);
    cargarSeleccionados();
  });

  $('#agregar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado-agregar').html('');
    $('#resultado-agregar').css({ color: 'black' });

    if (productos.length === 0) {
      $('#resultado-agregar').html('No hay productos seleccionados');
      $('#resultado-agregar').css({ color: 'red' });

      return;
    }

    const paquete = new FormData(form);
    paquete.append(
      'productos',
      `[${productos
        .map(p => `[${p.id},${p.proveedor.id_proveedor}]`)
        .join(',')}]`
    );

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/paquetes/crear-paquete.php`, {
        method: 'post',
        body: paquete
      })
    ).json();

    if (codigo >= 400) {
      $('#resultado-agregar').html(resultado);
      $('#resultado-agregar').css({ color: 'red' });

      return;
    }

    $('#resultado-agregar').html(resultado);
    $('#resultado-agregar').css({ color: 'green' });

    productos = [];
    cargarSeleccionados();

    $('#tabla-seleccionar').html('No hay productos seleccionados...');

    form.reset();
    await cargarTabla();
  });

  $('#mod-id-seleccionar').attr('disabled', '');
  $('#mod-submit-seleccionar').attr('disabled', '');

  $('#mod-nombre').attr('disabled', '');
  $('#mod-precio').attr('disabled', '');
  $('#mod-descuento').attr('disabled', '');
  $('#mod-stock').attr('disabled', '');
  $('#mod-descripcion').attr('disabled', '');
  $('#mod-submit').attr('disabled', '');
  $('#mod-reset').attr('disabled', '');

  $('#mod-id-seleccionar').val('');
  $('#tabla-seleccionar-modificar').html('No se selecciono ningun paquete...');

  $('#mod-nombre').val('');
  $('#mod-precio').val('');
  $('#mod-descuento').val('');
  $('#mod-stock').val('');
  $('#mod-descripcion').val('');
  $('#mod-descripcion').val('');

  $('#modificar-buscar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado-modificar').html('');
    $('#resultado-modificar').css({ color: 'black' });

    $('#resultado-seleccionar-modificar').html('');
    $('#resultado-seleccionar-modificar').css({ color: 'black' });

    const id = form[0].value;

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/paquetes/ver-paquete.php?id=${id}`, {
        method: 'get'
      })
    ).json();

    if (codigo >= 400) {
      $('#resultado-modificar').html(resultado);
      $('#resultado-modificar').css({ color: 'red' });

      $('#resultado-seleccionar-modificar').html('');
      $('#resultado-seleccionar-modificar').css({ color: 'black' });

      $('#mod-id-seleccionar').attr('disabled', '');
      $('#mod-submit-seleccionar').attr('disabled', '');

      $('#mod-nombre').attr('disabled', '');
      $('#mod-precio').attr('disabled', '');
      $('#mod-descuento').attr('disabled', '');
      $('#mod-stock').attr('disabled', '');
      $('#mod-descripcion').attr('disabled', '');
      $('#mod-submit').attr('disabled', '');
      $('#mod-reset').attr('disabled', '');

      $('#mod-id-seleccionar').val('');
      $('#tabla-seleccionar-modificar').html(
        'No se selecciono ningun paquete...'
      );

      $('#mod-nombre').val('');
      $('#mod-precio').val('');
      $('#mod-descuento').val('');
      $('#mod-stock').val('');
      $('#mod-descripcion').val('');
      $('#mod-descripcion').val('');

      return;
    }

    paqueteAux = resultado;

    $('#mod-id-seleccionar').removeAttr('disabled');
    $('#mod-submit-seleccionar').removeAttr('disabled');

    $('#mod-nombre').removeAttr('disabled');
    $('#mod-precio').removeAttr('disabled');
    $('#mod-descuento').removeAttr('disabled');
    $('#mod-stock').removeAttr('disabled');
    $('#mod-descripcion').removeAttr('disabled');
    $('#mod-submit').removeAttr('disabled');
    $('#mod-reset').removeAttr('disabled');

    $('#mod-nombre').val(paqueteAux.nombre);
    $('#mod-precio').val(paqueteAux.precio);
    $('#mod-descuento').val(paqueteAux.descuento);
    $('#mod-stock').val(paqueteAux.stock);
    $('#mod-descripcion').val(paqueteAux.descripcion);

    cargarSeleccionadosModificar();
  });

  $('#modificar-paquete').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    if (paqueteAux.productos.length === 0) {
      $('#resultado-modificar').html('No hay productos seleccionados');
      $('#resultado-modificar').css({ color: 'red' });

      return;
    }

    const paquete = new FormData(form);
    paquete.append(
      'productos',
      `[${paqueteAux.productos
        .map(p => `[${p.id},${p.id_proveedor}]`)
        .join(',')}]`
    );

    const { resultado, codigo } = await (
      await fetch(
        `${API_URL}/paquetes/modificar-paquete.php?id=${paqueteAux.id_paquete}`,
        { method: 'post', body: paquete }
      )
    ).json();

    if (codigo >= 400) {
      $('#resultado-modificar').html(resultado);
      $('#resultado-modificar').css({ color: 'red' });

      return;
    }

    $('#resultado-modificar').html(resultado);
    $('#resultado-modificar').css({ color: 'green' });

    form.reset();

    $('#mod-id-seleccionar').attr('disabled', '');
    $('#mod-submit-seleccionar').attr('disabled', '');

    $('#mod-nombre').attr('disabled', '');
    $('#mod-precio').attr('disabled', '');
    $('#mod-descuento').attr('disabled', '');
    $('#mod-stock').attr('disabled', '');
    $('#mod-descripcion').attr('disabled', '');
    $('#mod-submit').attr('disabled', '');
    $('#mod-reset').attr('disabled', '');

    $('#mod-id-seleccionar').val('');
    $('#tabla-seleccionar-modificar').html(
      'No se selecciono ningun paquete...'
    );

    $('#mod-nombre').val('');
    $('#mod-precio').val('');
    $('#mod-descuento').val('');
    $('#mod-stock').val('');
    $('#mod-descripcion').val('');
    $('#mod-descripcion').val('');

    await cargarTabla();
  });

  $('#modificar-seleccionar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado-seleccionar-modificar').html('');
    $('#resultado-seleccionar-modificar').css({ color: 'black' });

    const id = form[0].value;
    const producto = paqueteAux.productos.find(p => p.id === +id);

    if (producto) {
      $('#resultado-seleccionar-modificar').html(
        'Ese producto ya esta seleccionado'
      );
      $('#resultado-seleccionar-modificar').css({ color: 'red' });

      return;
    }

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/productos/ver-producto.php?id=${id}`, {
        method: 'get'
      })
    ).json();

    if (codigo >= 400) {
      $('#resultado-seleccionar-modificar').html(resultado);
      $('#resultado-seleccionar-modificar').css({ color: 'red' });

      return;
    }

    form[0].value = '';

    paqueteAux.productos.push(resultado);
    cargarSeleccionadosModificar();
  });
});

// load functions

function cargarSeleccionados() {
  $('#resultado-seleccionar').html('');
  $('#resultado-seleccionar').css({ color: 'black' });

  $('#tabla-seleccionar').html(
    productos.map(producto => renderRowProducto(producto)).join('')
  );

  $('*[data-action="deseleccionar"]').on('click', deseleccionar);
}

function cargarSeleccionadosModificar() {
  $('#resultado-seleccionar-modificar').html('');
  $('#resultado-seleccionar-modificar').css({ color: 'black' });

  $('#tabla-seleccionar-modificar').html(
    paqueteAux.productos
      .map(producto => renderRowProductoModificar(producto))
      .join('')
  );

  $('*[data-action="deseleccionar-m"]').on('click', deseleccionarModificar);
}

async function cargarTabla() {
  $('#resultado-borrar').html('');
  $('#resultado-borrar').css({ color: 'black' });

  $('#tabla-buscar').html(`Cargando...`);

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/paquetes/ver-paquetes.php`, { method: 'get' })
  ).json();

  if (codigo >= 400) {
    $('#tabla-buscar').html(`Ocurrio un error en la carga...`);
    return;
  }

  $('#tabla-buscar').html(
    resultado.map(paquete => renderRowPaquete(paquete)).join('')
  );

  $('*[data-action="borrar"]').on('click', borrar);
}

// click handlers

function deseleccionar(e) {
  const { target } = e;
  const id = target.getAttribute('data-id');

  productos = [...productos].filter(p => p.id !== id);

  cargarSeleccionados();
}

function deseleccionarModificar(e) {
  const { target } = e;
  const id = target.getAttribute('data-id');

  paqueteAux.productos = [...paqueteAux.productos].filter(p => p.id !== +id);

  cargarSeleccionadosModificar();
}

async function borrar(e) {
  const { target } = e;
  const id = target.getAttribute('data-id');

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/paquetes/borrar-paquete.php?id=${id}`, {
      method: 'delete'
    })
  ).json();

  await cargarTabla();

  $('#resultado-borrar').html(resultado);
  $('#resultado-borrar').css({ color: codigo >= 400 ? 'red' : 'green' });
}

// render functions

function renderRowPaquete(paquete) {
  const { id, nombre, precio, ruta, descuento, stock, cantidad_productos } =
    paquete;

  return `<tr>
    <td>${id}</td>
    <td>${nombre}</td>
    <td>${cantidad_productos}</td>
    <td>$${precio}</td>
    <td>${descuento}%</td>
    <td>${stock}</td>
    <td>
      <img src="img/${ruta}" width="64px" height="64px"/>
    </td>
    <td class="p-0 bg-red-500">
      <button class="cta w-full aspect-square bg-red-500" type="button" data-id="${id}" data-action="borrar">X</button>
    </td>
  </tr>`;
}

function renderRowProducto(producto) {
  const { id, nombre, precio, imagen } = producto;

  return `<tr>
    <td>${id}</td>
    <td>${nombre}</td>
    <td>$${precio}</td>
    <td>
      <img src="img/${imagen}" width="64px" height="64px"/>
    </td>
    <td class="p-0 bg-red-500">
      <button class="cta w-full aspect-square bg-red-500" type="button" data-id="${id}" data-action="deseleccionar">X</button>
    </td>
  </tr>`;
}

function renderRowProductoModificar(producto) {
  const { id, nombre, precio, ruta } = producto;

  return `<tr>
    <td>${id}</td>
    <td>${nombre}</td>
    <td>$${precio}</td>
    <td>
      <img src="img/${ruta}" width="64px" height="64px"/>
    </td>
    <td class="p-0 bg-red-500">
      <button class="cta w-full aspect-square bg-red-500" type="button" data-id="${id}" data-action="deseleccionar-m">X</button>
    </td>
  </tr>`;
}
