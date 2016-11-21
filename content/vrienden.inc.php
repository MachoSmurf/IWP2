<?
	if (!defined("IN_SYSTEM"))
	{
		header("Location: ../index.php");
		die();
	}


	$a = null;
	if (isset($_GET["action"]))
	{
		$a = $_GET["action"];
	}
	
	if ($a == "input")
	{
		$update = false;
		if (isset($_GET["id"]))
			{ 
				$update = true;

				//fetch current data that is to be updated
				$query = $dbConn->prepare("SELECT * FROM `vrienden` WHERE `vriend_id` = ?");
				$query -> bind_param("i", $_GET["id"]);
				$query -> execute();
				$query -> bind_result($vriend_id, $uID, $voornaam, $achternaam, $adres, $woonplaats, $mobiel, $beschrijving);
				$query -> fetch();
			}
		?>
		<div class="inputContainer">
		<?php 
			if (!$update) 
				{ 
					?> 
			<div><span style="float: left; text-decoration: underline; font-weight:bold;">Voeg een nieuwe vriend toe:</span></div>
					<?php
				}
				else
				{ 
					?> 
			<div><span style="float: left; text-decoration: underline; font-weight:bold;">Vriend bijwerken:</span></div>
					<?php
				}?>
			<form action="?p=vrienden&action=<?php if ($update) {echo "update&id=" . $_GET["id"]; } else { echo "voegtoe";} ?>" method="post">
				<div>
					<div class="inputLeft">Voornaam:</div>
					<div class="inputRight"><input type="text" name="voornaam" value="<?php if ($update) {echo $voornaam;} ?>" ></div>
				</div>
				<div>
					<div class="inputLeft">Achternaam:</div>
					<div class="inputRight"><input type="text" name="achternaam" value="<?php if ($update) {echo $achternaam;} ?>"></div>
				</div>
				<div>
					<div class="inputLeft">Adres: </div>
					<div class="inputRight"><input type="text" name="adres" value="<?php if ($update) {echo $adres;} ?>"></div>
				</div>
				<div>
					<div class="inputLeft">Woonplaats: </div>
					<div class="inputRight"><input type="text" name="woonplaats" value="<?php if ($update) {echo $woonplaats;} ?>"></div>
				</div>
				<div>
					<div class="inputLeft">Mobiel:</div>
					<div class="inputRight"><input type="text" name="mobiel" value="<?php if ($update) {echo $mobiel;} ?>"></div>
				</div>
				<?php 
				if (!$update) 
					{ 
						?> 
				<div>
					<div class="inputLeft">	Voeg een of meerdere automerken toe:</div> <?php 
					//show the list of car brands so the user can immediatly add them
					?>
					<div class="inputRight">
						<select name="automerk[]" multiple>
							<?php
							//fetch only brands that are not yet connected to this friend
							$stmt = $dbConn->prepare("SELECT automerk_id, automerk FROM `automerken`");
							$stmt->execute();
							$result = $stmt->get_result();

							while($row = $result->fetch_assoc())
							{
								?>
								<option value="<?php echo $row["automerk_id"]; ?>"><?php echo $row["automerk"]; ?></option>
								<?
							}
							$result->free();
							?>
						</select>
					</div>
				</div>
						<?php
					}
			?>
		</div>
		
		<div class="cmsContainer">
			<span style="font-weight: bold;">Beschrijving: </span><textarea name="beschrijving" id="editor1"><?php if ($update) {echo $beschrijving;} ?></textarea>
				<script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'editor1' );
            	</script>
			</div>
			<div>
				<input style="margin-top: 15px;" type="submit" value="<?php if ($update) {echo "Werk Bij"; } else { echo "Voeg Toe";} ?>" name="submit">
			</div>
			</form>
		</div>
		<?php 
	}

	elseif ($a == "voegtoe")
	{
		//do some input validation and input data into the database
		if ((isset($_POST["voornaam"])) && (isset($_POST["achternaam"])) && (isset($_POST["adres"])) && (isset($_POST["woonplaats"])) && (isset($_POST["mobiel"])) && (isset($_POST["beschrijving"])))
		{
			$voornaam		=	sanitize($_POST["voornaam"]);
			$achternaam		=	sanitize($_POST["achternaam"]);
			$adres			=	sanitize($_POST["adres"]);
			$woonplaats		=	sanitize($_POST["woonplaats"]);
			$mobiel			=	sanitize($_POST["mobiel"]);
			$beschrijving	=	sanitize($_POST["beschrijving"]);

			//check if the friend is allready in the database
			$checkQuery = $dbConn->prepare("SELECT `voornaam` FROM `vrienden` WHERE `user_id` = ? AND `voornaam`= ? AND `achternaam`=?");
			$checkQuery -> bind_param("iss", $_SESSION["uID"], $voornaam, $achternaam);
			$checkQuery -> execute();
			$checkQuery -> bind_result($vriendCheck);
			$checkQuery -> fetch();

			$exists = false;
			if  (($vriendCheck != null))
			{
				$exists = true;
			}

			if (!$exists)
			{
				$query 	=	$dbConn->prepare("INSERT INTO `vrienden` (`user_id`, `voornaam`, `achternaam`, `adres`, `woonplaats`, `mobiel`, `beschrijving`) VALUES (?, ?, ?, ?, ?, ?, ?)");
				$query->bind_param("issssss", $_SESSION["uID"], $voornaam, $achternaam, $adres, $woonplaats, $mobiel, $beschrijving);

				if ($query->execute())
				{
					//friend succesfully added, see if we need to link cars
					if (isset($_POST["automerk"]))
					{
						$friend_id = $query->insert_id;
						foreach($_POST["automerk"] as $merk)
						{
							settype($merk, "integer");
							createLink($friend_id, $merk);
						}
					}
					?>
					<div class="succes"><?php echo $voornaam; ?> is succesvol toegevoegd aan je vrienden!</div>
					<?php
				}
				else
				{
					?> <div class="error">Er ging iets fout bij het toevoegen van je nieuwe vriend. Probeer het nog eens.</div><?php
				}
			}
			else
			{
				?>
				<div class="error"><?php echo $voornaam; ?> is al een van je vrienden.</div>
				<?php
			}
		}
	}

	elseif ($a == "update")
	{
		if ((isset($_POST["voornaam"])) && (isset($_POST["achternaam"])) && (isset($_POST["adres"])) && (isset($_POST["woonplaats"])) && (isset($_POST["mobiel"])) && (isset($_POST["beschrijving"])))
		{
			$voornaam		=	sanitize($_POST["voornaam"]);
			$achternaam		=	sanitize($_POST["achternaam"]);
			$adres			=	sanitize($_POST["adres"]);
			$woonplaats		=	sanitize($_POST["woonplaats"]);
			$mobiel			=	sanitize($_POST["mobiel"]);
			$beschrijving	=	sanitize($_POST["beschrijving"]);
			
			$query 	=	$dbConn->prepare("UPDATE `vrienden` SET `voornaam`=?, `achternaam`=?, `adres`=?, `woonplaats`=?, `mobiel`=?, `beschrijving`=? WHERE `user_id`=? AND `vriend_id`=?");
			$query->bind_param("ssssssii", $voornaam, $achternaam, $adres, $woonplaats, $mobiel, $beschrijving, $_SESSION["uID"], $_GET["id"]);

			if ($query->execute())
			{
				?>
				<div class="succes"><?php echo $voornaam; ?> is succesvol bijgewerkt!</div>
				<?php
			}
			else
			{
				?><div class="error">Oeps, er ging iets fout bij het bijwerken van het je vriend. Probeer het nog eens.</div><?php
			}
		}
	}

	elseif ($a == "delete") 
	{
		if (isset($_GET["id"]))
		{
			$query =	$dbConn->prepare("DELETE FROM `vrienden` WHERE `vriend_id`=?");
			$query->bind_param("i", $_GET["id"]);

			if ($query->execute())
			{
				?>
				<div class="error">Vriend succesvol verwijderd.</div>
				<?php
			}
			else
			{
				?>
				<div class="error">Er ging iets mis bij het verwijderen van de vriend. Probeer het nog eens.</div>
				<?php
			}
		}
		else
		{
			?>
			<div class="error">Geen vriend gespecificeerd om te verwijderen</div>
			<?php
		}
	}

	elseif ($a == null)
	{
		//no action specified, show the friends overview
		?>
		<div><form action="index.php?p=vrienden&action=input" method="post"><input type="submit" value="Voeg vriend toe" class="button"></form></div>
		<div>
			<!--alle huidige aanwezige vrienden-->
			<table class="friendsTable">
				<tr>
					<th>Vriend ID</th>
					<th>Voornaam</th>
					<th>Achternaam</th>
					<th>Adres</th>
					<th>Woonplaats</th>
					<th>Mobiel</th>
					<th>Beschrijving</th>
					<th>Bewerken</th>
				</tr>
				<?php
					//fetch all	friends currently in the database
					$stmt =	$dbConn->prepare("SELECT * FROM `vrienden` WHERE `user_id`=? ORDER BY achternaam ASC");	
					$stmt->bind_param("i", $_SESSION["uID"]);
					$stmt->execute();
					$result = $stmt->get_result();

					while ($row =	$result->fetch_assoc())
					{
						?>
						<tr>
							<td><?php echo $row["vriend_id"] ?></td>
							<td><a href="?p=details&id=<?php echo $row["vriend_id"] ?>"><?php echo $row["voornaam"] ?></a></td>
							<td><a href="?p=details&id=<?php echo $row["vriend_id"] ?>"><?php echo $row["achternaam"] ?></a></td>
							<td><?php echo $row["adres"]; ?></td>
							<td><?php echo $row["woonplaats"]; ?></td>
							<td><?php echo $row["mobiel"]; ?></td>
							<td><?php echo $row["beschrijving"]; ?></td>
							<td><a href="?p=vrienden&action=input&id=<?php echo $row["vriend_id"]; ?>"><img src="./img/b_edit.png"></a>
							<a href="?p=vrienden&action=delete&id=<?php echo $row["vriend_id"]; ?>" onclick="return confirm('Weet je zeker dat je deze vriend wilt verwijderen?');"><img src="./img/b_drop.png"></a></td></tr>
						<?php
					}
				?>
			</table>
		</div>
		<?php
	}	
?>

