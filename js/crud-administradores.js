import { navbar } from './componentes/navbar-administracion.js';
import { API_URL } from './utils/config.js';

let administradorAux = {};

$(async () => {
  await navbar(['jefe']);

  await cargarTabla();

  $('#filtrar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;
    const { value: ci } = form[0];

    $('#resultado-borrar').html('');
    $('#resultado-borrar').css({ color: 'black' });

    form[0].value = '';

    $('#tabla-buscar').html(`Cargando...`);

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/administradores/ver-administradores.php`, {
        method: 'get',
        cache: 'no-cache'
      })
    ).json();

    if (codigo >= 400) {
      $('#tabla-buscar').html(`Ocurrio un error en la carga...`);
      return;
    }

    if (ci) {
      $('#tabla-buscar').html(
        resultado
          .filter(administrador => administrador.ci === ci)
          .map(administrador => renderRowAdministrador(administrador))
          .join('') || 'No existe ese administrador'
      );
    } else {
      $('#tabla-buscar').html(
        resultado
          .map(administrador => renderRowAdministrador(administrador))
          .join('')
      );
    }
  });

  $('#agregar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado-crear').html('');
    $('#resultado-crear').css({ color: 'black' });

    const administrador = new FormData(form);
    const { resultado, codigo } = await (
      await fetch(`${API_URL}/administradores/crear-administrador.php`, {
        method: 'post',
        body: administrador,
        cache: 'no-cache'
      })
    ).json();

    $('#resultado-crear').html(`${resultado}`);
    $('#resultado-crear').css({ color: codigo >= 400 ? 'red' : 'green' });
    await cargarTabla();
  });

  $('#mod-ci').attr('disabled', '');
  $('#mod-nombre').attr('disabled', '');
  $('#mod-apellido').attr('disabled', '');
  $('#mod-correo').attr('disabled', '');
  $('#mod-contra').attr('disabled', '');
  $('#mod-celular').attr('disabled', '');
  $('#mod-submit').attr('disabled', '');
  $('#mod-reset').attr('disabled', '');

  $('#mod-ci').val('');
  $('#mod-nombre').val('');
  $('#mod-apellido').val('');
  $('#mod-correo').val('');
  $('#mod-contra').val('');
  $('#mod-celular').val('');

  $('#modificar-buscar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado-modificar').html('');
    $('#resultado-modificar').css({ color: 'black' });

    const ci = form[0].value;

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/administradores/ver-administrador.php?ci=${ci}`, {
        method: 'get',
        cache: 'no-cache'
      })
    ).json();

    if (codigo >= 400) {
      $('#resultado-modificar').html(resultado);
      $('#resultado-modificar').css({ color: 'red' });

      $('#mod-ci').attr('disabled', '');
      $('#mod-nombre').attr('disabled', '');
      $('#mod-apellido').attr('disabled', '');
      $('#mod-correo').attr('disabled', '');
      $('#mod-contra').attr('disabled', '');
      $('#mod-celular').attr('disabled', '');
      $('#mod-submit').attr('disabled', '');
      $('#mod-reset').attr('disabled', '');

      $('#mod-ci').val('');
      $('#mod-nombre').val('');
      $('#mod-apellido').val('');
      $('#mod-correo').val('');
      $('#mod-contra').val('');
      $('#mod-celular').val('');

      return;
    }

    administradorAux = resultado;

    $('#mod-ci').removeAttr('disabled');
    $('#mod-nombre').removeAttr('disabled');
    $('#mod-apellido').removeAttr('disabled');
    $('#mod-correo').removeAttr('disabled');
    $('#mod-contra').removeAttr('disabled');
    $('#mod-celular').removeAttr('disabled');
    $('#mod-submit').removeAttr('disabled');
    $('#mod-reset').removeAttr('disabled');

    $('#mod-ci').val(administradorAux.ci);
    $('#mod-nombre').val(administradorAux.nombre);
    $('#mod-apellido').val(administradorAux.apellido);
    $('#mod-correo').val(administradorAux.correo);
    $('#mod-contra').val('contraseña por defecto');
    $('#mod-celular').val(administradorAux.celular);
  });

  $('#modificar-administrador').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado-modificar').html('');
    $('#resultado-modificar').css({ color: 'black' });

    if ($('#mod-contra').val() === 'contraseña por defecto')
      $('#mod-contra').val('');

    const administrador = new FormData(form);

    const { resultado, codigo } = await (
      await fetch(
        `${API_URL}/administradores/modificar-administrador.php?id=${administradorAux.id}`,
        { method: 'post', body: administrador, cache: 'no-cache' }
      )
    ).json();

    if (codigo >= 400) {
      $('#resultado-modificar').html(resultado);
      $('#resultado-modificar').css({ color: 'red' });
    } else {
      $('#resultado-modificar').html(resultado);
      $('#resultado-modificar').css({ color: 'green' });

      $('#mod-ci').attr('disabled', '');
      $('#mod-nombre').attr('disabled', '');
      $('#mod-apellido').attr('disabled', '');
      $('#mod-correo').attr('disabled', '');
      $('#mod-contra').attr('disabled', '');
      $('#mod-celular').attr('disabled', '');
      $('#mod-submit').attr('disabled', '');
      $('#mod-reset').attr('disabled', '');

      $('#mod-ci').val('');
      $('#mod-nombre').val('');
      $('#mod-apellido').val('');
      $('#mod-correo').val('');
      $('#mod-contra').val('');
      $('#mod-celular').val('');
    }

    await cargarTabla();
  });
});

async function cargarTabla() {
  $('#resultado-borrar').html('');
  $('#resultado-borrar').css({ color: 'black' });

  $('#tabla-buscar').html(`Cargando...`);

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/administradores/ver-administradores.php`, {
      method: 'get',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) {
    $('#tabla-buscar').html(`Ocurrio un error en la carga...`);
    return;
  }

  $('#tabla-buscar').html(
    resultado
      .map(administrador => renderRowAdministrador(administrador))
      .join('')
  );

  $('*[data-action="borrar"]').on('click', borrar);
}

function renderRowAdministrador(administrador) {
  const { rol, ci, nombre, apellido, correo, celular } = administrador;
  return `<tr>
    <td>${rol}</td>
    <td>${ci}</td>
    <td>${nombre}</td>
    <td>${apellido}</td>
    <td>${correo}</td>
    <td>${celular}</td>
    <td class="p-0 bg-red-500">
      <button class="cta w-full aspect-square bg-red-500" type="button" data-ci="${ci}" data-action="borrar">X</button>
    </td>
  </tr>`;
}

async function borrar(e) {
  const { target } = e;
  const ci = target.getAttribute('data-ci');

  const { resultado, codigo } = await (
    await fetch(
      `${API_URL}/administradores/borrar-administrador.php?ci=${ci}`,
      {
        method: 'delete',
        cache: 'no-cache'
      }
    )
  ).json();
  await cargarTabla();
  $('#resultado-borrar').html(resultado);
  $('#resultado-borrar').css({ color: codigo >= 400 ? 'red' : 'green' });
}
