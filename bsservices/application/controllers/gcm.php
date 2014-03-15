<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

define("GOOGLE_API_KEY", "AIzaSyDo3BcqK2HTNVs63GXAUIfi0qxiuYUo3EU");
define("GOOGLE_GCM_URL", "https://android.googleapis.com/gcm/send");

function send_gcm_notify($reg_id, $lat, $lng, $taskID) {

    $fields = array(
        'registration_ids' => array($reg_id),
        'data' => array(
            'lat' => $lat,
            'lng' => $lng,
            'taskID' => $taskID),
    );

    $headers = array(
        'Authorization: key=' . GOOGLE_API_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, GOOGLE_GCM_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Problem occurred: ' . curl_error($ch));
    }

    curl_close($ch);
    echo $result;
}
