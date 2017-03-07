<?php
        require_once('config.php');
        require_once('global.func.php');
	$t1 = microtime(true);
//	$url = "http://54.92.1.221:8200/controller";
	$url = "http://10.119.181.207:8200/controller";
	$text = isset($_POST["content"]) ? $_POST["content"] : "";//获得内容
       
	$sourceLang = "";//定义变量
	$targetLang = $_POST['targetLang'];
	if(!isset($_POST['sourceLang'])){//判断是否设置源语言
		$str = str_replace(PHP_EOL, ' ', $text);
		$language = `echo "$str" | curl -d @- 10.119.181.194:9008/detect`;
		$languages = json_decode($language);
		$sourceLang = $languages->responseData->language;
	}else{
		$sourceLang = $_POST['sourceLang'];
	}
	/*if($sourceLang == "zh"){
		 $text = str_replace(' ','%20',$text);
	}*/
	  //处理文本的内容
               $text = explode("\n",$text);
	//循环文本的内容，逐行翻译
	foreach($text as $v) {
		$response = array();
		if($v != ''){
			//如果源语言是中文的话，进行分词
//			if($sourceLang == "zh"){
//				$v = `curl "http://54.92.1.221:11200/?key=$v&format=simple"`;
//			}
			//调用java记忆库的接口
//			$memory_str = str_replace(' ','%20',$v);
//			$memory = `curl "http://54.92.1.221:8100/tmserver/$sourceLang/$targetLang/unit/$memory_str?min_similarity=90&max_candidates=1"`;
//			$memorys = json_decode($memory);
			if($sourceLang == "zh_news" || $sourceLang == "zh" || $sourceLang == "zh_edu" || $sourceLang == "zh_io"){
				$v = `echo "$v" | cconv -f UTF8-HK -t UTF8-CN `;
				//$v = `curl "http://54.92.1.221:11200/?key=$v&format=simple"`;
				if($targetLang != "pt")
					$v = `curl -G  "http://10.119.181.194:11200/" --data-urlencode "key=$v" -d "format=simple"`;
			}
//			if(!empty($memorys)){
//				$response['target'] = $memorys[0]->target;
//				$response['sign'] = 1;
//			} else {
//				$response['sign'] = 0;
				$q = array(
						"action" => "translate",
						"sourceLang" => $sourceLang,
						"targetLang" => $targetLang,
						"text" =>  $v,
						"alignmentInfo" =>isset($_POST["alignmentInfo"]) ? "true":"false",
#						"nBestSize"=>3,
						"nBestSize"=>1, #modify n-best size from 3 to 1 by ben: is it translate faster?
				);
				$responses = http_request($url, $q);//调用接口的方法
				$response = json_decode($responses,true);
//			}
			if(!isset($_POST['sourceLang'])){
				if(isset($config['language'][$sourceLang])){
					$response['sourceLang'] = $config['language'][$sourceLang];
				}else{
					$response['sourceLang'] = $config['language']['ot'];
				}
			}
			
			$response['source'] = $sourceLang;//源语言
			$t2 = microtime(true);
			$second=($t2-$t1)*1000;
			$response['timeflag']=$second;
			$response = json_encode($response);
		}else{
			$response = "";
		}
		echo $response."|||";
   	}
   
 ?>
