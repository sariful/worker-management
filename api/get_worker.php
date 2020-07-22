<?php
header('Content-Type: application/json');
include_once './config.php';
$json = [];
if(isset($_POST['entry'])) {
	$worker_type = $_POST['worker_type'];
	$name = $_POST['name'];
	$address = $_POST['address'];
	$phone_no = $_POST['phone_no'];
	$opening_balance = $_POST['opening_balance']; 
	$remarks = $_POST['remarks'];
	$sql = "INSERT INTO tb_worker_details(
		worker_type,
		name, 
		address,
		phone_no,
		opening_balance,
		remarks
	) VALUES (?, ?, ?,?,?,?)";
	if($stmt = $conn->prepare($sql)){
		if($stmt->bind_param("ssssss", $worker_type, $name, $address,$phone_no,$opening_balance,$remarks)){
			if($stmt->execute()){
				$json = [
					'status' => "success",
					'message' => "Success",
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
} else if (!empty($_POST['edit_id'])) {
	$name = $_POST['name'];
	$worker_type=$_POST['worker_type2'];
	$edit_id = $_POST['edit_id'];
	$address = $_POST['address'];
	$phone_no = $_POST['phone_no'];
	$opening_balance = $_POST['opening_balance']; 
	$remarks = $_POST['remarks'];
	$sql = "UPDATE tb_worker_details SET 
		name = ?,
		address = ?,
		phone_no = ?,
		opening_balance = ?,
		remarks = ?,
		worker_type=?
	WHERE worker_id = ?";
	if ($stmt = $conn->prepare($sql)) {
		if ($stmt->bind_param('ssssssi', $name,$address,$phone_no,$opening_balance,$remarks,$worker_type,$edit_id)) {
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
} else if(isset($_GET['delete_id'])) {
   	$delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM  tb_worker_details WHERE worker_id = ?";
    if ($stmt = $conn->prepare($sql)) {
    	if ($stmt->bind_param('i',$delete_id)) {
    		if ($stmt->execute()) {
    			$json['status'] = "success";
    			$json['message'] = "Success";
    		}
		}	
    }
} else if(isset($_GET['worker_type_id'])){
    $worker_type_id = $_GET['worker_type_id'];
	$sql = 'SELECT
		 worker_id as id,
		 name as text
	 FROM tb_worker_details 
	 WHERE worker_type = ?';
	if ($stmt = $conn->prepare($sql)) {
	 	$stmt->bind_param('i',$worker_type_id);
	 	$stmt->execute();
	 	$result=$stmt->get_result();
        foreach ($result as $row) {
			$json[] = $row;
	 	}
	}
} else {
	$sql = "SELECT  tb_worker_details.worker_id,
		tb_worker_details.name,
		tb_worker_details.worker_id as worker_id,
		tb_worker_details.address,
		tb_worker_details.phone_no,
		tb_worker_details.opening_balance,
		tb_worker_details.remarks,
		tb_worker.worker_type, 
		tb_worker.id AS worker_type_id
	FROM tb_worker_details
	LEFT JOIN tb_worker ON tb_worker_details.worker_type = tb_worker.id
	";
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