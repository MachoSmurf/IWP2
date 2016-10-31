<?php
	if (!defined("IN_SYSTEM"))
	{
		header("Location: ../index.php");
		die();
	}


/**
	*	checks whether the user is logged in through the PHP session
	*
	*	@return boolean returns true or false on the login
	*/
	function checkLogin()
	{
		global $settings;

		$loggedIn = false;
		if (isset($_SESSION["login"]))
		{
			if (($_SESSION["login"] == true) && (($_SESSION["lasttime"] + $settings["timeout"]) >= time()))
			{
				//user is logged in and timeout hasn't passed yet
				$_SESSION["lasttime"] 	= time();
				$loggedIn 				= true;
			}
			else
			{
				//user hasn't logged in or timeout has passed
				if (!$_SESSION["login"]){
					logout();
				}
				else{
					//logout was due to a session timeout. Show this to avoid user confusion
					logout("timeout", true);
				}
			}
		}	
		return $loggedIn;
	}

/**
	*	checks user credentials and sets session vars if ok
	*
	*	@return boolean returns true or false on the login
	*/
	function preformLogin($username, $password)
	{
		$login = false;
		global $settings;
		global $dbConn;

		//fetch the salt for this user from the database
		$query = $dbConn->prepare("SELECT `salt`, `password`, `username`, `user_id`, `rechten` FROM `user` WHERE `username` = ?");
		$query -> bind_param("s", $username);
		$query -> execute();
		$query -> bind_result($salt, $passwordHash, $username, $uID, $rechten);
		$query -> fetch();

		if  (($passwordHash != hash("sha256", $password . $salt)) || ($salt == null))
		{
			return $login;
		}
		else
		{
			//set session variables
			$_SESSION["login"]		=	true;
			$_SESSION["lasttime"]	=	time();
			$_SESSION["username"]	=	$username;
			$_SESSION["uID"]		=	$uID;
			$_SESSION["rechten"]	=	$rechten;
			return true;
		}		
	}

	/**
	*	remove the session data and redirect the user back to the loginpage
	*
	*	@param getVar string (optional) the GET variable that should be passed on the logout redirect
	*
	*	@param val string/bool/int (optional) the value that should be passed on the getVar set in the first param
	*/
	function logout($getVar = NULL, $val = NULL)
	{
		$_SESSION 	=	array();
		if (($getVar != NULL) && ($val != NULL)){
			header("Location: index.php?" . $getVar . "=" . $val);
			}
		else{
			header("Location: index.php");
		}
	}

	/**
	*	fetches page information, sets the page title and calls the correct file
	*/
	function handlePage()
	{
		global $settings;
		global $dbConn;

		$page = "";
		if (isset($_GET["p"]))
		{
			$page = $_GET["p"];
		}

		switch ($page) {
			case 'home':
				outputFramework("Home", "home");
				include './content/home.inc.php';
				break;

			case 'logout':
				logout();
				break;

			case 'auto':
				outputFramework("Automerken", "auto");		
				include './content/automerken.inc.php';
				break;

			case 'vrienden':
				outputFramework("Vrienden", "vrienden");		
				include './content/vrienden.inc.php';
				break;
	
			case 'settings':
				outputFramework("Instellingen", "instellingen");
				include './content/usrSettings.inc.php';
				break;

			case 'koppel':
				outputFramework("Vrienden koppelen aan automerk", "koppel");
				include './content/koppel.inc.php';
				break;

			case 'vriendauto':
				outputFramework("Overzicht van vrienden en hun auto\'s", "vriendauto");
				include './content/vriendauto.inc.php';
				break;

			case 'details':
				outputFramework("Detailoverzicht", "vrienden");
				include './content/details.inc.php';
				break;


			default:
				outputFramework("Home", "home");
				include './content/home.inc.php';
				break;
		}

		closeFramework();
	}

	/**
	 * Outputs the HTML framework with stylesheet info and page title
	 * 
	 * @param string $pageTitle sets the title of the HTML title tag
	 * 
	 * @param string $activepage sets which menu item is set as active
	 */
	function outputFramework($pageTitle, $activePage)
	{
		global $settings;
		$title =	$settings["page_title_prefix"] . $pageTitle;
		include './inc/framework.inc.php';
	}

	/**
	 * Outputs the HTML elements to close the page after the content
	 * 
	 */
	function closeFramework()
	{
		include './inc/frameworkEnd.inc.php';
	}

	/**
	 * generates a salt to be add to the password for extra security against rainbow table attacks
	 * 
	 * @return string of characters
	 *  
	 * */
	function generateSalt()
	{
		global $settings;
		$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $charactersLength = strlen($characters);
	    $randomString = "";
	    for ($i = 0; $i < $settings["salt_lenght"]; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	/**
	 * Reverses HTML sanitation for certain elements
	 * 
	 * @param $input string unsanitzed string 
	 * 
	 * @return string sanitezed string
	 * 
	 */
	function sanitize($dirty)
	{
		//functions written using str_replace are not powerfull enough to easilly filter out both tags AND their attributes. Using HTML purifier was more sensible.
		require_once './htmlpurifier-4.8.0/library/HTMLPurifier.auto.php';
		$config = HTMLPurifier_Config::createDefault();
	    $purifier = new HTMLPurifier($config);
	    $clean_html = $purifier->purify($dirty);
	    return $clean_html;
	}


	//////////////////////////////////////
	/*Page specific functions start here*/
	//////////////////////////////////////

	/**
	 * Function that checks whether the relation between a user and a friend is real, in order to prevent malicious input
	 *
	 * @param $friendID int ID of the friend that should be checked against the logged in user
	 *
	 * @return boolean returns true if the relation is real
	 *
	 */
	function checkFriendRelation($friendID)
	{
		global $dbConn;

		$stmt = $dbConn->prepare("SELECT u.user_id FROM user u, vrienden v WHERE v.user_id=u.user_id AND u.user_id=? AND v.vriend_id=?");
		$stmt->bind_param("ii", $_SESSION["uID"], $friendID);
		$stmt->execute();
		$stmt->bind_result($check);
		$stmt->fetch();
		$stmt->free_result();

		if ($check == 1)
			{return 1;}
		else
			{return 0;}
	}


	/**
	 * Function that checks whether the relation between a user and a friend is real, in order to prevent malicious input
	 *
	 * @param $friendID int ID of the friend that should be checked against the next param (car brand)
	 *
	 * @param $brandID int ID of the car brand that should be checked against the first param
	 *
	 * @return boolean returns true if the link is not made yet
	 *
	 */
	function checkBrandFriend($friendID, $brandID)
	{
		global $dbConn;

		$stmt = $dbConn->prepare("SELECT automerk_id FROM koppelingen WHERE vriend_id=? AND automerk_id=?");
		$stmt->bind_param("ii", $friendID, $brandID);
		$stmt->execute();
		$stmt->bind_result($check);
		$stmt->fetch();
		$stmt->free_result();

		if ($check == $brandID)
			{return 1;}
		else
			{return 0;}
	}

	/**
	 * Function that checks whether the relation between a user and a friend is real, in order to prevent malicious input
	 *
	 * @param $friendID int ID of the friend that should be checked against the next param (car brand)
	 *
	 * @param $brandID int ID of the car brand that should be checked against the first param
	 *
	 * @return boolean returns true if the link is succesfuly made
	 *
	 */
	function createLink($friendID, $brandID)
	{
		global $dbConn;

		$stmt = $dbConn->prepare("INSERT INTO `koppelingen` (`vriend_id`, `automerk_id`) VALUES (?, ?)");
		$stmt->bind_param("ii", $friendID, $brandID);

		if($stmt->execute())
		{
			$stmt->free_result();
			return true;
		}
		else
		{
			return false;
		}
	}


	/**
	 * Mails the admin on registration of new user
	 *
	 * @param $userID int new users ID
	 *
	 * @param $username string new users username
	 *
	 * @param $email string new users email
	 *
	 * @param $voornaam string new users first name
	 *
	 * @param $achternaam string new users achternaam
	 *
	 * @return boolean returns true if the link is succesfuly made
	 *
	 */
	function mailAdmin($userID, $username)
	{
		//send each admin an email with new user information
		global $dbConn;

		$stmt = $dbConn->prepare("SELECT email FROM `user` WHERE `rechten`=1");
		$stmt->execute();
		$result = $stmt->get_result();

		while($row = $result->fetch_assoc())
		{
			$to = $row["email"];
			$subject = "Nieuwe gebruiker vriendenboek";
			$message = "Een nieuwe gebruiker heeft zich aangemeld met de volgende gegevens:\n";
			$message.= "Gebruikers ID: " . $userID . "\n";
			$message.= "Gebruikersnaam: " . $username . "\n";
			$headers = "From: i358895@iris.fhict.nl";
			mail($to, $subject, $message, $headers);
		}
		$result->free_result();
	}



	/**
	 * Function that checks whether the relation between a user and a friend is real, in order to prevent malicious input
	 *
	 * @param $friendID int first part of the link to be deleted
	 *
	 * @param $brandID int second part of the link to be deleted
	 *
	 * @return boolean returns true if the link is succesfuly deleted
	 *
	 */
	function deleteLink($friendID, $brandID)
	{
		global $dbConn;

		$stmt = $dbConn->prepare("DELETE FROM koppelingen WHERE `vriend_id`=? AND `automerk_id`=? ");
		$stmt->bind_param("ii", $friendID, $brandID);
		if ($stmt->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
?>