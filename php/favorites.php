<?php
session_start();
ini_set('display_errors', 1); // Для вывода ошибок
error_reporting(E_ALL);

// Подключение к базе данных
$dbconn = pg_connect("host=127.0.0.1 port=5432 dbname=postgres user=postgres password=''");
if (!$dbconn) {
    echo "Не удалось подключиться к базе данных.";
    exit;
}

pg_set_client_encoding($dbconn, "UTF8"); // Установка кодировки

// Проверка сессии пользователя
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['recipeIds'])) {
        $user_id = $_SESSION['user_id'];
        $recipeIds = json_decode($_POST['recipeIds']);

        foreach ($recipeIds as $recipeId) {
            // Вставка данных в таблицу favorites
            $query = "INSERT INTO favorites (user_id, recipe_id) VALUES ($user_id, $recipeId)";
            $result = pg_query($dbconn, $query);
            if (!$result) {
                echo "Ошибка при добавлении в избранное.";
                exit;
            }
        }

        echo "Рецепты успешно добавлены в избранное!";
    } else {
        echo "Не выбраны рецепты для добавления в избранное.";
    }
} else {
    echo "Пользователь не аутентифицирован.";
}

pg_close($dbconn);
?>