document.getElementById("searchButton").addEventListener("click", function() {
    var searchInput = document.getElementById("searchInput").value.toLowerCase();
    var searchResults = document.getElementById("searchResults");
    searchResults.innerHTML = ""; // Очистка предыдущих результатов

    // Подготовка данных для запроса
    var formData = new FormData();
    formData.append('ingredients', searchInput);

    // Отправка запроса на сервер
    fetch('php/search.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Получение текста ответа
    .then(data => {
        searchResults.innerHTML = data; // Вывод результата на страницу
    })
    .catch(error => {
        console.error('Error:', error);
        searchResults.innerHTML = "Ошибка при обработке запроса.";
    });
});

// Обновление обработчика для добавления в избранное
document.getElementById("searchResults").addEventListener("submit", function(event) {
    event.preventDefault(); // Предотвращаем обычную отправку формы
    
    var formData = new FormData(event.target); // Сбор данных из формы
    
    fetch('php/search.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Обновляем страницу с новыми сообщениями о добавлении в избранное
    .then(data => {
        document.getElementById("searchResults").innerHTML = data;
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при добавлении рецептов в избранное.');
    });
});
