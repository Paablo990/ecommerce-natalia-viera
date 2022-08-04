$(() => {
  const urlHasParams = url => url.indexOf('?') !== -1;

  const getParamByURL = (url, name) =>
    url
      .split('?')[1]
      .split('&')
      .find(param => param.split('=')[0] === name)
      ?.split('=')[1] || `error param not found`;

  const $window = $(window);
  const url = $window.attr('location').href;

  // TODO: crear una pagina de error en vez de redireccionar al index

  if (!urlHasParams(url)) {
    $window.attr('location').href = './';
    return;
  }
  if (getParamByURL(url, 'id') === 'error param not found') {
    $window.attr('location').href = './';
  }

  // const productId = Number(getParamByURL(url, 'id'));
});
