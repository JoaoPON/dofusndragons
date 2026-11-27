<?php
require_once "conexao.php";
require_once "crud.php";
verificarLogin();

$pdo = conectar();
$idUsuario = (int) $_SESSION['usuario_id'];
$nomeUsuario = $_SESSION['usuario_nome'];
$fotoUsuario = $_SESSION['usuario_foto'];
$campanhas = listarCampanhas($pdo, $idUsuario);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="estilo.css">
    <script>
        function filtrarCampanhas() {
            var termo = document.getElementById("busca").value.toLowerCase();
            var cards = document.getElementsByClassName("card-campanha");

            for (var i = 0; i < cards.length; i++) {
                var texto = cards[i].innerText.toLowerCase();
                if (texto.indexOf(termo) > -1) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    </script>
</head>
<body>
    <header>
        <nav class="navbar navbar-dark bg-gradiente">
            <a class="navbar-brand font-weight-bold text-warning" href="painel.php">DofusNDragons</a>
            
            <ul class="nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-white d-flex align-items-center" href="perfil.php">
                        <img src="<?= $fotoUsuario ?>"
                            alt="Foto de <?= htmlspecialchars($nomeUsuario, ENT_QUOTES, 'UTF-8') ?>"
                            class="rounded-circle mr-2"
                            style="width: 28px; height: 28px; object-fit: cover;">
                        <span><?= htmlspecialchars($nomeUsuario, ENT_QUOTES, 'UTF-8') ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="logout.php">Sair</a>
                </li>
            </ul>

        </nav>
    </header>

    <div class="container container-box">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h2 class="font-weight-bold mb-2">Campanhas Disponíveis</h2>
            <a href="nova_campanha.php" class="btn btn-warning font-weight-bold mb-2">+ Criar Campanha</a>
        </div>

        <div class="form-group mt-3">
            <input type="text" id="busca" class="form-control" onkeyup="filtrarCampanhas()"
                   placeholder="Pesquisar campanha por título, sistema ou mestre...">
        </div>

        <hr class="linha-tema">

        <?php if (empty($campanhas)): ?>
            <p class="text-center text-muted py-4">Nenhuma campanha cadastrada.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($campanhas as $c): ?>
                    <?php
                    $cid = (int) $c['id'];
                    $ehMestre = ((int) $c['id_mestre'] === $idUsuario);
                    $mestre = buscarUsuario($pdo, $c['id_mestre']);
                    $participa = usuarioParticipa($pdo, $cid, $idUsuario);
                    $jogadores = listarJogadoresCampanha($pdo, $cid);
                    ?>
                    <div class="col-md-6 mb-4 card-campanha">
                        <div class="card h-100">
                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                <strong><?= htmlspecialchars($c['titulo']) ?></strong>
                                <span class="badge badge-warning text-dark"><?= htmlspecialchars($c['sistema']) ?></span>
                            </div>

                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <p class="mb-1"><strong>Mestre:</strong> <?= htmlspecialchars($c['nome_mestre']) ?></p>
                                        <p class="mb-0"><strong>Vagas:</strong> <?= (int) $c['total_jogadores'] ?> / <?= (int) $c['vagas'] ?> jogadores</p>
                                    </div>
                                    <img src="<?= htmlspecialchars($mestre['foto_url'], ENT_QUOTES, 'UTF-8') ?>"
                                        alt="Foto de <?= htmlspecialchars($c['nome_mestre'], ENT_QUOTES, 'UTF-8') ?>"
                                        class="rounded-circle ml-3"
                                        style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #d4a017; flex-shrink: 0;">
                                </div>

                                <p class="text-muted small"><?= nl2br(htmlspecialchars($c['descricao'] ?? 'Sem descrição.')) ?></p>

                                <hr>
                                <strong class="small">Jogadores inscritos:</strong>
                                <?php if (empty($jogadores)): ?>
                                    <p class="small text-muted mb-0">Nenhum jogador inscrito ainda.</p>
                                <?php else: ?>
                                    <ul class="list-unstyled small mb-0">
                                        <?php foreach ($jogadores as $j): ?>
                                            <?php $foto = htmlspecialchars($j['foto_url'], ENT_QUOTES, 'UTF-8'); ?>
                                            <li class="d-flex align-items-center mb-1">
                                                <img src="<?= $foto ?>"
                                                    alt="Foto de <?= htmlspecialchars($j['nome'], ENT_QUOTES, 'UTF-8') ?>"
                                                    class="rounded-circle mr-2"
                                                    style="width: 24px; height: 24px; object-fit: cover;">
                                                <span><?= htmlspecialchars($j['nome']) ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>

                            <div class="card-footer bg-light d-flex justify-content-between">
                                <?php if ($ehMestre): ?>
                                    <div>
                                        <a href="editar_campanha.php?id=<?= $cid ?>" class="btn btn-sm btn-primary">Editar</a>
                                        <a href="excluir_campanha.php?id=<?= $cid ?>" class="btn btn-sm btn-danger"
                                           onclick="return confirm('Tem certeza que deseja excluir esta campanha?');">Excluir</a>
                                    </div>
                                    <span class="badge badge-secondary align-self-center">Você é o Mestre</span>
                                <?php elseif ($participa): ?>
                                    <a href="sair_campanha.php?id=<?= $cid ?>" class="btn btn-sm btn-outline-danger">Sair da Campanha</a>
                                    <span class="badge badge-success align-self-center">Inscrito</span>
                                <?php else: ?>
                                    <a href="participar_campanha.php?id=<?= $cid ?>" class="btn btn-sm btn-success">Entrar como Jogador</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
