const primaryDisplayBtn = document.querySelector('.btn-ajax-response');
const btnRm = document.querySelector('.btn-ajax-response_rm');
const btnAdd = document.querySelector('.btn-ajax-response_add');

// Adding city to favs (FavoriteCityController)
async function addFavouriteCity(id, unit, lang) {
    fetch(`/add-city/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            primaryDisplayBtn.style.display = 'none';
            btnRm.style.display = 'block';
            btnAdd.style.display = 'none';

            return response.text();
        })
        .catch(error => {
            // Handle the error
            console.error('There was a problem with the fetch operation: ' + error.message);
        });
}

// Removing city from favs (FavoriteCityController)
function removeFavouriteCity(id) {
    fetch(`/remove-city/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            primaryDisplayBtn.style.display = 'none';
            btnRm.style.display = 'none';
            btnAdd.style.display = 'block';

            return response.text();
        })
        .catch(error => {
            // Handle the error
            console.error('There was a problem with the fetch operation: ' + error.message);
        });
}