export default function swiper() {
  let SliderDefault = document.querySelectorAll('.swiper-default');
  SliderDefault.forEach((s) => {
    // Elements
    let container = s.querySelector('.swiper-container');
    let dot = s.querySelector('.swiper-pagination');
    let next = s.querySelector('.swiper-button-next');
    let prev = s.querySelector('.swiper-button-prev');

    // Center
    let center = s.classList.contains('center') || false;
    // Effect
    let effect;
    s.classList.contains('fade') ? (effect = 'fade') : (effect = 'slide');
    // Loop
    let loop;
    s.classList.contains('loop') ? (loop = true) : (loop = false);

    // Enable swiper
    let swiper = new Swiper(container, {
      // Custom
      loop: loop,
      centeredSlides: center,
      effect: effect,
      // Default
      slidesPerView: 'auto',
      speed: 1200,
      autoplay: {
        delay: 6000,
        disableOnInteraction: false,
      },
      // Disabled if not enough slide
      watchOverflow: true,
      // For parents or childs hide/show
      observer: true,
      observeParents: true,
      observeSlideChildren: true,
      // Navigation dot
      pagination: {
        el: dot,
        clickable: true,
      },
      // Navigation arrow
      navigation: {
        nextEl: next,
        prevEl: prev,
      },
    });
  });
}
