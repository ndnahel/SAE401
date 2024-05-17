let userPreferences;
let forecastList;
let forecast;

// Elements to update
let when = document.getElementById('when');
let date = document.getElementById('date');
let weather = document.getElementById('weather');
let temp = document.getElementById('temp');
let feels = document.getElementById('feels');
let temp_min = document.getElementById('temp_min');
let temp_max = document.getElementById('temp_max');
let wind_speed = document.getElementById('wind_speed');
let wind_direction = document.getElementById('wind_direction');
let clouds = document.getElementById('clouds');
let humidity = document.getElementById('humidity');
let icon = document.getElementById('icon');

// Selecting user preferences on page load
document.addEventListener('DOMContentLoaded', async function() {
    userPreferences = await fetch('/api/user_preferences')
    .then(response => response.json());
});



let forecastCards = document.querySelectorAll('.forecastCard');

// Click on forecastCard -> update mainCard content
forecastCards.forEach(function(forecastCard) {
    forecastCard.addEventListener('click', async function() {
        const location = this.getAttribute('data-location');

        // Fetching forecast data to update mainCard content
        forecastList = await fetch(`/api/forecast/${location}/${userPreferences.unit}/${userPreferences.lang}`)
        .then(response => reponse = response.json());

        // Finding the forecast clicked in the forecast list fetched
        forecast = forecastList['list'].find(forecast => forecast.dt_txt === this.getAttribute('data-datetime'));

        // Updating mainCard content
        /* let forecastDate = new Date(forecast.dt_txt);
        const forecastDay = forecastDate.toLocaleString(userPreferences.lang == 'fr' ? 'fr-fr' : 'en-us', {  weekday: 'long' });
        const forecastMonth = forecastDate.toLocaleString(userPreferences.lang == 'fr' ? 'fr-fr' : 'en-us', { month: 'long' });
        forecastDate = `${forecastDate.getDate()} ${forecastMonth.charAt(0).toUpperCase()}${forecastMonth.slice(1)} - ${forecastDate.getHours()}:${forecastDate.getMinutes()}:${forecastDate.getSeconds()}`;
        
        const description = forecast.weather[0].description;


        when.innerHTML = `${forecastDay.charAt(0).toUpperCase()}${forecastDay.slice(1)}`;
        date.innerHTML = forecastDate;
        weather.innerHTML = `${description.charAt(0).toUpperCase()}${description.slice(1)}`;
        temp.innerHTML = `${forecast.main.temp}°${userPreferences.unit === 'metric' ? 'C' : 'F'}`;
        feels.innerHTML = `${Math.round(forecast.main.feels_like * 10) / 10}°${userPreferences.unit === 'metric' ? 'C' : 'F'}`;
        temp_min.innerHTML = `${Math.round(forecast.main.temp_min * 10) / 10}°`;
        temp_max.innerHTML = `${Math.round(forecast.main.temp_max * 10) / 10}°`;
        wind_speed.innerHTML = `${forecast.wind.speed} ${userPreferences.unit === 'metric' ? 'm/s' : 'mpH'}`;
        wind_direction.innerHTML = windDirection(forecast.wind.deg);
        clouds.innerHTML = `${forecast.clouds.all}%`;
        humidity.innerHTML = `${forecast.main.humidity}%`;
        icon.innerHTML = await fetch(`/assets/components/icons/${forecast.weather[0].icon}.svg`)
        .then(response => response.text()); */
    });
});


/* function windDirection(deg) {
    switch (deg) {
        case (deg > 10.5 && deg <= 30.5): return 'N/Nord-Est';
        case (deg > 30.5 && deg <= 60.5): return 'Nord-Est';
        case (deg > 60.5 && deg <= 80.5): return 'E/Nord-Est';
        case (deg > 80.5 && deg <= 100.5): return 'Est';
        case (deg > 100.5 && deg <= 130.5): return 'E/Sud-Est';
        case (deg > 130.5 && deg <= 150.5): return 'Sus-Est';
        case (deg > 150.5 && deg <= 180.5): return 'S/Sus-Est';
        case (deg > 180.5 && deg <= 200.5): return 'Sud';
        case (deg > 200.5 && deg <= 230.5): return 'S/Sud-Ouest';
        case (deg > 230.5 && deg <= 250.5): return 'Sud-Ouest';
        case (deg > 250.5 && deg <= 280.5): return 'O/Sud-Ouest';
        case (deg > 280.5 && deg <= 300.5): return 'Ouest';
        case (deg > 300.5 && deg <= 330.5): return 'O/Nord-Ouest';
        case (deg > 330.5 && deg <= 350.5): return 'Nord-Ouest';
        default: return 'Nord';
    }  
} */