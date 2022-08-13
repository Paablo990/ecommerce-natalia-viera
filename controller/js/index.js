const renderProducto = producto => {
  const { id, img, titulo, descripcion, precio, descuento } = producto;
  const precioFinal = precio - (precio * descuento) / 100;

  return `
  <li
    class="flex transform border border-gray-300 bg-white pr-4 pb-4 pt-4 transition-all focus-within:-translate-y-2 focus-within:shadow-md hover:-translate-y-2 hover:shadow-md sm:flex-col sm:p-2">
    <a href="#" class="max-w-[40%] p-2 sm:max-w-none">
      <img
        src="./assets/products/${img}"
        alt="${titulo}" loading="lazy" class="object-cover h-full" />
    </a>
    <div>
      <h4
        class="deco-horizontal relative block pb-4 text-2xl font-bold leading-none before:absolute before:bottom-0 before:left-0 before:h-[1px] before:w-full before:bg-natalia-gray-300 sm:text-xl">
        ${titulo}
      </h4>
      <p class="pt-4 text-xs leading-relaxed text-gray-600">
        ${descripcion}
      </p>
      <div class="flex items-center justify-between pt-4 md:flex-col md:gap-2 xl:flex-row xl:gap-0">
        <span class="font-semibold relative">
          $${precioFinal}
          ${
            descuento > 0
              ? `<span class="absolute -top-2 -right-2 line-through text-red-500 text-xs whitespace-nowrap">$${precio}</span>`
              : ``
          }
        </span>
        <button id="producto-${id}" type="button" class="cta product">
          Agregar
        </button>
      </div>
    </div>
  </li>
  `;
};

const renderPaquetes = paquete => {
  const { id, imgs, titulo, descripcion, precio, descuento } = paquete;
  const { img1, img2, img3 } = imgs;

  const precioFinal = precio - (precio * descuento) / 100;

  return `<li
    class="border border-gray-300 transition-all focus-within:-translate-y-2 focus-within:shadow-md hover:-translate-y-2 hover:shadow-md">
    <a href="#" class="grid grid-cols-3 grid-rows-2">
      <img
        class="col-span-2 row-span-2 h-full border-gray-300 object-cover p-2"
        src="./assets/products/${img1}"
        alt="${titulo}"
      />
      <img
        class="col-start-3 border-gray-300 object-cover p-2"
        src="./assets/products/${img2}"
        alt="${titulo}"
      />
      <img
        class="col-start-3 border-gray-300 object-cover p-2"
        src="./assets/products/${img3}"
        alt="${titulo}"
      />
    </a>
    <div class="p-2">
      <h4 class="deco-horizontal relative pb-3 text-xl font-bold">
        ${titulo}
      </h4>
      <p class="mt-3 text-xs leading-relaxed text-gray-600">
        ${descripcion}
      </p>
      <div class="flex items-center justify-between pt-4">
        <span class="font-semibold relative">
          $${precioFinal}
          ${
            descuento > 0
              ? `<span class="absolute -top-2 -right-2 whitespace-nowrap text-xs text-red-500 line-through">$${precio}</span>`
              : ``
          }
        </span>
        <button id="paquete-${id}" type="button" class="cta product">
          Agregar
        </button>
      </div>
    </div>
  </li>`;
};

const fetchData = async () =>
  Promise.all([
    (await fetch('./../controller/js/productos.json')).json(),
    (await fetch('./../controller/js/productos-oferta.json')).json(),
    (await fetch('./../controller/js/paquetes.json')).json(),
    (await fetch('./../controller/js/paquetes-oferta.json')).json(),
  ]);

$(async () => {
  const [productos, productosOferta, paquetes, paquetesOferta] =
    await fetchData();

  const $productos = $('#last-products');
  $productos.html(productos.map(producto => renderProducto(producto)).join(''));

  const $productosOferta = $('#offer-products');
  $productosOferta.html(
    productosOferta.map(producto => renderProducto(producto)).join('')
  );

  const $paquetes = $('#last-packs');
  $paquetes.html(paquetes.map(paquete => renderPaquetes(paquete)).join(''));

  const $paquetesOferta = $('#offer-packs');
  $paquetesOferta.html(
    paquetesOferta.map(paquete => renderPaquetes(paquete)).join('')
  );
});
