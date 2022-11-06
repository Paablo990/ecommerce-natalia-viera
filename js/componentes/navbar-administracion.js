import {
  obtenerDatosDeSesion,
  estaEnSesion
} from './../utils/validar-sesion.js';

const ITEMS_JEFE = [
  { link: './administracion-usuarios.html', label: 'Clientes' },
  { link: './administracion-pedidos.html', label: 'Pedidos' },
  { link: './crud-administradores.html', label: 'Administradores' },
  { link: './crud-productos.html', label: 'Productos' },
  { link: './crud-proveedores.html', label: 'Proveedores' },
  { link: './crud-paquetes.html', label: 'Paquetes' },
  { link: './', label: 'Cerrar Sesión', id: 'cerrar-sesion' }
];

const ITEMS_VENDEDOR = [
  { link: './administracion-usuarios.html', label: 'Clientes' },
  { link: './administracion-pedidos.html', label: 'Pedidos' },
  { link: './', label: 'Cerrar Sesión', id: 'cerrar-sesion' }
];

const ITEMS_COMPRADOR = [
  { link: './crud-productos.html', label: 'Productos' },
  { link: './crud-proveedores.html', label: 'Proveedores' },
  { link: './crud-paquetes.html', label: 'Paquetes' },
  { link: './', label: 'Cerrar Sesión', id: 'cerrar-sesion' }
];

const MENU_ITEMS = {
  jefe: ITEMS_JEFE,
  vendedor: ITEMS_VENDEDOR,
  comprador: ITEMS_COMPRADOR
};

export async function navbar(roles) {
  const conSesion = estaEnSesion();

  if (!conSesion) {
    window.location.replace('./');
    return;
  }

  const { rol } = (await obtenerDatosDeSesion()) ?? {};

  if (rol === undefined) {
    localStorage.clear();
    window.location.replace('./');
  }

  if (!roles.includes(rol)) {
    window.location.replace('./');
  }

  const menuItems = MENU_ITEMS[rol];
  $('#menu').html(menuItems.map(item => renderMenuItem(item)).join(''));

  $('#cerrar-sesion').on('click', e => {
    e.preventDefault();
    localStorage.clear();

    window.location.replace('./');
  });
}

function renderMenuItem(item) {
  const { link, label, id } = item;

  return `
    <li><a href="${link}" ${id ? `id="${id}"` : ``}>${label}</a></li>
  `;
}
