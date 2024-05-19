import Swiper from 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.mjs';

let recommendedCities = new Swiper(".recommendedCities", {
    observer: true,
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: {
        delay: 6000,
        pauseOnMouseEnter: true,
    },
    navigation: {
        nextEl: '.recommendedCities-button-next',
        prevEl: '.recommendedCities-button-prev',
    },
    breakpoints: {
        768: {
            slidesPerView: 1.6,
        },

        992: {
            slidesPerView: 1,
        },

        1600: {
            slidesPerView: 1.6,
        },
    },
});

let favoriteCities = new Swiper(".favoriteCities", {
    observer: true,
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: {
        delay: 6000,
        pauseOnMouseEnter: true,
    },
    navigation: {
        nextEl: '.favoriteCities-button-next',
        prevEl: '.favoriteCities-button-prev',
    },
    breakpoints: {
        768: {
            slidesPerView: 1.6,
        },

        992: {
            slidesPerView: 1,
        },

        1600: {
            slidesPerView: 1.6,
        },
    },
});