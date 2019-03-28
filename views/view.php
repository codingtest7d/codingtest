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
					<div class="col-sm-3">
						<ul>
							<li><a href="index.php">Home</a></li>
							<li><?php echo $Infinity->getServicesList(); ?></li>
							<li><a href="index.php?action=servicetypes">Service types</a></li>
							<li><a href="index.php?logout">Logout</a></li>
						</ul>
				</div>
				<div class="col-sm-9">
					<h2 class="text-danger"><?php echo $heading;?></h2>
						<?php echo $body; ?>
				</div>
			</form>
		</div>
	</body>
</html>