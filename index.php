<!DOCTYPE html>
<html>

<?php

//Set variables
$vin = "XXXXXXXX"; //Sost. le X con il numero di telaio
$user = "XXXXXXX"; //Sost. le X con il nome utente/email FordPass
$pass = "XXXXXXX"; //Sost. le X con la password FordPass
$make = "Ford";
$model = "XXXXXXXXXXX"; //Inserire qui il modello della vettura
$serbatoioLt = 0; //Indicare i litri totali del serabotio per il calcolo del carburante a bordo
$targa = "(IT) AA000AA"; //Inserire targa vettura
$clientId = "9fb503e0-715b-47e8-adfd-ad4b7770f73b"; //Valore fisso. !!NON CAMBIARE!!
$applicationId = "71A3AD0A-CF46-4CCF-B473-FC7FE5BC4592"; //Valore fisso. !!NON CAMBIARE!!
$token = ""; // !!Lasciare vuoto!!

// Get token
    
        // !! NON CAMBIARE NULLA SOTTO QUESTA RIGA !!

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcis.ice.ibmcloud.com/v1.0/endpoint/default/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id=9fb503e0-715b-47e8-adfd-ad4b7770f73b&grant_type=password&username=".$user."&password=".$pass);
    
    $headers = array();
    $headers[] = 'Accept: */*';
    $headers[] = 'Accept-Language: en-US';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    $headers[] = 'User-Agent: fordpass-na/353 CFNetwork/1121.2.2 Darwin/19.3.0';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    
    $tokenPayload = json_decode($result);
    $token = $tokenPayload->access_token;


//-------------------------
    // !! NON CAMBIARE NULLA SOTTO QUESTA RIGA !!
    
// Richiesta stato veicolo (JSON)

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://usapi.cv.ford.com/api/vehicles/v4/".$vin."/status?lrdt=01-01-1970%2000:00:00");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


$headers = array();
$headers[] = 'auth-token: '.$token;
$headers[] = 'Accept: */*';
$headers[] = 'Accept-Language: en-US';
$headers[] = 'Content-Type: application/json';
$headers[] = 'User-Agent: fordpass-na/353 CFNetwork/1121.2.2 Darwin/19.3.0';
$headers[] = 'Accept-Encoding: gzip, deflate, br';
$headers[] = 'Application-Id: 71A3AD0A-CF46-4CCF-B473-FC7FE5BC4592';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

$infoPayload = json_decode($result);
    
    echo $result;

//-------------------------

//Timestamp ultimo aggiornamento

$last_update_timestamp = $infoPayload->vehiclestatus->lastModifiedDate;
$last_update_timestamp = date_create($last_update_timestamp);

// Formattazione valori

	//Apertura

if ($infoPayload->vehiclestatus->lockStatus->value == "LOCKED") {
$statoPortiere = '<span style="color:darkgreen;">Bloccate</span>';
} else {
$statoPortiere = '<span style="color:red;">Sbloccate</span>';
}

	//Allarme
	
$alarm = $infoPayload->vehiclestatus->alarm->value;
switch($alarm) {
	case "NOTSET":
	$alarm = '<span style="color:darkgreen;">Disattivato</span>';
	break;
	case "SET":
	$alarm = '<span style="color:darkgreen;">Attivato</span>';
	break;
	case "ACTIVE":
	$alarm = '<span style="color:darkgreen;">Attivato</span>';
	break;
}

	// Motore

if ($infoPayload->vehiclestatus->ignitionStatus->value == "Off") {
$statoAcc = "Motore spento";
} else {
$statoAcc = "Motore acceso";
}

	//Pressione gomme

if ($infoPayload->vehiclestatus->tirePressure->value == "STATUS_GOOD") {
$statoGomme = '<span style="color:darkgreen;">OK</span>';
} else {
$statoGomme = '<span style="color:red;">Anomalia rilevata</span>';
}

	//Vita utile olio

if ($infoPayload->vehiclestatus->oil->oilLife == "STATUS_GOOD") {
$statoOlio = '<span style="color:darkgreen;">OK</span>';
} else {
$statoOlio = '<span style="color:red;">Anomalia rilevata</span>';
}

	//Posizione finestrini
	
$statoFinDrv = $infoPayload->vehiclestatus->windowPosition->driverWindowPosition->value;
$statoFinPass = $infoPayload->vehiclestatus->windowPosition->passWindowPosition->value;
$statoFinRearDrv = $infoPayload->vehiclestatus->windowPosition->rearDriverWindowPos->value;
$statoFinRearPass = $infoPayload->vehiclestatus->windowPosition->rearPassWindowPos->value;

switch($statoFinDrv){
    case "Fully_Closed":
        $statoFinDrv = '<span style="color:darkgreen;">Totalmente chiuso</span>';
        $winDrvClosed = true;
        break;
    case "BetFully_10PercentOpen":
    	$statoFinDrv = "<span style='color:red;'>Aperto (0% - 10%)</span>";
    	$winDrvClosed = false;
    	break;
    case "Bet10Percent_60Percent":
        $statoFinDrv = "<span style='color:red;'>Aperto (10% - 60%)</span>";
        $winDrvClosed = false;
        break;
    case "Bet60Percent_FullyOpen":
        $statoFinDrv = "<span style='color:red;'>Aperto (60% - 100%)</span>";
        $winDrvClosed = false;
        break;
    case "Fully_Open":
        $statoFinDrv = "<span style='color:red;'>Totalmente aperto</span>";
        $winDrvClosed = false;
        break;
        }
        
        switch($statoFinPass){
    case "Fully_Closed":
        $statoFinPass = "<span style='color:darkgreen;'>Totalmente chiuso</span>";
        $winPassClosed = true;
        break;
    case "BetFully_10PercentOpen":
    	$statoFinPass = "<span style='color:red;'>Aperto (0% - 10%)</span>";
    	$winPassClosed = false;
    	break;
    case "Bet10Percent_60Percent":
        $statoFinPass = "<span style='color:red;'>Aperto (10% - 60%)</span>";
        $winPassClosed = false;
        break;
    case "Bet60Percent_FullyOpen":
        $statoFinPass = "<span style='color:red;'>Aperto (60% - 100%)</span>";
        $winPassClosed = false;
        break;
    case "Fully_Open":
        $statoFinPass = "<span style='color:red;'>Totalmente aperto</span>";
        $winPassClosed = false;
        break;
        }
        
        switch($statoFinRearDrv){
    case "Fully_Closed":
        $statoFinRearDrv = "<span style='color:darkgreen;'>Totalmente chiuso</span>";
        $winRearDrvClosed = true;
        break;
    case "BetFully_10PercentOpen":
    	$statoFinRearDrv = "<span style='color:red;'>Aperto (0% - 10%)</span>";
    	$winRearDrvClosed = false;
    	break;
    case "Bet10Percent_60Percent":
        $statoFinRearDrv = "<span style='color:red;'>Aperto (10% - 60%)</span>";
        $winRearDrvClosed = false;
        break;
    case "Bet60Percent_FullyOpen":
        $statoFinRearDrv = "<span style='color:red;'>Aperto (60% - 100%)</span>";
        $winRearDrvClosed = false;
        break;
    case "Fully_Open":
        $statoFinRearDrv = "<span style='color:red;'>Totalmente aperto</span>";
        $winRearDrvClosed = false;
        break;
        }
        
		switch($statoFinRearPass){
    case "Fully_Closed":
        $statoFinRearPass = "<span style='color:darkgreen;'>Totalmente chiuso</span>";
        $winRearPassClosed = true;
        break;
    case "BetFully_10PercentOpen":
    	$statoFinRearPass = "<span style='color:red;'>Aperto (0% - 10%)</span>";
    	$winRearPassClosed = false;
    	break;
    case "Bet10Percent_60Percent":
        $statoFinRearPass = "<span style='color:red;'>Aperto (10% - 60%)</span>";
        $winRearPassClosed = false;
        break;
    case "Bet60Percent_FullyOpen":
        $statoFinRearPass = "<span style='color:red;'>Aperto (60% - 100%)</span>";
        $winRearPassClosed = false;
        break;
    case "Fully_Open":
        $statoFinRearPass = "<span style='color:red;'>Totalmente aperto</span>";
        $winRearPassClosed = false;
        break;
        }
        
    if ($winDrvClosed == true && $winPassClosed == true && $winRearDrvClosed == true && $winRearPassClosed == true)
    {
    $statoFinestrini = '<span style="color:darkgreen;">Tutti chiusi</span>';
    } else {
    $statoFinestrini = '<span style="color:red;">Rilevati finestrini aperti!</span>';
    }
    
    //Stato apertura vano motore
    
    if ($infoPayload->vehiclestatus->doorStatus->hoodDoor->value == "Closed" ) {
    $statoCofano = '<span style="color:darkgreen;">Chiuso</span>';
    $cofanoClosed = true;
    } else {
    $statoCofano = '<span style="color:red;">Aperto</span>';
    $cofanoClosed = false;
    }

	//Stato apertura bagagliaio
	
	if ($infoPayload->vehiclestatus->doorStatus->tailgateDoor->value == "Closed" ) {
    $statoBagagliaio = '<span style="color:darkgreen;">Chiuso</span>';
    $bagagliaioClosed = true;
    } else {
    $statoBagagliaio = '<span style="color:red;">Aperto</span>';
    $bagagliaioClosed = false;
    }
    
    //Stato aperuta portiere
    
	$portDrv = $infoPayload->vehiclestatus->doorStatus->driverDoor->value;
	$portPass = $infoPayload->vehiclestatus->doorStatus->passengerDoor->value;
	$portRearDrv = $infoPayload->vehiclestatus->doorStatus->leftRearDoor->value;
	$portRearPass = $infoPayload->vehiclestatus->doorStatus->rightRearDoor->value;
	
		switch($portDrv) {
		case "Closed":
			$portDrv = '<span style="color:darkgreen;">Chiuso</span>';
			$portDrvClosed = true;
			break;
		case "Ajar":
			$portDrv = '<span style="color:red;">Aperto</span>';
			$portDrvClosed = false;
			break;
		}
		
		switch($portPass) {
		case "Closed":
  			$portPass = '<span style="color:darkgreen;">Chiuso</span>';
 			$portPassClosed = true;
  			break;
		case "Ajar":
  			$portPass = '<span style="color:red;">Aperto</span>';
  			$portPassClosed = false;
  			break;
						  }
						  
		switch($portRearDrv) {
		case "Closed":
   			$portRearDrv = '<span style="color:darkgreen;">Chiuso</span>';
			$portRearDrvClosed = true;
    		break;
		case "Ajar":
		    $portRearDrv = '<span style="color:red;">Aperto</span>';
		    $portRearDrvClosed = false;
		    break;
					          }
					          
			switch($portRearPass) {
		case "Closed":
   			$portRearPass = '<span style="color:darkgreen;">Chiuso</span>';
  			$portRearPassClosed = true;
  			break;
		case "Ajar":
   			$portRearPass = '<span style="color:red;">Aperto</span>';
    		$portRearPassClosed = false;
    		break;
         							}
         							
	 if ($portDrvClosed == true && $portPassClosed == true && $portRearDrvClosed == true && $portRearPassClosed == true && $cofanoClosed == true && $bagagliaioClosed == true) {
    	$allDoorsClosed = true;
        } else {
        $allDoorsClosed = false;
        
        $doorsNotClosed = array ();
        
        if ($portDrvClosed == false) {
        array_push($doorsNotClosed, "port. ant. sx");
        }
        
        if ($portPassClosed == false) {
        array_push($doorsNotClosed, "port. ant. dx");
        }

        if ($portRearDrvClosed == false) {
        array_push($doorsNotClosed, "port. post. sx");
        }
        
        if ($portRearPassClosed == false) {
        array_push($doorsNotClosed, "port. post. dx");
        }
        
        if ($cofanoClosed == false) {
        array_push($doorsNotClosed, "vano motore");
        }
        
        if ($bagagliaioClosed == false) {
        array_push($doorsNotClosed, "bagagliaio");
        }
        
		if (count($doorsNotClosed) == 1) {
        $openString = "aperta: ";} else {
        $openString = "aperte: ";
        }
     
     	$portAperte = "";
		foreach($doorsNotClosed as $element){
    	$portAperte .= $element.", ";
											}								
		$portAperte = substr_replace($portAperte ,"",-2);
		$statoPortiere .= " (".count($doorsNotClosed)." ".$openString.$portAperte.")";
        
    }
    
    //Stato batteria
    
    $battVolt = $infoPayload->vehiclestatus->battery->batteryStatusActual->value;
    $battVolt = "+/- ".$battVolt.",0 volt";
    $battStatus = $infoPayload->vehiclestatus->battery->batteryHealth->value;
    
   if ($battStatus == "STATUS_GOOD") {
   $battStatus = '<span style="color:darkgreen;">OK</span>';
   } else {
   $battStatus = '<span style="color:red;">Anomalia rilevata</span>';
   }
   
   //  Carburante
   
   $livCarb = $infoPayload->vehiclestatus->fuel->fuelLevel;
   $livCarb = (int)$livCarb;
   if ($livCarb > 100) {
   $livCarb = 100;
   }
   $litersCarb = ($serbatoioLt * $livCarb) / 100;
   $litersCarb = number_format($litersCarb, 1);
   $range = $infoPayload->vehiclestatus->fuel->distanceToEmpty;
   
   //Posizione
	
	$latitude = $infoPayload->vehiclestatus->gps->latitude;
	$longitude = $infoPayload->vehiclestatus->gps->longitude;
	$coordinates = $latitude.",".$longitude;
	$positionType = $infoPayload->vehiclestatus->gps->status;
	
	switch($positionType) {
		case "CURRENT":
		$positionType = "Attuale";
		break;
		case "LAST_KNOWN":
		$positionType = "Ultima rilevata";
		break;
	}
//-------------------------
    //Interfaccia grafica: qui si può personalizzare come meglio si vuole. Il tema utilizzato è in Jquery

?>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/jquery.mobile.flatui.css" />
  <script src="js/jquery.js"></script>
  <script src="js/jquery.mobile-1.4.0-rc.1.js"></script>
</head>
<body>
  <div data-role="page">


    <div data-role="header">
            <h1>FordCheck</h1>
    </div>

    <div data-role="content" role="main">

      
        <div data-role="collapsible" data-collapsed-icon="flat-new" data-expanded-icon="flat-cross">
          <h3>Info generali sul veicolo</h3>
          <p>Numero di telaio: <?php echo $vin; ?></p>
          <p>Marca: <?php echo $make; ?></p>
          <p>Modello: <?php echo $model; ?></p>
          <p>Targa: <?php echo $targa; ?></p>
          <p>Lettura odometro: <?php echo $infoPayload->vehiclestatus->odometer->value; ?> km</p>
          <p>Stato accensione: <?php echo $statoAcc; ?></p>
          
        </div>
        
        <div data-role="collapsible" data-collapsed-icon="flat-lock" data-expanded-icon="flat-cross">
          <h3>Sicurezza del veicolo</h3>
          <table width=100%>
			<tbody>
			<tr>
			Allarme antifurto: <?php echo $alarm; ?><br>
			Stato portiere: <?php echo $statoPortiere; ?><br>
			Stato finestrini: <?php echo $statoFinestrini; ?>
			</tr>
			<tr>
			</tr>
			<tr>
			<td>Vano motore: <?php echo $statoCofano; ?> </td>
			<td>Bagagliaio: <?php echo $statoBagagliaio; ?></td>
			</tr>
			<tr>
			<td>Port. ant. sx: <?php echo $portDrv; ?></td>
			<td>Port. ant. dx: <?php echo $portPass; ?></td>
			</tr>
			<tr>
			<td>Port. post. sx: <?php echo $portRearDrv; ?></td>
			<td>Port. post. dx: <?php echo $portRearPass; ?></td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			</tr>
			<tr>
			<td>Fines. ant. sx: <?php echo $statoFinDrv; ?></td>
			<td>Fines. ant. dx: <?php echo $statoFinPass; ?></td>
			</tr>
			<tr>
			<td>Fines. post. sx: <?php echo $statoFinRearDrv; ?>&nbsp;</td>
			<td>Fines. post. dx: <?php echo $statoFinRearPass; ?>&nbsp;</td>
			</tr>
			</tbody>
		  </table>
        </div>
        
        <div data-role="collapsible" data-collapsed-icon="flat-settings" data-expanded-icon="flat-cross">
          <h3>Parametri tecnici</h3>
          <table style="width: 100%;">
			<tbody>
			<tr>
			<td>Pressione pneumatici: <?php echo $statoGomme; ?> </td>
			</tr>
			<tr>
			<td>Ant. sx (<?php echo $infoPayload->vehiclestatus->TPMS->leftFrontTirePressure->value; ?> mbar)</td>
			<td>Ant. dx (<?php echo $infoPayload->vehiclestatus->TPMS->rightFrontTirePressure->value; ?> mbar)</td>
			</tr>
			<tr>
			<td>Post. sx (<?php echo $infoPayload->vehiclestatus->TPMS->outerLeftRearTirePressure->value; ?> mbar)</td>
			<td>Post. dx (<?php echo $infoPayload->vehiclestatus->TPMS->outerRightRearTirePressure->value; ?> mbar)</td>
			</tbody>
		 </table>
          <p>Vita utile olio: <?php echo $infoPayload->vehiclestatus->oil->oilLifeActual; ?>% (<?php echo $statoOlio; ?>)</p>
          <p>Stato batteria: <?php echo $battStatus." (".$battVolt.")"; ?></p>
        </div>
        
        <div data-role="collapsible" data-collapsed-icon="flat-menu" data-expanded-icon="flat-cross">
          <h3>Carburante</h3>
          <p>Livello carburante: <?php echo $livCarb."% (".$litersCarb." lt)"; ?> </p>
          <p>Autonomia residua: <?php echo $range." km"; ?></p>
        </div>
    
    	<div data-role="collapsible" data-collapsed-icon="flat-location" data-expanded-icon="flat-cross">
    	<h3>Posizione</h3>
    	<p>Tipo di rilevamento posiz.: <?php echo $positionType; ?></p>
    	<p> Coordinate: <br>Latitudine: <?php echo $latitude; ?><br>Longitudine: <?php echo $longitude; ?>
    	<?php $gmapLink = "https://maps.google.com/maps?q=".$coordinates."&t=&z=13&ie=UTF8&iwloc=&output=embed"; ?>
    	<?php echo '<div class="gmap_canvas"><iframe width="100%" id="gmap_canvas" src="'.$gmapLink.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></div>'; ?>
    	</div>
    	
    </div
>
    <div data-role="footer">
      <h1>Ultimo aggiornamento: <?php echo date_format($last_update_timestamp, 'm/d/Y H:i')."<br> ver. 1.0 <br> Developed by Luca d'Addabbo"; ?></h1>
    </div>

  </div>
    </body>
</html>
