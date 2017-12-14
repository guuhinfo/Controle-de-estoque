<?php
	session_start();

	// para assegurar que o usuário não entrou diretamente pelo link da página sem realizar a autenticação
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		include('../conn.inc.php');

		$lista = $_POST['lista'];
		$data = date("Y-m-d");
		$pk = date("Y-m-d H:i:s");

		$i = 0;
		$tam = count($lista);
		while ($i < $tam) {
			$id = $lista[$i];
			$sql = "SELECT id, item FROM estoque WHERE id = $id;";

			if ($res = mysqli_query($conn, $sql)) {
				$row = mysqli_fetch_assoc($res);
				$item = $row['item'];

				$sql = "INSERT INTO listas VALUES (default, '$id', '$item', '$data', '$pk');";

				mysqli_query($conn, $sql);
			}

			$i++;
		}

		header("Location: ../../listas.php");
	}
	else {
		header("Location: ../../painel-admin.php");
		exit();
	}
?>
