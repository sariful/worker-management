<?php
header('Content-Type: application/json');
include_once './config.php';
$json = [];
if(isset($_POST['received_id'])) {
    $worker_name = $_POST['worker_name'];
    $date_time = $_POST['date_time'];
    $total_qty=$_POST['total_qty'];
    $total_amount=$_POST['total_amount'];
    $sql = "INSERT INTO receive (
		worker_name, 
		date,
		total_quantity,
		total_amount
		) VALUES (?,?,?,?)";
	if($stmt = $conn->prepare($sql)){
		if($stmt->bind_param("ssii", $worker_name, $date_time,$total_qty,$total_amount)){
			if($stmt->execute()){
				$stmt->close();
				$json['header_status'] = "success";
				$receive_id = $conn->insert_id;
				$json['last_id'] = $receive_id;
				$details_sql = "INSERT INTO received_item (
					receive_id,
					assignment_item_id,
					quantity,
					rate,
					amount
				) VALUES (?, ?, ?,?,?)";
				if($stmt = $conn->prepare($details_sql)){
					if($stmt->bind_param("iiddd", $receive_id, $assignment_item_id, $quantity, $rate, $amount)){
						foreach ($_POST['assignment_item_id'] as $key => $value) {
							$assignment_item_id = $_POST['assignment_item_id'][$key];
							$size = $_POST['Size'][$key];
							$quantity=$_POST['quantity'][$key];
							$rate=$_POST['rate'][$key];
							$amount=$_POST['amount'][$key];
                            // $lot_id=$_POST['lot_id'][$key]; 
							if($stmt->execute()){
								$json['status'] = 'success';
								$json['message'] = 'Success';
								$json['details_last_id'][] = $conn->insert_id;
							} else {
								$json['status'] = 'error';
								$json['message'] = 'Error1. Something Went Wrong';
								$json['debug'][] = $conn->error;
							}
						} 
					} else {
						$json['status'] = 'error';
						$json['message'] = 'Error2. Something Went Wrong';
						$json['debug'] = $conn->error;
					}
				} else {
					$json['status'] = 'error';
					$json['message'] = 'Error3. Something Went Wrong';
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
} else if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
	// TODO:  create date range sql
	$start_date=$_GET['start_date'];
	$end_date=$_GET['end_date'];
	$sql="SELECT
	    id,
	    DATE_FORMAT(receive.date, '%d-%m-%Y') as date,
	   	tb_worker_details.name,
	    total_quantity,
	    total_amount
	FROM  receive
 			LEFT JOIN tb_worker_details ON receive.worker_name =tb_worker_details.worker_id WHERE date BETWEEN '$start_date' AND '$end_date'";
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
 } else if(isset($_GET['delete_id'])){
	$delete_id=$_GET['delete_id'];
     $sql = "DELETE FROM  receive WHERE id =?";
    if ($stmt = $conn->prepare($sql)) {
    	if ($stmt->bind_param('i',$delete_id)) {
    		if ($stmt->execute()){
					$details_sql = "DELETE FROM received_item WHERE receive_id =?";
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
} else if (!empty($_GET['id'])) {
	$id=$_GET['id'];
	$header_sql = "SELECT receive.id,
	    tb_worker_details.name as worker_name,
	    tb_worker_details.worker_id as worker_id,
	    date as date_sort,
	    DATE_FORMAT(receive.date, '%d-%m-%Y') as date,
		total_quantity,
		total_amount
	 FROM receive
		LEFT JOIN tb_worker_details ON receive.worker_name =tb_worker_details.worker_id WHERE id =?";
	if ($stmt = $conn->prepare($header_sql)) {
    	if ($stmt->bind_param('i', $id)) {
    		if ($stmt->execute()) {
    			if($result=$stmt->get_result()){
    				$stmt->close();
    			foreach ($result as $r) {
    			    $json['data']=$r;
    			}
               $item_sql = "SELECT
			        ri.id,
				    ri.receive_id as receive_id,
				    ri.quantity,
				    ri.amount,
				    ri.rate,
				    ld.lot_no AS lot,
				    ld.id AS lot_id,
				    ld.design_no AS design_no,
				    ai.size as size,
				    ai.id as assignment_item_id
			     FROM received_item ri
					LEFT JOIN assignment_item ai ON ri.assignment_item_id = ai.id
					LEFT JOIN lot_details ld ON ai.lot_id = ld.id WHERE receive_id =?"; 
                if ($stmt = $conn->prepare($item_sql)) {
					if ($stmt->bind_param('i', $id)) {
						if ($stmt->execute()) {
							if ($item_result = $stmt->get_result()) {
									$stmt->close();
								foreach ($item_result as $row) {
									$json['items'][] = $row;
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
 } else if(!empty($_POST["received_item_edit_id"])){
	$edit_id = $_POST['received_item_edit_id'];
    $worker = $_POST['worker'];
	$date = $_POST['date_time'];
	$total_quantity=$_POST['total_qty'];
	$total_amount=$_POST['total_amount'];
    $sql = "UPDATE receive SET 
		date=?,
		worker_name=?,
		total_quantity=?,
		total_amount=?
   	WHERE id = ?";
	if($stmt = $conn->prepare($sql)){
		if($stmt->bind_param("siiii",$date,$worker,$total_quantity,$total_amount,$edit_id)){
			if($stmt->execute()){
				$stmt->close();
				$json['header_status'] = "success";
				$json['header_message'] = "Success";
				$receive_id = $edit_id;
				$json['last_id'] = $receive_id;
				// $json['form_data'] = $_POST;
				$sql_item = "DELETE FROM  received_item WHERE receive_id = '$edit_id'";
				// if (true) {
				if ($conn->query($sql_item)) {
					$details_sql = "INSERT INTO received_item(
						assignment_item_id,
						receive_id,
						quantity,
						rate,
						amount
					) VALUES (?, ?, ?, ?,?)";
					if($stmt = $conn->prepare($details_sql)){
						if($stmt->bind_param("iiiii", $assignment_item_id, $edit_id ,$quantity, $rate, $amount)){
							foreach ($_POST['lot_id'] as $key => $value) {
								$assignment_item_id = $_POST['assignment_item_id'][$key];
								$lot_id = $_POST['lot_id'][$key];
								$quantity = $_POST['quantity'][$key];
								$rate = $_POST['rate'][$key];
								$amount = $_POST['amount'][$key];

								if($stmt->execute()){
									$json['status'] = 'success';
									$json['message'] = 'Success';
									$json['details_last_id'][] =$conn->insert_id;
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
} else {
	$sql = "SELECT
    	r.id,
    	 DATE_FORMAT(r.date, '%d-%m-%Y') as date,
   		tb_worker_details.name,
   		r.total_quantity,
    	r.total_amount
	FROM  receive r
 		LEFT JOIN tb_worker_details ON r.worker_name =tb_worker_details.worker_id";
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
 // $conn->insert_id
echo json_encode($json, JSON_PRETTY_PRINT);
?>