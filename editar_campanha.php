<?php
require_once "conexao.php";
require_once "crud.php";
verificarLogin();

$pdo = conectar();
$idUsuario = (int) $_SESSION['usuario_id'];
$idCampanha = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$campanha = buscarCampanha($pdo, $idCampanha);
if (!$campanha || (int) $campanha['id_mestre'] !== $idUsuario) {
    header("Location: painel.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = trim($_POST['titulo'] ?? '');
    $sistema = trim($_POST['sistema'] ?? '');
    $vagas = max(1, (int) ($_POST['vagas'] ?? 4));
    $descricao = trim($_POST['descricao'] ?? '');

    atualizarCampanha($pdo, $idCampanha, $idUsuario, $titulo, $sistema, $vagas, $descricao);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Campanha</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-dark bg-gradiente">
            <a class="navbar-brand font-weight-bold text-warning" href="painel.php">DofusNDragons</a>
            <ul class="nav">
                <li class="nav-item"><a class="nav-link text-white" href="painel.php">Campanhas</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="perfil.php">Meu Perfil</a></li>
                <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <div class="container container-box" style="max-width: 650px;">
        <h2 class="text-center font-weight-bold">Editar Campanha</h2>
        <hr class="linha-tema">

        <form method="post" action="editar_campanha.php?id=<?= $idCampanha ?>">
            <div class="form-group">
                <label for="titulo">Título da Campanha</label>
                <input type="text" class="form-control" id="titulo" name="titulo"
                       value="<?= htmlspecialchars($campanha['titulo']) ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="sistema">Sistema</label>
                    <input type="text" class="form-control" id="sistema" name="sistema"
                           value="<?= htmlspecialchars($campanha['sistema']) ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="vagas">Número de Vagas</label>
                    <input type="number" class="form-control" id="vagas" name="vagas" min="1" max="15"
                           value="<?= (int) $campanha['vagas'] ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição / Sinopse</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="4"><?= htmlspecialchars($campanha['descricao'] ?? '') ?></textarea>
            </div>

            <hr class="linha-tema">
            <div class="text-center">
                <a href="painel.php" class="btn btn-secondary mr-2">Cancelar</a>
                <button type="submit" class="btn btn-primary font-weight-bold px-4">Atualizar Campanha</button>
            </div>
        </form>
    </div>

    <footer class="text-center text-muted py-4">
        <small>DofusNDragons — Gerenciador de Mesas de RPG</small>
    </footer>
</body>
</html>
