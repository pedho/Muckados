<?php
// session_start();
//nessa pg
// para deslogar unset($_SESSION["username"])
session_start(); // INICIA A SESSÃO
if (!isset($_SESSION["username"])) {
    exit("Usuário não autenticado."); // Mensagem para debugging
} else {
    $username = $_SESSION["username"];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $tipo = (isset($_POST["tipo"]) && $_POST["tipo"] != null) ? $_POST["tipo"] : NULL;
    $marca = (isset($_POST["marca"]) && $_POST["marca"] != null) ? $_POST["marca"] : NULL;
    $sabor = (isset($_POST["sabor"]) && $_POST["sabor"] != null) ? $_POST["sabor"] : NULL;
    $legenda = (isset($_POST["legenda"]) && $_POST["legenda"] != null) ? $_POST["legenda"] : "";
    $quantidade = (isset($_POST["quantidade"]) && $_POST["quantidade"] != null) ? $_POST["quantidade"] : "";
    $preço = (isset($_POST["preço"]) && $_POST["preço"] != null) ? $_POST["preço"] : NULL;
    $nome_view = (isset($_POST["nome_view"]) && $_POST["nome_view"] != null) ? $_POST["nome_view"] : "";
    $tipo_view = (isset($_POST["tipo_view"]) && $_POST["tipo_view"] != null) ? $_POST["tipo_view"] : NULL;
    $marca_view = (isset($_POST["marca_view"]) && $_POST["marca_view"] != null) ? $_POST["marca_view"] : NULL;
    $sabor_view = (isset($_POST["sabor_view"]) && $_POST["sabor_view"] != null) ? $_POST["sabor_view"] : NULL;
    $legenda_view = (isset($_POST["legenda_view"]) && $_POST["legenda_view"] != null) ? $_POST["legenda_view"] : "";
    $quantidade_view = (isset($_POST["quantidade_view"]) && $_POST["quantidade_view"] != null) ? $_POST["quantidade_view"] : "";
    $preço_view = (isset($_POST["preço_view"]) && $_POST["preço_view"] != null) ? $_POST["preço_view"] : NULL;
    
} else if (!isset($id)) {

    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nome = NULL;
    $tipo = NULL;
    $marca = NULL;
    $sabor = NULL;
    $legenda = NULL;
    $quantidade = NULL;
    $preço = NULL;
    $nome_view = NULL;
    $tipo_view = NULL;
    $marca_view = NULL;
    $sabor_view = NULL;
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
    
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save_prod" && $nome != "") {
    try {
        $stmt = $con->prepare("INSERT INTO Produto (nome, usuario, tipo, marca, sabor, legenda, quantidade, preço) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $username);
        $stmt->bindParam(3, $tipo);
        $stmt->bindParam(4, $marca);
        $stmt->bindParam(5, $sabor);
        $stmt->bindParam(6, $legenda);
        $stmt->bindParam(7, $quantidade);
        $stmt->bindParam(8, $preço);
         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $nome = null;
                $tipo = null;
                $marca = null;
                $sabor = null;
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
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "att_prod" && $nome_view != "") {
    try {
        $stmt = $con->prepare("UPDATE Produto set nome = ?, tipo = ?, marca = ?, sabor = ?, legenda = ?, quantidade = ?, preço = ? where id = ?");
        $stmt->bindParam(1, $nome_view);
        $stmt->bindParam(2, $tipo_view);
        $stmt->bindParam(3, $marca_view);
        $stmt->bindParam(4, $sabor_view);
        $stmt->bindParam(5, $legenda_view);
        $stmt->bindParam(6, $quantidade_view);
        $stmt->bindParam(7, $preço_view);
        $stmt->bindParam(8, $id);

         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = NULL;
                $nome_view = NULL;
                $tipo_view = NULL;
                $marca_view = NULL;
                $sabor_view = NULL;
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
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del_prod" && isset($_POST["id"])) {
    $id = $_POST["id"];
    try {
        $stmt = $con->prepare("DELETE FROM Produto WHERE id = ?");
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Produto deletado com sucesso!";
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
        <form action="?act=save_prod" method="POST" name="form1">
          <h1>Adicionar produtos</h1>
          <hr>
          <input type="hidden" name="id" <?php
            if (isset($id) && $id != null || $id != "") {
                echo "value=\"{$id}\"";
            }
            ?>/>

          Nome:
          <input type="text" name="nome" <?php
            if (isset($nome) && $nome != null || $nome != "") {
                echo "value=\"{$nome}\"";
            }
            ?>/>
          <br/>
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
          
          Sabor:
          <input type="text" name="sabor" <?php
            if (isset($sabor) && $sabor != null || $sabor != "") {
                echo "value=\"{$sabor}\"";
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
         <?php
         $comando1 = $con->query("SELECT * FROM Produto INNER JOIN Login ON Produto.usuario = Login.usuario WHERE Produto.usuario = '$username'");
         
         while ($var_linha = $comando1->fetch()) {
            
            echo "<form action=\"?act=att_prod\" method=\"POST\" style=\"display: inline;\">
                   <input type=\"hidden\" name=\"id\" value=\"" . $var_linha['id'] . "\" />
                   Nome: <input type=\"text\" name=\"nome_view\" value=\"" . $var_linha['nome'] . "\" /><br/>
                   Tipo: <input type=\"text\" name=\"tipo_view\" value=\"" . $var_linha['tipo'] . "\" /><br/>
                   Marca: <input type=\"text\" name=\"marca_view\" value=\"" . $var_linha['marca'] . "\" /><br/>
                   Sabor: <input type=\"text\" name=\"sabor_view\" value=\"" . $var_linha['sabor'] . "\" /><br/>
                   Legenda: <input type=\"text\" name=\"legenda_view\" value=\"" . $var_linha['legenda'] . "\" /><br/>
                   Quantidade: <input type=\"text\" name=\"quantidade_view\" value=\"" . $var_linha['quantidade'] . "\" /><br/>
                   Preço: <input type=\"text\" name=\"preço_view\" value=\"" . $var_linha['preço'] . "\" /><br/>
                   <input type=\"submit\" value=\"Atualizar\" onclick=\"return confirm('Atualizar este produto?');\"/>
                   </form>
                   <form action=\"?act=del_prod\" method=\"POST\" style=\"display: inline;\">
                    <input type=\"hidden\" name=\"id\" value=\"" . $var_linha['id'] . "\" />
                    <input type=\"submit\" value=\"Deletar\" onclick=\"return confirm('Deletar este produto?');\"/>
                    </form>
                    <hr/>";
        }
        ?>
         

    </body>
</html>
