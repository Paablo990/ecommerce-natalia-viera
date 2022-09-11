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

  const deleteProductoFromApi = async id => {
    const url = `/ecommerce-natalia-viera/model/api/productos.php?id=${id}`;
    return await llamadaFetch(url, { method: 'DELETE' });
  };

  const getProductosFromApi = async () => {
    const url = `/ecommerce-natalia-viera/model/api/productos.php`;
    return await llamadaFetch(url, { method: 'GET' });
  };

  const { productos } = (await getProductosFromApi()).data;

  const cargarTabla = productos => {
    const tablaProductosHTML = productos
      .map(producto => renderFilaProducto(producto))
      .join('');

    const $tablaProductos = $('#tabla-productos');
    $tablaProductos.html(tablaProductosHTML);

    $(`button[data-borrar="true"]`).on('click', async e => {
      const { target } = e;
      const id = target.getAttribute('data-id');

      await deleteProductoFromApi(id);

      const { productos } = (await getProductosFromApi()).data;

      cargarTabla(productos);
    });
  };

  cargarTabla(productos);

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
        <button data-id="${id}" data-borrar="true" type="button">
          BORRAR
        </button>
      </td>
    </tr>
    `;
  }
});
