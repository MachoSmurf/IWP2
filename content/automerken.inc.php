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

	//update brand or add new brand
	if ($a == "input")
	{
		//Check whether the rights are OK
		if ($_SESSION["rechten"]==1)
		{
			$update = false;
			if (isset($_GET["id"]))
				{ 
					$update = true;

					//fetch current data that is to be updated
					$query = $dbConn->prepare("SELECT `automerk`, `plaatje`, `omschrijving` FROM `automerken` WHERE `automerk_id` = ?");
					$query -> bind_param("i", $_GET["id"]);
					$query -> execute();
					$query -> bind_result($automerk, $plaatje, $omschrijving);
					$query -> fetch();
				}
			?>
			<div class="inputContainer">
				<?php if ($update) 
					{
						?>
						<div><span style="float: left; text-decoration: underline; font-weight:bold;">Voeg een nieuwe auto toe:</span></div>
						<?php
					}
					else
					{
						?>
						<div><span style="float: left; text-decoration: underline; font-weight:bold;">Bewerk een automerk:</span></div>
						<?php
					}
					?>
				<form action="?p=auto&action=<?php if ($update) {echo "update&id=" . $_GET["id"]; } else { echo "voegtoe";} ?>" method="post">
				<div>
					<div class="inputLeft">Merknaam:</div>
					<div class="inputRight"><input type="text" name="merknaam" value="<?php if ($update) {echo $automerk;} ?>" ></div>
				</div>
				<div>
					<div class="inputLeft">Plaatje URL:</div>
					<div class="inputRight"><input type="text" name="URL" value="<?php if ($update) {echo $plaatje;} ?>"></div>
				</div>
				<div>
					<div class="inputLeft">Omschrijving:</div>
					<div class="inputRight"><textarea name="omschrijving" id="editor1"><?php if ($update) {echo $omschrijving;} ?></textarea>
					<script>
	                // Replace the <textarea id="editor1"> with a CKEditor
	                // instance, using default configuration.
	                CKEDITOR.replace( 'editor1' );
	            	</script></div>
				</div>
				<div>
					<div class="inputLeft">
						<input type="submit" value="<?php if ($update) {echo "Werk Bij"; } else { echo "Voeg Toe";} ?>" name="submit">
					</div>
				</div>
				</form>
			</div>
			<?php
		}
	}

	//do the adding in the database (process the filled out form)
	elseif ($a == "voegtoe")
	{
		//do some input validation and input data into the database
		//Check whether the rights are OK
		if ($_SESSION["rechten"]==1)
		{
			if ((isset($_POST["merknaam"])) && (isset($_POST["URL"])) && (isset($_POST["omschrijving"])))
			{
				$merknaam		=	sanitize($_POST["merknaam"]);
				$URL			=	sanitize($_POST["URL"]);
				$omschrijving	=	sanitize($_POST["omschrijving"]);

				//check if the brand is allready in the database
				$checkQuery = $dbConn->prepare("SELECT `automerk` FROM `automerken` WHERE `automerk` = ?");
				$checkQuery -> bind_param("s", $merknaam);
				$checkQuery -> execute();
				$checkQuery -> bind_result($merkCheck);
				$checkQuery -> fetch();

				$exists = false;
				if  (($merkCheck != null))
				{
					$exists = true;
				}

				if (!$exists)
				{
					$query 	=	$dbConn->prepare("INSERT INTO `automerken` (`automerk`, `plaatje`, `omschrijving`) VALUES (?, ?, ?)");
					$query->bind_param("sss", $merknaam, $URL, $omschrijving);

					if ($query->execute())
					{
						?>
						<div>Het automerk <?php echo $merknaam; ?> is succesvol toegevoegd!</div>
						<?php
					}
					else
					{
						echo "Oeps, er ging iets fout bij het toevoegen van het nieuwe automerk. Probeer het nog eens.";
					}
				}
				else
				{
					?>
					<div>Het automerk <?php echo $merknaam; ?> is al beschikbaar in de database.</div>
					<?php
				}
			}
		}
	}

	//do the update in the database
	elseif ($a == "update")
	{
		//Check whether the rights are OK
		if ($_SESSION["rechten"]==1)
		{
			if ((isset($_POST["merknaam"])) && (isset($_POST["URL"])) && (isset($_POST["omschrijving"])))
			{
				//sanitize input
				$merknaam		=	sanitize($_POST["merknaam"]);
				$URL			=	sanitize($_POST["URL"]);
				$omschrijving	=	sanitize($_POST["omschrijving"]);
				
				$query 	=	$dbConn->prepare("UPDATE `automerken` SET `automerk`=?, `plaatje`=?, `omschrijving`=? WHERE `automerk_id`=?");
				$query->bind_param("sssi", $merknaam, $URL, $omschrijving, $_GET["id"]);

				if ($query->execute())
				{
					?>
					<div class="succes">Het automerk <?php echo $merknaam; ?> is succesvol bijgewerkt!</div>
					<?php
				}
				else
				{
					?><div class="error">Oeps, er ging iets fout bij het bijwerken van het automerk. Probeer het nog eens.</div><?php
				}
			}
		}
	}

	//delete brand from database
	elseif ($a == "delete") 
	{
		//Check whether the rights are OK
		if ($_SESSION["rechten"]==1)
		{
			if (isset($_GET["id"]))
			{
				$query =	$dbConn->prepare("DELETE FROM `automerken` WHERE `automerk_id`=?");
				$query->bind_param("i", $_GET["id"]);

				if ($query->execute())
				{
					?>
					<div class="error">Automerk succesvol verwijderd.</div>
					<?php
				}
				else
				{
					?>
					<div class="error">Er ging iets mis bij het verwijderen van het automerk. Probeer het nog eens.</div>
					<?php
				}
			}
			else
			{
				?>
				<div class="error">Geen automerk gespecificeerd om te verwijderen</div>
				<?php
			}
		}	
	}

	elseif ($a == null)
	{
		//no action specified, show the car brand overview
		if ($_SESSION["rechten"]==1)
		{
		?>
		<div><form action="index.php?p=auto&action=input" method="post"><input type="submit" value="Voeg automerk toe"></form></div>
		<?php } ?>
		<div>
			<!--alle huidige aanwezige automerken-->
			<table class="friendsTable">
				<tr>
					<th>ID</th>
					<th>Automerk</th>
					<th>Plaatje</th>
					<th>Omschrijving</th>
					<?php 
						//check if user can edit and delete
						if ($_SESSION["rechten"] == 1)
						{
							echo "<th>Bewerken</th>";
						}
					?>
				</tr>
				<?php
					//fetch all	brands currently in the database
					$result	=	$dbConn->query("SELECT * FROM `automerken`");
					if ($result->num_rows > 0)
					{
						while ($row =	$result->fetch_assoc())
						{
							?>
							<tr>
								<td><?php echo $row["automerk_id"] ?></td>
								<td><?php echo $row["automerk"] ?></td>
								<td><img src="<?php echo $row["plaatje"]; ?>" class="automerkPlaatjeKlein"/></td>
								<td><?php echo $row["omschrijving"] ?></td>
								<?php

								if ($_SESSION["rechten"] == 1)
									{
										?>
										<td><a href="?p=auto&action=input&id=<?php echo $row["automerk_id"]; ?>"><img src="./img/b_edit.png"></a>
										<a href="?p=auto&action=delete&id=<?php echo $row["automerk_id"]; ?>" onclick="return confirm('Weet je zeker dat je dit automerk wilt verwijderen?');"><img src="./img/b_drop.png"></a></td>
										<?php
									}
								?>
							</tr>
							<?php
						}
					}
				?>
			</table>
		</div>
		<?php
	}	
?>

