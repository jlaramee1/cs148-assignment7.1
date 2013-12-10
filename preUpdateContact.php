<?php
session_start();
ini_set("arg_separator.output", "&amp;");
ini_set("url_rewriter.tags", "a=href,area=href,frame=src,input=src");
session_name("jlaramee_assignment7.1");

$debug = false;
if (isset($_GET["debug"])) {
    $debug = true;
}

include("connect.php");
include("top.php");
include("header.php");
include("nav.php");

$baseURL = "http://www.uvm.edu/~jlaramee/";
$folderPath = "cs148/assignment7.1/";

// full URL of this form
$yourURL = $baseURL . $folderPath . "preUpdateContact.php";

$fromPage = getenv("http_referer");

    
		$sql = "SELECT pkEmail FROM tblContactInfo";

    if ($debug)
        echo "<p>SQL: " . $sql . "</p>";

    $stmt = $db->prepare($sql);

    $stmt->execute();

    $emails = $stmt->fetchAll();
		
		if ($debug){
	 			foreach ($emails as $email => $val){
	 		  				print "<pre>";
        				var_dump($emails);
        				print "</pre>";
				}
		}
		$arrlength=count($emails);
		
		if (isset($_POST['cmdSubmitted'])) {
			 $arrayemail = $_POST["lstEmails"];
		}
		
		echo "<h1>Contacts </h1>";
    echo '<form action="updateContact.php"';
    echo 'method="post"';
    echo 'id="frmRegister">';  
    echo '<fieldset class="listbox"><legend>Contacts</legend><select name="lstEmails" size="1" tabindex="10">';
		for ($x=0; $x<$arrlength; $x++){
        echo '<option value="' . $emails[$x]["pkEmail"] . '">' . $emails[$x]["pkEmail"] . '</option>';
    }
		echo '<option value="">Add Contact</option>';
 	  echo "</select>";
		
		echo $_POST["lstEmails"];
		
		echo "<input type='submit' name='cmdSubmitted' value='Submit' /></fieldset>";
		echo '</form>';
		
		
		
		
		include("footer.php");
?>
	
</body>
</html>
