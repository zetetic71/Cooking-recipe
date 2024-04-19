window.onload = function() {
    fetchFavorites();
};

function fetchFavorites() {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "php/selected.php", true); // Убедитесь, что путь к PHP файлу верный
    xhr.onload = function() {
        if (xhr.status == 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Используем map для создания HTML-элементов и <br> для разделения
                let favoritesHtml = response.favorites.map(fav => `<div>${fav.title}</div>`).join('<li></li><br></br>');
                // Используем innerHTML для интерпретации строки как HTML
                document.getElementById("searchResults").innerHTML = favoritesHtml + '<br></br>';
            } else {
                document.getElementById("searchResults").textContent = response.message;
            }
        }
    };
    xhr.onerror = function() {
        console.error("An error occurred fetching the data.");
    };
    xhr.send();
}