const burgerBars = document.querySelector(".menu-burger");
const phoneMenu = document.querySelector(".phone-menu");
const close = document.querySelector(".phone-menu div span");

burgerBars.addEventListener("click", function () {
  phoneMenu.classList.add("show-phone-menu");
});
/*close.addEventListener("click", function () {
  phoneMenu.classList.remove("show-phone-menu");
});*/
