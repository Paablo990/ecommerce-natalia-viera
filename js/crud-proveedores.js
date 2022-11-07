import { API_URL } from './utils/config.js';

let proveedorAux = {};

$(async () => {
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
      await fetch(`${API_URL}/proveedores/ver-proveedores.php`, {
        method: 'get'
      })
    ).json();

    if (codigo >= 400) {
      $('#tabla-buscar').html(`Ocurrio un error en la carga...`);
      return;
    }

    if (id) {
      $('#tabla-buscar').html(
        resultado
          .filter(proveedor => proveedor.id === +id)
          .map(proveedor => renderRowProveedor(proveedor))
          .join('') || 'No existe ese proveedor'
      );

      $('*[data-action="borrar"]').on('click', borrar);
    } else {
      $('#tabla-buscar').html(
        resultado.map(proveedor => renderRowProveedor(proveedor)).join('')
      );

      $('*[data-action="borrar"]').on('click', borrar);
    }
  });

  $('#agregar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado-crear').html('');
    $('#resultado-crear').css({ color: 'black' });

    const proveedor = new FormData(form);
    const { resultado, codigo } = await (
      await fetch(`${API_URL}/proveedores/crear-proveedor.php`, {
        method: 'post',
        body: proveedor
      })
    ).json();

    $('#resultado-crear').html(`${resultado}`);
    $('#resultado-crear').css({ color: codigo >= 400 ? 'red' : 'green' });

    await cargarTabla();
  });

  $('#mod-nombre').attr('disabled', '');
  $('#mod-correo').attr('disabled', '');
  $('#mod-calle').attr('disabled', '');
  $('#mod-puerta').attr('disabled', '');
  $('#mod-telefono').attr('disabled', '');
  $('#mod-submit').attr('disabled', '');
  $('#mod-reset').attr('disabled', '');

  $('#mod-nombre').val('');
  $('#mod-correo').val('');
  $('#mod-calle').val('');
  $('#mod-puerta').val('');
  $('#mod-telefono').val('');

  $('#modificar-buscar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado-modificar').html('');
    $('#resultado-modificar').css({ color: 'black' });

    const id = form[0].value;

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/proveedores/ver-proveedor.php?id=${id}`, {
        method: 'get'
      })
    ).json();

    if (codigo >= 400) {
      $('#resultado-modificar').html(resultado);
      $('#resultado-modificar').css({ color: 'red' });

      $('#mod-nombre').attr('disabled', '');
      $('#mod-correo').attr('disabled', '');
      $('#mod-calle').attr('disabled', '');
      $('#mod-puerta').attr('disabled', '');
      $('#mod-telefono').attr('disabled', '');
      $('#mod-submit').attr('disabled', '');
      $('#mod-reset').attr('disabled', '');

      $('#mod-nombre').val('');
      $('#mod-correo').val('');
      $('#mod-calle').val('');
      $('#mod-puerta').val('');
      $('#mod-telefono').val('');

      return;
    }

    proveedorAux = resultado;

    $('#mod-nombre').removeAttr('disabled');
    $('#mod-correo').removeAttr('disabled');
    $('#mod-calle').removeAttr('disabled');
    $('#mod-puerta').removeAttr('disabled');
    $('#mod-telefono').removeAttr('disabled');
    $('#mod-submit').removeAttr('disabled');
    $('#mod-reset').removeAttr('disabled');

    $('#mod-nombre').val(proveedorAux.nombre);
    $('#mod-correo').val(proveedorAux.correo);
    $('#mod-calle').val(proveedorAux.calle);
    $('#mod-puerta').val(proveedorAux.nro_puerta);
    $('#mod-telefono').val(proveedorAux.telefono);
  });

  $('#modificar-proveedor').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado-modificar').html('');
    $('#resultado-modificar').css({ color: 'black' });

    const proveedor = new FormData(form);

    const { resultado, codigo } = await (
      await fetch(
        `${API_URL}/proveedores/modificar-proveedor.php?id=${proveedorAux.id}`,
        { method: 'post', body: proveedor }
      )
    ).json();

    if (codigo >= 400) {
      $('#resultado-modificar').html(resultado);
      $('#resultado-modificar').css({ color: 'red' });
    } else {
      $('#resultado-modificar').html(resultado);
      $('#resultado-modificar').css({ color: 'green' });

      $('#mod-nombre').attr('disabled', '');
      $('#mod-correo').attr('disabled', '');
      $('#mod-calle').attr('disabled', '');
      $('#mod-puerta').attr('disabled', '');
      $('#mod-telefono').attr('disabled', '');
      $('#mod-submit').attr('disabled', '');
      $('#mod-reset').attr('disabled', '');

      $('#mod-nombre').val('');
      $('#mod-correo').val('');
      $('#mod-calle').val('');
      $('#mod-puerta').val('');
      $('#mod-telefono').val('');
    }

    await cargarTabla();
  });
});

async function cargarTabla() {
  $('#resultado-borrar').html('');
  $('#resultado-borrar').css({ color: 'black' });

  $('#tabla-buscar').html(`Cargando...`);

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/proveedores/ver-proveedores.php`, {
      method: 'get'
    })
  ).json();

  if (codigo >= 400) {
    $('#tabla-buscar').html(`Ocurrio un error en la carga...`);
    return;
  }

  $('#tabla-buscar').html(
    resultado.map(proveedor => renderRowProveedor(proveedor)).join('')
  );

  $('*[data-action="borrar"]').on('click', borrar);
}

async function borrar(e) {
  const { target } = e;
  const id = target.getAttribute('data-id');

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/proveedores/borrar-proveedor.php?id=${id}`, {
      method: 'delete'
    })
  ).json();
  await cargarTabla();
  $('#resultado-borrar').html(resultado);
  $('#resultado-borrar').css({ color: codigo >= 400 ? 'red' : 'green' });
}

function renderRowProveedor(proveedor) {
  const { id, nombre, correo, calle, nro_puerta: puerta, telefono } = proveedor;
  return `<tr>
    <td>${id}</td>
    <td>${nombre}</td>
    <td>${correo}</td>
    <td>${calle}</td>
    <td>${puerta}</td>
    <td>${telefono}</td>
    <td class="p-0 bg-red-500">
      <button class="cta w-full aspect-square bg-red-500" type="button" data-id="${id}" data-action="borrar">X</button>
    </td>
  </tr>`;
}
