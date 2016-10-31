<?php
	ini_set("SMTP", "mailrelay.fhict.local");

	$to = "raymond.jetten@student.fontys.nl";
	$subject = "Nieuwe gebruiker vriendenboek";
	$message = "Een nieuwe gebruiker heeft zich aangemeld met de volgende gegevens:\n";
	$message.= "Gebruikers ID: " . $userID . "\n";
	$message.= "Gebruikersnaam: " . $username . "\n";
	$headers = "From: i358895@iris.fhict.nl";
	
	echo mail($to, $subject, $message, $headers);
?>