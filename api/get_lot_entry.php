<?php
header('Content-Type: application/json');
include_once './config.php';
$json = [];
if(isset($_POST['entry'])) {
	$lot_no = $_POST['lot_no'];
	$design_no = $_POST['design_no'];
	$item = $_POST['item/desc'];
	$dozon = $_POST['dozon'];
	$quantity = $_POST['quantity'];
	$rate = $_POST['rate'];
	$amount = $_POST['amount'];
	$date=$_POST['date_time'];
	$sql = "INSERT INTO lot_details(
		lot_no,
		design_no,
		item,
		dozon,
		quantity,
		rate,
		amount,
		date_time
	) VALUES (?,?,?,?,?,?,?,?)";
	if($stmt = $conn->prepare($sql)){
		if($stmt->bind_param("sssiiiis", $lot_no,$design_no,$item,$dozon,$quantity,$rate,$amount,$date)){
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
	$edit_id = $_POST['edit_id'];
	$lot_no = $_POST['lot_no'];
    $design_no = $_POST['design_no'];
	$item = $_POST['item'];
	$dozon = $_POST['dozon'];
	$quantity = $_POST['quantity'];
	$rate = $_POST['rate'];
	$amount = $_POST['amount'];
	$date=$_POST['date_time'];
	$sql = "UPDATE lot_details SET 
		lot_no = ?,
		design_no=?,
		item=?,
		dozon=?,
		quantity=?,
		rate=?,
		amount=?,
		date_time=?
	WHERE id = ?";
	if ($stmt = $conn->prepare($sql)) {
		if ($stmt->bind_param("sssiiiisi", $lot_no,$design_no,$item,$dozon,$quantity,$rate,$amount,$date,$edit_id)) {
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
}  else if (isset($_GET['delete_id'])) {
   	$delete_id = $_GET['delete_id'];
   	$sql = "DELETE FROM  lot_details WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
    	if ($stmt->bind_param('i',$delete_id)) {
    		if ($stmt->execute()) {
    			$json['status'] = "success";
    			$json['message'] = "Success";
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
	} else {
		$json = [
			'status' => "error",
			'message' => "Error. Something Went Wrong",
		    'debug' => $conn->error
		]; 
	}
} else if (isset($_GET['lot_no'])){
	$lot_no2=$_GET['lot_no'];
	$sql = "SELECT * FROM lot_details WHERE id='$lot_no2'";
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
	$sql = "SELECT * FROM lot_details order by date_time";
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