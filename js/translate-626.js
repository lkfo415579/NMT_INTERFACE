/*!
Translate function for GTC
*/
$(function() {
	var trans_target_text = '';
	$( "#radioset" ).buttonset();
	$( "#radioset2" ).buttonset();
	$('#select, #select2').button({
	  text: false,
	  icons: {
		primary: "ui-icon-triangle-1-s"
	  }	
	 
	})
	.click(function() {
	 var menu = $( this ).parent().next().show().position({
			my: "left top",
			at: "left bottom",
			of: this
	  });
	 
	  $( document ).one( "click", function() {
		menu.hide();
	  });
	  return false;
	})
	.parent()
	  .buttonset()
	  .next()
		.hide()
		.menu();
		
	$('.animated').autosize({append: "\n"}).keyup(function(){
		//$('#trans_btn').trigger('click');
	});
	//右边文本框获得菜单里语言并赋值
	$("#ui-id-8 a").click(function(){
		var content = $(this).text();//获得菜单所对应的值
		var lang = $(this).attr("data-lang");//获得对应的语言
		$("#radioset2").find('label:last').prev().attr("id","to_"+lang);  
		$("#radioset2").find('label:last').prev().val(lang);
		$("#radioset2").find('label:last').attr("for","to_"+lang);
		$("#radioset2").find('label:last').find(".ui-button-text").text(content);
		$("#radioset2").find('label:last').prev().prop('checked',true).button("refresh");
	})
	//左边文本框获得菜单里语言并赋值
	$("#ui-id-1 a").click(function(){
		var content = $(this).text();//获得菜单所对应的值
		var lang = $(this).attr("data-lang");//获得对应的语言 
		$("#radioset").find('label:last').prev().prev().prev().prop('checked',true).button("refresh");
		$("#radioset").find('label:last').prev().prev().prev().attr("id","to_"+lang);  
		$("#radioset").find('label:last').prev().prev().prev().val(lang);
		$("#radioset").find('label:last').prev().prev().attr("for","to_"+lang);
		$("#radioset").find('label:last').prev().prev().find(".ui-button-text").text(content);
	})
	//翻译按钮的点击事件
	$('#trans_btn').click(function(){
		var _content = $('.animated').val();//获得文本框的内容
		if($.trim(_content)=='') return false; //输入内容为空就不处理
		if(!$('input[name="from"]').is(':checked')
			|| !$('input[name="to"]').is(':checked')) return false;
		
		var lang = $('input[name="from"]:checked').val();
		var data = {
					'content':_content,
					'sourceLang':lang,
					'targetLang':$('input[name="to"]:checked').val(),
					"alignmentInfo": "true"
				  };
		//判断是否是自动检测语言
		if (lang == "auto") {
			detect();
		}else{
			$.ajax({url:"translate.php",data:data,type:"post",success:function(data){
				show(data);
			}})
		}
	});
    //上传按钮的点击事件   
	$('#show_upload').click(function(){
		$('#upload_form').slideToggle();	
		$('#trans_source').slideToggle();	
		return false;
	});
	// 默认语言
	$('input[name="from"][value="auto"]').prop('checked',true).button("refresh");
	$('input[name="to"][value="en"]').prop('checked',true).button("refresh");
	
  });
 /*
  *  在目标语言框里翻译相关内容
  * (此方法是处理后台返回的json数据) 
  */
  var k = 0;
  function show(data) {
   	  try { 
			var data_arr = new Array();
			data_arr = data.split('|||');//处理后台的json数据
			var text1 = "";
			//对齐信息初始化
			$("#source_layer").empty();
			$("#trans_target").empty();
			$("#align").empty();
			for (var d=0;d<data_arr.length;d++ ) 
			 {
					var _arr = data_arr[d];
					if(_arr != ""){
						var v = $.parseJSON(_arr);
						if(v.sourceLang) {
							var lan = 'Detect '+ v.sourceLang;
							//为检测语言的按钮赋相应的语言
							if($("label[for=from_auto]").find(".ui-button-text").text() != lan) {
								
						        $("label[for=from_auto]").find(".ui-button-text").text('Detect '+ v.sourceLang);
							}
						  	}else{
						  		$("label[for=from_auto]").find(".ui-button-text").text('Detect language');
						  	}
						if (v.errorCode != 0)
						{
						    //判断检测的语言是否和目标语言相同，若相同，原样输出
						   if(v.source == $('input[name="to"]:checked').val()){
								var str = $('.animated').val();
								var reg=new RegExp("\n","g"); 
								str= str.replace(reg,"<br/>"); 
								$("#trans_target").html(str);
						   }else{
							    //输出对应的错误信息
								$("#trans_target").html('Error ' + v.errorCode + ': ' + v.errorMessage);
						   }
						   return;
						} else {
							var sentences = v['translation'][0]['translated'];
							var k = 0;
							if ($("#source_layer").html()!="") {
								$("#source_layer").html($("#source_layer").html()+ "<br>");
								$("#trans_target").html($("#trans_target").html()+ "<br>");
							 }
							$("#align").append('<h1>Phrase alignment information (mouse hover)</h1>');
							sentences.forEach(showSentenceAlignment);
							// Hovers
							setupHovers();
							$("#align").fadeIn('slow');
						}
				 }else {
					text1 = "<br>";
					$("#source_layer").html($("#source_layer").html() + text1);
					$("#trans_target").html($("#trans_target").html() + text1);
					setupHovers();
			   }
		  }
		
    }
    catch(exc){ alert(exc); /*for(key in data){alert(key);}*/ }
  }
  /*正向最大匹配机械分词*/
  function max_match_words(word_dict,src_sentence){
      /*
       * @description 格式化词库,按长度分成若干组，并按长度倒序排列
       * @param {Array of String} dict 全局词库
       * @return {Object} 格式化后的词库
       */
       var format_dict = function (dict) {
           //1、把词库按长度分成若干组,key为词长度，value为此长度的词列表
           var map = {};
           for (var i = 0; i < dict.length; i++) {
        	   var word = dict[i];
        	   word = $.trim(word); 
               if (!map[word.length])
                   map[word.length] = [];
               map[word.length].push(word);
           }

           //2、声明一个长度数组，按词的长度倒序排列
           var len_array = [];
           for (var len in map) {
               len_array.push(len);
           }
           len_array.sort(function (a, b) { return parseInt(b) - parseInt(a) });

           //3、声明一个词库数组，每个元素是一组等长词，且按长度倒序排列
           var result = [];
           for (var i = 0; i < len_array.length; i++) {
               var key = len_array[i];
               result.push(map[key]);
           }
           
           return result;
       };
      /*
       * @description 从格式化词库里找到一个最长的匹配
       * @param {String} input 进行匹配的字符串
       * @param {int} pos 匹配字符串的匹配位置
       * @param {Object} dict，格式化词库
       * @return {String} 如果匹配成功，返回匹配的单词，否则返回空字符串
       */
       var match_words = function (input, pos, dict) {
           for (var i = 0; i < dict.length; i++) {
               var word_group = dict[i];
               for (var j = 0; j < word_group.length; j++) {
                   var word = word_group[j];
                   if (input.substr(pos, word.length) === word)
                       return word;
               }
           }
           return "";
       }
       /*
       * 进行正向最大匹配机械分词
       */
       String.prototype.SplitWords = function () {
           var result = [], pos = 0, len = this.valueOf().length;
			
           var formated_dict = format_dict(word_dict);
			while (pos < len) {
				var match_word = match_words(this.valueOf(), pos, formated_dict);
				if (match_word.length > 0) {
                   result.push(match_word);
                   pos += match_word.length;
               }
               else {
                   result.push(this.valueOf()[pos]);
                   pos = pos + 1;
               }
           }
		  
           return result;
       }
       var sens = src_sentence.SplitWords().join('@');
       var new_sens = sens.split('@ @');
       return new_sens;
		
  }
  
  /*获得对齐信息*/
  function showSentenceAlignment(sen) {
		// computing zipped alignment 这个获得对齐信息
		align = sen['alignment-raw'];//对齐信息
		src_words = sen['src-tokenized'].split(' ');//源语言
		tgt_words = sen['tgt-tokenized'].split(' ');//目标语言
		zip = [];
		var phrase_zip = [];
		for (var i=0; i< align.length; i++){
			a = align[i];
			zip.push([tgt_words.slice(a['tgt-start'], a['tgt-end'] + 1).join(' '), 
					src_words.slice(a['src-start'], a['src-end'] + 1).join(' ')]);
			//新建一个数组，重新获得源语言的词库
			phrase_zip.push([src_words.slice(a['src-start'],a['src-end'] + 1).join(' ')]);
		}
		//phrase_zip.push(' ');
		//调用正向最大匹配分词方法
		var new_phrase_zip = max_match_words(phrase_zip,sen['src-tokenized']);
		// displaying the zipped alignment
		var textSrc="";
		var textTgt="";
		var html  = '<div class="sentence" >';
		for (var j=1; j>=0; j--) {
				html += '<div class="line">';
				for (var i=0; i<zip.length; i++) {
						var token = zip[i][j];
						if (j==1) {
								var temp_token = new_phrase_zip[i];
								var temp_src_words = temp_token.split(' ');
								//获得用户输入的文本
								var user_text = $("#news_title").val().split(' ');
								//如果源语言是中文的话，去除空格!
								//源语言自动检测为中文的话，也需要去除空格(待处理!!!!!)
								if($('input[name="from"]:checked').val() == 'zh' ){
									temp_token = temp_token.replace(/[ ]/g,"");
									token = token.replace(/[ ]/g,"");
									if(token == temp_token){
										textSrc  += '<span class="tokenized_token"  tokenorder="' + (i + 10000*k) + '">' + token + '</span>';
									}else{
										textSrc  += '<span class="tokenized_token"  tokenorder="' + (i + 10000*k) + '" >' + temp_token + '</span>';
									}
								}else{
									if(token == temp_token){
										var token_src_words = token.split(' ');
										if(token_src_words[i] !== user_text[i]){
											token_src_words[i] = user_text[i];
											token = token_src_words.join(" ");
										}
										textSrc  += '<span class="tokenized_token"  tokenorder="' + (i + 10000*k) + '">' + token + ' </span>';
									}else{
										textSrc  += '<span class="tokenized_token"  tokenorder="' + (i + 10000*k) + '" >' + temp_token + ' </span>';
									}
									
								}
						} else {
							if($('input[name="to"]:checked').val() == 'zh'){
								//如果目标语言是中文的话，去除空格!
								token = token.replace(/[ ]/g,"");
								textTgt += '<span class="tokenized_token"  tokenorder="' + (i + 10000*k) + '">' + token + '</span>';
							}else{
								textTgt += '<span class="tokenized_token"  tokenorder="' + (i + 10000*k) + '">' + token + ' </span>';
							}
						}
						html +=  '<span class="token" onclick="save(this);" tokenorder="' + (i + 10000*k) + '">' + token + '</span>';
					}
				html += '</div>';
			}
			html += '</div>';
		$("#source_layer").html($("#source_layer").html()+ textSrc)
		$("#trans_target").html($("#trans_target").html()+ textTgt);
		$("#align").html($("#align").html() + html);
		k++;
}
/*鼠标滑过对齐信息触发的事件*/
function setupHovers() {
	$("span.token").hover(
		function() {
				var x = $(this);
				var tokenOrder = x.attr('tokenorder');
				$('[tokenorder = ' + tokenOrder + ']').addClass("hover");
		},
		function() {
				var x = $(this);
				var tokenOrder = x.attr('tokenorder');
				$('[tokenorder = ' + tokenOrder + ']').removeClass("hover");
			}
	  );
	  
	  $("span.tokenized_token").hover(
		function() {
				var x = $(this);
				var tokenOrder = x.attr('tokenorder');
				$('[tokenorder = ' + tokenOrder + ']').addClass("hover");
		},
		function() {
				var x = $(this);
				var tokenOrder = x.attr('tokenorder');
				$('[tokenorder = ' + tokenOrder + ']').removeClass("hover");
			}
	  );
}
/*自动识别语言*/
 function detect(){
	var _content = $('.animated').val();
	_content = _content.replace("|||", "&bar;&bar;&bar");
	 var query = { 
			"action":"translate",
            "content": _content,
            "targetLang":$('input[name="to"]:checked').val(),
            "alignmentInfo": "true" };
	$.post("translate.php",
            query,
            function(data) { show(data); });
			
	}
/*对齐信息可编辑 暂时不用*/
function save(obj){
	var tdObj = $(obj);
	var Text = tdObj.text(); // 获取原来的内容
	var input = $("<input type='text' value='" + Text + "' style='font-size:15px' />");//获得input框
	input.click(function() { // 设置文本框的点击事件
      	return false;
    });
    tdObj.html(input);//将文本框替换成当前的内容
    // 触发文本框的focus事件后再触发select事件
    input.trigger("focus").trigger("select");
    // 文件框的焦点失去事件, 把文本框中填写的内容变成
    input.blur(function() {
         if(input.val()!=""){
				var New_Text = input.val();
                $.ajax({url:"save_align_text.php",type:"post",data:"New_Text="+New_Text,dataType:"json",success:function(data){
					if(data.code == "success"){
						//tdObj.html(input.val());
						tdObj.html(data.New_Text);
						alert("修改成功");
					} 
				}
			});
          }else{
                 tdObj.html(Text);
          }
    });
}
/*右边按钮触发的事件*/
function to_detect(){
	$('#trans_btn').click();
}
/*左边按钮触发的事件*/
function from_detect(){
	$('#trans_btn').click();
	$("label[for=from_auto]").find(".ui-button-text").text('Detect language');	
}
