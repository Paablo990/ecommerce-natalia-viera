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

  const getProveedoresFromApi = async () => {
    const url = `/ecommerce-natalia-viera/model/api/proveedores.php`;
    return await llamadaFetch(url, { method: 'GET' });
  };

  const deleteProveedorFromApi = async id => {
    const url = `/ecommerce-natalia-viera/model/api/proveedores.php?id=${id}`;
    return await llamadaFetch(url, { method: 'DELETE' });
  };

  const { proveedores } = (await getProveedoresFromApi()).data;

  const cargarProveedores = async proveedores => {
    const $proveedoresTabla = $('#tabla-proveedores');
    $proveedoresTabla.html(
      proveedores
        .map(proveedor => {
          const { id, nombre, correo, calle, nro_puerta, telefono } = proveedor;

          return `<tr>
        <td>${id}</td>
        <td>${nombre}</td>
        <td>${correo}</td>
        <td>${calle}</td>
        <td>${nro_puerta}</td>
        <td>${telefono}</td>
        <td><button data-id="${id}" data-borrar="true" type="button">
        BORRAR
      </button></td>
      </tr>;`;
        })
        .join('')
    );

    $(`button[data-borrar="true"]`).on('click', async e => {
      const { target } = e;
      const id = target.getAttribute('data-id');

      await deleteProveedorFromApi(id);

      const { proveedores } = (await getProveedoresFromApi()).data;

      cargarProveedores(proveedores);
    });
  };

  cargarProveedores(proveedores);
});
