<?php
	 $sourceLang = $_POST['sourceLang'];//获得源语言
	 $targetLang = $_POST['targetLang'];//获得目标语言
	 $Src_Sen = $_POST['Src_Sen'];//获取源语言当前句子
	 $Src_Len = $_POST['Src_Len'];//获取源语言词长度
     $Tgt_Sen = $_POST['Tgt_Sen'];//获得目标语言句子
     $Tgt_Len = $_POST['Tgt_Len'];//获取目标语言词长度
     $default_str = "# Sentence pair (1) source length " .$Src_Len. " target length " .$Tgt_Len. " aligment";
     $str =  $default_str."\n".$Src_Sen."\n".$Tgt_Sen."\n"; 
     if($sourceLang == 'en' && $targetLang == 'zh'){
     	$file = fopen('user_edit/en-zh.bg', 'a+');
		fwrite($file,$str);
		fclose($file);
     }
	 $_POST['code'] = 'success';
	 echo json_encode($_POST);
?>
