<?php

function remote_request($url, $post_data = array(), $options = []) {

    $options += array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_ENCODING => '',
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_USERAGENT => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (K HTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36",
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_URL => $url,
    );

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);

    if( $post_data ) {

        if(is_array($post_data)) {
            curl_setopt_array($ch, array(
                CURLOPT_POST => count($post_data),
                CURLOPT_POSTFIELDS => $post_data,
            ));
        } else {
            curl_setopt_array($ch, array(
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $post_data,
            ));
        }

    }

    $result['response'] = curl_exec($ch);
    $result['info'] = curl_getinfo($ch);
    $result['errno'] = curl_errno($ch);
    $result['error'] = curl_error($ch);

    return $result;
}


	$uid = $_GET["user"];  //   User personal UID from Telegram
	$token = "XXX"; // Your API Token. Contact us @UMsupportTG to get a token.
	$url = "https://api.ibhldr.com:48480/v1/get-interests/";

	$request = [
		'uid' => $uid,
		'interests' => true
	];

	$options = [
		CURLOPT_HTTPHEADER => array("Content-Type: application/json", "Accept: application/json", "Authorization: Bearer ".$token)
	];

	$res = remote_request($url, json_encode($request), $options);  // CURL request

	$data = json_decode($res['response'], true);

	$result = array();
	if(isset($data['error'])) {
        $result[] = $data['error'];
    } else {
        foreach($data['interests'] as $category) {
            $cat_name = $category['b'];
            $result[] = $category['b'];
            if(isset($category['cs'])) {
                foreach($category['cs'] as $row) {
                    $result[] = " -- " . $row['c'];
                }
            }
        }
    }

	print_r(implode("<br>", $result));


?>