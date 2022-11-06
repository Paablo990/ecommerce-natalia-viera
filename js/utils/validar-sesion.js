import { API_URL } from './config.js';

export function estaEnSesion() {
  const correo = localStorage.getItem('correo');
  const contra = localStorage.getItem('contra');

  return correo !== null && contra !== null;
}

export async function obtenerDatosDeSesion() {
  const correo = localStorage.getItem('correo');
  const contra = localStorage.getItem('contra');

  const data = new FormData();

  data.append('correo', correo);
  data.append('contra', contra);

  const fetchId = async data => {
    const { datos, codigo } = await (
      await fetch(`${API_URL}/usuarios/inicio-de-sesion.php`, {
        method: 'post',
        body: data
      })
    ).json();

    return { datos, codigo };
  };

  const { datos, codigo } = await fetchId(data);

  if (codigo >= 400) return null;

  const { id, rol } = datos;

  const fetchDatos = async id => {
    const { resultado } = await (
      await fetch(`${API_URL}/usuarios/ver-usuario.php?id=${id}`, {
        method: 'get'
      })
    ).json();

    return { resultado };
  };

  const { resultado } = await fetchDatos(id);
  resultado['rol'] = rol;

  return resultado;
}
