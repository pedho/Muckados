<?php

session_start();
if (!isset($_SESSION["username"])) {
    exit("Usuário não autenticado.");
} else {
    $username = $_SESSION["username"];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $tipo = (isset($_POST["tipo"]) && $_POST["tipo"] != null) ? $_POST["tipo"] : NULL;
    $marca = (isset($_POST["marca"]) && $_POST["marca"] != null) ? $_POST["marca"] : "";
    $cor = (isset($_POST["cor"]) && $_POST["cor"] != null) ? $_POST["cor"] : "";
    $legenda = (isset($_POST["legenda"]) && $_POST["legenda"] != null) ? $_POST["legenda"] : "";
    $quantidade = (isset($_POST["quantidade"]) && $_POST["quantidade"] != null) ? $_POST["quantidade"] : "";
    $preço = (isset($_POST["preço"]) && $_POST["preço"] != null) ? $_POST["preço"] : NULL;
    $tipo_view = (isset($_POST["tipo_view"]) && $_POST["tipo_view"] != null) ? $_POST["tipo_view"] : NULL;
    $marca_view = (isset($_POST["marca_view"]) && $_POST["marca_view"] != null) ? $_POST["marca_view"] : "";
    $cor_view = (isset($_POST["cor_view"]) && $_POST["cor_view"] != null) ? $_POST["cor_view"] : "";
    $legenda_view = (isset($_POST["legenda_view"]) && $_POST["legenda_view"] != null) ? $_POST["legenda_view"] : "";
    $quantidade_view = (isset($_POST["quantidade_view"]) && $_POST["quantidade_view"] != null) ? $_POST["quantidade_view"] : "";
    $preço_view = (isset($_POST["preço_view"]) && $_POST["preço_view"] != null) ? $_POST["preço_view"] : NULL;
    
} else if (!isset($id)) {
    
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $tipo = NULL;
    $marca = NULL;
    $cor = NULL;
    $legenda = NULL;
    $quantidade = NULL;
    $preço = NULL;
    $tipo_view = NULL;
    $marca_view = NULL;
    $cor_view = NULL;
    $legenda_view = NULL;
    $quantidade_view = NULL;
    $preço_view = NULL;
}
try {
	$con = new PDO('pgsql:host=localhost;port=5432;dbname=postgres', 'postgres', 'pabd');
	
	if ($con) {
		echo "deu certo";
        
	}
} catch (PDOException $e) {
	echo 'DEU ERRADO!!!' . $e;
}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save_vest" && $tipo != "") {
    try {
        $stmt = $con->prepare("INSERT INTO Vestuario (usuario, tipo, marca, cor, legenda, quantidade, preço) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $tipo);
        $stmt->bindParam(3, $marca);
        $stmt->bindParam(4, $cor);
        $stmt->bindParam(5, $legenda);
        $stmt->bindParam(6, $quantidade);
        $stmt->bindParam(7, $preço);
         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $tipo = null;
                $marca = null;
                $cor = null;
                $legenda = null;
                $quantidade = null;
                $preço = null;

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
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "att_vest" && $tipo_view != "" && $id != "") {
    try {
        $stmt = $con->prepare("UPDATE Vestuario set tipo = ?, marca = ?, cor = ?, legenda = ?, quantidade = ?, preço = ? where id = ?");
        $stmt->bindParam(1, $tipo_view);
        $stmt->bindParam(2, $marca_view);
        $stmt->bindParam(3, $cor_view);
        $stmt->bindParam(4, $legenda_view);
        $stmt->bindParam(5, $quantidade_view);
        $stmt->bindParam(6, $preço_view);
        $stmt->bindParam(7, $id);

         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = NULL;
                $tipo_view = NULL;
                $marca_view = NULL;
                $cor_view = NULL;
                $legenda_view = NULL;
                $quantidade_view = NULL;
                $preço_view = NULL;

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
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del_vest" && isset($_POST["id"])) {
    $id = $_POST["id"];
    try {
        $stmt = $con->prepare("DELETE FROM Vestuario WHERE id = ?");
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Vestuario deletado com sucesso!";
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
    </head>
    <body>

        <form action="?act=save_vest" method="POST" name="form2" >
          <h1>Adicionar vestuario</h1>
          <hr>
          <input type="hidden" name="id" <?php
            if (isset($id) && $id != null || $id != "") {
                echo "value=\"{$id}\"";
            }
            ?>/>
          Tipo:
          <input type="text" name="tipo" <?php
            if (isset($tipo) && $tipo != null || $tipo != "") {
                echo "value=\"{$tipo}\"";
            }
            ?>/>
          <br/>
          Marca:
          <input type="text" name="marca" <?php
            if (isset($marca) && $marca != null || $marca != "") {
                echo "value=\"{$marca}\"";
            }
            ?>/>
          <br/>
          Cor:
          <input type="text" name="cor" <?php
            if (isset($cor) && $cor != null || $cor != "") {
                echo "value=\"{$cor}\"";
            }
            ?>/>
         <br/>
          Legenda:
           <input type="text" name="legenda" <?php
            if (isset($legenda) && $legenda != null || $legenda != "") {
                echo "value=\"{$legenda}\"";
            }
            ?>/>
         <br/>
         Quantidade:
           <input type="text" name="quantidade" <?php
            if (isset($quantidade) && $quantidade != null || $quantidade != "") {
                echo "value=\"{$quantidade}\"";
            }
            ?>/>
           <br/>
           Preço:
          <input type="text" name="preço" <?php
            if (isset($preço) && $preço != null || $preço != "") {
                echo "value=\"{$preço}\"";
            }
            ?>/>
           <br/>
         <input type="submit" value="Salvar" />
         <input type="reset" value="Novo" />
         <hr>
         </form>

         <?php
         $comando1 = $con->query("SELECT Vestuario.id AS vestuario_id, Login.id AS usuario_id, Vestuario.*, Login.*
                         FROM Vestuario 
                         INNER JOIN Login ON Vestuario.usuario = Login.usuario 
                         WHERE Vestuario.usuario = '$username'");
         
         while ($var_linha = $comando1->fetch()) {
            
            echo "<form action=\"?act=att_vest\" method=\"POST\" style=\"display: inline;\">
                   <input type=\"hidden\" name=\"id\" value=\"" . $var_linha['vestuario_id'] . "\" />
                   Tipo: <input type=\"text\" name=\"tipo_view\" value=\"" . $var_linha['tipo'] . "\" /><br/>
                   Marca: <input type=\"text\" name=\"marca_view\" value=\"" . $var_linha['marca'] . "\" /><br/>
                   Cor: <input type=\"text\" name=\"cor_view\" value=\"" . $var_linha['cor'] . "\" /><br/>
                   Legenda: <input type=\"text\" name=\"legenda_view\" value=\"" . $var_linha['legenda'] . "\" /><br/>
                   Quantidade: <input type=\"text\" name=\"quantidade_view\" value=\"" . $var_linha['quantidade'] . "\" /><br/>
                   Preço: <input type=\"text\" name=\"preço_view\" value=\"" . $var_linha['preço'] . "\" /><br/>
                   <input type=\"submit\" value=\"Atualizar\" onclick=\"return confirm('Atualizar esta peça??');\"/>
                   </form>
                   <form action=\"?act=del_vest\" method=\"POST\" style=\"display: inline;\">
                    <input type=\"hidden\" name=\"id\" value=\"" . $var_linha['vestuario_id'] . "\" />
                    <input type=\"submit\" value=\"Deletar\" onclick=\"return confirm('Deletar esta peça?');\"/>
                    </form>
                    <hr/>";
        }
        ?>

    </body>
</html>
