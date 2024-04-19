document.addEventListener("DOMContentLoaded", function() {
    fetch('php/profile.php')  // Замените на путь к вашему PHP-скрипту
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('username').textContent = `Добро пожаловать на сайт), ${data.username}!`;
            } else {
                alert(data.message); // Сообщение о том, что пользователь не вошёл
            }
        })
        .catch(error => console.error('Ошибка:', error));
});
