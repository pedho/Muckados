<?php
// na outra pg
if o login funcionou {
    $_SESSION["username"] = pessoa do banco de dados q vc puxou
}

// session_start();
//nessa pg
if (!isset($_SESSION["username"])) {
    exit();
} else {

    $username = $_SESSION["username"]
}
// para deslogar unset($_SESSION["username"])




if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $usuario = (isset($_POST["usuario"]) && $_POST["usuario"] != null) ? $_POST["usuario"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $tipo = (isset($_POST["tipo"]) && $_POST["tipo"] != null) ? $_POST["tipo"] : NULL;
    $marca = (isset($_POST["marca"]) && $_POST["marca"] != null) ? $_POST["marca"] : NULL;
    $sabor = (isset($_POST["sabor"]) && $_POST["sabor"] != null) ? $_POST["sabor"] : NULL;
    $legenda = (isset($_POST["legenda"]) && $_POST["legenda"] != null) ? $_POST["legenda"] : "";
    $quantidade = (isset($_POST["quantidade"]) && $_POST["quantidade"] != null) ? $_POST["quantidade"] : "";
    $preço = (isset($_POST["preço"]) && $_POST["preço"] != null) ? $_POST["preço"] : NULL;
    
} else if (!isset($id)) {

    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $usuario = NULL;
    $nome = NULL;
    $tipo = NULL;
    $marca = NULL;
    $sabor = NULL;
    $legenda = NULL;
    $quantidade = NULL;
    $preço = NULL;
    
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
        $stmt = $con->prepare("INSERT INTO Produto (usuario, nome, tipo, marca, sabor, legenda, quantidade, preço) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $nome);
        $stmt->bindParam(3, $tipo);
        $stmt->bindParam(4, $marca);
        $stmt->bindParam(5, $sabor);
        $stmt->bindParam(6, $legenda);
        $stmt->bindParam(7, $quantidade);
        $stmt->bindParam(8, $preço);
         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = NULL;
                $usuario = NULL;
                $nome = NULL;
                $tipo = NULL;
                $marca = NULL;
                $sabor = NULL;
                $legenda = NULL;
                $quantidade = NULL;
                $preço = NULL;
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
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "att_prod" && $nome != "") {
    try {
        $stmt = $con->prepare("UPDATE Produto set nome = ?, tipo = ?, marca = ?, sabor = ?, legenda = ?, quantidade = ?, preço = ? where id = ?");
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $tipo);
        $stmt->bindParam(3, $marca);
        $stmt->bindParam(4, $sabor);
        $stmt->bindParam(5, $legenda);
        $stmt->bindParam(6, $quantidade);
        $stmt->bindParam(7, $preço);
        $stmt->bindParam(8, $id);

         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = NULL;
                $usuario = NULL;
                $nome = NULL;
                $tipo = NULL;
                $marca = NULL;
                $sabor = NULL;
                $legenda = NULL;
                $quantidade = NULL;
                $preço = NULL;

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

          <input type="hidden" name="usuario" <?php
            if (isset($usuario) && $usuario != null || $usuario != "") {
                echo "value=\"{$usuario}\"";
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
          <input type="text" name="tipo" <?php
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
         $comando1 = $con->query("SELECT * FROM produto");
         
         while ($var_linha = $comando1->fetch()) {
            
            echo "<form action=\"?act=att_prod\" method=\"POST\">
                   <input type=\"hidden\" name=\"id\" value=\"" . $var_linha['id'] . "\" />
                   Nome: <input type=\"text\" name=\"nome\" value=\"" . $var_linha['nome'] . "\" /><br/>
                   Tipo: <input type=\"text\" name=\"tipo\" value=\"" . $var_linha['tipo'] . "\" /><br/>
                   Marca: <input type=\"text\" name=\"marca\" value=\"" . $var_linha['marca'] . "\" /><br/>
                   Sabor: <input type=\"text\" name=\"sabor\" value=\"" . $var_linha['sabor'] . "\" /><br/>
                   Legenda: <input type=\"text\" name=\"legenda\" value=\"" . $var_linha['legenda'] . "\" /><br/>
                   Quantidade: <input type=\"text\" name=\"quantidade\" value=\"" . $var_linha['quantidade'] . "\" /><br/>
                   Preço: <input type=\"text\" name=\"preço\" value=\"" . $var_linha['preço'] . "\" /><br/>
                   <input type=\"submit\" value=\"Atualizar\" />
                   </form><hr/>";
        }
        ?>
         

    </body>
</html>
