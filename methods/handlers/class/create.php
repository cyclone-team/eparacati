<?php
$basepath = '../../../';
$requiredRoles = ['gestao'];
require_once '../../bootstrap.php';

$classesPagePath = "../../../dashboard/pages/turmas.php";

try {
    $name = $_POST['name'];
    $grade = $_POST['grade'];
    $pdtInput = $_POST['pdt'] ?? null;
    $leaderInput = $_POST['leader'] ?? null;
    $viceLeaderInput = $_POST['vice_leader'] ?? null;

    function getUserId($input, $userManager, $expectedRole)
    {
        if ($input) {
            global $classesPagePath;
            if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                $userInfo = $userManager->getInfo($input, 'email');
                if ($userInfo && isset($userInfo['id'])) {
                    if ($userInfo['role'] !== $expectedRole) {
                        Utils::alert("Usuário de email $input não possui o cargo esperado de " . Utils::formatRoleName($expectedRole, true), $classesPagePath);
                        exit();
                    }
                    return $userInfo['id'];
                } else {
                    Utils::alert("Usuário de email $input não encontrado.", $classesPagePath);
                    exit();
                }
            }

            $userInfo = $userManager->getInfo($input, 'id', ['role']);
            if ($userInfo && $userInfo['role'] !== $expectedRole) {
                Utils::alert("Usuário de ID $input não possui o cargo esperado de " . Utils::formatRoleName($expectedRole, true), $classesPagePath);
                exit();
            }

            return $input;
        }
        return null;
    }

    $pdtId = getUserId($pdtInput, $userManager, 'professor');
    $leaderId = getUserId($leaderInput, $userManager, 'aluno');
    $viceLeaderId = getUserId($viceLeaderInput, $userManager, 'aluno');

    $classManager->create($name, $grade, $pdtId, $leaderId, $viceLeaderId);
    $newClassInfo = $classManager->getInfo($name, 'name', ['name']);
    $logger->action(
        $currentUser['id'],
        'add',
        'classes',
        null,
        sprintf('Nova turma criada: %s (ID: %s)', $newClassInfo['name'], $newClassInfo['id']),
        Utils::getIp()
    );

    Utils::redirect($classesPagePath);
} catch (Exception $e) {
    error_log($e->getMessage());
    Utils::alert("Ocorreu um erro ao criar a turma. Por favor, tente novamente mais tarde.", $classesPagePath);
    exit();
}
