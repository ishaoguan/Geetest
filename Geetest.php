<?php 
/**
 * 使用Get的方式返回：challenge和capthca_id 此方式以实现前后端完全分离的开发模式 专门实现failback
 * @author Tanxu
 */
//error_reporting(0);
require_once 'lib/class.geetestlib.php';

$fileName = @$_GET['gt'];

$idandkey = file_get_contents(md5($fileName));
if (strlen($idandkey) < 20) {
	echo "{}";
	return;
}

$CAPTCHA_ID = json_decode($idandkey)->CAPTCHA_ID;
$PRIVATE_KEY = json_decode($idandkey)->PRIVATE_KEY;

$GtSdk = new GeetestLib($CAPTCHA_ID, $PRIVATE_KEY);
session_start();

$data = array(
		"user_id" => "test", # 网站用户id
		"client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
		"ip_address" => $_SERVER['REMOTE_ADDR'] # 请在此处传输用户请求验证时所携带的IP
	);

$status = $GtSdk->pre_process($data, 1);
$_SESSION['gtserver'] = $status;
$_SESSION['user_id'] = $data['user_id'];
echo $GtSdk->get_response_str();
 ?>