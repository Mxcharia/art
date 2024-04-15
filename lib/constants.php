<?php
class Constants
{
  // represent admins
  const ADMIN = 1;
  // represent users 
  const USER = 2;
  // represent users that are gallery owners
  const GALLERY = 3;

  public static $config = array(
    "env"              => "sandbox",
    "BusinessShortCode" => "174379",
    "key"              => "Ifln1ghFyYgk9vA7FWbrGmpLCQIjh5VU9Jtqp2g7sYiAc0f5",
    "secret"           => "O0AcxXjOUFm8Z1hwbxd1fy1jX9IK6ilzOj3SQ1Z3ywUV1KZZX2ObgDpJMLoqwYbz",
    "TransactionType"  => "CustomerPayBillOnline",
    "passkey"          => "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919",
    "CallBackURL"      => "https://8867-41-60-236-129.ngrok-free.app/art/lib/callback.php", //When using localhost, Use Ngrok to forward the response to your Localhost
    "AccountReference" => "ARTZZ LTD",
    "TransactionDesc"  => "Payment",
  );
}
