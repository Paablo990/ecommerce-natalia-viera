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

  const { proveedores } = (await getProveedoresFromApi()).data;

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
    </tr>;`;
      })
      .join('')
  );
});
