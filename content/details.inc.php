<?php
	if (!defined("IN_SYSTEM"))
	{
		header("Location: ../index.php");
		die();
	}

	//check relation
	if (checkFriendRelation($_GET["id"]))
	{
		$vriend_id = sanitize($_GET["id"]);
		settype($vriend_id, "integer");

		$stmt = $dbConn->prepare("SELECT voornaam, achternaam, adres, woonplaats, mobiel, beschrijving FROM vrienden WHERE `vriend_id`=? ");
		$stmt->bind_param("i", $vriend_id);
		$stmt->execute();
		$stmt->bind_result($voornaam, $achternaam, $adres, $woonplaats, $mobiel, $beschrijving);
		$stmt->fetch();
		$stmt->free_result();

		?>
			<div class="detailsContainer">
				<span class="detailsLeft">Voornaam:</span><span class="detailsRight"><?php echo $voornaam; ?></span><br>
				<span class="detailsLeft">Achternaam:</span><span class="detailsRight"><?php echo $achternaam; ?></span><br>
				<span class="detailsLeft">adres:</span><span class="detailsRight"><?php echo $adres; ?></span><br>
				<span class="detailsLeft">Woonplaats:</span><span class="detailsRight"><?php echo $woonplaats; ?></span><br>
				<span class="detailsLeft">Mobiel:</span><span class="detailsRight"><?php echo $mobiel; ?></span><br>
				<span class="detailsLeft">Beschrijving:</span><span class="detailsLeft" style="clear: both; width: 100%;"><?php echo $beschrijving; ?></span>
			</div>
		<?php

		//fetch all info regarding this friend:		
		$stmt = $dbConn->prepare("SELECT a.automerk, a.plaatje, a.omschrijving, a.automerk_id FROM vrienden v, koppelingen k, automerken a WHERE v.vriend_id=? AND v.vriend_id=k.vriend_id AND a.automerk_id=k.automerk_id");	
		$stmt->bind_param("i", $vriend_id);
		$stmt->execute();
		$result = $stmt->get_result();

		while($row =	$result->fetch_assoc())
		{			
			?>
			<div class="carBox">
				<span class="detailsLeft">Auto Merk: <?php echo $row["automerk"]; ?></span><br>
				<span class="detailsLeft">
					<a href="?p=koppel&action=delete&brandID=<?php echo $row["automerk_id"]; ?>&friendID=<?php echo $vriend_id; ?>" onclick="return confirm('Weet je zeker dat dit automerk bij je vriend wilt verwijderen?');"><img src="./img/b_drop.png"></a>
				</span>
				<span class="loginBoxInput">
					<img src="<?php echo $row["plaatje"]; ?>" class="plaatjeOverzicht" title="<?php echo $row["omschrijving"]; ?>">
				</span>
			</div>
			<?
		}		
	}
?>