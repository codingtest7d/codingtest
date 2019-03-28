<?php
session_start();

require("classes/class.infinity.php");


if(isset($_GET['logout'])){
	unset($_SESSION['client_id']);
	unset($_SESSION['client_secret']);

	header("Location:");
}


//Get ID & secret 
if(isset($_POST['client_id']) && isset($_POST['client_secret'])){
	$_SESSION['client_id'] = $_POST['client_id'];
	$_SESSION['client_secret'] = $_POST['client_secret'];
	header("Location: index.php");

}

else if(isset($_SESSION['client_id']) && isset($_SESSION['client_secret'])){

	try {

		$Infinity = new Infinity($_SESSION['client_id'],$_SESSION['client_secret']);
	} catch  (Exception $e) {
    // handle exception
		$error = $e->getMessage();
		require("views/auth.php");
		exit();
	}


	if(!empty($_GET)){

		switch ($_GET['action']){

			case "allservices":
				$heading = "All Services";
				$body = $Infinity->getAllServices();

			break;

			case "servicetypes":
				$heading = "Service Types";
				$body = $Infinity->getServiceTypes();
			break;

			case "getservicedetails":
				$heading = "Service Details";
				if(isset($_GET['id'])){
					$body=$Infinity->getServiceDetails($_GET['id']);
				}else{
					header("Location: index.php");
				}
			break;

			default:
				header("Location: index.php");
			break;	
		}
		
		require("views/view.php");

	}else{
		$heading = "Home";
		$body = "Nothing to see here...";
		require("views/view.php");
	}



}else{

	require("views/auth.php");
}

?>
