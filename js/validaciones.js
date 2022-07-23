// TODO: usar variables de entorno para el host

async function validarInputsVacios(inputs) {
  const url = `http://localhost/natalia-viera-ecommerce/php/ValidacionInputsVacios.php`;
  const req = {};

  for (const input of inputs) {
    const id = input.attr('id');
    const value = input.val();
    req[id] = value;
  }

  const headers = {
    method: 'POST',
    body: JSON.stringify(req)
  };

  const res = await fetch(url, headers);
  const data = await res.json();

  return data;
}
