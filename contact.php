<?
	
$debug = false;
if ($debug) print "<p>DEBUG MODE IS ON</p>";

$baseURL = "http://www.uvm.edu/~jlaramee/";
$folderPath = "cs148/assignment7.1/";
// full URL of this form
$yourURL = $baseURL . $folderPath . "contact.php";

require_once("connect.php");

//#############################################################################
// set all form variables to their default value on the form. for testing i set
// to my email address. you lose 10% on your grade if you forget to change it.

$firstName = "";
$lastName = "";
$email = "";
$phoneNum = "";
$address = "";
$city = "";
$state = "";
$zip = "";
$comments = "";
$admin = "jlaramee@uvm.edu";

// $email = "";
//#############################################################################
// 
// flags for errors

$firstNameERROR = false;
$lastNameERROR = false;
$emailERROR = false;
$phoneERROR = false;
$addressERROR = false;
$cityERROR = false;
$stateERROR = false;
$zipERROR = false;
$commentsERROR = false;



//#############################################################################
//  

$mailed = false;
$messageA = "";
$messageB = "";
$messageC = "";


//-----------------------------------------------------------------------------
// 
// Checking to see if the form's been submitted. if not we just skip this whole 
// section and display the form
// 
//#############################################################################
// minor security check

if (isset($_POST["btnSubmit"])) {
    $fromPage = getenv("http_referer");

    if ($debug)
        print "<p>From: " . $fromPage . " should match ";
        print "<p>Your: " . $yourURL;

    if ($fromPage != $yourURL) {
        die("<p>Sorry you cannot access this page. Security breach detected and reported.</p>");
    }


//#############################################################################
// replace any html or javascript code with html entities
//

		$firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
		$lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $email = htmlentities($_POST["txtEmail"], ENT_QUOTES, "UTF-8");
		$phoneNum = htmlentities($_POST["txtPhone"], ENT_QUOTES, "UTF-8");
		$address = htmlentities($_POST["txtAddress"], ENT_QUOTES, "UTF-8");
		$city = htmlentities($_POST["txtCity"], ENT_QUOTES, "UTF-8");
		$state = htmlentities($_POST["txtState"], ENT_QUOTES, "UTF-8");
		$zip = htmlentities($_POST["txtZip"], ENT_QUOTES, "UTF-8");
		$comments = htmlentities($_POST["txtComments"], ENT_QUOTES, "UTF-8");


//#############################################################################
// 
// Check for mistakes using validation functions
//
// create array to hold mistakes
// 

    include ("validation_functions.php");

    $errorMsg = array();


//############################################################################
// 
// Check each of the fields for errors then adding any mistakes to the array.
//
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^       Check email address
    
	  if (empty($firstName)) {
        $errorMsg[] = "Please enter your First Name";
        $firstNameERROR = true;
    } else {
        $valid = verifyText($firstName); /* test for non-valid  data */
        if (!$valid) {
            $errorMsg[] = "I'm sorry, the Name you entered is not valid.";
            $emailERROR = true;
        }
    }
		
		if (empty($lastName)) {
        $errorMsg[] = "Please enter your Last Name";
        $lastNameERROR = true;
    } else {
        $valid = verifyText($lastName); /* test for non-valid  data */
        if (!$valid) {
            $errorMsg[] = "I'm sorry, the Name you entered is not valid.";
            $emailERROR = true;
        }
    }

		if (empty($email)) {
        $errorMsg[] = "Please enter your Email Address";
        $emailERROR = true;
    } else {
        $valid = verifyEmail($email); /* test for non-valid  data */
        if (!$valid) {
            $errorMsg[] = "I'm sorry, the email address you entered is not valid.";
            $emailERROR = true;
        }
    }

	  if (empty($phoneNum)) {
        $errorMsg[] = "Please enter your Phone Number";
        $phoneERROR = true;
    } else {
        $valid = verifyText($phoneNum); /* test for non-valid  data */
        if (!$valid) {
            $errorMsg[] = "I'm sorry, the Phone Number you entered is not valid.";
            $phoneERROR = true;
        }
    }

		if (empty($address)) {
        $errorMsg[] = "Please enter your Address";
        $addressERROR = true;
    } else {
        $valid = verifyText($address); /* test for non-valid  data */
        if (!$valid) {
            $errorMsg[] = "I'm sorry, the Address you entered is not valid.";
            $addressERROR = true;
        }
    }
		
		if (empty($city)) {
        $errorMsg[] = "Please enter your City";
        $cityERROR = true;
    } else {
        $valid = verifyText($city); /* test for non-valid  data */
        if (!$valid) {
            $errorMsg[] = "I'm sorry, the City you entered is not valid.";
            $cityERROR = true;
        }
    }
		
		if (empty($state)) {
        $errorMsg[] = "Please enter your State";
        $stateERROR = true;
    } else {
        $valid = verifyText($state); /* test for non-valid  data */
        if (!$valid) {
            $errorMsg[] = "I'm sorry, the State you entered is not valid.";
            $stateERROR = true;
        }
    }
		
		if (empty($zip)) {
        $errorMsg[] = "Please enter your Zip Code";
        $zipERROR = true;
    } else {
        $valid = verifyPhone($zip); /* test for non-valid  data */
        if (!$valid) {
            $errorMsg[] = "I'm sorry, the Zip Code you entered is not valid.";
            $zipERROR = true;
        }
    }
		
		if (empty($comments)) {
        $errorMsg[] = "Please enter your Comments";
        $commentsERROR = true;
    } else {
        $valid = verifyText($comments); /* test for non-valid  data */
        if (!$valid) {
            $errorMsg[] = "I'm sorry, the Comments you entered are not valid.";
            $commentsERROR = true;
        }
    }
		
//############################################################################
// 
// Processing the Data of the form
//

    if (!$errorMsg) {
        if ($debug) print "<p>Form is valid</p>";

//############################################################################
//
// the form is valid so now save the information
//    
        $primaryKeyA = "";
				$primaryKeyB = "";
				$primaryKeyC = "";
        $dataEntered = false;
        
        try {
            $db->beginTransaction();
           
            $sql = 'INSERT INTO tblAddress SET ';
						$sql .= 'fldAddress="' . $address . '", ';
						$sql .= 'fldCity="' . $city . '", ';
						$sql .= 'fldState="' . $state . '", ';
						$sql .= 'fldZip="' . $zip . '"';
            $stmt = $db->prepare($sql);
            if ($debug) print "<p>sql ". $sql;
       
            $stmt->execute();
            
            $primaryKeyA = $db->lastInsertId();
            if ($debug) print "<p>pk tblAddress= " . $primaryKeyA;
						
            $sql2 = 'INSERT INTO tblContactInfo SET ';
						$sql2 .= 'pkEmail="' . $email . '", ';
						$sql2 .= 'fldFirstName="' . $firstName . '", ';
						$sql2 .= 'fldLastName="' . $lastName . '", ';
						$sql2 .= 'fldTelephone="' . $phoneNum . '"';
            $stmt2 = $db->prepare($sql2);
						
						$stmt2->execute();
						
						$primaryKeyB = $email;
 						if ($debug) print "<p>pk tblContactInfo= " . $primaryKeyB;
						
            if ($debug) print "<p>pk tblAddress= " . $primaryKeyA;
						
            $sql3 = 'INSERT INTO tblMessage SET ';
						$sql3 .= 'fkEmail="' . $email . '", ';
						$sql3 .= 'fldMessage="' . $comments . '"';
            $stmt3 = $db->prepare($sql3);
						
						$stmt3->execute();
						
						$primaryKeyC = $db->lastInsertID();
 						if ($debug) print "<p>pk tblMessage= " . $primaryKeyC;
						
						
            $sql4 = 'INSERT INTO tblContactInfoAddress SET ';
						$sql4 .= 'fkAddressID="' . $primaryKeyA . '", ';
						$sql4 .= 'fkEmail="' . $email . '"';
            $stmt4 = $db->prepare($sql4);
						
						$stmt4->execute();
						
 						if ($debug) print "<p>pk tblContactInfoAddress= " . $primaryKeyA . " & " . $email;
												
            // all sql statements are done so lets commit to our changes
            $dataEntered = $db->commit();
            if ($debug) print "<p>transaction complete ";
        } catch (PDOExecption $e) {
            $db->rollback();
            if ($debug) print "Error!: " . $e->getMessage() . "</br>";
            $errorMsg[] = "There was a problem with accpeting your data please contact us directly.";
        }


        // If the transaction was successful, give success message
        if ($dataEntered) {
            if ($debug) print "<p>data entered now prepare keys ";
            //#################################################################
            // create a key value for confirmation

            $sql = "SELECT fldDateJoined FROM tblContactInfo WHERE pkEmail=" . $email;
            $stmt = $db->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $dateSubmitted = $result["fldDateJoined"];

            $key1 = sha1($dateSubmitted);
            $key2 = $primaryKeyA;
						$key3 = $primaryKeyB;
						$key4 = $primaryKeyC;

            if ($debug) print "<p>key 1: " . $key1;
            if ($debug) print "<p>key 2: " . $key2;
						if ($debug) print "<p>key 3: " . $key3;
            if ($debug) print "<p>key 4: " . $key4;


            //#################################################################
            //
            //Put forms information into a variable to print on the screen
            //

            $messageA = '<h2>Thank you for sending us a message!</h2>';
            $messageB .="";
            $messageC .= "<p><b>The Email Address you entered:</b><i>   " . $email . "</i></p>";

            //##############################################################
            //
            // email the form's information
            //
            
            $subject = "Sonotone of burlington website message.";
            include_once('mailMessage.php');
            $mailed = sendMail($admin, $subject, $messageA . $messageC);
        } //data entered   
    } // no errors 
}// ends if form was submitted. 

    include ("top.php");

    $ext = pathinfo(basename($_SERVER['PHP_SELF']));
    $file_name = basename($_SERVER['PHP_SELF'], '.' . $ext['extension']);

    print '<body id="' . $file_name . '">';

    include ("header.php");
    include ("nav.php");
    ?>

    <section id="main">
        <h1>Register </h1>

        <?
//############################################################################
//
//  In this block  display the information that was submitted and do not 
//  display the form.
//
        if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) {
            print "<h2>Your Request has ";

            if (!$mailed) {
                echo "not ";
            }

            echo "been processed</h2>";

            print "<p>A copy of this message has ";
            if (!$mailed) {
                echo "not ";
            }
            print "been sent to: " . $email . "</p>";

            echo $messageA . $messageC;
        } else {


//#############################################################################
//
// Here we display any errors that were on the form
//

            print '<div id="errors">';

            if ($errorMsg) {
                echo "<ol>\n";
                foreach ($errorMsg as $err) {
                    echo "<li>" . $err . "</li>\n";
                }
                echo "</ol>\n";
            }

            print '</div>';
            ?>
            <!--   Take out enctype line    -->
            <form action="<? print $_SERVER['PHP_SELF']; ?>"
                  enctype="multipart/form-data"
                  method="post"
                  id="frmRegister">
                <fieldset class="contact">
                    <legend>Contact Us</legend>
										<article class= "formSpace">
														 <label class="required" for="txtFirstName">First Name </label><br/>
                    				 <input id ="txtFirstName" name="txtFirstName" class="element text medium<?php if ($emailERROR) echo ' mistake'; ?>" type="text" maxlength="255" value="<?php echo $firstName; ?>" placeholder="enter your first name" onfocus="this.select()"  tabindex="10"/>
										</article>
										
										<article class= "formSpace">
														 <label class="required" for="txtLastName">Last Name </label><br/>
                    				 <input id ="txtLastName" name="txtLastName" class="element text medium<?php if ($emailERROR) echo ' mistake'; ?>" type="text" maxlength="255" value="<?php echo $lastName; ?>" placeholder="enter your last name" onfocus="this.select()"  tabindex="20"/>
										</article>
										
										<article class= "formSpace">
                    				 <label class="required" for="txtEmail">Email </label><br/>
                    				 <input id ="txtEmail" name="txtEmail" class="element text medium<?php if ($emailERROR) echo ' mistake'; ?>" type="text" maxlength="255" value="<?php echo $email; ?>" placeholder="enter your preferred email address" onfocus="this.select()"  tabindex="30"/>
										</article>
										
										<article class= "formSpace">
                    				 <label class="required" for="txtPhone">Phone Number </label><br/>
                    				 <input id ="txtPhone" name="txtPhone" class="element text medium<?php if ($emailERROR) echo ' mistake'; ?>" type="text" maxlength="255" value="<?php echo $phone; ?>" placeholder="enter your preferred phone number" onfocus="this.select()"  tabindex="40"/>
										</article>
										
										<article class= "formSpace">
                    				 <label class="required" for="txtAddress">Address </label><br/>
                    				 <input id ="txtAddress" name="txtAddress" class="element text medium<?php if ($emailERROR) echo ' mistake'; ?>" type="text" maxlength="255" value="<?php echo $address; ?>" placeholder="enter your preferred address" onfocus="this.select()"  tabindex="50"/>
										</article>
										
										<article class= "formSpace">
                    				 <label class="required" for="txtCity">City </label><br/>
                    				 <input id ="txtCity" name="txtCity" class="element text medium<?php if ($emailERROR) echo ' mistake'; ?>" type="text" maxlength="255" value="<?php echo $city; ?>" placeholder="enter your city" onfocus="this.select()"  tabindex="60"/>
										</article>
										
										<article class= "formSpace">
                    				 <label class="required" for="txtState">State </label><br/>
                    				 <input id ="txtState" name="txtState" class="element text medium<?php if ($emailERROR) echo ' mistake'; ?>" type="text" maxlength="255" value="<?php echo $state; ?>" placeholder="enter your state" onfocus="this.select()"  tabindex="70"/>
										</article>
										
										<article class= "formSpace">
                    				 <label class="required" for="txtZip">Zip </label><br/>
                    				 <input id ="txtZip" name="txtZip" class="element text medium<?php if ($emailERROR) echo ' mistake'; ?>" type="text" maxlength="255" value="<?php echo $zip; ?>" placeholder="enter your zip code" onfocus="this.select()"  tabindex="80"/>
										</article>
										
										<article class= "formSpace">
                    				 <label class="required" for="txtComments">Comments </label><br/>
                    				 <input id ="txtComments" name="txtComments" class="element text medium<?php if ($emailERROR) echo ' mistake'; ?>" type="text" maxlength="255" value="<?php echo $comments; ?>" placeholder="enter your comments" onfocus="this.select()"  tabindex="90"/>
										</article>
										
                </fieldset> 


                <fieldset class="buttons">
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Register" tabindex="991" class="button">
                    <input type="reset" id="butReset" name="butReset" value="Reset Form" tabindex="993" class="button" onclick="reSetForm()" >
                </fieldset>                    

            </form>
            <?php
        } // end body submit
        if ($debug)
            print "<p>END OF PROCESSING</p>";
        ?>
    </section>


    <?
    include ("footer.php");
    ?>

</body>
</html>