<?php
$basepath = "../../../";
$requiredRoles = ["gestao"];
require_once "../../bootstrap.php";
$classesPagePath = '../../../dashboard/pages/turmas.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $_SESSION['upload_error'] = "Método inválido";
  Utils::redirect($classesPagePath);
  exit;
}

try {
  if (!isset($_FILES['excel_file'])) {
    Utils::alert("Nenhum arquivo enviado", $classesPagePath);
  }

  $allowedTypes = [
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
    'application/vnd.ms-excel' // .xls
  ];

  if (!in_array($_FILES['excel_file']['type'], $allowedTypes)) {
    Utils::alert("Tipo de arquivo inválido. Use .xlsx ou .xls", $classesPagePath);
  }

  $result = $classManager->bulkCreate($_FILES['excel_file']['tmp_name']);

  $_SESSION['upload_success'] = $result['success'];
  $_SESSION['upload_errors'] = $result['errors'];

  $createdClasses = array_map(function($name, $id) {
    return "$name ($id)";
  }, array_keys($result['created_classes']), $result['created_classes']);
  $createdClassesMessage = "Foram adicionadas " . $result['success'] . " novas turmas: " . implode("\n", $createdClasses);

  $logger->action(
  $currentUser['id'],
  'add',
  'classes',
  null,
  $createdClassesMessage,
  Utils::getIp()
);

  Utils::redirect($classesPagePath);
} catch (Exception $e) {
  Utils::alert($_SESSION['upload_error'] = $e->getMessage(), $classesPagePath);
}
