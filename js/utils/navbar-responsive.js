$(() => {
  $(window).on('resize', e => {
    if (window.innerWidth >= 765) {
      const isVisible = $('#menu2').attr('data-visible');

      if (isVisible === 'true') return;
      $('#menu2').attr('data-visible', 'true');
    }
  });
  $('#toggle-menu').on('click', e => {
    const isVisible = $('#menu2').attr('data-visible');
    $('#menu2').attr('data-visible', isVisible === 'true' ? 'false' : 'true');
  });
});
