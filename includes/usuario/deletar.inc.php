<?php
	session_start();

	// para assegurar que o usuário não entrou diretamente pelo link da página sem realizar a autenticação
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		include('../conn.inc.php');

		if (isset($_GET['id'])) {
			$id = mysqli_real_escape_string($conn, $_GET['id']);
		}

		// deleta o usuário
		$sql = "DELETE FROM usuarios WHERE id='$id';";

		if ($res = mysqli_query($conn, $sql)) {
			header("Location: ../../deletar-usuario.php?deletar=sucesso");
		}
		else {
			header("Location: ../../deletar-usuario.php?deletar=erro");
		}
	}
	else {
		header("Location: ../../deletar-usuario.php?deletar=erro");
		exit();
	}
?>