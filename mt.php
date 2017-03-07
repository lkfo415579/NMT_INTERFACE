<?php
		header('Content-type: text/html; charset=utf-8');
		require_once('global.func.php');
		$sourceLang = $_POST['sourceLang'];
		$targetLang = $_POST['targetLang'];
		$text = $_POST["content"];//获得内容
		if (!isset($sourceLang) || $sourceLang == ''|| !isset($targetLang) || $targetLang == '' || !isset($text) || $text == ''){
			echo json_encode(array('errorCode' => 400, 'errorMessage' => "Parameter is not valid"));
			exit();
		}
		//调用java记忆库的接口
		$memory_str = str_replace(' ','%20',$text);
		$memory = `curl "http://172.31.7.194:8100/tmserver/$sourceLang/$targetLang/unit/$memory_str?min_similarity=90&max_candidates=1"`;
		$memorys = json_decode($memory);
		$data = array();
		if(!empty($memorys)){
			$data['target'] = $memorys[0]->target;
		} else {
		         /*if($sourceLang == "zh"){
                                //$v = `curl "http://54.92.1.221:11200/?key=$v&format=simple"`;
                                $text = `curl -G  "http://54.92.1.221:11200/" --data-urlencode "key=$text" -d "format=simple"`;
			}*/
			$q = array(
					"action" => "translate",
					"sourceLang" => $sourceLang,
					"targetLang" => $targetLang,
					"text" =>  $text,
					"alignmentInfo" =>isset($_POST["alignmentInfo"]) ? "true":"false",
			);
			$url = "http://54.92.1.221:8200/controller";
			$responses = http_request($url, $q);//调用接口的方法
			$response = json_decode($responses,true);
			if ($response['errorCode'] == 0){
				$target = $response['translation'][0]['translated'];
				$target_data = array();
				foreach($target as $k => $v){
					$target_data[$k]=$v['text'];
				}
			}else{
				echo json_encode(array('errorCode' => $response['errorCode'] , 'errorMessage' => $response['errorMessage']));
				exit();
			}
			if($_POST["alignmentInfo"] == 'true'){
				$data['translation'] = $response['translation'];
			}else{
				$data['target'] = $target_data;
			}
		}
		$data = json_encode($data);
		echo $data;
	
 ?>
