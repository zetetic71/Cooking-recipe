document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Предотвращаем стандартную отправку формы

        const emailInput = document.querySelector("input[name='email']");
        const passwordInput = document.querySelector("input[name='psw']");

        // Проверки на пустоту полей
        if (!emailInput.value || !passwordInput.value) {
            alert("Пожалуйста, заполните все поля.");
            return;
        }

        // Проверка формата электронной почты
        if (!/\S+@\S+\.\S+/.test(emailInput.value)) {
            alert("Некорректный формат почты.");
            return;
        }

        // Собираем данные для отправки
        const formData = new FormData();
        formData.append('email', emailInput.value);
        formData.append('psw', passwordInput.value);

        // Отправляем данные на сервер с помощью Fetch API
        fetch('php/signIn.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Перенаправляем на страницу Profile.html после успешного входа
                window.location.href = 'Profile.html';
            } else {
                alert(data.message);  // Выводим сообщение об ошибке
            }
        })
        .catch(error => console.error('Ошибка:', error));
    });
});
