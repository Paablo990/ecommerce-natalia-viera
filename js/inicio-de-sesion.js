import { API_URL } from './utils/config.js';

$(() => {
  $('#inicio-de-sesion').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    const usuario = new FormData(form);

    const { datos, resultado, codigo } = await (
      await fetch(`${API_URL}/usuarios/inicio-de-sesion.php`, {
        method: 'post',
        body: usuario
      })
    ).json();

    if (codigo >= 400) {
      $('#resultado').html(resultado);
      $('#resultado').css({ color: 'red' });

      return;
    }

    localStorage.setItem('correo', $('#correo').val());
    localStorage.setItem('contra', $('#contra').val());

    const { rol } = datos;

    if (rol !== 'cliente') {
      window.location.replace('./administracion.html');
      return;
    }

    window.location.replace('./');
  });

  $('#inicio-de-sesion').on('reset', () => {
    $('#resultado').html('');
    $('#resultado').css({ color: 'black' });
  });
});
