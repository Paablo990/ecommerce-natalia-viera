$(() => {
  $('#btn-crear').on('click', async e => {
    e.preventDefault();

    // TODO: recuperar telefono, nombre y cedula
    const $correo = $('#correo');
    const $contra = $('#contra');
    const inputs = [$correo, $contra];

    inputs.forEach(input => $(`#error_${input.attr('id')}`).html(''));

    // TODO: validar que sea un gmail (se perdio por el preventDefault() :8)
    // TODO: validar que el telefono sea valido
    // TODO: validar que la cedula sea valida

    const res = await validarInputsVacios(inputs);
    const inputsVacios = Object.keys(res);

    if (inputsVacios.length === 0) $('#register-form').submit();

    inputsVacios.forEach(inputID => {
      $(`#error_${inputID}`).html('Este campo no puede estar vacio');
    });
  });
});
