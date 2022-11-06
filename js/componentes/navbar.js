import {
  obtenerDatosDeSesion,
  estaEnSesion
} from './../utils/validar-sesion.js';

const ITEMS_CON_SESION = [
  { link: './', label: 'Inicio' },
  { link: './productos.html', label: 'Productos' },
  { link: './paquetes.html', label: 'Paquetes' },
  { link: './carrito.html', label: 'Carrito' },
  { link: './pedidos.html', label: 'Pedidos' },
  { link: './', label: 'Cerrar Sesión', id: 'cerrar-sesion' }
];

export async function navbar(isIndex) {
  const conSesion = estaEnSesion();

  if (!conSesion) {
    return;
  }

  const { rol } = (await obtenerDatosDeSesion()) ?? {};

  if (rol === undefined) {
    localStorage.clear();
    if (isIndex) return;
    return;
  }

  if (rol !== 'cliente') {
    window.location.replace('./administracion.html');
  }

  const menuItems = ITEMS_CON_SESION;
  $('#menu').html(menuItems.map(item => renderMenuItem(item)).join(''));

  $('#cerrar-sesion').on('click', e => {
    e.preventDefault();
    localStorage.clear();
    window.location.href = './';
  });
}

function renderMenuItem(item) {
  const { link, label, id } = item;

  return `
    <li><a class="${!id ? `p-2` : `cta`}" href="${link}" ${
    id ? `id="${id}"` : ``
  }>${label}</a></li>
  `;
}
