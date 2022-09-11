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

  const postProveedorToApi = async proveedor => {
    const url = `/ecommerce-natalia-viera/model/api/proveedores.php`;
    const cabeceras = {
      method: 'POST',
      body: JSON.stringify({ proveedor }),
    };

    return await llamadaFetch(url, cabeceras);
  };

  const $nombre = $('#add-nombre');
  const $correo = $('#add-correo');
  const $direccion = $('#add-direccion');
  const $numeroPuerta = $('#add-nropuerta');
  const $telefono = $('#add-telefono');

  $('#add-form').on('submit', async e => {
    e.preventDefault();

    const nombre = $nombre.val();
    const correo = $correo.val();
    const direccion = $direccion.val();
    const numeroPuerta = $numeroPuerta.val();
    const telefono = $telefono.val();

    const proveedor = {
      nombre,
      correo,
      calle: direccion,
      nro_puerta: numeroPuerta,
      telefonos: [telefono],
    };

    const { mensaje } = (await postProveedorToApi(proveedor)).data;

    $('#resultado').html(mensaje);
  });
});
