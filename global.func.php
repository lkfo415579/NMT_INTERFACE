<?php
	/**
	 * 调用接口公共方法
	 */
	function http_request($url,$post = array()){
        $curl = curl_init($url);
        $opts = array(
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => array('Content-Type: application/json; charset=utf-8'),
                CURLOPT_POSTFIELDS => json_encode($post)
        );
        curl_setopt_array($curl, $opts);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
	}
	
?>