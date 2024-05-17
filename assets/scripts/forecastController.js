let userPreferences;

document.addEventListener('DOMContentLoaded', async function() {
    userPreferences = await fetch('/api/user_preferences')
        .then(response => response.json());
});

let forecastCards = document.querySelectorAll('.forecastCard');
forecastCards.forEach(function(forecastCard) {
    forecastCard.addEventListener('click', async function(event) {
        const location = this.getAttribute('data-location');
        const forecast = await fetch(`/api/forecast/${location}/${userPreferences.unit}/${userPreferences.lang}`)
            .then(response => response.json());
    });
});
