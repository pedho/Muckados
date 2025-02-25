<?php

session_start();
if (!isset($_SESSION["username"])) {
  exit("Usuário não autenticado.");
} else {
    $username = $_SESSION["username"];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $usuario = (isset($_POST["usuario"]) && $_POST["usuario"] != null) ? $_POST["usuario"] : "";
    $setor = (isset($_POST["setor"]) && $_POST["setor"] != null) ? $_POST["setor"] : NULL;
    $senha = (isset($_POST["senha"]) && $_POST["senha"] != null) ? $_POST["senha"] : "";
    $usuario_view = (isset($_POST["usuario_view"]) && $_POST["usuario_view"] != null) ? $_POST["usuario_view"] : "";
    $setor_view = (isset($_POST["setor_view"]) && $_POST["setor_view"] != null) ? $_POST["setor_view"] : NULL;
    $senha_view = (isset($_POST["senha_view"]) && $_POST["senha_view"] != null) ? $_POST["senha_view"] : "";
    
} else if (!isset($id)) {
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $usuario = NULL;
    $setor = NULL;
    $senha = NULL;
    $setor_view = NULL;
    $senha_view = NULL;
}
try {
	$con = new PDO("pgsql:host=localhost; dbname=postgres", "postgres", "pabd");
	
	if ($con) {
		echo "deu certo";
        
	}
} catch (PDOException $e) {
	echo 'DEU ERRADO!!!' . $e;
}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save_user" && $usuario != "") {
    try {
        $stmt = $con->prepare("INSERT INTO Login (usuario, setor, senha) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $usuario);
        $stmt->bindParam(2, $setor);
        $stmt->bindParam(3, $senha);
         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $usuario = null;
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
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "att_user" && $usuario_view != "") {
    try {
        $stmt = $con->prepare("UPDATE Login set usuario = ?, setor = ?, senha = ? where id = ?");
        $stmt->bindParam(1, $usuario_view);
        $stmt->bindParam(2, $setor_view);
        $stmt->bindParam(3, $senha_view);
        $stmt->bindParam(4, $id);


        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = NULL;
                $usuario_view = NULL;
                $setor_view = NULL;
                $senha_view = NULL;

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
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del_user" && isset($_POST["id"])) {
    $id = $_POST["id"];
    try {
        $stmt = $con->prepare("DELETE FROM Login WHERE id = ?");
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Usuario deletado com sucesso!";
                $id = NULL;
            }
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    }catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Muckados</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>

        <form action="?act=save_user" method="POST" name="form2" >
          <h1>Adicionar usuario</h1>
          <hr>
          <input type="hidden" name="id" <?php
            if (isset($id) && $id != null || $id != "") {
                echo "value=\"{$id}\"";
            }
            ?>/>
          Usuario:
          <input type="text" name="usuario" <?php
            if (isset($usuario) && $usuario != null || $usuario != "") {
                echo "value=\"{$usuario}\"";
            }
            ?>/>
          <br/>
          Setor:
          <input type="text" name="setor" <?php
            if (isset($setor) && $setor != null || $setor != "") {
                echo "value=\"{$setor}\"";
            }
            ?>/>
          <br/>
          Senha:
          <input type="text" name="senha" <?php
            if (isset($senha) && $senha != null || $senha != "") {
                echo "value=\"{$senha}\"";
            }
            ?>/>
         <br/>
           <br/>
         <input type="submit" value="Salvar" />
         <input type="reset" value="Novo" />
         <hr>
         </form>

         <?php
         $comando1 = $con->query("SELECT Login.id AS usuario_id, Login.*
                         FROM Login");
         
         while ($var_linha = $comando1->fetch()) {
            
            echo "<form action=\"?act=att_user\" method=\"POST\" style=\"display: inline;\">
                   <input type=\"hidden\" name=\"id\" value=\"" . $var_linha['usuario_id'] . "\" />
                   Usuario: <input type=\"text\" name=\"usuario_view\" value=\"" . $var_linha['usuario'] . "\" /><br/>
                   Setor: <input type=\"text\" name=\"setor_view\" value=\"" . $var_linha['setor'] . "\" /><br/>
                   Senha: <input type=\"text\" name=\"senha_view\" value=\"" . $var_linha['senha'] . "\" /><br/>
                   <input type=\"submit\" value=\"Atualizar\" onclick=\"return confirm('Atualizar este usuario?');\"/>
                   </form>
                   <form action=\"?act=del_user\" method=\"POST\" style=\"display: inline;\">
                    <input type=\"hidden\" name=\"id\" value=\"" . $var_linha['usuario_id'] . "\" />
                    <input type=\"submit\" value=\"Deletar\" onclick=\"return confirm('Deletar este usuario?');\"/>
                    </form>
                    <hr/>";
        }
        ?>

    </body>
</html>
