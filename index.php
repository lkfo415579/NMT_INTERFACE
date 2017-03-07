<?php
require_once('config.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta property="wb:webmaster" content="37222c7b000110b7" />
		
		<title><?php echo $_LANG['title']?></title>
		
		<!-- CSS -->
		<link href="css/bootstrap3.css" rel="stylesheet">
		<link href="css/jquery-ui-1.10.4.custom3.css" rel="stylesheet">
		<link href="css/jumbotron3.css" rel="stylesheet">
		<link href="js/uploadify.css" rel="stylesheet">
		
		<script src="js/jquery-1.10.2.js" type="text/javascript"></script>
		<script src="js/jquery-ui-1.10.4.custom.js" type="text/javascript"></script>
		<script src="js/jquery.autosize.js" type="text/javascript"></script>
		<script src="js/jquery.uploadify.min.js" type="text/javascript"></script>
		
		<script type="text/javascript" src="js/jquery.zclip.min.js"></script>
		<script type="text/javascript" src="js/copy_target.js"></script>
		<script type="text/javascript">
			var now=new Date(); 
			var number = now.getYear().toString()+now.getMonth().toString()+now.getDate().toString()+now.getHours().toString()+now.getMinutes().toString()+now.getSeconds().toString(); 
			document.write("\<script language=\"javascript\" type=\"text/javascript\" src=\"js/translate.js?"+number+"\"><\/script\>"); 
		</script>
	</head>
	<body>
		<!-- Logo Container --> 
		<div class="container">
			<a class="navbar-brand" href="http://nlp2ct.cis.umac.mo"><img src="images/Logo_lab2.png" alt="NLP2CT" /></a>
		</div>
		<!-- Translation Container --> 
		<div class="container">
			<div class="row">
				<!-- Left Part --> 
				<div class="col-lg-6">
					<form style="margin-top: 1em;">
						<input type="hidden" name="check" id="check" value="<?php echo $_LANG['check']?>" />
						<input type="hidden" name="check_old" id="check_old" value="<?php echo $_LANG['check_lang']?>" />
						<input type="hidden" name="align_next" id="align_next" value="<?php echo $_LANG['align']?>" />
						<input type="hidden" id="lang_field_zh" value="<?php echo $_LANG['zh'];?>" />
						<input type="hidden" id="lang_field_en" value="<?php echo $_LANG['en'];?>" />
						<input type="hidden" name="trans_msg_1" id="trans_msg_1" value="<?php echo $_LANG['trans_msg_1']?>" />
						<input type="hidden" name="trans_msg_2" id="trans_msg_2" value="<?php echo $_LANG['trans_msg_2']?>" />
						<input type="hidden" name="trans_msg_3" id="trans_msg_3" value="<?php echo $_LANG['trans_msg_3']?>" />
						<input type="hidden" name="trans_msg_4" id="trans_msg_4" value="<?php echo $_LANG['trans_msg_4']?>" />
						<!-- lang_field start -->
						<!-- lang_field end -->
						<div id="radioset">
							<?php
								$index = 0;
								$from_languages = '';
								foreach($config['language'] as $key=>$lang) {
									if($index++>2){
										$from_languages .= '<li><a href="javascript:void(0);" data-lang="'.$key.'">'.$lang.'</a></li>';
									}else {
							?>
										<input type="radio" id="from_<?=$key?>" name="from" value="<?=$key?>" onclick="from_detect();" />
										<label for="from_<?=$key?>"><?=$lang?></label>
							<?php								
									}
								}
							?>
						
							<input type="radio" id="from_auto" name="from" value="auto" class="ui-helper-hidden-accessible" onclick="javascript:detect();" />
							<label for="from_auto"><?php echo $_LANG['check_lang'];?></label>
							<button id="select"><?php echo $_LANG['select_lang'];?></button>
							<div id="gt-swap" class="trans-swap-button jfk-button button-standard swap-button-standard" role="button" tabindex="0" data-tooltip="调转两种语言" aria-label="调转两种语言" aria-disabled="true" style="-webkit-user-select: none;display:none;" >
								<span class="jfk-button-img-swap">
									<img src="images/swap_1.png" title="调转语言" class="swap_img">
								</span>
							</div>
							<div id="gt-swap-disabled" class="trans-swap-button jfk-button button-standard swap-button-standard" role="button" tabindex="0" data-tooltip="调转两种语言" aria-label="调转两种语言" aria-disabled="true" style="-webkit-user-select: none;">
								<span class="jfk-button-img">
									<img src="images/swap_2.png" title="调转语言" class="swap_img">
								</span>
							</div>
						</div>
						<ul>
							<?=$from_languages?>
							<li><a href="javascript:void(0);" data-lang="pt"><?php echo $_LANG['pt'];?></a></li>
							<li><a href="javascript:void(0);" data-lang="en"><?php echo $_LANG['en'];?></a></li>
							<li><a href="javascript:void(0);" data-lang="zh"><?php echo $_LANG['zh'];?></a></li>
						</ul>
						<div id="trans_source">
							<textarea class="form-control animated" id="news_title"></textarea>
						</div>
					</form>
					<div class="notice">						
						<!--<span style="float:right;">-->
							<button type="button" class="btn btn-primary" id="trans_btn"><?php echo $_LANG['translate'];?></button>
						<!--</span>-->
					</div>
				</div>
				<!-- Right Part --> 
				<div class="col-lg-6">
					<form style="margin-top: 1em;">
						<div id="radioset2">
							<?php
								$index = 0;
								$to_languages = '';
								foreach($config['language'] as $key=>$lang) {
									if($index++>2){
										$to_languages .= '<li><a href="javascript:void(0);" data-lang="'.$key.'">'.$lang.'</a></li>';
									}else {
							?>
								<input type="radio" id="to_<?=$key?>" name="to" value="<?=$key?>" onclick="to_detect();" />
								<label for="to_<?=$key?>"><?=$lang?></label>
							<?php								
									}
								}
							?>
							<button id="select2"><?php echo $_LANG['select_lang'];?></button>
						</div>
						<ul>
							<?=$to_languages?>
							<li><a href="javascript:void(0);" data-lang="pt"><?php echo $_LANG['pt'];?></a></li>
							<li><a href="javascript:void(0);" data-lang="en"><?php echo $_LANG['en'];?></a></li>
							<li><a href="javascript:void(0);" data-lang="zh"><?php echo $_LANG['zh'];?></a></li>
						</ul>
					</form>
					<div type="text" value="" class="trans_target" id="trans_target"></div>
					<div class="trans-msg"></div>
					<div class="output-copy">
						 <a href="javascript:void(0)" id="cp-btn" title="复制"><img src="images/copy.gif" alt="output-copy" /></a> 
					</div>
				</div>
			</div>
			<!-- NMT align-->
			<div id="nmt-select"> </div>
			<div id="nmt-align"> </div>
			<!-- Align Part-->
			<div id="align-wrap">
			  <div id="align"></div>
			</div> 

			<!-- <hr />-->
			
			<!-- Footer Part -->
			<div class="footer">

				<p align="right">
				<!--<a href="<?php echo $_SERVER['PHP_SELF']; ?>?lang=cn" target="_self">中文版</a>-->
				<!--<a href="<?php echo $_SERVER['PHP_SELF']; ?>?lang=en" target="_self">English Version</a>-->
                                <a href="http://nlp2ct.cis.umac.mo/MT/index.php?lang=cn" target="_self">中文版</a>
				<a href="http://nlp2ct.cis.umac.mo/MT/index.php?lang=en" target="_self">English Version</a>
				</p>

				<hr />

				<p align="center">
					Copyright©&nbsp;&nbsp;2012-2017 <a href="http://nlp2ct.cis.umac.mo/">NLP<sup>2</sup>CT Lab</a><br />Natural Language Processing &amp; Portuguese-Chinese Machine Translation Laboratory
					<a href="http://nlp2ct.cis.umac.mo/">自然語言處理與中葡機器翻譯實驗室</a>
					<br />
					<br />
					<a href="http://www.umac.mo"><img src="images/um_logo_bw_vp2.png" alt="University of Macau" style="border-style: none" /></a>				
				</p>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#zclip-ZeroClipboardMovie_1").attr("title","复制");
			})
		</script>
	</body>
</html>
