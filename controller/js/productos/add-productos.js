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

  const postProductoToApi = async producto => {
    const url = `/ecommerce-natalia-viera/model/api/productos.php`;
    const cabeceras = {
      method: 'POST',
      body: JSON.stringify({ producto }),
    };

    return await llamadaFetch(url, cabeceras);
  };

  const getProveedoresFromApi = async () => {
    const url = `/ecommerce-natalia-viera/model/api/proveedores.php`;
    return await llamadaFetch(url, { method: 'GET' });
  };

  const { proveedores } = (await getProveedoresFromApi()).data;

  const selectProveedoresHTML = proveedores
    .map(proveedor => renderOptionProveedor(proveedor))
    .join('');

  const $addSelectProveedores = $('#add-proveedor');
  $addSelectProveedores.html(selectProveedoresHTML);

  const $nombre = $('#add-nombre');
  const $precio = $('#add-nombre');
  const $descuento = $('#add-nombre');
  const $stock = $('#add-nombre');
  const $descripcion = $('#add-nombre');
  const $imagenes = Object.values($('#add-imagenes').prop('files'));
  const $proveedores = $('#add-proveedor');

  $('#add-form').on('submit', async e => {
    e.preventDefault();

    const nombre = $nombre.val();
    const precio = $precio.val();
    const descuento = $descuento.val();
    const stock = $stock.val();
    const descripcion = $descripcion.val();
    const imagenes = $imagenes.map(({ name }) => name);
    const proveedor = $proveedores.val();

    const producto = {
      nombre,
      precio,
      descuento,
      stock,
      descripcion,
      imagenes,
      id_proveedor: proveedor,
    };

    const { mensaje } = (await postProductoToApi(producto)).data;

    $('#resultado').html(mensaje);
  });

  function renderOptionProveedor(proveedor) {
    const { id, nombre } = proveedor;
    return `<option value="${id}">${nombre}</option>`;
  }
});
