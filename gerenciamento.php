<?php

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $setor = (isset($_POST["setor"]) && $_POST["setor"] != null) ? $_POST["setor"] : "";
    $senha = (isset($_POST["senha"]) && $_POST["senha"] != null) ? $_POST["senha"] : NULL;
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["nome"]) && $_GET["nome"] != null) ? $_GET["nome"] : "";
    $nome = NULL;
    $setor = NULL;
    $senha = NULL;
}
try {
    $conexao = new PDO("pgsql:host=localhost; dbname=20221214010008", "postgres", "pabd");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $erro) {
    echo "Erro na conexão:" . $erro->getMessage();
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
    try {
        if ($usuario != "") {
            $stmt = $conexao->prepare("UPDATE login SET usuario=?, setor=?, senha=? WHERE usuario = ?");
            $stmt->bindParam(4, $usuario);
        } else {
            $stmt = $conexao->prepare("INSERT INTO login (usuario, setor, senha) VALUES (?, ?, ?)");
        }
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $setor);
        $stmt->bindParam(3, $senha);
         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $nome = null;
                $setor = null;
                $senha = null;
            } else {
                echo "Erro ao tentar efetivar cadastro";
            }
        } else {
               throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $usuario != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM login WHERE usuario = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $usuario = $rs->usuario;
            $setor = $rs->setor;
            $senha = $rs->senha;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $usuario != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM login WHERE usuario = ?");
        $stmt->bindParam(1, $usuario, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Registo foi excluído com êxito";
            $id = null;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Gerenciamento de usuários</title>
    </head>
    <body>
        <form action="?act=save" method="POST" name="form1" >
          <h1>Gerenciamento de usuários</h1>
          <hr>
            Usuário:
            <input type="text" name="nome" />
            Setor:
            <input type="text" name="setor" />
            Senha:
            <input type="password" name="senha" />
            <input type="submit" value="salvar" />
            <input type="reset" value="Novo" />
         <hr>
       </form>

       <table border="1" width="100%">
            <tr>
                <th>Nome</th>
                <th>Setor</th>
                <th>Senha</th>
                <th>Ações</th>
            </tr>

            <?php
                try {
                
                    $stmt = $conexao->prepare("SELECT * FROM login");
                
                        if ($stmt->execute()) {
                            while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                                echo "<tr>";
                                echo "<td>".$rs->usuario."</td><td>".$rs->setor."</td><td>".$rs->senha
                                            ."</td><td><center><a href=\"?act=upd&id=" . $rs->usuario . "\">[Alterar]</a>"
                                            ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                            ."<a href=\"?act=del&id=" . $rs->usuario . "\">[Excluir]</a></center></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "Erro: Não foi possível recuperar os dados do banco de dados";
                        }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
            ?>
        </table>
    </body>
</html>