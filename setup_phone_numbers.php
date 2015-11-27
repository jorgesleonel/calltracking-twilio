<?php
	require_once('./include/config.php');
    $client = new TwilioRestClient($AccountSid, $AuthToken);

	if ( isset($_REQUEST['Sid'])){
		//update a Twilio number
	    $data = array(
	    	"FriendlyName" => $_REQUEST['friendly_name'],
	    	"VoiceUrl" => $_REQUEST['url']
	    );
	    $response = $client->request("/$ApiVersion/Accounts/$AccountSid/IncomingPhoneNumbers/" . $_REQUEST['Sid'], "POST", $data); 
	    if($response->IsError)
	    	echo "Erro ao atualizaro o numero: {$response->ErrorMessage}\n";
		else
	    	echo "<div class=\"confirm\"><img src=\"images/check.png\">Updated: {$response->ResponseXml->IncomingPhoneNumber->PhoneNumber}</div>";
	}

	if ( isset($_REQUEST['area_code'])){
		//purchase new Twilio number
	    $data = array(
	    	"FriendlyName" => $_REQUEST['friendly_name'],
	    	"VoiceUrl" => $_REQUEST['url'],	 
	    	"AreaCode" => $_REQUEST['area_code']
	    );
	    $response = $client->request("/$ApiVersion/Accounts/$AccountSid/IncomingPhoneNumbers", "POST", $data); 
	    if($response->IsError)
	    	echo "Error purchasing phone number: {$response->ErrorMessage}\n";
		else
	    	echo "<div class=\"confirm\"><img src=\"images/check.png\">Purchased: {$response->ResponseXml->IncomingPhoneNumber->PhoneNumber}</div>";
	}

	$twilio_numbers=Util::get_all_twilio_numbers();
    $response = $client->request("/$ApiVersion/Accounts/$AccountSid/IncomingPhoneNumbers", "GET"); // Obtem os numeros de telefone
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

	<html lang="en">
		<head>
			<title>Acompanhamento de Chamadas</title>

			<meta http-equiv="content-type" content="text/html; charset=utf-8">
			<link rel="stylesheet" type="text/css" href="./css/main.css" media="screen">
		</head>
		<body>
			<div id="container">
				<h1>Seus numeros Twilio</h1>
				<?php if (count($twilio_numbers)>0){ ?>
					<table id="rounded-corner">
				    <thead>
				    	<tr>
				        	<th scope="col" class="rounded-company">Numero</th>
				            <th scope="col" class="rounded-q1">Nome</th>
				            <th scope="col" class="rounded-q4">URL</th>
				        </tr>
				    </thead>
				    <tbody>
					<?php foreach($response->ResponseXml->IncomingPhoneNumbers->IncomingPhoneNumber AS $number){ ?>
						<tr>
							<form method="POST">
							<input type="hidden" name="Sid" value="<?php echo($number->Sid);?>">
							<td><?php echo($number->PhoneNumber);?></td>
							<td><input type="text" name="friendly_name" value="<?php echo($number->FriendlyName);?>" size="30"></td>
							<td><input type="text" value="<?php echo($number->VoiceUrl);?>" size="60" name="url"> <input type="submit" value="Update"></td>
							</form>
						</tr>
						<?php } ?>
		    		</tbody>
				</table>
				<?php } else { ?>
					Nao houve aquisicao de numeros Twilio ainda.  Voce pode adquirir os numeros atraves do formulario abaixo.
					<br/><br/>Ou ainda usar o teste gratuito apontando o Sandbox Voice URL para: <span id="sandbox"></span><br/>
					Eh possivel mudar  <a href="https://www.twilio.com/user/account" target="_blank">clicando aqui</a> e buscando no menu Developer Tools/Sandbox.
				<?php } ?>
			<h1>Adquira um numero</h1>
			<table id="rounded-corner">
		    <thead>
		    	<tr>
		        	<th scope="col" class="rounded-company">Codigo de Area</th>
		            <th scope="col" class="rounded-q1">Nome</th>
		            <th scope="col" class="rounded-q4">URL</th>
		        </tr>
		    </thead>
		    <tbody>
				<tr>
					<form method="POST">
					<td><input type="text" name="area_code"></td>
					<td><input type="text" name="friendly_name" size="30"></td>
					<td><input type="text" name="url" id="new_url" value="http://yourserver/call_tracking/handle_incoming_call.php" size="60"> <input type="submit" value="Purchase"></td>
					</form>
				</tr>
			</tbody>
			</table>
	
			<div id="footer">
				<h3><a href="index.php">back to home</a></h3>
				<p><a href="http://www.twilio.com/"><img src="./images/twilio_logo.png" border="0"></a></p>
			</div>
		</div>
		
		<script>
			document.getElementById('new_url').value=document.location.href.split("setup_phone_numbers.php")[0] + 'handle_incoming_call.php';
			document.getElementById('sandbox').innerHTML=document.location.href.split("setup_phone_numbers.php")[0] + 'handle_incoming_call.php';
		</script>
		
	</body>
</html>

