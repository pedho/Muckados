<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $tipo = (isset($_POST["tipo"]) && $_POST["tipo"] != null) ? $_POST["tipo"] : NULL;
    $marca = (isset($_POST["marca"]) && $_POST["marca"] != null) ? $_POST["marca"] : "";
    $cor = (isset($_POST["cor"]) && $_POST["cor"] != null) ? $_POST["cor"] : "";
    $legenda = (isset($_POST["legenda"]) && $_POST["legenda"] != null) ? $_POST["legenda"] : "";
    $quantidade = (isset($_POST["quantidade"]) && $_POST["quantidade"] != null) ? $_POST["quantidade"] : "";
    
} else if (!isset($id)) {
    
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $tipo = NULL;
    $marca = NULL;
    $cor = NULL;
    $legenda = NULL;
    $quantidade = NULL;
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
        $stmt = $con->prepare("INSERT INTO Vestuario (tipo, marca, cor, legenda) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $tipo);
        $stmt->bindParam(2, $marca);
        $stmt->bindParam(3, $cor);
        $stmt->bindParam(4, $legenda);
         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $tipo = null;
                $marca = null;
                $cor = null;
                $legenda = null;
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
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "delete" && $id != "") {
    try {
        $stmt = $con->prepare("DELETE FROM Vestuario WHERE COD = ?");
        $stmt->bindParam(1, $id);
         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados apagados com sucesso!";
                $id = null;
                $tipo = null;
                $marca = null;
                $cor = null;
                $legenda = null;
            } else {
                echo "Erro ao tentar deletar";
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
         <input type="submit" value="Salvar" />
         <input type="reset" value="Novo" />
         <hr>

         <?php
         $comando1 = $con->query("SELECT * FROM produto");
         
         while ($var_linha = $comando1->fetch()) {
            
            echo "<form action=\"?act=att_prod\" method=\"POST\">
                   <input type=\"hidden\" name=\"id\" value=\"" . $var_linha['id'] . "\" />
                   Tipo: <input type=\"text\" name=\"tipo\" value=\"" . $var_linha['tipo'] . "\" /><br/>
                   Marca: <input type=\"text\" name=\"marca\" value=\"" . $var_linha['marca'] . "\" /><br/>
                   Legenda: <input type=\"text\" name=\"legenda\" value=\"" . $var_linha['legenda'] . "\" /><br/>
                   Quantidade: <input type=\"text\" name=\"quantidade\" value=\"" . $var_linha['quantidade'] . "\" /><br/>
                   <input type=\"submit\" value=\"Atualizar\" />
                   </form><hr/>";
        }
        ?>

    </body>
</html>
