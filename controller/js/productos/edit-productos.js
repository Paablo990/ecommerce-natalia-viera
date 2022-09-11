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

  const getProductosFromApi = async () => {
    const url = `/ecommerce-natalia-viera/model/api/productos.php`;
    return await llamadaFetch(url, { method: 'GET' });
  };

  const getProductosByIdFromApi = async id => {
    const url = `/ecommerce-natalia-viera/model/api/productos.php?id=${id}`;
    return await llamadaFetch(url, { method: 'GET' });
  };

  const putProductoInApi = async (id, producto) => {
    const url = `/ecommerce-natalia-viera/model/api/productos.php?id=${id}`;
    const cabeceras = {
      method: 'PUT',
      body: JSON.stringify({ producto }),
    };

    return await llamadaFetch(url, cabeceras);
  };

  const { productos } = (await getProductosFromApi()).data;
  const { proveedores } = (await getProveedoresFromApi()).data;

  const selectProveedoresHTML = proveedores
    .map(proveedor => renderOptionProveedor(proveedor))
    .join('');

  const $addSelectProveedores = $('#edit-proveedor');
  $addSelectProveedores.html(selectProveedoresHTML);

  const $id = $('#edit-id');
  const $nombre = $('#edit-nombre');
  const $precio = $('#edit-precio');
  const $descuento = $('#edit-descuento');
  const $stock = $('#edit-stock');
  const $descripcion = $('#edit-descripcion');
  const $proveedores = $('#edit-proveedor');

  let _imagenes;

  const cargarTabla = productos => {
    const tablaProductosHTML = productos
      .map(producto => renderFilaProducto(producto))
      .join('');

    const $tablaProductos = $('#tabla-productos');
    $tablaProductos.html(tablaProductosHTML);

    $(`button[data-edit="true"]`).on('click', async e => {
      const { target } = e;
      const idProd = target.getAttribute('data-id');

      const { producto } = (await getProductosByIdFromApi(idProd)).data;
      const {
        nombre,
        precio,
        descuento,
        stock,
        descripcion,
        proveedor,
        imagenes,
      } = producto;
      const { id_proveedor } = proveedor;

      console.log(idProd);

      cargarDatos({
        idProd,
        nombre,
        precio,
        descuento,
        imagenes,
        stock,
        descripcion,
        id_proveedor,
      });
    });
  };

  cargarTabla(productos);

  $('#edit-form').on('submit', async e => {
    e.preventDefault();

    const id = $id.val();
    const nombre = $nombre.val();
    const precio = Number($precio.val());
    const descuento = Number($descuento.val());
    const stock = Number($stock.val());
    const descripcion = $descripcion.val();
    const proveedor = Number($proveedores.val());

    const producto = {
      nombre,
      precio,
      imagenes: _imagenes,
      descuento,
      stock,
      descripcion,
      id_proveedor: proveedor,
    };

    const { mensaje } = (await putProductoInApi(id, producto)).data;
    $('#resultado').html(mensaje);

    location.reload();
  });

  $id.val('');
  $nombre.val('');
  $precio.val('');
  $descuento.val('');
  $stock.val('');
  $descripcion.val('');
  $proveedores.val('');

  $nombre.attr('disabled', '');
  $precio.attr('disabled', '');
  $descuento.attr('disabled', '');
  $stock.attr('disabled', '');
  $descripcion.attr('disabled', '');
  $proveedores.attr('disabled', '');

  function cargarDatos({
    idProd,
    nombre,
    precio,
    descuento,
    imagenes,
    stock,
    descripcion,
    id_proveedor,
  }) {
    _imagenes = imagenes;
    $nombre.val(nombre);
    $precio.val(precio);
    $descuento.val(descuento);
    $stock.val(stock);
    $descripcion.val(descripcion);
    $id.val(idProd);

    $proveedores.html(
      proveedores
        .map(proveedores => {
          const { id, nombre } = proveedores;
          return `
          <option value="${id}" ${id == id_proveedor ? 'selected' : ''}>
            ${nombre}
          </option>
          `;
        })
        .join('')
    );

    $nombre.removeAttr('disabled');
    $precio.removeAttr('disabled');
    $descuento.removeAttr('disabled');
    $stock.removeAttr('disabled');
    $descripcion.removeAttr('disabled');
    $proveedores.removeAttr('disabled');
  }

  function renderFilaProducto(producto) {
    const { id, nombre, precio, descuento, stock } = producto;
    return `
    <tr>
      <td>${id}</td>
      <td>${nombre}</td>
      <td>${precio}</td>
      <td>${descuento}</td>
      <td>${stock}</td>
      <td>
      <button data-id="${id}" data-edit="true" type="button">
        EDITAR
      </button>
    </td>
    </tr>
    `;
  }

  function renderOptionProveedor(proveedor) {
    const { id, nombre } = proveedor;
    return `<option value="${id}">${nombre}</option>`;
  }
});
