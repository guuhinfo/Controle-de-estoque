<?php
	session_start();
	include_once("includes/conn.inc.php");
	
	// verifica se o usuario realizou o login
	if (!isset($_SESSION['id'])) {
		header("Location: index.html");
		exit;
	}

	$userid = $_SESSION['id'];
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta lang="pt-br">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Painel</title>
	<!-- Bootstrap core CSS-->
	<link href="vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
	<!-- Custom fonts for this template-->
	<link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<!-- Page level plugin CSS-->
	<link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
	<!-- Custom styles for this template-->
	<link href="css/sb-admin.css" rel="stylesheet">
	<!-- CSS personalizado -->
	<link rel="stylesheet" href="css/style.css">
<!--	<link href="http://harvesthq.github.io/chosen/chosen.css" rel="stylesheet"/>-->
	<link rel="stylesheet" href="css/bootstrap-chosen.css">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">

	<?php
		if ($_SESSION['tipo'] == 'comum') {
			echo "<nav class='navbar navbar-expand-lg navbar-dark bg-dark fixed-top' id='mainNav'>
					<a class='navbar-brand' href='painel.php'>Controle de Estoque</a>
					<button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarResponsive' aria-controls='navbarResponsive' aria-expanded='false' aria-label='Toggle navigation'>
						<span class='navbar-toggler-icon'></span>
					</button>

					<div class='collapse navbar-collapse' id='navbarResponsive'>
						<ul class='navbar-nav navbar-sidenav' id='exampleAccordion'>
							<li class='nav-item' data-toggle='tooltip' data-placement='right' title='Tables'>
								<a class='nav-link' href='painel.php'>
									<i class='fa fa-fw fa-table'></i>
									<span class='nav-link-text'>Buscar</span>
								</a>
							</li>
						</ul>

						<ul class='navbar-nav sidenav-toggler'>
							<li class='nav-item'>
								<a class='nav-link text-center' id='sidenavToggler'>
									<i class='fa fa-fw fa-angle-left'></i>
								</a>
							</li>
						</ul>

						<ul class='navbar-nav ml-auto'>
							<li class='nav-item'>
								<a class='nav-link' data-toggle='modal' data-target='#exampleModal'><i class='fa fa-fw fa-sign-out'></i>Sair</a>
							</li>
						</ul>
					</div>
				</nav>";
		}
		else {
			echo "<nav class='navbar navbar-expand-lg navbar-dark bg-dark fixed-top' id='mainNav'>
					<a class='navbar-brand' href='painel-admin.php'>Controle de Estoque</a>
					<button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarResponsive' aria-controls='navbarResponsive' aria-expanded='false' aria-label='Toggle navigation'>
						<span class='navbar-toggler-icon'></span>
					</button>

					<div class='collapse navbar-collapse' id='navbarResponsive'>
						<ul class='navbar-nav navbar-sidenav' id='exampleAccordion'>
							<li class='nav-item' data-toggle='tooltip' data-placement='right' title='Estoque'>
								<a class='nav-link nav-link-collapse collapsed' data-toggle='collapse' href='#collapseEstoque' data-parent='#exampleAccordion'>
									<i class='fa fa-fw fa-shopping-basket'></i>
									<span class='nav-link-text'>Estoque</span>
								</a>
								<ul class='sidenav-second-level collapse' id='collapseEstoque'>
									<li>
										<a class='nav-link' href='alterar-item.php'>
											<span class='nav-link-text'>Alterar Item</span>
										</a>
									</li>
									<li>
										<a class='nav-link' href='buscar-item.php'>
											<span class='nav-link-text'>Buscar Item</span>
										</a>
									</li>
									<li>
										<a class='nav-link' href='cadastrar-item.php'>
											<span class='nav-link-text'>Cadastrar Item</span>
										</a>
									</li>

									<li>
										<a class='nav-link' href='deletar-item.php'>
											<span class='nav-link-text'>Deletar Item</span>
										</a>
									</li>
								</ul>
							</li>
							<li class='nav-item' data-toggle='tooltip' data-placement='right' title='Usuario'>
								<a class='nav-link nav-link-collapse collapsed' data-toggle='collapse' href='#collapseUsuario' data-parent='#exampleAccordion'>
									<i class='fa fa-fw fa-user'></i>
									<span class='nav-link-text'>Usuário</span>
								</a>
								<ul class='sidenav-second-level collapse' id='collapseUsuario'>
									<li>
										<a class='nav-link' href='cadastrar-usuario.php'>
											<span class='nav-link-text'>Cadastrar Usuário</span>
										</a>
									</li>
									<li>
										<a class='nav-link' href='deletar-usuario.php'>
											<span class='nav-link-text'>Deletar Usuário</span>
										</a>
									</li>
								</ul>
							</li>
						</ul>

						<ul class='navbar-nav sidenav-toggler'>
							<li class='nav-item'>
								<a class='nav-link text-center' id='sidenavToggler'>
									<i class='fa fa-fw fa-angle-left'></i>
								</a>
							</li>
						</ul>

						<ul class='navbar-nav ml-auto'>
							<li class='nav-item'>
								<a class='nav-link' data-toggle='modal' data-target='#exampleModal'><i class='fa fa-fw fa-sign-out'></i>Sair</a>
							</li>
						</ul>
					</div>
				</nav>";
		}
	?>
	
	<!-- Navigation-->
	

	<div class="content-wrapper">
		<div class="container-fluid">
			<!-- Breadcrumbs-->
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="painel.php">Painel</a>
				</li>
				<li class="breadcrumb-item active">Buscar</li>
			</ol>
			
			<div class="caixa">
				<?php
					$id = 0;
					$saida = 0;
					$msg = false;
				
					// guarda o id do item selecionado
					if (isset($_GET['item'])) {
						$id = mysqli_real_escape_string($conn, $_GET['item']);
					}

					// busca pelo item
					$sql = "SELECT id, item, quantidade FROM estoque WHERE id='$id';";

					if ($res = mysqli_query($conn, $sql)) {
						$row = mysqli_fetch_assoc($res);
						
						$quantidade = $row['quantidade'];
						
						if (isset($_POST['saida'])) {
							$saida = mysqli_real_escape_string($conn, $_POST['saida']);
							
							if ($saida < 0) {
								$msg = "<div class='alert alert-danger'>
											<strong>Erro!</strong> Quantidade usada inválida.
										</div>";
							}
							else {
								$quantidade = $quantidade - $saida;
							}
						}

						printf("<form class='info-item' action='buscar?item=$id' method='post'>
									<div class='form-group'>
										<h1>%s</h1>
										<p>Quantidade atual: %d</p>
										<label for='saida'>Quantidade usada:</label>
										<input class='form-control' type='number' min='1' name='saida' id='saida'>
										<button class='btn btn-primary'>Salvar</button>
									</div>
								</form>", $row['item'], $quantidade);
					}
				
					
				
					if (isset($_POST['saida']) && !$msg) {				
						$sql = "UPDATE estoque SET quantidade = '$quantidade' WHERE id='$id';";

						if ($res = mysqli_query($conn, $sql)) {
							echo "<div class='alert alert-success'>
									  <strong>Sucesso!</strong> A quantidade do item foi atualizada.
								  </div>";
						}
					}
					else {
						echo $msg;
					}


					// se a busca retornar resultados
//										if ($res = mysqli_query($conn, $sql)) {
//											// percorre pelos resultados
//											while ($row = mysqli_fetch_assoc($res)) {
//												
//												printf("<tr><td>%s</td><td>%d</td><td></td></tr>", $row['item'], $row['quantidade']);
//											}
//											
//											mysqli_free_result($res);
//										}								
				?>
			</div>

			<!-- Scroll to Top Button-->
			<a class="scroll-to-top rounded" href="#page-top">
				<i class="fa fa-angle-up"></i>
			</a>
			<!-- Logout Modal-->
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Tem certeza que deseja sair?</h5>
							<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">×</span>
					</button>
						</div>
						<div class="modal-body">Clique em "Sair" se deseja encerrar a sessão atual.</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
							<form action="includes/logout.inc.php" method="post">
								<button class="btn btn-primary" type="submit">Sair</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Bootstrap core JavaScript-->
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/popper/popper.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<!-- Core plugin JavaScript-->
	<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
	<!-- Page level plugin JavaScript-->
	<script src="vendor/chart.js/Chart.min.js"></script>
	<script src="vendor/datatables/jquery.dataTables.js"></script>
	<script src="vendor/datatables/dataTables.bootstrap4.js"></script>
	<!-- Custom scripts for all pages-->
	<script src="js/sb-admin.min.js"></script>
	<script src="http://harvesthq.github.io/chosen/chosen.jquery.js"></script>
	<script src="js/custom.js"></script>
	<!-- Custom scripts for this page-->
	<script src="js/sb-admin-datatables.min.js"></script>
	<script src="js/sb-admin-charts.min.js"></script>
</body>

</html>