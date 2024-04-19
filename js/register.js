document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.querySelector('form');

    registerForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(registerForm);
        const data = {};
        formData.forEach((value, key) => { data[key] = value; });

        // Проверка совпадения паролей
        if (data['psw'] !== data['psw-repeat']) {
            alert('Пароли не совпадают!');
            return;
        }

        // Отправка данных на сервер
        fetch('php/register.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Регистрация прошла успешно!');
                window.location.href = 'SignIn.html';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
        });
    });
});