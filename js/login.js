$(() => {
  $('#btn-ingresar').on('click', async e => {
    e.preventDefault();

    const $correo = $('#correo');
    const $contra = $('#contra');
    const inputs = [$correo, $contra];

    inputs.forEach(input => $(`#error_${input.attr('id')}`).html(''));

    // TODO: validar que sea un gmail (se perdio por el preventDefault() :8)

    const res = await validarInputsVacios(inputs);
    const inputsVacios = Object.keys(res);

    if (inputsVacios.length === 0) $('#login-form').submit();

    inputsVacios.forEach(inputID => {
      $(`#error_${inputID}`).html('Este campo no puede estar vacio');
    });
  });
});
