 <?php

 class Infinity {


 	private $curl;

	private $client_id;
	private $client_secret;
	private $api_endpoint_path = "http://demo.infiny.cloud/api/";

	private $access_token;
	private $access_token_expiry_time;
	private $refresh_token;

	public function __construct($client_id, $client_secret){
	
		$this->curl =  curl_init();
		$this->client_secret = $client_secret;
		$this->client_id = $client_id;

		curl_setopt_array($this->curl, [
   		CURLOPT_RETURNTRANSFER => true,
   		CURLOPT_CONNECTTIMEOUT => 10,
   		CURLOPT_TIMEOUT => 10,
   		CURLOPT_POST  => true,
		CURLOPT_HTTPHEADER => array('Accept:application/vnd.cloudlx.v1+json')]);

		$auth = $this->request_auth_token();

		if(!$auth){
			throw new Exception('Authentication failed');
		}

	}

	private function request_auth_token(){
		
		if(file_exists(".access_token") && time()-filemtime(".access_token") < 3600 ){
				$this->access_token_expiry_time = filemtime(".access_token") + 3600;
				$this->refresh_token =  file_get_contents(".refresh_token");
				$this->access_token =  file_get_contents(".access_token");
		}

		else if(file_exists(".refresh_token") && time()-filemtime(".refresh_token") < 604800 ){
			
			$this->refresh_token =  file_get_contents(".refresh_token");
			$this->access_token_expiry_time = time()+3600;
		
			$post = [
		        "refresh_token" => $this->refresh_token,
		        "grant_type" => "refresh_token",
		       	"client_id" => $this->client_id,
       			"client_secret" => $this->client_secret
			];

			$api_endpoint = $this->api_endpoint_path . "oauth2/refresh-token";

			curl_setopt($this->curl, CURLOPT_URL, $api_endpoint ); 
			curl_setopt($this->curl,CURLOPT_POSTFIELDS , $post);
			$response = curl_exec($this->curl);

			 
			$reponseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);   
			
			///Something went wrong				
			 if($reponseCode!=200){
			 	return false;
			 }

			$json_response = json_decode($response);
		
			$this->access_token = $json_response->access_token;

			$file = fopen(".access_token", "w");
			$written = fwrite($file,$this->access_token);

			$this->refresh_token = $json_response->refresh_token;
			$this->expiry_time = time() + 3600;
		
		}else{

			$post = [
		        "grant_type" => 'client_credentials',
		        "client_id" => $this->client_id,
		        "client_secret" => $this->client_secret
			];

			$api_endpoint = $this->api_endpoint_path . "oauth2/access-token";

			curl_setopt($this->curl, CURLOPT_URL, $api_endpoint ); 
			curl_setopt($this->curl,CURLOPT_POSTFIELDS , $post);
			$response = curl_exec($this->curl);


			$reponseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE); 
			 if($reponseCode!=200){
			 	return false;
			 }

			$json_response = json_decode($response);

			$this->access_token = $json_response->access_token;
			$this->refresh_token = $json_response->refresh_token;
			$this->expiry_time = time() + 3600;

			$file = fopen(".refresh_token", "w");
			$written = fwrite($file,$this->refresh_token);

			$file = fopen(".access_token", "w");
			$written = fwrite($file,$this->access_token);
		
   		}
   		return true;
	}


	public function getAllServices(){

		
		$api_endpoint = $this->api_endpoint_path . "services" ;
	
		curl_setopt($this->curl, CURLOPT_URL, $api_endpoint); 
		curl_setopt($this->curl, CURLOPT_HTTPGET, true);
		$headers = array(
			'Accept:application/vnd.cloudlx.v1+json',
			'Authorization: Bearer ' . $this->access_token,
			);

		curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);

		$response = curl_exec($this->curl);
		$json_response =json_decode($response,true);

		$output = "";


		foreach($json_response['services'] as $object){

			$output .= "<h3>". $object["name"] . "</h3>";
			$output .= $this->displayArray($object);
		}

		return $output;
	}

	public function getServiceDetails($service){

		$api_endpoint = $this->api_endpoint_path . "services/".$service."/service";

	
		curl_setopt($this->curl, CURLOPT_URL, $api_endpoint); 
		curl_setopt($this->curl, CURLOPT_HTTPGET, true);
		$headers = array(
			'Accept:application/vnd.cloudlx.v1+json',
			'Authorization: Bearer ' . $this->access_token,
			);

		curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);

		$response = curl_exec($this->curl);
		$json_response =json_decode($response,true);
	
		$output = $this->displayArray($json_response);

		return $output;

	}

	public function getServiceTypes(){
		$api_endpoint = $this->api_endpoint_path . "services/types";
		curl_setopt($this->curl, CURLOPT_URL, $api_endpoint); 
		curl_setopt($this->curl, CURLOPT_HTTPGET, true);
		
		$headers = array(
			'Accept:application/vnd.cloudlx.v1+json',
			'Authorization: Bearer ' . $this->access_token,
			);

		curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);

		$response = curl_exec($this->curl);
		$json_response =json_decode($response,true);
		$output = "";

	
		foreach($json_response['service_types'] as $object){

			$output .= "<h3>". $object["name"] . "</h3>";
			$output .= $this->displayArray($object);
		}
		return $output;
	}

	public function getServicesList(){

	
		$api_endpoint = $this->api_endpoint_path . "services" ;

		curl_setopt($this->curl, CURLOPT_URL, $api_endpoint); 
		curl_setopt($this->curl, CURLOPT_HTTPGET, true);
		
		$headers = array(
			'Accept:application/vnd.cloudlx.v1+json',
			'Authorization: Bearer ' . $this->access_token,
			);

		curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);

		$response = curl_exec($this->curl);

		$json_response =json_decode($response,true);


		$output = "<a href='index.php?action=allservices'>Services</a><br>";

		foreach($json_response['services'] as $object){
			$output .= "<a href='index.php?action=getservicedetails&id=". $object["id"]."'> - ". $object["name"] . "</a><br>";
		}

		return $output;
	}


	function displayArray($array,$output="") {
	    if (is_array($array)) {
	        $output .= "<table class='table table-light'>";
	         
	        foreach ($array as $key => $value) {
	                 $output .= '<tr><td valign="top">';
	                 $output .= '<strong>' . $key . "</strong></td><td>";

	                   if(!is_array($value)){
	                   		if($value ===null){
	                   			$output .= "null";
	                   		}else if($value === false){
	                   			$output .= "No";
	                   		}else if($value === true){
	                   			$output .= "Yes";
	                   		}else{
	                   			$output .= $value;
	                   		}	
	                	}

	               	$output.= $this->displayArray($value);
	                $output .= "</td></tr>";
	        }
	         $output .= "</table>";
	        return  $output;
	    }
	     $output .= $array;
	}
 


 }


 ?>