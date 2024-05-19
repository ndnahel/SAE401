// Adding city to favs (FavoriteCityController)
function addFavouriteCity(id) {
    fetch(`/add-city/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .catch(error => {
            // Handle the error
            console.error('There was a problem with the fetch operation: ' + error.message);
        });
}