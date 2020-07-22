<?php
header('Content-Type: application/json');
include_once './config.php';
$json = [];
if(isset($_POST['entry'])) {
	// $worker_type = $_POST['worker_type'];
	$worker = $_POST['worker_type_id'];
	$date_time = $_POST['date_time'];
	$total_qty=$_POST['total_qty'];
	$total_amount=$_POST['total_amount'];
	$sql = "INSERT INTO assignment(
		-- worker_type,
		worker, 
		date_time,
		total_quantity,
		total_amount
	) VALUES (?, ?, ?,?)";
	if($stmt = $conn->prepare($sql)){
		if($stmt->bind_param("ssii", $worker, $date_time,$total_qty,$total_amount)){
			if($stmt->execute()){
				$json['header_status'] = "success";
				$assignment_id=$conn->insert_id;
				$json['last_id'] = $assignment_id;
				$details_sql = "INSERT INTO assignment_item(
					assignment_id,
					lot_id,
					size,
					quantity,
					rate,
					amount
				) VALUES (?, ?, ?,?,?,?)";
				if($stmt = $conn->prepare($details_sql)){
					if($stmt->bind_param("iisiii",$assignment_id, $lot_id, $size,$quantity,$rate,$amount)){
						foreach ($_POST['lot_id'] as $key => $value) {
							$lot_id = $_POST['lot_id'][$key];
							$size = $_POST['Size'][$key];
							$quantity=$_POST['quantity'][$key];
							$rate=$_POST['rate'][$key];
							$amount=$_POST['amount'][$key];
							if($stmt->execute()){
								$json['status'] = 'success';
								$json['message'] = 'Success';
								$json['details_last_id'][] = $conn->insert_id;
							} else {
								$json['status'] = 'error';
								$json['message'] = 'Tech Error';
								$json['debug'] = $conn->error;
							}
						} 
					}else {
						$json['status'] = 'error';
						$json['message'] = 'Tech Error';
						$json['debug'] = $conn->error;
					}
				} else {
					$json['status'] = 'error';
					$json['message'] = 'Tech Error';
					$json['debug'] = $conn->error;
				}
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
	}
}  else if(!empty($_POST["assignment_edit_id"])){
	$edit_id = $_POST['assignment_edit_id'];
    // $worker_type2 = $_POST['worker_type2'];
    $worker2 = $_POST['worker2'];
	$date = $_POST['date_time'];
	$total_quantity=$_POST['total_qty'];
	$total_amount=$_POST['total_amount'];
    $sql = "UPDATE assignment SET 
		date_time=?,
		-- worker_type=?,
		worker=?,
		total_quantity=?,
		total_amount=?
	       WHERE id = ?";
	if ($stmt = $conn->prepare($sql)) {
		if ($stmt->bind_param("siiii",$date,$worker2,$total_quantity,$total_amount,$edit_id)) {
			if ($stmt->execute()) {
				$stmt->close();
				$json['header_status'] = "success";
				$receive_id = $edit_id;
				$json['last_id'] = $receive_id;
				$sql = "DELETE FROM  assignment_item WHERE assignment_id = '$edit_id'";
				if ($conn->query($sql)) {
					$details_sql = "INSERT INTO assignment_item(
						assignment_id,
						lot_id,
						size,
						quantity,
						rate,
						amount
					) VALUES (?, ?, ?,?,?,?)";
					if($stmt = $conn->prepare($details_sql)){
						if($stmt->bind_param("iisiii",$edit_id, $lot_id, $size,$quantity,$rate,$amount)){
							foreach ($_POST['lot_id'] as $key => $value) {
								$lot_id = $_POST['lot_id'][$key];
								$size = $_POST['Size'][$key];
								$quantity=$_POST['quantity'][$key];
								$rate=$_POST['rate'][$key];
								$amount=$_POST['amount'][$key];
								if ($stmt->execute()) {
									$json['status'] = 'success';
									$json['message'] = 'Success';
									$json['details_last_id'][] = $conn->insert_id;
								} else {
									$json['status'] = 'error';
									$json['message'] = 'Tech Error';
									$json['debug'] = $conn->error;
								}
							} 
						} else {
							$json['status'] = 'error';
							$json['message'] = 'Tech Error';
							$json['debug'] = $conn->error;
						}
					}
				}
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
			  	'message' => "This data can not be deleted",
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
} else if (!empty($_GET['id'])) {
	$id=$_GET['id'];
	$header_sql = "SELECT
		     -- a.id as id,
		    -- worker_type,
		tb_worker_details.name  as worker,
		tb_worker_details.worker_id as worker_id, 
		tb_worker_details.worker_type as worker_type_id,
		a.total_quantity as total_quantity,
		a.total_amount as total_amount,
		date_time as date_sort, 
	    DATE_FORMAT(a.date_time, '%d-%m-%Y') as date_time
	FROM assignment a
	LEFT JOIN tb_worker_details ON a.worker = tb_worker_details.worker_id
	WHERE id = ? ";
	if ($stmt = $conn->prepare($header_sql)) {
		if ($stmt->bind_param('i', $id)) {
			if ($stmt->execute()) {
				if ($result = $stmt->get_result()) {
					$stmt->close();
					foreach ($result as $r) {
						$json['header'] = $r;
					}
					$items_sql = "SELECT lot_details.design_no as design_no,
					    lot_details.lot_no as lot_no,
					    ai.lot_id,
					    ai.assignment_id as id,
						ai.size as size,
						ai.rate as rate,
						ai.quantity as quantity,
						ai.amount as amount
					FROM assignment_item ai
					LEFT JOIN lot_details ON ai.lot_id = lot_details.id
				    WHERE ai.assignment_id = ?";
				    if ($stmt = $conn->prepare($items_sql)) {
						if ($stmt->bind_param('i', $id)) {
							if ($stmt->execute()) {
								if ($item_result = $stmt->get_result()) {
									$stmt->close();
									foreach ($item_result as $r) {
										$json['items'][] = $r;
									}
								} else {
									$json['status'] = 'error'; 
									$json['debug'] = $conn->error;
								}
							} else {
								$json['status'] = 'error'; 
								$json['debug'] = $conn->error;
							}
						} else {
							$json['status'] = 'error'; 
							$json['debug'] = $conn->error;
						}
					} else {
						$json['status'] = 'error'; 
						$json['debug'] = $conn->error;
					}
				}
			} else {
				$json=[
				   'status' => 'error',
				   'message' => 'Tech Error',
				   'debug' => $conn->error
				];
			}
		} else {
			$json=[
				'status' => 'error',
				'message' => 'Tech Error',
				'debug' => $conn->error
			];
		}
  	} else {     
        $json=[
	        'status' => "error",
		    'message' => "Error1. Something Went Wrong",
		    'debug' => $conn->error
		];
	}
} else if(isset($_GET['delete_id'])){
	$delete_id=$_GET['delete_id'];
     $sql = "DELETE FROM  assignment WHERE id =?";
    if ($stmt = $conn->prepare($sql)) {
    	if ($stmt->bind_param('i',$delete_id)) {
    		if ($stmt->execute()){
					$details_sql = "DELETE FROM assignment_item WHERE assignment_id =?";
    		    if ($stmt = $conn->prepare($details_sql)) {
    	            if ($stmt->bind_param('i',$delete_id)) {
    		            if ($stmt->execute()) {
    			                 $json['status'] = "success";
    			                  $json['message'] = "Success";
    	                }
                    }
                }
            }
        }
    }
} else if (isset($_GET['worker_no'])){
	$worker_no=$_GET['worker_no'];
	$sql = "SELECT
	    ai.id,
	    ai.lot_id,
	    ai.size,
	    ai.quantity,
	    ai.rate,
	    ai.amount,
	    ai.assignment_id,
	    ld.lot_no,
	    ld.design_no,
	    ld.item
	FROM assignment_item ai
		LEFT JOIN assignment a ON a.id=ai.assignment_id
		LEFT JOIN lot_details ld ON ld.id=ai.lot_id
	WHERE worker='$worker_no'";
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

} else if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
	// TODO:  create date range sql
	$start_date=$_GET['start_date'];
	$end_date=$_GET['end_date'];
	$sql="SELECT assignment.id,
	    tb_worker.worker_type as worker_type,
	    tb_worker_details.name as worker,
	    DATE_FORMAT(assignment.date_time, '%d-%m-%Y') as date_time,
	    assignment.total_quantity as total_quantity,
	    assignment.total_amount as total_amount
	FROM assignment
		LEFT JOIN tb_worker ON assignment.worker_type=tb_worker.id
		LEFT JOIN tb_worker_details ON assignment.worker=tb_worker_details.worker_id WHERE date_time BETWEEN '$start_date' AND '$end_date'";
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
 } else {
   	$sql = "SELECT assignment.id,
	    tb_worker.worker_type as worker_type,
	    tb_worker_details.name as worker,
	    DATE_FORMAT(assignment.date_time, '%d-%m-%Y') as date_time,
	    assignment.total_quantity as total_quantity,
	    assignment.total_amount as total_amount
    FROM assignment
		LEFT JOIN tb_worker_details ON assignment.worker=tb_worker_details.worker_id
		LEFT JOIN tb_worker ON tb_worker_details.worker_type=tb_worker.id";
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
echo json_encode($json, JSON_PRETTY_PRINT);