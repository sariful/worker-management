<?php
header('Content-Type: application/json');
include_once './config.php';
$json = [];
	$sql = "SELECT
	 	ld.lot_no as lot_no,
		ld.design_no as design_no ,
    	ld.item as item,
    	wd.name as name,
      	DATE_FORMAT(a.date_time, '%d-%m-%Y') as date,
    	ai.quantity as quantity,
    	DATE_FORMAT(r.date, '%d-%m-%Y') as receive_date,
    	SUM(ri.quantity) as receive_quantity
	FROM lot_details ld
		LEFT JOIN assignment_item ai ON ld.id=ai.lot_id
		LEFT JOIN assignment a ON ai.assignment_id=a.id
		LEFT JOIN tb_worker_details wd ON a.worker=wd.worker_id
		LEFT JOIN received_item ri ON ai.id=ri.assignment_item_id
		LEFT JOIN receive r ON ri.receive_id=r.id
	GROUP BY ld.id, wd.worker_id, ai.id";
	if ($result = $conn->query($sql)) {
		foreach ($result as $row) {
			$json['data'][] = $row;
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