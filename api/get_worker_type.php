<?php
header('Content-Type: application/json');
include_once './config.php';
$json = [];
if (isset($_POST['entry'])) {
	$worker_type = $_POST['worker_type'];
	if ($stmt =$conn->prepare("INSERT INTO tb_worker(worker_type) VALUES(?)")) {
		if ($stmt->bind_param("s",$worker_type)) {
			if ($stmt->execute()) {
				$json = [
					'status' => "success",
					'message' => "Success",
					'debug' => $conn->error
				];
			} else {
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
	} else {
		$json = [
			'status' => "error",
			'message' => "Error. Something Went Wrong",
			'debug' => $conn->error
		];
	}
} else if (!empty($_POST['type_edit_id'])) {
	$edit_id = $_POST['type_edit_id'];
	$worker_type = $_POST['worker_type'];
	$sql = "UPDATE tb_worker SET 
		worker_type = ?
	WHERE id = ?";
	if ($stmt = $conn->prepare($sql)) {
		if ($stmt->bind_param('si',$worker_type,$edit_id)) {
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
	}
	} else if (isset($_GET['type_delete_id'])) {
   	$delete_id = $_GET['type_delete_id'];
    $sql = "DELETE FROM  tb_worker WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
    	if ($stmt->bind_param('i',$delete_id)) {
    		if ($stmt->execute()) {
    			$json['status'] = "success";
    			$json['message'] = "Success";
    		}
		}	
    }
} else {
	$sql = "SELECT id, worker_type as text FROM tb_worker order by id";
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