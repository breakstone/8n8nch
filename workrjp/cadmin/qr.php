<?php
require("../_includes/qrcode/Image/QRCode.php");

$url = "http://".$_REQUEST["url"].".8n8n.co.jp";

$QR = new Image_QRCode();
$options = array(
		"module_size" => 5
);
$QR->makeCode(htmlspecialchars($url, ENT_QUOTES),$options);
?>