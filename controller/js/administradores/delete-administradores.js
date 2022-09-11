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

  const deleteAdministradorFromApi = async id => {
    const url = `/ecommerce-natalia-viera/model/api/administradores.php?id=${id}`;
    return await llamadaFetch(url, { method: 'DELETE' });
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

  $('button[data-delete="true"]').on('click', async e => {
    const { target } = e;
    const idProd = target.getAttribute('data-id');

    console.log(idProd);

    await deleteAdministradorFromApi(idProd);

    location.reload();
  });

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
      <button data-id="${id}" data-delete="true" type="button">
        BORRAR
      </button>
    </td>
  </tr>`;
  }
});
