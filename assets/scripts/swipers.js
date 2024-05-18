import Swiper from 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.mjs';

let recommendedCities = new Swiper(".recommendedCities", {
    observer: true,
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    slideToClickedSlide: true,
    autoplay: {
        delay: 6000,
        pauseOnMouseEnter: true,
    },
    grabCursor: true,
    breakpoints: {
        768: {
            slidesPerView: 1.6,
        },
    },
});