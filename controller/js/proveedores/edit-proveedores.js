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

  const getProveedorByIdFromApi = async id => {
    const url = `/ecommerce-natalia-viera/model/api/proveedores.php?id=${id}`;
    return await llamadaFetch(url, { method: 'GET' });
  };

  const getProveedoresFromApi = async () => {
    const url = `/ecommerce-natalia-viera/model/api/proveedores.php`;
    return await llamadaFetch(url, { method: 'GET' });
  };

  const putProveedorInApi = async (proveedor, id) => {
    const url = `/ecommerce-natalia-viera/model/api/proveedores.php?id=${id}`;
    const cabeceras = {
      method: 'PUT',
      body: JSON.stringify({ proveedor }),
    };

    return await llamadaFetch(url, cabeceras);
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
      <td>
        <button data-id="${id}" data-edit="true" type="button">
          EDITAR
        </button>
      </td>
    </tr>;`;
        })
        .join('')
    );
  };
  await cargarProveedores(proveedores);

  const $id = $('#edit-id');
  const $nombre = $('#edit-nombre');
  const $correo = $('#edit-correo');
  const $direccion = $('#edit-direccion');
  const $numeroPuerta = $('#edit-nropuerta');
  const $telefono = $('#edit-telefono');

  $('button[data-edit="true"]').on('click', async e => {
    const idProd = e.target.getAttribute('data-id');
    const { proveedor } = (await getProveedorByIdFromApi(idProd)).data;

    const { nombre, correo, calle, nro_puerta, telefono } = proveedor;

    console.log(proveedor);

    $id.val(idProd);
    $nombre.val(nombre);
    $correo.val(correo);
    $direccion.val(calle);
    $numeroPuerta.val(nro_puerta);
    $telefono.val(telefono);

    $nombre.removeAttr('disabled');
    $correo.removeAttr('disabled');
    $direccion.removeAttr('disabled');
    $numeroPuerta.removeAttr('disabled');
    $telefono.removeAttr('disabled');
  });

  $('#edit-form').on('submit', async e => {
    e.preventDefault();

    const id = $id.val();
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
      telefono: [telefono],
    };

    const { mensaje } = await putProveedorInApi(proveedor, id);

    location.reload();
  });

  $id.val('');
  $nombre.val('');
  $correo.val('');
  $direccion.val('');
  $numeroPuerta.val('');
  $telefono.val('');

  $nombre.attr('disabled', 'true');
  $correo.attr('disabled', 'true');
  $direccion.attr('disabled', 'true');
  $numeroPuerta.attr('disabled', 'true');
  $telefono.attr('disabled', 'true');
});
