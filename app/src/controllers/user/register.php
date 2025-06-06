<?php
$requiredRoles = ["gestao"];
require_once "../../bootstrap.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Navigation::alert(
        'Método inválido',
        "",
        'error',
        $_SERVER['HTTP_REFERER']
    );
    exit;
}

try {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $role = $_POST["role"];
    $class_id = $_POST["class"];

    $result = $userModel->register($name, $email, $phone, $password, $role, $class_id);

    $logger->action(
        $currentUser['id'],
        'add',
        'users',
        $result,
        "Usuário '$name' registrado",
        Security::getIp()
    );

    $_SESSION['alert'][] = [
        'titulo' => 'Sucesso',
        'mensagem' => 'Usuário registrado com sucesso',
        'tipo' => 'success'
    ];
    Navigation::redirect($_SERVER['HTTP_REFERER']);
} catch (Exception $e) {
    Navigation::alert(
        'Erro ao Registrar',
        $e->getMessage(),
        'error',
        $_SERVER['HTTP_REFERER']
    );
    exit;
}
