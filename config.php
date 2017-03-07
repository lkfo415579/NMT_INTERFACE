<?php
session_start();
if(isset($_GET['lang'])){
 	$_SESSION['lang'] = $_GET['lang'];
}
if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'en'){
	require_once 'languages/en_us.php';
}else{
	require_once 'languages/zh-cn.php';
}
$config = array(
	'language' => array(	
					'zh' =>  $_LANG['zh'],
					'en' =>  $_LANG['en'],
					'pt' =>  $_LANG['pt'],
//					'fr' =>  $_LANG['fr'],
//					'de' =>  $_LANG['de'],
//					'es' =>  $_LANG['es'],
//					'ru' =>  $_LANG['ru'],
//					'ar' =>  $_LANG['ar'],
//					'jp' =>  $_LANG['jp'],
//					'tr' =>  $_LANG['tr'],
					'ot' =>	$_LANG['ot'],
					),
//	'lang_field'=> array(
//					'General'=> $_LANG['General'],
//					'news'=> $_LANG['News'],
//					'edu' =>  $_LANG['UN'],
//					'io' =>  $_LANG['IO'],
					
//					),
	'upload_folder'		 => '/uploads',
);
?>
