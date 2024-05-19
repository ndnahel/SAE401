// Updating searchbar input to update mainCard
function selectTown(city) {
    document.getElementById('search_result').value = city;
    document.getElementById('search-form').submit();
}