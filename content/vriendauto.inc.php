<?php
	if (!defined("IN_SYSTEM"))
	{
		header("Location: ../index.php");
		die();
	}

	if (isset($_GET["groot"]))
	{
		$automerk_id = sanitize($_GET["groot"]);
		settype($automerk_id, "integer");
		//show the car in a large image (opdr13)
		$stmt = $dbConn->prepare("SELECT plaatje, omschrijving FROM automerken WHERE automerk_id=?");
		$stmt->bind_param("i", $automerk_id);
		$stmt->execute();
		$stmt->bind_result($plaatje, $omschrijving);
		$stmt->fetch();
		$stmt->free_result();

		?>
		<img src="<?php echo $plaatje;?>" title="<?php echo $omschrijving;?>" alt="<?php echo $omschrijving;?>" class="plaatjeGroot">
		<?
	}
	else
	{

		$stmt = $dbConn->prepare("SELECT v.voornaam, v.achternaam, v.beschrijving, a.automerk, a.plaatje, a.automerk_id FROM vrienden v, koppelingen k, automerken a, user u WHERE u.user_id=? AND u.user_id=v.user_id AND v.vriend_id=k.vriend_id AND a.automerk_id=k.automerk_id");
		$stmt->bind_param("i", $_SESSION["uID"]);
		$stmt->execute();
		$result = $stmt->get_result();

		?>
		<table class="friendsTable">
			<tr>
				<th>Vriend</th>
				<th>Omschrijving</th>
				<th>Automerk</th>
				<th></th>
			</tr>		
			<?php
			while($row =	$result->fetch_assoc())
			{
				?>
				<tr>
					<td><?php echo $row["voornaam"] . " " . $row["achternaam"]; ?></td>
					<td><?php echo $row["beschrijving"]; ?></td>
					<td><?php echo $row["automerk"]; ?></td>
					<td><a href="?p=vriendauto&groot=<? echo $row["automerk_id"]; ?>" ><img src="<?php echo $row["plaatje"]; ?>" class="plaatjeKlein"></a></td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
	}
?>