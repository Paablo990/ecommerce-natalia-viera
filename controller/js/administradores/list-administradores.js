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

  const getAdministradoresFromApi = async () => {
    const url = `/ecommerce-natalia-viera/model/api/administradores.php`;
    return await llamadaFetch(url, { method: 'GET' });
  };

  const { administradores } = (await getAdministradoresFromApi()).data;

  const cargarTabla = administradores => {
    const tablaAdministradoresHTML = administradores
      .map(administrador => renderFilaAdministrador(administrador))
      .join('');

    const $tablaAdministradores = $('#tabla-administradores');
    $tablaAdministradores.html(tablaAdministradoresHTML);
  };
  cargarTabla(administradores);

  function renderFilaAdministrador(administrador) {
    const { rol, ci, nombre_1, apellido_1, correo, celulares } = administrador;

    return `<tr>
    <td>${rol}</td>
    <td>${ci}</td>
    <td>${nombre_1}</td>
    <td>${apellido_1}</td>
    <td>${correo}</td>
    <td>${celulares}</td>
  </tr>`;
  }
});
