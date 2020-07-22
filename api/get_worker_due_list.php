<?php
header('Content-Type: application/json');
include_once './config.php';
$json = [];
$sql = "SELECT
    wd.name,
    w.worker_type as worker_type,
    (
    SELECT
        SUM(quantity)
    FROM
        assignment_item ai
    LEFT JOIN
        assignment a
    ON
        a.id = ai.assignment_id
    WHERE
        a.worker = wd.worker_id
	) AS assigned_qty,
	(
	SELECT
	    SUM(amount)
	FROM
	    assignment_item ai
	LEFT JOIN
	    assignment a
	ON
	    a.id = ai.assignment_id
	WHERE
	    a.worker = wd.worker_id
	) AS assigned_amt,
	 (
	    SELECT
	        SUM(quantity)
	    FROM
	        received_item ri
	    LEFT JOIN
	        receive r
	    ON
	        r.id = ri.receive_id
	    WHERE
	        r.worker_name = wd.worker_id
	) AS receive_qty,
	(
	    SELECT
	        SUM(amount)
	    FROM
	        received_item ri
	    LEFT JOIN
	        receive r
	    ON
	        r.id = ri.receive_id
	    WHERE
	        r.worker_name = wd.worker_id
	) AS receive_amt,
	(
	    SELECT
	        SUM(amount)
	    FROM
	        payment_worker pw
	   
	    WHERE
	        pw.worker_id = wd.worker_id
	) AS payment_amt
	FROM tb_worker_details wd
LEFT JOIN tb_worker w ON wd.worker_type=w.id";
   		
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
echo json_encode($json, JSON_PRETTY_PRINT);
?>
