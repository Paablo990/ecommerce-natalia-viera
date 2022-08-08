
const $primaryNav = $("#primary-nav");
const $buttonNav = $("#toggle-nav");

$buttonNav.on("click", ()=>{
  const state = $primaryNav.attr("data-visible");
  if(state === "false"){
    $primaryNav.attr("data-visible", "true");
  }else{
    $primaryNav.attr("data-visible", "false");
  }
})