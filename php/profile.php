<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Пользователь вошёл в систему
    $response = [
        'success' => true,
        'username' => $_SESSION['username'] ?? 'Аноним',
    ];
} else {
    // Пользователь не вошёл в систему
    $response = [
        'success' => false,
        'message' => 'Пользователь не аутентифицирован'
    ];
}

echo json_encode($response);
?>
