<?php
function cadastrar($pdo, $dados) {
    try {
        $email_check = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $email_check->execute([':email' => $dados['email']]);

        if ($email_check->fetchColumn()) {
            echo "<script>alert('Erro: Este e-mail já está cadastrado!'); history.back();</script>";
            exit();
        }

        $sql = "INSERT INTO usuarios (nome, email, senha, foto_url)
                VALUES (:nome, :email, :senha, :foto_url)";
        $perfil = $pdo->prepare($sql);
        $perfil->execute([
            ':nome' => $dados['nome'],
            ':email' => $dados['email'],
            ':senha' => $dados['senha'],
            ':foto_url' => $dados['foto_url']
        ]);

        header("Location: login.html");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao cadastrar: " . $e->getMessage();
    }
}

function logar($conexao, $email, $senha) {
    $perfils = "SELECT * FROM usuarios WHERE email = :email";
    $email_check = $conexao->prepare($perfils);
    $email_check->execute([':email' => $email]);

    $usuario = $email_check->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_foto'] = $usuario['foto_url'];

        header("Location: painel.php");
        exit();
    } else {
        echo "<script>alert('Erro: E-mail ou senha errados!'); history.back();</script>";
        exit();
    }
}

function buscarUsuario($pdo, $id) {
    $id_check = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
    $id_check->execute([':id' => $id]);
    return $id_check->fetch(PDO::FETCH_ASSOC);
}

function atualizarPerfil($pdo, $id, $nome, $email, $fotoUrl) {
    try {
        $usuario_check = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND id != :id");
        $usuario_check->execute([':email' => $email, ':id' => $id]);

        if ($usuario_check->fetchColumn()) {
            echo "<script>alert('Erro: Este e-mail já está em uso!'); history.back();</script>";
            exit();
        }

        $fotoFinal = $fotoUrl != "" ? $fotoUrl : "https://cdn-icons-png.flaticon.com/512/12225/12225935.png";

        $sql = "UPDATE usuarios SET nome = :nome, email = :email, foto_url = :foto_url WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':foto_url' => $fotoFinal,
            ':id' => $id
        ]);

        $_SESSION['usuario_nome'] = $nome;
        $_SESSION['usuario_email'] = $email;
        $_SESSION['usuario_foto'] = $fotoFinal;

        header("Location: perfil.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao atualizar perfil: " . $e->getMessage();
    }
}

function excluirConta($pdo, $id) {
    try {
        $id_check = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $id_check->execute([':id' => $id]);
        session_unset();
        session_destroy();
        header("Location: index.html");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao excluir conta: " . $e->getMessage();
    }
}

function criarCampanha($pdo, $titulo, $sistema, $vagas, $descricao, $idMestre) {
    try {
        $sql = "INSERT INTO campanhas (titulo, sistema, vagas, descricao, id_mestre)
                VALUES (:titulo, :sistema, :vagas, :descricao, :id_mestre)";
        $mesa = $pdo->prepare($sql);
        $mesa->execute([
            ':titulo' => $titulo,
            ':sistema' => $sistema,
            ':vagas' => $vagas,
            ':descricao' => $descricao,
            ':id_mestre' => $idMestre
        ]);

        header("Location: painel.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao criar campanha: " . $e->getMessage();
    }
}

function listarCampanhas($pdo, $idUsuario) {
    $idUsuario = (int) $idUsuario;

    $sql = "SELECT c.*,
                   u.nome AS nome_mestre,
                   (SELECT COUNT(*)
                    FROM campanha_jogadores cj
                    WHERE cj.id_campanha = c.id) AS total_jogadores,
                   CASE
                       WHEN c.id_mestre = :id_mestre THEN 'Mestre'
                       WHEN EXISTS (
                           SELECT 1
                           FROM campanha_jogadores cj2
                           WHERE cj2.id_campanha = c.id
                             AND cj2.id_usuario = :id_jogador
                       ) THEN 'Jogador'
                       ELSE 'Sem vínculo'
                   END AS papel_usuario
            FROM campanhas c
            INNER JOIN usuarios u ON u.id = c.id_mestre
            ORDER BY
                CASE
                    WHEN c.id_mestre = :id_mestre THEN 1
                    WHEN EXISTS (
                        SELECT 1
                        FROM campanha_jogadores cj3
                        WHERE cj3.id_campanha = c.id
                          AND cj3.id_usuario = :id_jogador
                    ) THEN 2
                    ELSE 3
                END ASC,
                c.id DESC";

    $mesas = $pdo->prepare($sql);
    $mesas->execute([
        ':id_mestre'  => $idUsuario,
        ':id_jogador' => $idUsuario
    ]);

    return $mesas->fetchAll(PDO::FETCH_ASSOC);
}

function buscarCampanha($pdo, $idCampanha) {
    $sql = "SELECT c.*, u.nome AS nome_mestre
            FROM campanhas c
            INNER JOIN usuarios u ON u.id = c.id_mestre
            WHERE c.id = :id";
    $mesa = $pdo->prepare($sql);
    $mesa->execute([':id' => $idCampanha]);
    return $mesa->fetch(PDO::FETCH_ASSOC);
}

function atualizarCampanha($pdo, $idCampanha, $idMestre, $titulo, $sistema, $vagas, $descricao) {
    try {
        $sql = "UPDATE campanhas
                SET titulo = :titulo, sistema = :sistema, vagas = :vagas, descricao = :descricao
                WHERE id = :id AND id_mestre = :id_mestre";
        $mesa = $pdo->prepare($sql);
        $mesa->execute([
            ':titulo' => $titulo,
            ':sistema' => $sistema,
            ':vagas' => $vagas,
            ':descricao' => $descricao,
            ':id' => $idCampanha,
            ':id_mestre' => $idMestre
        ]);

        header("Location: painel.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao atualizar campanha: " . $e->getMessage();
    }
}

function excluirCampanha($pdo, $idCampanha, $idMestre) {
    try {
        $sql = "DELETE FROM campanhas WHERE id = :id AND id_mestre = :id_mestre";
        $mesa = $pdo->prepare($sql);
        $mesa->execute([
            ':id' => $idCampanha,
            ':id_mestre' => $idMestre
        ]);

        header("Location: painel.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao excluir campanha: " . $e->getMessage();
    }
}

function listarJogadoresCampanha($pdo, $idCampanha) {
    $sql = "SELECT u.id, u.nome, u.email, u.foto_url
            FROM campanha_jogadores cj
            INNER JOIN usuarios u ON u.id = cj.id_usuario
            WHERE cj.id_campanha = :id_campanha
            ORDER BY u.nome ASC";
    $jogadores = $pdo->prepare($sql);
    $jogadores->execute([':id_campanha' => $idCampanha]);
    return $jogadores->fetchAll(PDO::FETCH_ASSOC);
}

function usuarioParticipa($pdo, $idCampanha, $idUsuario) {
    $id_check = $pdo->prepare("SELECT id FROM campanha_jogadores WHERE id_campanha = :c AND id_usuario = :u");
    $id_check->execute([':c' => $idCampanha, ':u' => $idUsuario]);
    return (bool) $id_check->fetchColumn();
}

function participarCampanha($pdo, $idCampanha, $idUsuario) {
    try {
        $campanha = buscarCampanha($pdo, $idCampanha);
        if (!$campanha) {
            header("Location: painel.php");
            exit();
        }
        if ((int)$campanha['id_mestre'] === (int)$idUsuario) {
            echo "<h3 style='text-align:center; margin-top:40px;'>Você já é o mestre desta campanha!</h3>";
            header("refresh:2; url=painel.php");
            exit();
        }
        if (usuarioParticipa($pdo, $idCampanha, $idUsuario)) {
            echo "<h3 style='text-align:center; margin-top:40px;'>Você já está inscrito nesta campanha!</h3>";
            header("refresh:2; url=painel.php");
            exit();
        }

        $jogadores = $pdo->prepare("SELECT COUNT(*) FROM campanha_jogadores WHERE id_campanha = :c");
        $jogadores->execute([':c' => $idCampanha]);
        $inscritos = (int) $jogadores->fetchColumn();

        if ($inscritos >= (int)$campanha['vagas']) {
            echo "<h3 style='text-align:center; margin-top:40px;'>Esta campanha não possui mais vagas!</h3>";
            header("refresh:2; url=painel.php");
            exit();
        }

        $sql = "INSERT INTO campanha_jogadores (id_campanha, id_usuario) VALUES (:c, :u)";
        $inscricao = $pdo->prepare($sql);
        $inscricao->execute([':c' => $idCampanha, ':u' => $idUsuario]);

        header("Location: painel.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao participar da campanha: " . $e->getMessage();
    }
}

function sairCampanha($pdo, $idCampanha, $idUsuario) {
    try {
        $sql = "DELETE FROM campanha_jogadores WHERE id_campanha = :c AND id_usuario = :u";
        $mesa = $pdo->prepare($sql);
        $mesa->execute([':c' => $idCampanha, ':u' => $idUsuario]);

        header("Location: painel.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao sair da campanha: " . $e->getMessage();
    }
}
?>
