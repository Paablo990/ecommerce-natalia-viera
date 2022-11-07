import { navbar } from './componentes/navbar-administracion.js';
import { API_URL } from './utils/config.js';

let productoAux = {};

$(async () => {
  await navbar(['jefe', 'comprador']);
  await cargarTabla();

  $('#filtrar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;
    const { value: id } = form[0];

    $('#resultado-borrar').html('');
    $('#resultado-borrar').css({ color: 'black' });

    form[0].value = '';

    $('#tabla-buscar').html(`Cargando...`);

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/productos/ver-productos.php`, { method: 'get' })
    ).json();

    if (codigo >= 400) {
      $('#tabla-buscar').html(`Ocurrio un error en la carga...`);
      return;
    }

    if (id) {
      $('#tabla-buscar').html(
        resultado
          .filter(producto => producto.id === +id)
          .map(producto => renderRowProducto(producto))
          .join('') || 'No existe ese producto'
      );

      $('*[data-action="borrar"]').on('click', borrar);
    } else {
      $('#tabla-buscar').html(
        resultado.map(producto => renderRowProducto(producto)).join('')
      );

      $('*[data-action="borrar"]').on('click', borrar);
    }
  });

  await cargarProveedoresCrear();

  $('#agregar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado-crear').html('');
    $('#resultado-crear').css({ color: 'black' });

    const producto = new FormData(form);
    const { resultado, codigo } = await (
      await fetch(`${API_URL}/productos/crear-producto.php`, {
        method: 'post',
        body: producto
      })
    ).json();

    $('#resultado-crear').html(`${resultado}`);
    $('#resultado-crear').css({ color: codigo >= 400 ? 'red' : 'green' });
    await cargarTabla();
  });

  $('#agregar').on('reset', async () => {
    $('#resultado-crear').html('');
    $('#resultado-crear').css({ color: 'black' });
  });

  $('#mod-nombre').attr('disabled', '');
  $('#mod-imagen').attr('disabled', '');
  $('#mod-precio').attr('disabled', '');
  $('#mod-descuento').attr('disabled', '');
  $('#mod-stock').attr('disabled', '');
  $('#mod-categoria').attr('disabled', '');
  $('#mod-proveedor').attr('disabled', '');
  $('#mod-descripcion').attr('disabled', '');
  $('#mod-submit').attr('disabled', '');
  $('#mod-reset').attr('disabled', '');

  $('#mod-nombre').val('');
  $('#mod-imagen').val('');
  $('#mod-precio').val('');
  $('#mod-descuento').val('');
  $('#mod-stock').val('');
  $('#mod-descripcion').val('');

  await cargarProveedoresModificar();

  $('#modificar-buscar').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado-modificar').html('');
    $('#resultado-modificar').css({ color: 'black' });

    const id = form[0].value;

    const { resultado, codigo } = await (
      await fetch(`${API_URL}/productos/ver-producto.php?id=${id}`, {
        method: 'get'
      })
    ).json();

    if (codigo >= 400) {
      $('#resultado-modificar').html(resultado);
      $('#resultado-modificar').css({ color: 'red' });

      $('#mod-nombre').attr('disabled', '');
      $('#mod-imagen').attr('disabled', '');
      $('#mod-precio').attr('disabled', '');
      $('#mod-descuento').attr('disabled', '');
      $('#mod-stock').attr('disabled', '');
      $('#mod-categoria').attr('disabled', '');
      $('#mod-proveedor').attr('disabled', '');
      $('#mod-descripcion').attr('disabled', '');
      $('#mod-submit').attr('disabled', '');
      $('#mod-reset').attr('disabled', '');

      $('#mod-nombre').val('');
      $('#mod-imagen').val('');
      $('#mod-precio').val('');
      $('#mod-descuento').val('');
      $('#mod-stock').val('');
      $('#mod-descripcion').val('');

      return;
    }

    productoAux = resultado;

    $('#mod-nombre').removeAttr('disabled');
    $('#mod-imagen').removeAttr('disabled');
    $('#mod-precio').removeAttr('disabled');
    $('#mod-descuento').removeAttr('disabled');
    $('#mod-stock').removeAttr('disabled');
    $('#mod-categoria').removeAttr('disabled');
    $('#mod-proveedor').removeAttr('disabled');
    $('#mod-descripcion').removeAttr('disabled');
    $('#mod-submit').removeAttr('disabled');
    $('#mod-reset').removeAttr('disabled');

    $('#mod-nombre').val(productoAux.nombre);
    $('#mod-precio').val(productoAux.precio);
    $('#mod-descuento').val(productoAux.descuento);
    $('#mod-stock').val(productoAux.stock);

    const opcionesCategoria = Array.from($('#mod-categoria')[0]).map(op => {
      const cop = op.cloneNode(true);
      cop.removeAttribute('selected');

      if (cop.value === productoAux.categoria)
        cop.setAttribute('selected', 'true');

      return cop;
    });
    $('#mod-categoria')[0].innerHTML = '';
    $('#mod-categoria')[0].append(...opcionesCategoria);
    $('#mod-categoria')[0].defaultValue;

    const opcionesProveedor = Array.from($('#mod-proveedor')[0]).map(op => {
      const cop = op.cloneNode(true);
      cop.removeAttribute('selected');

      if (+cop.value === productoAux.proveedor.id_proveedor)
        cop.setAttribute('selected', 'true');

      return cop;
    });
    $('#mod-proveedor')[0].innerHTML = '';
    $('#mod-proveedor')[0].append(...opcionesProveedor);
    $('#mod-proveedor')[0].defaultValue;

    $('#mod-descripcion').val(productoAux.descripcion);
  });

  $('#modificar-producto').on('submit', async e => {
    e.preventDefault();
    const { target: form } = e;

    $('#resultado-modificar').html('');
    $('#resultado-modificar').css({ color: 'black' });

    const producto = new FormData(form);

    const { resultado, codigo } = await (
      await fetch(
        `${API_URL}/productos/modificar-producto.php?id=${productoAux.id}`,
        { method: 'post', body: producto }
      )
    ).json();

    if (codigo >= 400) {
      $('#resultado-modificar').html(resultado);
      $('#resultado-modificar').css({ color: 'red' });
    } else {
      $('#resultado-modificar').html(resultado);
      $('#resultado-modificar').css({ color: 'green' });
    }

    await cargarTabla();
  });

  $('#modificar-producto').on('reset', () => {
    $('#resultado-modificar').html('');
    $('#resultado-modificar').css({ color: 'black' });
  });

  $('*[data-action="borrar"]').on('click', borrar);
});

// load functions

async function cargarProveedoresCrear() {
  $('#proveedor').html('Cargando...');

  const { resultado: proveedores, codigo } = await (
    await fetch(`${API_URL}/proveedores/ver-proveedores.php`, {
      method: 'get'
    })
  ).json();

  if (codigo >= 400) {
    $('#proveedor').html('<option>Ocurrio un error en la carga...</option>');
    return;
  }

  $('#proveedor').html(
    proveedores.map(proveedor => renderOptionProveedor(proveedor)).join('')
  );
}

async function cargarProveedoresModificar() {
  $('#mod-proveedor').html('Cargando...');

  const { resultado: proveedores, codigo } = await (
    await fetch(`${API_URL}/proveedores/ver-proveedores.php`, {
      method: 'get'
    })
  ).json();

  if (codigo >= 400) {
    $('#mod-proveedor').html(
      '<option>Ocurrio un error en la carga...</option>'
    );
    return;
  }

  $('#mod-proveedor').html(
    proveedores.map(proveedor => renderOptionProveedor(proveedor)).join('')
  );
}

async function cargarTabla() {
  $('#resultado-borrar').html('');
  $('#resultado-borrar').css({ color: 'black' });

  $('#tabla-buscar').html(`Cargando...`);

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/productos/ver-productos.php`, { method: 'get' })
  ).json();

  if (codigo >= 400) {
    $('#tabla-buscar').html(`Ocurrio un error en la carga...`);
    return;
  }

  $('#tabla-buscar').html(
    resultado.map(producto => renderRowProducto(producto)).join('')
  );

  $('*[data-action="borrar"]').on('click', borrar);
}

// render functions

async function borrar(e) {
  const { target } = e;
  const id = target.getAttribute('data-id');

  const { resultado, codigo } = await (
    await fetch(`${API_URL}/productos/borrar-producto.php?id=${id}`, {
      method: 'delete'
    })
  ).json();

  await cargarTabla();

  $('#resultado-borrar').html(resultado);
  $('#resultado-borrar').css({ color: codigo >= 400 ? 'red' : 'green' });
}

function renderRowProducto(producto) {
  const { id, nombre, precio, ruta, descuento, stock, nombre_proveedor } =
    producto;

  return `<tr>
    <td>${id}</td>
    <td>${nombre}</td>
    <td>${nombre_proveedor}</td>
    <td>$${precio}</td>
    <td>${descuento}%</td>
    <td>${stock}</td>
    <td>
      <img src="img/${ruta}" width="64px" height="64px"/>
    </td>
    <td class="p-0 bg-red-500">
      <button class="cta w-full aspect-square bg-red-500" type="button" data-id="${id}" data-action="borrar">X</button>
    </td>
  </tr>`;
}

function renderOptionProveedor(proveedor) {
  const { id, nombre } = proveedor;
  return `<option value="${id}">${nombre}</option>`;
}
