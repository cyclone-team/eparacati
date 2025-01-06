<?php
require_once '../conn.php';
require_once '../utils.php';
require_once '../usermanager.php';
$userManager = new UserManager($conn);

UserManager::verifySession('../login.php', ['gestao']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = Utils::passw($_POST['password']);
  $role = $_POST['role'];
  $class_id = $_POST['class_id'];

  $userManager->editUser($id, $name, $email, $password, $role, $class_id);
}
