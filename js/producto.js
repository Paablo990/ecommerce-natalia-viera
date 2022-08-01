$(() => {
  const $window = $(window);
  const url = $window.attr('location').href;

  //TODO: crear una pagina de error en vez de redireccionar al index

  if (!urlHasParams(url)) {
    $window.attr('location').href = './';
    return;
  }
  if (getParamByURL(url, 'id') === 'error param not found') {
    $window.attr('location').href = './';
    return;
  }

  const productId = Number(getParamByURL(url, 'id'));
  console.log(productId);

  function urlHasParams(url) {
    return url.indexOf('?') !== -1;
  }

  function getParamByURL(url, name) {
    return (
      url
        .split('?')[1]
        .split('&')
        .find(param => param.split('=')[0] === name)
        ?.split('=')[1] || `error param not found`
    );
  }
});
