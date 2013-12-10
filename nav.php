<nav class="navigation">
     <ol class="plain">
<?php 
if(basename($_SERVER['PHP_SELF'])=="home.php"){
    print '<li class="activePage">Home</li>' . "\n";
} else {
    print '<li class="bottomSpace"><a href="home.php">Home</a></li>' . "\n";
} 

if(basename($_SERVER['PHP_SELF'])=="about.php"){
    print '<li class="activePage">About Us</li>' . "\n";
} else {
    print '<li class="bottomSpace"><a href="about.php">About Us</a></li>' . "\n";
}

if(basename($_SERVER['PHP_SELF'])=="services.php"){
    print '<li class="activePage">Services</li>' . "\n";
} else {
    print '<li class="bottomSpace"><a href="services.php">Services</a></li>' . "\n";
}

if(basename($_SERVER['PHP_SELF'])=="hearingaids.php"){
    print '<li class="activePage">Hearing Aids</li>' . "\n";
} else {
    print '<li class="bottomSpace"><a href="hearingaids.php">Hearing Aids</a></li>' . "\n";
}

if(basename($_SERVER['PHP_SELF'])=="contact.php"){
    print '<li class="activePage">Contact Us</li>' . "\n";
} else {
    print '<li class="bottomSpace"><a href="contact.php">Contact Us</a></li>' . "\n";
}

if(basename($_SERVER['PHP_SELF'])=="staff.php"){
    print '<li class="activePage">Staff</li>' . "\n";
} else {
    print '<li class="bottomSpace"><a href="staff.php">Staff</a></li>' . "\n";
}

if(basename($_SERVER['PHP_SELF'])=="location.php"){
    print '<li class="activePage">Location</li>' . "\n";
} else {
    print '<li class="bottomSpace"><a href="location.php">Location</a></li>' . "\n";
}

if(basename($_SERVER['PHP_SELF'])=="links.php"){
    print '<li class="activePage">Links</li>' . "\n";
} else {
    print '<li class="bottomSpace"><a href="links.php">Links</a></li>' . "\n";
}

if(basename($_SERVER['PHP_SELF'])=="preUpdateContact.php"){
    print '<li class="activePage">Admin</li>' . "\n";
} else {
    print '<li class="bottomSpace"><a href="preUpdateContact.php">Admin</a></li>' . "\n";
} 
// repeat above for each menu option
?>
			
</nav>