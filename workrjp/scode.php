<?php
// Get the PHP helper library from twilio.com/docs/php/install
require_once('/twilio/Services/Twilio.php'); // Loads the library
 
$account_sid = 'AC3e87413f8187b4b3d2724ad36a3c2b32'; 
$auth_token = 'a12f2acb7199b95312b19220123da193'; 
$client = new Services_Twilio($account_sid, $auth_token); 
 
$client->account->messages->create(array( 
	'To' => "+818068030708", 
	'From' => "+16602624998", 
	'Body' => "123456",   
));

?>