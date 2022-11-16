import { navbar } from './componentes/navbar-administracion.js';
import { API_URL } from './utils/config.js';

$(async () => {
  await navbar(['jefe', 'vendedor']);

  await cargarTablaUsuariosNA();

  $('#filtrar-na').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;
    const { value: ci } = form[0];

    $('#resultado-na').html('');
    $('#resultado-na').css({ color: 'black' });

    form[0].value = '';

    $('#tabla-usuarios-na').html(`Cargando...`);

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/usuarios/ver-usuarios-na.php`, {
        method: 'get',
        cache: 'no-cache'
      })
    ).json();

    if (codigo >= 400) {
      $('#tabla-usuarios-na').html(`Ocurrio un error en la carga...`);
      return;
    }

    if (ci) {
      $('#tabla-usuarios-na').html(
        resultado
          .filter(usuario => usuario.ci === ci)
          .map(usuario => renderRowUsuarioNA(usuario))
          .join('') || 'No existe ese usuario'
      );
    } else {
      $('#tabla-usuarios-na').html(
        resultado.map(usuario => renderRowUsuarioNA(usuario)).join('')
      );
    }
  });

  await cargarTablaUsuarios();

  $('#filtrar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;
    const { value: ci } = form[0];

    $('#resultado').html('');
    $('#resultado').css({ color: 'black' });

    form[0].value = '';

    $('#tabla-usuarios').html(`Cargando...`);

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/usuarios/ver-usuarios.php`, {
        method: 'get',
        cache: 'no-cache'
      })
    ).json();

    if (codigo >= 400) {
      $('#tabla-usuarios').html(`Ocurrio un error en la carga...`);
      return;
    }

    if (ci) {
      $('#tabla-usuarios').html(
        resultado
          .filter(usuario => usuario.ci === ci)
          .map(usuario => renderRowUsuario(usuario))
          .join('') || 'No existe ese usuario'
      );
    } else {
      $('#tabla-usuarios').html(
        resultado.map(usuario => renderRowUsuario(usuario)).join('')
      );
    }
  });
});

async function cargarTablaUsuariosNA() {
  $('#resultado-na').html('');
  $('#resultado-na').css({ color: 'black' });

  $('#tabla-usuarios-na').html(`Cargando...`);

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/usuarios/ver-usuarios-na.php`, {
      method: 'get',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) {
    $('#tabla-usuarios-na').html(`Ocurrio un error en la carga...`);
    return;
  }

  $('#tabla-usuarios-na').html(
    resultado.map(usuario => renderRowUsuarioNA(usuario)).join('')
  );

  $('*[data-action="denegar"]').on('click', denegar);
  $('*[data-action="aceptar"]').on('click', aceptar);
}

async function cargarTablaUsuarios() {
  $('#resultado').html('');
  $('#resultado').css({ color: 'black' });

  $('#tabla-usuarios').html(`Cargando...`);

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/usuarios/ver-usuarios.php`, {
      method: 'get',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) {
    $('#tabla-usuarios').html(`Ocurrio un error en la carga...`);
    return;
  }

  $('#tabla-usuarios').html(
    resultado.map(usuario => renderRowUsuario(usuario)).join('')
  );

  $('*[data-action="suspender"]').on('click', suspender);
}

async function aceptar(e) {
  const { target } = e;
  const ci = target.getAttribute('data-ci');

  $('#resultado-na').html('');
  $('#resultado-na').css({ color: 'black' });

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/usuarios/aceptar-usuario.php?ci=${ci}`, {
      method: 'post',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) {
    $('#resultado-na').html(resultado);
    $('#resultado-na').css({ color: 'red' });

    return;
  }

  await cargarTablaUsuariosNA();
  await cargarTablaUsuarios();

  $('#resultado-na').html(resultado);
  $('#resultado-na').css({ color: 'green' });
}

async function denegar(e) {
  const { target } = e;
  const ci = target.getAttribute('data-ci');

  $('#resultado-na').html('');
  $('#resultado-na').css({ color: 'black' });

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/usuarios/denegar-usuario.php?ci=${ci}`, {
      method: 'post',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) {
    $('#resultado-na').html(resultado);
    $('#resultado-na').css({ color: 'red' });

    return;
  }

  await cargarTablaUsuariosNA();

  $('#resultado-na').html(resultado);
  $('#resultado-na').css({ color: 'green' });
}

async function suspender(e) {
  const { target } = e;
  const ci = target.getAttribute('data-ci');

  $('#resultado').html('');
  $('#resultado').css({ color: 'black' });

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/usuarios/suspender-usuario.php?ci=${ci}`, {
      method: 'post',
      cache: 'no-cache'
    })
  ).json();

  if (codigo >= 400) {
    $('#resultado').html(resultado);
    $('#resultado').css({ color: 'red' });

    return;
  }

  await cargarTablaUsuariosNA();
  await cargarTablaUsuarios();

  $('#resultado').html(resultado);
  $('#resultado').css({ color: 'green' });
}

function renderRowUsuarioNA(usuario) {
  const { ci, nombre, apellido, correo, celular } = usuario;
  return `
    <tr>
      <td>${ci}</td>
      <td>${nombre}</td>
      <td>${apellido}</td>
      <td>${correo}</td>
      <td>${celular}</td>
      <td class="p-0 bg-natalia-blue-400">
        <button class="cta w-full" type="button" data-ci="${ci}" data-action="denegar">
          Denegar
        </button>
      </td>
      <td class="p-0 bg-natalia-blue-400">
        <button class="cta w-full" type="button" data-ci="${ci}" data-action="aceptar">
          Aceptar
        </button>
      </td>
    </tr>;
  `;
}

function renderRowUsuario(usuario) {
  const { ci, nombre, apellido, correo, celular } = usuario;
  return `
    <tr>
      <td>${ci}</td>
      <td>${nombre}</td>
      <td>${apellido}</td>
      <td>${correo}</td>
      <td>${celular}</td>
      <td class="p-0 bg-natalia-blue-400">
        <button class="cta w-full" type="button" data-ci="${ci}" data-action="suspender">
          Suspender
        </button>
      </td>
    </tr>;
  `;
}
