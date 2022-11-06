import { navbar } from './componentes/navbar-administracion.js';

$(async () => {
  await navbar(['comprador', 'vendedor', 'jefe']);
});
