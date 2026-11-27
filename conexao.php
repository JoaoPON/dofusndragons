<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function conectar() {
    $host = "localhost";
    $dbname = "dofusndragons_db";
    $user = "root";
    $pws = "";

    try {
        $conexao = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pws);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;
    } catch (PDOException $e) {
        echo "Erro ao conectar: " . $e->getMessage();
        exit;
    }
}

function verificarLogin() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: login.html");
        exit();
    }
}
?>
