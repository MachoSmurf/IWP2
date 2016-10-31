<?php
	if (!defined("IN_SYSTEM"))
	{
		header("Location: ../index.php");
		die();
	}

	$showform	=	true;

	//check if a link should be deleted
	if (isset($_GET["action"]))
	{
		if ($_GET["action"]=="delete")
		{
			$deleteBrandID = sanitize($_GET["brandID"]);
			settype($deleteID, "integer");

			$deleteFriendID = sanitize($_GET["friendID"]);
			settype($friendID, "integer");

			//check rights
			if (checkFriendRelation($friendID) || $_SESSION["rechten"]==1)
			{
				if(deleteLink($deleteFriendID, $deleteBrandID))
				{
					?>
					<div class="succes">Het automerk is succesvol losgekoppeld van je vriend.</div>
					<?php
				}
				else
				{
					?>
					<div class="error">Er is een fout opgetreden bij het verbreken van de koppeling.</div>
					<?php
				}
			}
			$showform	=	false;
		}
	}

	//Form was send, process
	if ( (isset($_POST["vriend"])) && (isset($_POST["automerk"])) && (isset($_POST["submit"])))
	{
		//LET OP: alleen invoegen wanneer de entry:
		//A) nog niet bestaat
		//B) de toe te voegen vriend ook echt vriend is van deze gebruiker en niet van een andere gebruiker (URL hacken)
		
		//sanitize and force integer
		$vriend_id = sanitize($_POST["vriend"]);
		settype($vriend_id, "integer");
		$automerk = sanitize($_POST["automerk"]);
		settype($automerk, "integer");

		//check if this link doens't exist allready and the friend is not just a random friend, but a friend of this user. 

		if ((checkFriendRelation($vriend_id)==1) && (checkBrandFriend($vriend_id, $automerk)==0))
		{
			//we can link this friend of the logged on user to a brand
			if(createLink($vriend_id, $automerk))
			{
				?>
				<div class="succes">Het automerk is succesvol aan je vriend gekoppeld.</div>
				<?php
			}
			else
			{
				?>
				<div class="error">Er is een fout opgetreden bij het maken van de koppeling.</div>
				<?php
			}
		}
		else
		{
			?><div class="error">Deze koppeling kan niet gemaakt worden (controleer of de koppeling al bestaat).</div><?php
		}
		$showform	=	false;
	}
	

	if ($showform)
	{
	?>
		<div>
			Koppel hier een van je vrienden aan een of meerdere automerken.
		</div>
		<div>
		<form action="?p=koppel&action=voegtoe" method="post">
			<table class="friendsTable" style="border: solid 1px; width: 30%;">
				<tr>
					<th>Vriend</th>
					<th></th>
					<th>Automerk</th>
				</tr>
				<tr>
					<td><select name="vriend">
					<?php
						$stmt = $dbConn->prepare("SELECT vriend_id, voornaam, achternaam FROM `vrienden` WHERE `user_id`=?");
						$stmt->bind_param("i", $_SESSION["uID"]);
						$stmt->execute();
						$result = $stmt->get_result();

						while($row = $result->fetch_assoc())
						{
							?>
							<option value="<?php echo $row["vriend_id"]; ?>"><?php echo $row["voornaam"] . " " . $row["achternaam"]; ?></option>
							<?
						}
						$result->free_result();
					?>
					</select>
					</td>
					<td>
						<input type="submit" value="Koppel" name="submit">
					</td>
					<td>
						<select name="automerk">
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
						$result->free_result();
					?>
					</select>
					</td>
				</tr>
			</table>
		</form>
		</div>
	<?php
	}
?>