<?php
header('Content-Type: application/json');
include_once './config.php';
$json = [];
if(isset($_GET['worker_name'])){
  	if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
		$start_date=$_GET['start_date'];
		$end_date=$_GET['end_date'];
		$worker_name=$_GET['worker_name'];
		$json['assignment_data']=[];
		$json['receive_data']=[];
		$json['payment_data']=[];
		/*  assignment */
        $sql = "SELECT 
			l.lot_no as lot_no,
			ai.quantity as quantity,
			ai.amount as amount,
			DATE_FORMAT(a.date_time, '%d-%m-%y') as date,
			l.design_no as design_no,
			l.item as item
      	FROM assignment_item ai
		   	LEFT JOIN assignment a ON ai.assignment_id=a.id
		   	LEFT JOIN lot_details l ON ai.lot_id=l.id 
	   	WHERE a.worker= '$worker_name' and a.date_time BETWEEN '$start_date' AND '$end_date'";
       	if ($result = $conn->query($sql)) {
			foreach ($result as $row) {
				$json['assignment_data'][] = $row;
			}
	    } else {
	     	$json = [
				'status' => "error",
				'message' => "Error. Something Went Wrong",
				'debug' => $conn->error
	       	];
      	}
		/* assignment end  */

		/* receive  */
		$receive_sql= "SELECT 
			DATE_FORMAT(r.date, '%d-%m-%y') as date,
			ld.lot_no,
			ld.item,
			ld.design_no,
           	ri.quantity,
	        ri.amount
		FROM received_item ri
		 	LEFT JOIN receive r ON ri.receive_id=r.id
		 	LEFT JOIN assignment_item ai ON ai.id = ri.assignment_item_id
		 	LEFT JOIN lot_details ld ON ld.id = ai.lot_id
	 	WHERE r.worker_name = '$worker_name' and r.date BETWEEN '$start_date' AND '$end_date'";
       	if ($result = $conn->query($receive_sql)) {
			foreach ($result as $row) {
				$json['receive_data'][] = $row;
	  		}
   		}  else {
		   	$json = [
				'status' => "error",
				'message' => "Error. Something Went Wrong",
				'debug' => $conn->error
			];
		}
		/* receive end */

		/* payment  */
		$receive_sql= "SELECT 
		  DATE_FORMAT(date, '%d-%m-%y') as date,
		  amount
		 FROM payment_worker pw
		 	LEFT JOIN tb_worker_details wd ON pw.worker_id=wd.worker_id
		 WHERE pw.worker_id='$worker_name' and date BETWEEN '$start_date' AND '$end_date'";
       	if ($result = $conn->query($receive_sql)) {
			foreach ($result as $row) {
				$json['payment_data'][] = $row;
	  		}
   		}  else {
			$json = [
				'status' => "error",
				'message' => "Error. Something Went Wrong",
				'debug' => $conn->error
			];
		}
	} else {
		$json = [
			"status" => "error",
			"message" => "Please Enter Date",
			'debug' => $conn->error
		];
	}
} else {
	$json = [
		"status" => "error",
		"message" => "Please Select Worker",
		'debug' => $conn->error
	];
}
echo json_encode($json, JSON_PRETTY_PRINT);
?>