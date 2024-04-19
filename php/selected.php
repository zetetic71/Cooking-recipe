<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не аутентифицирован']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $dbconn = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=''");
    $dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT recipes.title FROM favorites
            JOIN recipes ON favorites.recipe_id = recipes.recipe_id
            WHERE favorites.user_id = :userId";
    
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'favorites' => $results]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => "Connection failed: " . $e->getMessage()]);
}
?>
