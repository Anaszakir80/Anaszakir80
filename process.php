<?php

function getLocationInfoByIp($ip){
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));    
    if($ip_data && $ip_data->geoplugin_countryName != null){
        $result = $ip_data->geoplugin_city.', '.$ip_data->geoplugin_countryCode;
    }
    return $result;
}

// Configure your Subject Prefix and Recipient here
$subjectPrefix = 'Form Submission';
$emailTo = 'info@360authors.com';
$errors = array(); // array to hold validation errors
$data = array(); // array to pass back data

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['leave']) && $_POST['leave'] != '') {
        header('Location: https://360authors.com/thankyou');
        exit();
    }
    $name    = stripslashes(trim($_POST['name']));
    $email   = stripslashes(trim($_POST['email']));
    $phone = stripslashes(trim($_POST['phone']));
    $ip = $_SERVER['REMOTE_ADDR'];
    $referrer = $_SERVER["HTTP_REFERER"];

    if(isset($_POST['msg'])) {
        $msg = stripslashes(trim($_POST['msg']));
    }
    if(isset($_POST['service'])) {
        $service = stripslashes(trim($_POST['service']));
    }
    if(isset($_POST['package']) && $_POST['package'] != '') {
        $package = stripslashes(trim($_POST['package']));
    }
     if(isset($_POST['intent']) && $_POST['intent'] != '') {
        $intent = stripslashes(trim($_POST['intent']));
    }
    // if there are any errors in our errors array, return a success boolean or false

    $subject = "$subjectPrefix";
    $body    = '
        <strong>Name: </strong>'.$name.'<br />
        <strong>Email: </strong>'.$email.'<br />
        <strong>Phone: </strong>'.$phone.'<br />';
    if(isset($msg)) {
        $body .= '<strong>Message: </strong>'.$msg.'<br />';
    }
    if(isset($service)) {
        $body .= '<strong>Service: </strong>'.$service.'<br />';
    }
    if(isset($package)) {
        $body .= '<strong>Package: </strong>'.$package.'<br />';
    }
    if(isset($intent)) {
        $body .= '<strong>Intent: </strong>'.$intent.'<br />';
    }
    $body .= '
        <strong>Submitted From: </strong>'.$referrer.'<br />
        <strong>IP Address: </strong>'.$ip.'<br />
        <strong>Location: </strong>'.getLocationInfoByIp($ip).'<br />
    ';
    $headers  = "MIME-Version: 1.1" . PHP_EOL;
    $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
    $headers .= "Content-Transfer-Encoding: 8bit" . PHP_EOL;
    $headers .= "Date: " . date('r', $_SERVER['REQUEST_TIME']) . PHP_EOL;
    $headers .= "Message-ID: <" . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>' . PHP_EOL;
    $headers .= "From: " . "Admin 360Autors" . "<admin@360authors.com>" . PHP_EOL;
    $headers .= "Return-Path: $emailTo" . PHP_EOL;
    $headers .= "Reply-To: $email" . PHP_EOL;
    $headers .= "X-Mailer: PHP/". phpversion() . PHP_EOL;
    $headers .= "X-Originating-IP: " . $_SERVER['SERVER_ADDR'] . PHP_EOL;
    mail($emailTo, "=?utf-8?B?" . base64_encode($subject) . "?=", $body, $headers);
    $data['type'] = 'success';
    $data['text'] = 'Your message has been sent successfully';
    
    // return all our data to an AJAX call
    header('Location: https://360authors.com/thankyou');
    exit();
}