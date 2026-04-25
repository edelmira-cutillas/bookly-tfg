document.addEventListener("DOMContentLoaded", () => {
  const el = document.getElementById("myCarousel");

  if (el) {
    Carousel(el, {
    infinite: true
  }, {
    Autoscroll
  }).init();
    }
});