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
    </tr>
    `;
  }
});
