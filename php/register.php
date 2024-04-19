<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Получение данных JSON из запроса
$input = json_decode(file_get_contents('php://input'), true);

// Подключение к базе данных
$dbconn = new PDO("pgsql:host=127.0.0.1;port=5432;dbname=postgres;user=postgres;password=''");
$dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$username = filter_var($input['username'], FILTER_SANITIZE_STRING);
$email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
$password = password_hash($input['psw'], PASSWORD_DEFAULT);

try {
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $dbconn->prepare($query);
    $stmt->execute([$username, $email, $password]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => "Ошибка при регистрации: " . $e->getMessage()]);
}
?>
