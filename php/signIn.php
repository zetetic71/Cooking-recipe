<?php
session_start();  // Начинаем сессию

// Подключение к базе данных с использованием PDO для PostgreSQL
try {
    $dbconn = new PDO("pgsql:host=127.0.0.1;port=5432;dbname=postgres;user=postgres;password=''");
    $dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Очистка пользовательского ввода
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Проверка данных формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['psw']);

    // Проверка наличия пользователя в базе данных
    $query = "SELECT user_id, password, username FROM users WHERE email = :email";
    $stmt = $dbconn->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($password, $user['password'])) {
            // Пароль верный, пользователя можно аутентифицировать
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];  // Сохраняем username пользователя в сессию
            $_SESSION['email'] = $email;
            
            echo json_encode(['success' => true]);  // Возвращаем ответ об успешной аутентификации
            exit();
        } else {
            // Неверный логин или пароль
            echo json_encode(['success' => false, 'message' => 'Неправильный email или пароль.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Неправильный email или пароль.']);
    }
    $stmt = null;
    $dbconn = null;
}
?>