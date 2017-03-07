<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
	require_once('config.php');
	require_once('global.func.php');
	$url = "http://192.168.2.231:8200/server";
	// Define a destination
	$targetFolder = $config['upload_folder']; 
	if (1) {
		$tempFile = $_FILES['Filedata']['tmp_name'];
		//$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
		//$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
		$targetFile = './uploads/' . $_FILES['Filedata']['name'];
		// Validate the file type
		$fileTypes = array('txt'); // File extensions
		$fileParts = pathinfo($_FILES['Filedata']['name']);
		$texts = "";
		if (in_array($fileParts['extension'],$fileTypes)) {
			move_uploaded_file($tempFile,$targetFile);
			$handle = fopen($targetFile,'r');
			if($handle) {
				
				while (!feof($handle)) {
					$buffer = fgets($handle); 
					$buffer = iconv('gb2312', 'utf-8', $buffer);
					$brarray = explode("\n",$buffer);
					foreach($brarray as $content){
						$texts .= $content;
					}
				}
				fclose($handle);
		   }
		} else {
			echo 'Invalid file type.';
		}
		$sourceLang = "";
		if($_POST['sourceLang'] == 'auto'){//判断是否设置源语言
			$texts = str_replace(PHP_EOL, ' ', $texts);
			$language = `echo "$texts" | curl -d @- 192.168.2.237:9008/detect`;
			$languages = json_decode($language);
			$sourceLang = $languages->responseData->language;
		}else{
			$sourceLang = $_POST['sourceLang'];
		}
		if ($sourceLang == "zh") {
			$texts = `curl "http://127.0.0.1:11200/?key=$texts&format=simple"`;
		}
		$q = array(
					"action" => "translate",
					"sourceLang" => $sourceLang,
					"targetLang" => $_POST['targetLang'],
					"text" =>  $texts,
					"alignmentInfo" =>isset($_POST["alignmentInfo"]) ? "true":"false",
		);
		$responses = http_request($url, $q);//调用接口的方法
		$response = json_decode($responses,true);
		if($_POST['sourceLang'] == 'auto'){
			if(isset($config['language'][$sourceLang])){
					$response['sourceLang'] = $config['language'][$sourceLang];
			}
		}
		$response['sign'] = 0;
		$response['source'] = $sourceLang;//源语言
		$response = json_encode($response);
		print $response;
   }
  ?>

