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

// Обработка POST запроса поиска
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['ingredients'])) {
    $ingredients = explode(',', $_POST['ingredients']);
    $ingredients = array_map(function($item) use ($dbconn) {
        return pg_escape_string($dbconn, trim(mb_strtolower($item, 'UTF-8')));
    }, $ingredients);
    
    $ingredientsList = "'" . implode("', '", $ingredients) . "'";

    // SQL запрос с выборкой названия, описания и URL изображения
    $query = "SELECT r.recipe_id, r.title, r.description, r.image_url FROM recipes r
              JOIN ingredients i ON r.recipe_id = i.recipe_id
              WHERE i.ingredient_name IN ($ingredientsList)";

    // Выполнение запроса
    $result = pg_query($dbconn, $query);
    if (!$result) {
        echo "Ошибка выполнения запроса.";
        exit;
    }

    // Сбор данных для вывода с чекбоксами
    $responseData = "<form method='POST' action=''><ul>";
    while ($row = pg_fetch_assoc($result)) {
        $recipeId = $row['recipe_id'];
        $responseData .= "<li><input type='checkbox' id='recipe$recipeId' name='selectedRecipes[]' value='$recipeId'>";
        $responseData .= "<label for='recipe$recipeId'><strong>" . htmlspecialchars($row['title']) . "</strong>: " 
                         . htmlspecialchars($row['description'])
                         . "<br><img src='" . htmlspecialchars($row['image_url']) . "' alt='recipe image' class='recipe-image'></label></li>";
    }
    $responseData .= "</ul><input type='submit' value='Добавить в избранное'></form>";
    
    echo $responseData;
}

// Обработка POST запроса для добавления в избранное
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['selectedRecipes'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // Получение user_id из сессии или установка по умолчанию
    foreach ($_POST['selectedRecipes'] as $recipe_id) {
        $recipe_id = pg_escape_string($recipe_id);
        $checkQuery = "SELECT * FROM favorites WHERE user_id = $user_id AND recipe_id = $recipe_id";
        $checkResult = pg_query($dbconn, $checkQuery);

        if (pg_num_rows($checkResult) == 0) {
            $insertQuery = "INSERT INTO favorites (user_id, recipe_id) VALUES ($user_id, $recipe_id)";
            $insertResult = pg_query($dbconn, $insertQuery);
            if ($insertResult) {
                echo "Рецепт с ID $recipe_id добавлен в избранное.<br>";
            } else {
                echo "Не удалось добавить рецепт с ID $recipe_id в избранное.<br>";
            }
        } else {
            echo "Рецепт с ID $recipe_id уже находится в избранном.<br>";
        }
    }
}

pg_close($dbconn);
?>
