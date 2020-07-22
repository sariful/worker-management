<?php
header('Content-Type: application/json');
include_once './config.php';
$json = [];
if(isset($_POST['entry'])){
	   $worker_name=$_POST['worker_name'];
	   $date_time=$_POST['date_time'];
	   $adjustment=$_POST['adjustment'];
	   $amount=$_POST['amount'];
	   $payment_method=$_POST['payment_method'];
	   $cheque_no=$_POST['cheque_no'];
	   $remarks=$_POST['remarks'];
  $sql = "INSERT INTO payment_worker(
		adjustment,
		date, 
		worker_id,
		amount,
		payment_method_id,
		cheque_no,
		remarks
	) VALUES (?,?,?,?,?,?,?)";
	if($stmt = $conn->prepare($sql)){
		if($stmt->bind_param("isiiiss", $adjustment,$date_time,$worker_name,$amount,$payment_method,$cheque_no,$remarks)){
			    if($stmt->execute()){
				$json = [
					'status' => "success",
					'message' => "Success",
					'debug' => $conn->error
				];
			}   else {
				$json = [
					'status' => "success",
					'message' => "Success",
					'debug' => $conn->error
				];
				
			}
	  } else {
			$json = [
				'status' => "error",
				'message' => "Error. Something Went Wrong",
				'debug' => $conn->error
			];
		}
}   else {
		$json = [
			'status' => "error",
			'message' => "Error. Something Went Wrong",
			'debug' => $conn->error
		];
			}
} else if (!empty($_POST['edit_id'])) {
	   $edit_id = $_POST['edit_id'];
	   $worker_name=$_POST['worker_name'];
	   $date_time=$_POST['date_time'];
	   $adjustment=$_POST['adjustment'];
	   $amount=$_POST['amount'];
	   $payment_method=$_POST['payment_method'];
	   $cheque_no=$_POST['cheque_no'];
	   $remarks=$_POST['remarks'];
	$sql = "UPDATE payment_worker SET 
		worker_id = ?,
		adjustment=?,
		amount=?,
		payment_method_id=?,
		cheque_no=?,
		remarks=?,
		date=?
	WHERE id = ?";
	if ($stmt = $conn->prepare($sql)) {
		if ($stmt->bind_param("iiiisssi", $worker_name,$adjustment,$amount,$payment_method,$cheque_no,$remarks,$date_time,$edit_id)) {
			if ($stmt->execute()) {
				$json = [
					'status' => "success",
					'message' => "Successfully edited",
					'debug' => $conn->error
				];
			} else {
				$json = [
					'status' => "error",
					'message' => "error",
					'debug' => $conn->error
				];
			}
		} else {
			$json = [
				'status' => "error",
				'message' => "Error. Something Went Wrong",
				'debug' => $conn->error
			];
		}
	} else {
		$json = [
			'status' => "error",
			'message' => "Error. Something Went Wrong",
		    'debug' => $conn->error
		]; 
	}
}

 else if(isset($_GET['delete_id'])){
          $delete_id = $_GET['delete_id'];
       $sql = "DELETE FROM  payment_worker WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
    	if ($stmt->bind_param('i',$delete_id)) {
    		if ($stmt->execute()) {
    			$json['status'] = "success";
    			$json['message'] = "Success";
    		} else {
				$json = [
					'status' => "error",
					'message' => "error",
					'debug' => $conn->error
				];
			}
		} else {
			$json = [
				'status' => "error",
				'message' => "Error. Something Went Wrong",
				'debug' => $conn->error
			];
		}
	} else {
		$json = [
			'status' => "error",
			'message' => "Error. Something Went Wrong",
		    'debug' => $conn->error
		]; 
	}
} else if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
         $start_date=$_GET['start_date'];
	    $end_date=$_GET['end_date'];
   $sql = "SELECT p.id,
        p.adjustment,
		DATE_FORMAT(date, '%d-%m-%Y') as date, 
		tb_worker_details.name as worker_id, 
		p.amount,
		payment_method.text as payment_method_id,
		p.cheque_no,
		p.remarks
 FROM payment_worker p
  LEFT JOIN payment_method  ON p.payment_method_id=payment_method.id
  LEFT JOIN tb_worker_details  ON p.worker_id=tb_worker_details.worker_id WHERE date BETWEEN '$start_date' AND '$end_date'";
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
}
else {
$sql = "SELECT p.id,
        p.adjustment,
		DATE_FORMAT(date, '%d-%m-%Y'),
		p.date as date,
		tb_worker_details.name as worker,
		tb_worker_details.worker_id as worker_id, 
		p.amount,
		payment_method.text as payment_method,
		payment_method.id as payment_method_id,
		p.cheque_no,
		p.remarks
 FROM payment_worker p
 LEFT JOIN payment_method  ON p.payment_method_id=payment_method.id
  LEFT JOIN tb_worker_details  ON p.worker_id=tb_worker_details.worker_id";
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
}
echo json_encode($json);
?>