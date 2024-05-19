let forecastCards = document.querySelectorAll('.forecastCard');

forecastCards.forEach((card) => {
        card.addEventListener('click', () => {
                if (!card.classList.contains('forecastCard-active')) {
                    forecastCards.forEach((item) => {
                        if (item !== card) {
                            item.classList.remove('forecastCard-active');
                        }
                    });
                    card.classList.toggle('forecastCard-active');
                    //scroll to active card
                    setTimeout(() => {
                        card.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 68);
                }
            }
        );
    }
);
