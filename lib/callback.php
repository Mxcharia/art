<?php
include 'sql.php'; // Include the Mysql class file

$orderNo = isset($_SESSION['orderNo']) ? $_SESSION['orderNo'] : '';

$content = file_get_contents('php://input'); //Receives the JSON Result from Safaricom
$res = json_decode($content, true); //Convert the JSON to an array

$dataToLog = array(
  date("Y-m-d H:i:s"), //Date and time
  " MerchantRequestID: " . $res['Body']['stkCallback']['MerchantRequestID'],
  " CheckoutRequestID: " . $res['Body']['stkCallback']['CheckoutRequestID'],
  " ResultCode: " . $res['Body']['stkCallback']['ResultCode'],
  " ResultDesc: " . $res['Body']['stkCallback']['ResultDesc'],
);

$data = implode(" - ", $dataToLog);
$data .= PHP_EOL;
file_put_contents('/srv/http/art/uploads/transaction_log', $data, FILE_APPEND); //Logs the results to our log file

$mysql = new Mysql();
$result = $mysql->selectwhere('stk_transactions', 'MerchantRequestID', '=', $res['Body']['stkCallback']['MerchantRequestID']);
while ($stk_transaction = mysqli_fetch_assoc($result)) {
  $orderNo = $stk_transaction['order_no'];
  if ($res['Body']['stkCallback']['ResultCode'] == "0") {
    $rs = $mysql->updateOrderStatus(1, $orderNo);
    unset($_SESSION['orderNo']);
  } else {
    $rs = $mysql->updateOrderStatus(0, $orderNo);
    unset($_SESSION['orderNo']);
  }

  if ($rs) {
    file_put_contents('/srv/http/art/uploads/error_log', "Records Inserted\n", FILE_APPEND);
  } else {
    file_put_contents('/srv/http/art/uploads/error_log', "Failed to insert Records\n", FILE_APPEND);
  }
}
