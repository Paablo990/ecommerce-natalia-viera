import { API_URL } from './utils/config.js';

$(() => {
  $('#registro').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    const usuario = new FormData(form);

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/usuarios/registrar-usuario.php`, {
        method: 'post',
        body: usuario
      })
    ).json();

    if (codigo >= 400) {
      $('#resultado').html(resultado);
      $('#resultado').css({ color: 'red' });

      return;
    }

    $('#resultado').html(resultado);
    $('#resultado').css({ color: 'green' });

    form.reset();

    window.location.replace('./');
  });

  $('#registro').on('reset', () => {
    $('#resultado').html('');
    $('#resultado').css({ color: 'black' });
  });
});
