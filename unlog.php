<?php
session_destroy();
echo json_encode(array(
	"session" => false 
));
exit;
?>