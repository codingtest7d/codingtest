<html>
	<head>
		<title>
			Services
		</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">

	</head>
	<body>	
		<div class="container">
			<h1 class="text-center">7D Digital Coding Challenge</h1>
			<form action="index.php" method="post">
				<div class="row ">
					<div class="col-sm-4"></div>
					<div class="col-sm-4">
						<label for="client_id">Client ID</label>
						<input type="text" name="client_id" class="form-control">
						<label for="client_secret">Client Secret</label>
						<input type="text" name="client_secret" class="form-control">
						<input type="submit" class="form-control">
						<?php  if(isset($error)){ echo $error;}?>

					</div>
					<div class="col-sm-4"></div>
				</div>
			</form>
		</div>
	</body>
</html>



