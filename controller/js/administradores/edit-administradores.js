$(async () => {
  const llamadaFetch = async (url, cabecera) => {
    const respose = await fetch(url, cabecera);
    const { status } = respose;
    const data = await respose.json();

    const res = {
      status,
      data,
    };

    return res;
  };

  const putAdministradorInApi = async (id, administrador) => {
    const url = `/ecommerce-natalia-viera/model/api/administradores.php?id=${id}`;
    const cabeceras = {
      method: 'PUT',
      body: JSON.stringify({ administrador }),
    };

    return await llamadaFetch(url, cabeceras);
  };

  const getAdministradorByIdFromApi = async id => {
    const url = `/ecommerce-natalia-viera/model/api/administradores.php?id=${id}`;
    return await llamadaFetch(url, { method: 'GET' });
  };

  const getAdministradoresFromApi = async () => {
    const url = `/ecommerce-natalia-viera/model/api/administradores.php`;
    return await llamadaFetch(url, { method: 'GET' });
  };

  const { administradores } = (await getAdministradoresFromApi()).data;

  const cargarTabla = async administradores => {
    const tablaAdministradoresHTML = administradores
      .map(administrador => renderFilaAdministrador(administrador))
      .join('');

    const $tablaAdministradores = $('#tabla-administradores');
    $tablaAdministradores.html(tablaAdministradoresHTML);
  };
  await cargarTabla(administradores);

  const $id = $('#edit-id');
  const $ci = $('#edit-ci');
  const $nombre = $('#edit-nombre');
  const $apellido = $('#edit-apellido');
  const $correo = $('#edit-correo');
  const $contra = $('#edit-contra');
  const $telefono = $('#edit-telefono');

  $('button[data-edit="true"]').on('click', async e => {
    const { target } = e;
    const idAdministrador = target.getAttribute('data-id');

    const { administrador } = (
      await getAdministradorByIdFromApi(idAdministrador)
    ).data;

    const { id, ci, nombre_1, apellido_1, correo, contra, celulares } =
      administrador;

    $id.val(id);
    $ci.val(ci);
    $nombre.val(nombre_1);
    $apellido.val(apellido_1);
    $correo.val(correo);
    $contra.val(contra);
    $telefono.val(celulares);

    $ci.removeAttr('disabled');
    $nombre.removeAttr('disabled');
    $apellido.removeAttr('disabled');
    $correo.removeAttr('disabled');
    $contra.removeAttr('disabled');
    $telefono.removeAttr('disabled');
  });

  $('#edit-form').on('submit', async e => {
    e.preventDefault();

    const id = Number($id.val());
    const ci = $ci.val();
    const nombre = $nombre.val();
    const apellido = $apellido.val();
    const correo = $correo.val();
    const contra = $contra.val();
    const telefono = $telefono.val();

    const administrador = {
      ci,
      nombre_1: nombre,
      nombre_2: null,
      apellido_1: apellido,
      apellido_2: null,
      correo,
      contra,
      celulares: [telefono],
    };

    console.log(id);

    const { mensaje } = (await putAdministradorInApi(id, administrador)).data;
    $('#resultado').html(mensaje);

    location.reload();
  });

  $ci.val('');
  $nombre.val('');
  $apellido.val('');
  $correo.val('');
  $contra.val('');
  $telefono.val('');

  $ci.attr('disabled', 'true');
  $nombre.attr('disabled', 'true');
  $apellido.attr('disabled'), 'true';
  $correo.attr('disabled', 'true');
  $contra.attr('disabled', 'true');
  $telefono.attr('disabled', 'true');

  function renderFilaAdministrador(administrador) {
    const { id, rol, ci, nombre_1, apellido_1, correo, celulares } =
      administrador;

    return `<tr>
    <td>${rol}</td>
    <td>${ci}</td>
    <td>${nombre_1}</td>
    <td>${apellido_1}</td>
    <td>${correo}</td>
    <td>${celulares}</td>
    <td>
      <button data-id="${id}" data-edit="true" type="button">
        EDITAR
      </button>
    </td>
  </tr>`;
  }
});
