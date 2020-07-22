<?php
header('Content-Type: application/json');
include_once './config.php';
$json = [];
$sql = "SELECT 
	id,
	text as text 
FROM payment_method";
if ($result = $conn->query($sql)) {
	foreach ($result as $row) {
		$json[] = $row;
	}
} else {
	$json = [
		'status' => "error",
		'message' => "Error. Something Went Wrong",
		'debug' => $conn->error
	];
}
echo json_encode($json);
?>