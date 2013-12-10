<?php
session_start();
ini_set("arg_separator.output", "&amp;");
ini_set("url_rewriter.tags", "a=href,area=href,frame=src,input=src");
session_name("jlaramee_assignment7.1");

/* the purpose of this page is to display a form to allow a person to either
 * add a new record if not pk was passed in or to update a record if a pk was
 * passed in.
 * 
 * notice i have more than one submit button on the form and i need to make
 * sure they have different names
 * 
 * Written By: Robert Erickson robert.erickson@uvm.edu
 * Updated By: Jacob Laramee
 * Last updated on: November 5, 2013
 * 
 * 
 -- --------------------------------------------------------

    --
    -- Table structure for table `tblPoet`
    --

    CREATE TABLE IF NOT EXISTS `tblPoet` (
      `pkPoetId` int(11) NOT NULL AUTO_INCREMENT,
      `fldFname` varchar(20) DEFAULT NULL,
      `fldLastName` varchar(20) DEFAULT NULL,
      `fldBirthDate` date DEFAULT NULL,
      PRIMARY KEY (`pkPoetId`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

 */


//-----------------------------------------------------------------------------
// 
// Initialize variables
//  

$debug = false;
if (isset($_GET["debug"])) {
    $debug = true;
}

include("connect.php");

$baseURL = "http://www.uvm.edu/~jlaramee/";
$folderPath = "cs148/assignment7.1/";

// full URL of this form
$yourURL = $baseURL . $folderPath . "updateContact.php";

$fromPage = getenv("http_referer");

if ($debug) {
    print "<p>From: " . $fromPage . " should match ";
    print "<p>Your: " . $yourURL;
}
$_SESSION["recEmail"];
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// initialize my form variables either to what is in table or the default 
// values.
// display record to update
if (isset($_POST["lstEmails"])) {
    

    // you may want to add another security check to make sure the person
    // is allowed to delete records.
    
    $id = htmlentities($_POST["lstEmails"], ENT_QUOTES);
		$_SESSION["recEmail"] = $id;

    $sql = "SELECT pkEmail, fldFirstName, fldLastName, fldTelephone, fldDateJoined ";
    $sql .= "FROM tblContactInfo ";
    $sql .= 'WHERE pkEmail="' . $id . '"';

    if ($debug)
        print "<p>sql " . $sql;

    $stmt = $db->prepare($sql);

    $stmt->execute();
		
		$contacts = $stmt->fetchAll();
    if ($debug) {
        print "<pre>";
        print_r($contacts);
        print "</pre>";
    }
		
		foreach ($contacts as $contact) {
        $firstName = $contact["fldFirstName"];
        $lastName = $contact["fldLastName"];
        $email = $contact["pkEmail"];
				$phoneNum = $contact["fldTelephone"];
				$dateJoined = $contact["fldDateJoined"];
		}
		
		$sql2 = "SELECT tblAddress.pkAddressID, tblAddress.fldAddress, ";
		$sql2 .= "tblAddress.fldCity, tblAddress.fldState, tblAddress.fldZip ";
    $sql2 .= "FROM tblAddress, tblContactInfoAddress, tblContactInfo ";
    $sql2 .= 'WHERE tblContactInfo.pkEmail="' . $id . '" AND ';
		$sql2 .= 'tblContactInfo.pkEmail=tblContactInfoAddress.fkEmail AND ';
		$sql2 .= 'tblAddress.pkAddressID=tblContactInfoAddress.fkAddressID';
		
		//if ($debug)
			 print "<p>sql2 " . $sql2;
			 
		$stmt2 = $db->prepare($sql2);
		
		$stmt2->execute();
		
		$locations = $stmt2->fetchAll();
		if ($debug) {
			 print "<pre>";
			 print_r($locations);
			 print "</pre>";
		}
		
		foreach ($locations as $location) {
        $addressID = $location["pkAddressID"];
        $address = $location["fldAddress"];
        $city = $location["fldCity"];
				$state = $location["fldState"];
				$zip = $location["fldZip"];
		}
		
		$sql3 = "SELECT fkEmail, fldMessage FROM tblMessage WHERE ";
		$sql3 .= 'fkEmail="' . $id . '"';
		
		$stmt3 = $db->prepare($sql3);
		$stmt3->execute();
		
		$messages = $stmt3->fetchAll();
		if ($debug){
			 print "<pre>";
			 print_r($messages);
			 print "</pre>";
		}
		
		foreach ($messages as $message) {
						$comments = $message["fldMessage"];			
		}
		
		$_SESSION["recAddressID"] = $addressID;

} else{
			  $firstName = "";
        $lastName = "";
        $email = "";
				$phoneNum = "";
				$dateJoined = "";
				$addressID = "";
				$address = "";
				$city = "";
				$state = "";
				$zip = "";
				$comments = "";
}// end isset lstEmails

//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// simple deleting record. 
if (isset($_POST["cmdDelete"])) {
//-----------------------------------------------------------------------------
// 
// Checking to see if the form's been submitted. if not we just skip this whole 
// section and display the form
// 
//#############################################################################
// minor security check
    if ($fromPage != $yourURL) {
        die("<p>Sorry you cannot access this page. Security breach detected and reported.</p>");
    }

    // you may want to add another security check to make sure the person
    // is allowed to delete records.
		$delId = htmlentities($_POST["lstEmails"], ENT_QUOTES);
		$delId2 = $_SESSION["recAddressID"];
    // I may need to do a select to see if there are any related records.
    // and determine my processing steps before i try to code.

    $sql = "DELETE ";
    $sql .= "FROM tblContactInfo ";
    $sql .= 'WHERE pkEmail="' . $delId . '"';

    print "<p>sql " . $sql;

    $stmt = $db->prepare($sql);

    $DeleteData = $stmt->execute();
		
		echo 'fkaddressid: "' . $delId2 . '"';
		
    $sql2 = "DELETE ";
    $sql2 .= "FROM tblAddress ";
    $sql2 .= 'WHERE pkAddressID="' . $delId2 . '"';
		
		print "<p>sql2 " . $sql2;
		
		$stmt2 = $db->prepare($sql2);
		$stmt2->execute();
		
		$sql3 = "DELETE ";
    $sql3 .= "FROM tblContactInfoAddress ";
    $sql3 .= 'WHERE fkAddressID="' . $delId2 . '" ';
		$sql3 .= 'AND fkEmail="' . $delId . '"';
		
		print "<p>sql3 " . $sql3;
		
		$stmt3 = $db->prepare($sql3);
		$stmt3->execute();
		
		$sql4 = 'DELETE FROM tblMessage WHERE fkEmail="' . $delId . '"';
		
		print "<p>sql4 " . $sql4;
		
		$stmt4 = $db->prepare($sql4);
		$stmt4->execute();
		
    // at this point you may or may not want to redisplay the form
    if($DeleteData){
        exit();
    }
}

//-----------------------------------------------------------------------------
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// if form has been submitted, validate the information both add and update
if (isset($_POST["btnSubmitted"])) {
    if ($fromPage != $yourURL) {
        die("<p>Sorry you cannot access this page. Security breach detected and reported.</p>");
    }
    
    // initialize my variables to the forms posting	
    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES);
    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES);
		$email = htmlentities($_POST["txtEmail"], ENT_QUOTES);
		$phoneNum = htmlentities($_POST["txtPhone"], ENT_QUOTES);
    $address = htmlentities($_POST["txtAddress"], ENT_QUOTES, "UTF-8");
		$city = htmlentities($_POST["txtCity"], ENT_QUOTES, "UTF-8");
		$state = htmlentities($_POST["txtState"], ENT_QUOTES, "UTF-8");
		$zip = htmlentities($_POST["txtZip"], ENT_QUOTES, "UTF-8");
		$comments = htmlentities($_POST["txtComments"], ENT_QUOTES, "UTF-8");

    
    // Error checking forms input
    include ("validation_functions.php");

    $errorMsg = array();

    //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    // begin testing each form element 
    if ($firstName == "") {
        $errorMsg[] = "Please enter your First Name";
    } else {
        $valid = verifyAlphaNum($firstName); /* test for non-valid  data */
        if (!$valid) {
            $error_msg[] = "First Name must be letters and numbers, spaces, dashes and ' only.";
        }
    }

    if ($lastName == "") {
        $errorMsg[] = "Please enter your Last Name";
    } else {
        $valid = verifyAlphaNum($lastName); /* test for non-valid  data */
        if (!$valid) {
            $error_msg[] = "Last Name must be letters and numbers, spaces, dashes and ' only.";
        }
    }

		if ($email == "") {
        $errorMsg[] = "Please enter your Email Address";
    } else {
        $valid = verifyEmail($lastName); /* test for non-valid  data */
        if (!$valid) {
            $error_msg[] = "Email must be in the correct format.";
        }
    }
		
		if ($phoneNum == "") {
        $errorMsg[] = "Please enter your Phone Number";
    } else {
        $valid = verifyText($phoneNum); /* test for non-valid  data */
        if (!$valid) {
            $error_msg[] = "Phone Number must be in the right format.";
        }
    }
		
		if ($address == "") {
        $errorMsg[] = "Please enter your Address";
    } else {
        $valid = verifyText($address); /* test for non-valid  data */
        if (!$valid) {
            $errorMsg[] = "I'm sorry, the Address you entered is not valid.";
        }
   }
		
		if ($city == "") {
        $errorMsg[] = "Please enter your City";
    } else {
        $valid = verifyText($city); /* test for non-valid  data */
        if (!$valid) {
            $errorMsg[] = "I'm sorry, the City you entered is not valid.";
        }
    }
		
		if ($state == "") {
        $errorMsg[] = "Please enter your State";
    } else {
        $valid = verifyText($state); /* test for non-valid  data */
        if (!$valid) {
            $errorMsg[] = "I'm sorry, the State you entered is not valid.";
        }
    }
		
		if ($zip == "") {
        $errorMsg[] = "Please enter your Zip Code";
    } else {
        $valid = verifyPhone($zip); /* test for non-valid  data */
        if (!$valid) {
            $errorMsg[] = "I'm sorry, the Zip Code you entered is not valid.";
        }
    }
		
    //- end testing ---------------------------------------------------
    
    //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    // there are no input errors so form is valid now we need to save 
    // the information checking to see if it is an update or insert
    // query based on the hidden html input for id
    if (!$errorMsg) {
        
        if ($debug)
            echo "<p>Form is valid</p>";
						
				$primaryKey = "";
								
        if ($_SESSION["recEmail"] != "") { // update record
            $sql = "UPDATE ";
            $sql .= "tblContactInfo, tblAddress, tblContactInfoAddress, tblMessage SET ";
            $sql .= "tblContactInfo.fldFirstName='$firstName', ";
            $sql .= "tblContactInfo.fldLastName='$lastName', ";
						$sql .= "tblContactInfo.fldTelephone='$phoneNum', ";
            $sql .= "tblAddress.fldAddress='$address', ";
            $sql .= "tblAddress.fldCity='$city', ";
						$sql .= "tblAddress.fldState='$state',";
						$sql .= "tblAddress.fldZip='$zip', ";
						$sql .= "tblMessage.fldMessage='$comments' ";
            $sql .= 'WHERE pkEmail="' . $_SESSION["recEmail"] . '" AND ';
						$sql .= 'tblContactInfo.pkEmail=tblContactInfoAddress.fkEmail AND ';
						$sql .= 'tblAddress.pkAddressID=tblContactInfoAddress.fkAddressID ';
						$sql .= 'AND tblMessage.fkEmail=tblContactInfo.pkEmail';
						
						echo 'sql: "' . $sql . '"';
						
						$stmt = $db->prepare($sql);
						$enterData = $stmt->execute();

        } else { // insert record
            $sql = "INSERT INTO ";
						$sql .= "tblContactInfo SET ";
						$sql .= "pkEmail='$email', ";
            $sql .= "fldFirstName='$firstName', ";
            $sql .= "fldLastName='$lastName', ";
						$sql .= "fldTelephone='$phoneNum'";
						
						$stmt = $db->prepare($sql);
						$enterData = $stmt->execute();
						
						$sql2 = "INSERT INTO ";
						$sql2 .= "tblAddress SET ";
						$sql2 .= "fldAddress='$address', ";
            $sql2 .= "fldCity='$city', ";
            $sql2 .= "fldState='$state', ";
						$sql2 .= "fldZip='$zip'";
						
						$stmt2 = $db->prepare($sql2);
						$stmt2->execute();
						
						$primaryKey = $db->lastInsertId();
						
						$sql3 = "INSERT INTO ";
						$sql3 .= "tblContactInfoAddress SET ";
						$sql3 .= "fkEmail='$email', ";
						$sql3 .= "fkAddressID='$primaryKey'";
						
						$stmt3 = $db->prepare($sql3);
						$stmt3->execute();
						
						$sql4 = "INSERT INTO tblMessage SET fkEmail='$email', fldMessage='$comments'";
						
						$stmt4 = $db->prepare($sql4);
						$stmt4->execute();
						
            if ($debug){
            	   echo "<p>SQL: " . $sql . "</p>";
								 echo "<p>SQL2: " . $sql2 . "</p>";
								 echo "<p>SQL3: " . $sql3 . "</p>";
								 echo "<p>SQL4: " . $sql4 . "</p>";
				    }
        }
        // notice the SQL is basically the same. the above code could be replaced
        // insert ... on duplicate key update but since we have other procssing to
        // do i have split it up.

				
        // Processing for other tables falls into place here. I like to use
        // the same variable $sql so i would repeat above code as needed.
        //if ($debug){
            print "<p>Record has been updated";
        //}
        
        // update or insert complete
        if($enterData){
            exit();
        }
        
    }// end no errors	
} // end isset cmdSubmitted
 
include("top.php");
include("header.php");
include("nav.php");

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// display any errors at top of form page
if ($errorMsg) {
    echo "<ul>\n";
    foreach ($errorMsg as $err) {
        echo "<li style='color: #ff6666'>" . $err . "</li>\n";
    }
    echo "</ul>\n";
} //- end of displaying errors ------------------------------------

if ($id != "") {
    print "<h1>Edit Contact Information</h1>";
    //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
    // display a delete option
    ?>
    <form action="<? print $_SERVER['PHP_SELF']; ?>" method="post">
        <fieldset>
            <input type="submit" name="cmdDelete" value="Delete" />
            <?php print '<input name= "lstEmails" type="hidden" id="lstEmails" value="' . $id . '"/>'; ?>
        </fieldset>	
    </form>
    <?
    //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^% 
} else {
    print "<h1>Add Contact Information</h1>";
}
?>

<form action="<? print $_SERVER['PHP_SELF']; ?>" method="post">
    <fieldset>
        <label for="txtFirstName"	>First Name*</label><br>
        <input name="txtFirstName" type="text" size="20" id="txtFirstName" <? print 'value="'. $firstName .'"'; ?>/><br>

        <label for="txtLastName">Last Name*</label><br>
        <input name="txtLastName" type="text" size="20" id="txtLastName" <? print 'value="' . $lastName . '"'; ?>/><br>

        <label for="txtEmail">Email*</label><br>
        <input name="txtEmail" type="text" size="20" id="txtEmail" <? print 'value="'. $email . '"'; ?> /><br>
				
				<label for="txtPhone">Telephone Number*</label><br>
        <input name="txtPhone" type="text" size="20" id="txtPhone" <? print 'value="'. $phoneNum . '"'; ?> /><br>

        <label for="txtAddress"	>Address*</label><br>
        <input name="txtAddress" type="text" size="20" id="txtAddress" <? print 'value="'. $address .'"'; ?>/><br>

        <label for="txtCity">City*</label><br>
        <input name="txtCity" type="text" size="20" id="txtCity" <? print 'value="' . $city . '"'; ?>/><br>

        <label for="txtState">State*</label><br>
        <input name="txtState" type="text" size="20" id="txtState" <? print 'value="'. $state . '"'; ?> /><br>
				
				<label for="txtZip">Zip Code*</label><br>
        <input name="txtZip" type="text" size="20" id="txtZip" <? print 'value="'. $zip . '"'; ?> /><br>

				<label for="txtComments">Comments*</label><br>
        <input name="txtComments" type="text" size="20" id="txtComments" <? print 'value="'. $comments . '"'; ?> /><br>

        <?
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// if there is a record then we need to be able to pass the pk back to the page
        if ($id != "")
            print '<input name= "id" type="hidden" id="id" value="' . $id . '"/>';
        ?>
        <input type="submit" name="btnSubmitted" value="Submit" />
    </fieldset>		
</form>
<?php

include ("footer.php");

?>
</body>
</html>
