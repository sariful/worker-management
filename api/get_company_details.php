<?php
header('Content-Type: application/json');
include_once './config.php';
$json = [];
if(isset($_POST['entry'])) {
	$name = $_POST['name'];
	$address = $_POST['Address'];
	$mobile = $_POST['Mobile'];
	$email=$_POST['Email'];
	$gst=$_POST['gst'];
	$extension=$_POST['Extension'];
	$bank_name=$_POST['Bank_Name'];
	$bank_ac_no=$_POST['Bank_Ac_No'];
	$bank_ifsc=$_POST['Bank_IFSC'];
	$bank_rtgs=$_POST['Bank_RTGS'];
	$invoice_suffix=$_POST['Invoice_Suffix'];
	$invoice_prefix=$_POST['Invoice_Prefix'];
	$print_setup=$_POST['Print_Setup'];
	$sql = "INSERT INTO company_details(
		name, 
		address,
		mobile,
		email,
		gst,
		extension,
		bank_name,
		bank_ac_no,
		bank_ifsc,
		bank_rtgs,
		invoice_suffix,
		invoice_prefix,
		print
	) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
	if($stmt = $conn->prepare($sql)){
		if($stmt->bind_param("ssisssssssssi", $name, $address,$mobile,$email,$gst,$extension,$bank_name,$bank_ac_no,$bank_ifsc,$bank_rtgs,$invoice_suffix,$invoice_prefix,$print_setup)){
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
	 $address = $_POST['Address'];
	 $Mobile = $_POST['Mobile'];
	 $Email = $_POST['Email'];
	 $gst = $_POST['gst'];
	 $Extension = $_POST['Extension'];
	 $Print_Setup=$_POST['Print_Setup'];
	 $Bank_Name = $_POST['Bank_Name'];
	 $Bank_Ac_No = $_POST['Bank_Ac_No'];
	 $Bank_IFSC = $_POST['Bank_IFSC'];
	 $Bank_RTGS = $_POST['Bank_RTGS'];
	 $Invoice_Suffix = $_POST['Invoice_Suffix'];
	 $Invoice_Prefix = $_POST['Invoice_Prefix'];
	 $Print_Setup=$_POST['Print_Setup'];
	 $edit_id = $_POST['edit_id'];
 $sql = "UPDATE company_details SET 
	 name=?,
	 address = ?,
	 mobile=?,
	 email=?,
	 gst=?,
	 extension=?,
	 bank_name=?,
	 bank_ac_no=?,
	 bank_ifsc=?,
	 bank_rtgs=?,
	 invoice_suffix=?,
	 invoice_prefix=?,
	 print=?
 WHERE id = ?";
 if ($stmt = $conn->prepare($sql)) {
 	if ($stmt->bind_param('ssissssssssssi',$name,$address,$Mobile,$Email,$gst,$Extension,$Bank_Name,$Bank_Ac_No,$Bank_IFSC,$Bank_RTGS,$Invoice_Suffix,$Invoice_Prefix,$Print_Setup,$edit_id)) {
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
} else {
	$sql = "SELECT company_details.id,
		print_setup.text,
		print_setup.id as print_id,
		company_details.address,
		company_details.mobile,
		company_details.name,
		company_details.email,
		company_details.gst,
		company_details.extension,
		company_details.bank_name,
		company_details.bank_ac_no,
		company_details.bank_ifsc,
		company_details.bank_rtgs,
		company_details.invoice_suffix,
		company_details.invoice_prefix
	FROM company_details
	LEFT JOIN print_setup ON company_details.print = print_setup.id
	";
	if ($result = $conn->query($sql)) {
		foreach ($result as $row) {
			$json['data'] = $row;
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