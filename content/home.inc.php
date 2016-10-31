<?php
	if (!defined("IN_SYSTEM"))
	{
		header("Location: ../index.php");
		die();
	}

	?>
	<div>
		<p>Welkom <?php echo $_SESSION["username"];?>!</p>
		<?php 
		if ($_SESSION["rechten"] == 1) 
		{
			?>
			<p>Je bent ingelogd als beheerder.</p>
			<?php
		} ?>
		<p>Met het menu aan de linkerkant kun je door het vriendenboek navigeren.</p>
	</div>