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

  const postAdministradorToApi = async (administrador, rol) => {
    const url = `/ecommerce-natalia-viera/model/api/administradores.php?${rol}=true`;
    const cabeceras = {
      method: 'POST',
      body: JSON.stringify({ administrador }),
    };

    return await llamadaFetch(url, cabeceras);
  };

  const $ci = $('#add-ci');
  const $nombre = $('#add-nombre');
  const $apellido = $('#add-apellido');
  const $correo = $('#add-correo');
  const $contra = $('#add-contra');
  const $telefono = $('#add-telefono');
  const $rol = $('#add-rol');

  $('#add-form').on('submit', async e => {
    e.preventDefault();

    const ci = $ci.val();
    const nombre = $nombre.val();
    const apellido = $apellido.val();
    const correo = $correo.val();
    const contra = $contra.val();
    const telefono = $telefono.val();
    const rol = $rol.val();

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

    const { mensaje } = (await postAdministradorToApi(administrador, rol)).data;
    $('#resultado').html(mensaje);
  });
});
