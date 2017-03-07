/*!
Translate function for GTC
*/
$(document).ready(function(){
	if(document.getElementById('news_title').addEventListener){
		document.getElementById('news_title').addEventListener('input', fanyi_change, false);
	}else{
		document.getElementById('news_title').attachEvent('onpropertychange', function(){if(window.event.propertyName == "value"){fanyi_change()}})
	}
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
		sel_lan_right();
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
	$("#ui-id-10 a").click(function(){
		$(this).addClass("hover-select").parent().siblings().find("a").removeAttr("class");
		var content = $(this).text();//获得菜单所对应的值
		var lang = $(this).attr("data-lang");//获得对应的语言
		$("#radioset2").find('label:last').prev().attr("id","to_"+lang);  
		$("#radioset2").find('label:last').prev().val(lang);
		$("#radioset2").find('label:last').attr("for","to_"+lang);
		$("#radioset2").find('label:last').find(".ui-button-text").text(content);
		$("#radioset2").find('label:last').prev().prop('checked',true).button("refresh");
	});
	//左边文本框获得菜单里语言并赋值
	$("#ui-id-1 a").click(function(){
		$(this).addClass("hover-select").parent().siblings().find("a").removeAttr("class");
		var content = $(this).text();//获得菜单所对应的值
		var lang = $(this).attr("data-lang");//获得对应的语言 
		$("#radioset").find('label:last').prev().prev().prev().prop('checked',true).button("refresh");
		$("#radioset").find('label:last').prev().prev().prev().attr("id","from_"+lang);  
		$("#radioset").find('label:last').prev().prev().prev().val(lang);
		$("#radioset").find('label:last').prev().prev().attr("for","from_"+lang);
		$("#radioset").find('label:last').prev().prev().find(".ui-button-text").text(content);
	});
	//左边文本框获得子菜单里的领域语言并赋值
	$(".lang_menu").find("a").click(function(){
		var lang  = $(this).parents("div").attr("lang");
		var content = $(this).text();
		var _lang = $(this).attr("data-lang");
		if(lang.substr(0,2) == 'zh'){
			if(_lang == 'General') {
				var lang_zh = $("#lang_field_zh").val();
				$("#radioset").find('label:eq(0)').prev().attr("id","from_zh");
				$("#radioset").find('label:eq(0)').prev().val("zh");
				$("#radioset").find('label:eq(0)').attr("for","from_zh");
				$("#radioset").find('label:eq(0)').find(".ui-button-text").text(lang_zh);
				//$("#radioset").find('label:eq(0)').prev().prop('checked',true).button("refresh");
			} else {
				$("#radioset").find('label:eq(0)').prev().attr("id","from_zh_" + _lang);
				$("#radioset").find('label:eq(0)').prev().val("zh_" + _lang);
				$("#radioset").find('label:eq(0)').attr("for","from_zh_" + _lang);
				$("#radioset").find('label:eq(0)').find(".ui-button-text").text(content);
			}
			$("#radioset").find('label:eq(0)').prev().prop('checked',true).button("refresh");
		}else{
		     if(_lang == 'General') {
				var lang_en = $("#lang_field_en").val();
				$("#radioset").find('label:eq(1)').prev().attr("id","from_en");
				$("#radioset").find('label:eq(1)').prev().val("en");
				$("#radioset").find('label:eq(1)').attr("for","from_en");
				$("#radioset").find('label:eq(1)').find(".ui-button-text").text(lang_en);
				//$("#radioset").find('label:eq(0)').prev().prop('checked',true).button("refresh");
		     } else {
				$("#radioset").find('label:eq(1)').prev().attr("id","from_en_" + _lang);
				$("#radioset").find('label:eq(1)').prev().val("en_" + _lang);
				$("#radioset").find('label:eq(1)').attr("for","from_en_" + _lang);
				$("#radioset").find('label:eq(1)').find(".ui-button-text").text(content);
		   }			
		  $("#radioset").find('label:eq(1)').prev().prop('checked',true).button("refresh");
		}
	});
	//翻译按钮的点击事件
	$('#trans_btn').click(function(){
		var _content = $('.animated').val();//获得文本框的内容
		if($.trim(_content)=='') return false; //输入内容为空就不处理
		if(_content.length >=3000){
			alert("不允许超过3000字符");
			return false;
		}
		if(!$('input[name="from"]').is(':checked')
			|| !$('input[name="to"]').is(':checked')) return false;
		
		var lang = $('input[name="from"]:checked').val();
		var targetLang = $('input[name="to"]:checked').val();
		var data = {
					'content':_content,
					'sourceLang':lang,
					'targetLang':targetLang,
					"alignmentInfo": "true"
				  };
		//判断是否是自动检测语言
		if(lang.substr(0,2) == targetLang){
			var str = $('.animated').val();
			var reg=new RegExp("\n","g"); 
			str= str.replace(reg,"<br/>"); 
			$("#trans_target").html(str);
		}else if(lang == "auto"){
			detect();
		}else{
			$.ajax({url:"translate.php",data:data,async:true,type:"post",success:function(data){
				show(data);
		  }})
		}
	});
       $("#gt-swap").click(function(){
		var src = $('input[name="from"]:checked').val().substr(0,2);
		var src_text = $("#radioset").find('label[for="from_'+src+'"]').find(".ui-button-text").text();
		
		var trg = $('input[name="to"]:checked').val().substr(0,2);
		var trg_text = $("#radioset2").find('label[for="to_'+trg+'"]').find(".ui-button-text").text();
		
		var lang_en = $("#lang_field_en").val();
		var lang_zh = $("#lang_field_zh").val();
		if(src == 'zh' || src == 'en') {
			$("input[name='to'][value='"+src+"']").prop('checked',true).button("refresh");
			//alert("111");
		} else {
			//alert("222");
			$("#radioset2").find('label:last').prev().attr("id","to_"+src);  
			$("#radioset2").find('label:last').prev().val(src);
			$("#radioset2").find('label:last').attr("for","to_"+src);
			$("#radioset2").find('label:last').find(".ui-button-text").text(src_text);
			$("#radioset2").find('label:last').prev().prop('checked',true).button("refresh");
			
		}
		if(trg == 'zh') {
			//直接给左边的第一个赋值
			$("#radioset").find('input:first').attr("id","from_"+trg);
			$("#radioset").find('input:first').val(trg);
			$("#radioset").find('label:first').attr("for","from_"+trg);
			$("#radioset").find('label:first').find(".ui-button-text").text(trg_text);
			$("#radioset").find('input:first').prop('checked',true).button("refresh");
		} else if (trg == 'en') {
			//直接给左边的第二个赋值
			$("#radioset").children('input').eq(1).attr("id","from_"+trg);
			$("#radioset").children('input').eq(1).val(trg);
			$("#radioset").children('label').eq(1).attr("for","from_"+trg);
			$("#radioset").children('label').eq(1).find(".ui-button-text").text(trg_text);
			$("#radioset").children('input').eq(1).prop('checked',true).button("refresh");
		} else {
			//alert("444");
			$("#radioset").find('label:last').prev().prev().prev().prop('checked',true).button("refresh");
			$("#radioset").find('label:last').prev().prev().prev().attr("id","from_"+trg);  
			$("#radioset").find('label:last').prev().prev().prev().val(trg);
			$("#radioset").find('label:last').prev().prev().attr("for","from_"+trg);
			$("#radioset").find('label:last').prev().prev().find(".ui-button-text").text(trg_text);

		}
	});
    //上传按钮的点击事件   
	$('#show_upload').click(function(){
		var targetLang = $('input[name="to"]:checked').val();//目标语言
		var sourceLang = $('input[name="from"]:checked').val();//源语言
		$('#file_upload').uploadify({
			'swf'      : 'js/uploadify.swf',
			'uploader' : 'uploadify.php',
			'formData' : {
				'sourceLang' : sourceLang,
				'targetLang' : targetLang,
				"alignmentInfo" : "true"
				},
				//上传成功
				'onUploadSuccess' : function(file, data, response) {
						console.info(file.name + ' | ' + response + ':' + data);
						show(data);
				},
			//上传错误
			'onUploadError' : function(file, errorCode, errorMsg, errorString) {
				console.info('The file ' + file.name + ' could not be uploaded: ' + errorString);
			}
		});
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
  var source = '';
  function show(data) {
   	  try { 
			var data_arr = new Array();
			data_arr = data.split('|||');//处理后台的json数据
			var text = '';//文本初始化
			var trans_msg = '';
			var check = $('#check').val();
			var check_old = $('#check_old').val();
			var align_next = $('#align_next').val();
			var trans_msg_1 = $("#trans_msg_1").val();
			var trans_msg_2 = $("#trans_msg_2").val();
			var trans_msg_3 = $("#trans_msg_3").val();
			var trans_msg_4 = $("#trans_msg_4").val();
			$("#align").empty();
			$(".trans-msg").empty();
			$("#align").css('display','none');
			$("#trans_target").empty();
			for (var d=0;d<data_arr.length;d++ ) 
			 {
					var _arr = data_arr[d];
					if(_arr != ""){
						var v = $.parseJSON(_arr);
						source = v.source;
						if(v.sourceLang) {
							var lan = check + v.sourceLang;
							//为检测语言的按钮赋相应的语言
							if($("label[for=from_auto]").find(".ui-button-text").text() != lan) {
								
						      	  $("label[for=from_auto]").find(".ui-button-text").text(lan);
							}
						 }else{
							 $("label[for=from_auto]").find(".ui-button-text").text(check_old);
						 }
						if(v.sign == 1) {
							text += v['target'] ;
							text += "<br/>";
							$("#trans_target").html(text);
						} else {
							if (v.errorCode != 0)
							{
							    //判断检测的语言是否和目标语言相同，若相同，原样输出
							   //if(v.source == $('input[name="to"]:checked').val()){
									var str = $('.animated').val();
									//console.log(str);  
									var reg=new RegExp("\n","g"); 
									var str= str.replace(reg,"<br/>");
									//console.info(str + '2'); 
									$("#trans_target").html(str);
						
							  // }else{
								   //输出对应的错误信息
								  // $("#trans_target").html(v.errorMessage);
							  // }
							  var src = $('input[name="from"]:checked').val();
							  var src_text = $("#radioset").find('label[for="from_'+src+'"]').find(".ui-button-text").text();
							  var trg = $('input[name="to"]:checked').val();
							  var trg_text = $("#radioset2").find('label[for="to_'+trg+'"]').find(".ui-button-text").text();
							  if(v.sourceLang){
								trans_msg = "<span>" + trans_msg_1 + "</span>" +
										"<span>" + trans_msg_2 + "</span>" +
										"<span class='warm-msg-text'>&nbsp;" + v.sourceLang + "&nbsp;</span>" +
										"<span>"+ trans_msg_3 +"</span>"+
										"<span class='warm-msg-text'>&nbsp;" + trg_text + "&nbsp;</span>" +
										"<span>"+ trans_msg_4 +"</span>";
											
								}else{
								   trans_msg ="<span>" + trans_msg_1 + "</span>" +
									      "<span>" + trans_msg_2 + "</span>" +
									      "<span class='warm-msg-text'>&nbsp;" + src_text + "&nbsp;</span>" +
									      "<span>"+ trans_msg_3 +"</span>"+
									      "<span class='warm-msg-text'>&nbsp;" + trg_text + "&nbsp;</span>" +
									      "<span>"+ trans_msg_4 +"</span>";
							}
							$(".trans-msg").html(trans_msg);					
							} else {
								var sentences = v['translation'][0]['translated'];
								/*Total translation(start)*/
								for (var i=0; i<sentences.length; i++){
										text += sentences[i]['text'] + "";
								}
								text += "<br/>";
								$("#trans_target").html(text);
								/*Total translation(end)*/
								var k = 0;
							        //$("#align").append("<h1>"+align_next+"</h1>");
								if($("#align").find("h1").size() == 0){
									$("#align").append("<h1>" + align_next + "</h1>");
								}
								//sentences.forEach(showSentenceAlignment);
								$(sentences).each(function(m,n){
									showSentenceAlignment(n);
								});
								// Hovers
								setupHovers();
								$("#align").fadeIn('slow');
							}
						}
				   }else{
					 text += "<br />";
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
		// computing zipped alignment 
	        var align = sen['alignment-raw'];//对齐信息
		var src_words = sen['src-tokenized'].split(' ');//源语言
		var tgt_words = sen['tgt-tokenized'].split(' ');//目标语言
		var zip = [];
		var phrase_zip = [];
		for (var i=0; i< align.length; i++){
			var a = align[i];
			zip.push([tgt_words.slice(a['tgt-start'], a['tgt-end'] + 1).join(' '), 
					src_words.slice(a['src-start'], a['src-end'] + 1).join(' ')]);
			//新建一个数组，重新获得源语言的词库
			phrase_zip.push([src_words.slice(a['src-start'],a['src-end'] + 1).join(' ')]);
		}
		//调用正向最大匹配分词方法
		var new_phrase_zip = max_match_words(phrase_zip,sen['src-tokenized']);
		//console.info(new_phrase_zip + '3');
		// displaying the zipped alignment
		var html  = '<div class="sentence" >';
		var zip1 = zip;
		for (var j=1; j>=0; j--) {
				html += '<div class="line">';
				for (var i=0; i<zip.length; i++) {
						var token = zip[i][j];
						var temp_token = "";
						temp_token = new_phrase_zip[i];//这是调序以后的源语言
						//console.info(temp_token + '4');
						if(temp_token == undefined || temp_token == null){
							temp_token = "";
							//console.info(temp_token + '10');
						}
						if (j==1) {
								var x = 0;
								for(; x < zip1.length; ++x){
									if($.trim(zip1[x][1]) == $.trim(temp_token)){
										zip1[x][1] = '';
										break;
									}
								}
								//获得用户输入的文本
								var user_text = $("#news_title").val().split(' ');
								//如果源语言是中文的话或自动检测为中文去除空格
								if($('input[name="from"]:checked').val() == 'zh' || source == 'zh'){
									temp_token = temp_token.replace(/[ ]/g,"");
									//console.info(temp_token + '5');
									token = token.replace(/[ ]/g,"");
									//console.info(token + '6');
									if(token == temp_token){//这是判断
										html  += '<span class="tokenized_token"  tokenorder="' + (i + 10000*k) + '" >' + token + '</span>';
									}else{
										html  += '<span class="tokenized_token"  tokenorder="' + (x + 10000*k) + '" >' + temp_token + '</span>';
									}
								}else{
									if(token == temp_token){
										html  += '<span class="tokenized_token"  tokenorder="' + (i + 10000*k) + '" >' + token + ' </span>';
									}else{
										html  += '<span class="tokenized_token"  tokenorder="' + (x + 10000*k) + '" >' + temp_token + ' </span>';
									}
									
								}
						} else {
							if($('input[name="to"]:checked').val() == 'zh'){
								//如果目标语言是中文的话，去除空格!
								token = token.replace(/[ ]/g,"");
								html += '<span class="tokenized_token" onclick="save(this);" tokenorder="' + (i + 10000*k) + '">' + token + '</span>';
							}else{
								html += '<span class="tokenized_token" onclick="save(this);" tokenorder="' + (i + 10000*k) + '">' + token + ' </span>';
							}
						}
					}
				html += '</div>';
			}
			html += '</div>';
			$("#align").html($("#align").html() + html);
			k++;
}
  /*鼠标滑过对齐信息触发的事件*/
  function setupHovers() {
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

/*对齐信息可编辑 暂时不用*/
function save(obj){
	var tdObj = $(obj);
	var up_text = "";
	var Text = tdObj.text(); // 获取当前要修改的词语
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
        	 	var sourceLang = $('input[name="from"]:checked').val();//获得源语言
        		var targetLang = $('input[name="to"]:checked').val();//获得目标语言
        		tdObj.html(input.val());
				var Tgt_Sen = tdObj.parent().text();//获取目标语言的句子
				var Tgt_Len = Tgt_Sen.length;
				var Src_Sen = tdObj.parent().parent().find('.line').eq(0).text();//获取源语言的句子
				var Src_Len = Src_Sen.length;
				var data = {
						'sourceLang':sourceLang,
						'targetLang':targetLang,
						'Src_Sen': Src_Sen,
						'Src_Len': Src_Len,
						'Tgt_Sen': Tgt_Sen,
						'Tgt_Len': Tgt_Len
				   };
                $.ajax({url:"save_align_text.php",type:"post",data:data,dataType:"json",success:function(data){
					if(data.code == "success"){
						tdObj.html(input.val());
						tdObj.html(input.val());
						$(".line:odd").each(function(){
						      up_text += $(this).text() + "<br/>";
						});
						
						$("#trans_target").html(up_text);
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
	sel_lan_right();
	$("#gt-swap").css("display","block");
	$("#gt-swap-disabled").css("display","none");
}
/*自动识别语言*/
function detect(){
	sel_lan_right();
	$("#gt-swap").css("display","none");
	$("#gt-swap-disabled").css("display","block");
	var _content = $('.animated').val();
	_content = _content.replace("|||", "&bar;&bar;&bar");
	if(_content.length >=3000){
		alert("不允许超过3000字符");
		return false;
	}
	var query = { 
		   "action":"translate",
          	   "content": _content,
                   "targetLang":$('input[name="to"]:checked').val(),
          	   "alignmentInfo": "true" };
	$.post("translate.php",
           query,
           function(data) { show(data); });
			
}
/*即时翻译*/
function fanyi_change(){
	var lang = $('input[name="from"]:checked').val();
	var targetLang = $('input[name="to"]:checked').val();
	var _content = $('.animated').val();
	if(_content.length >=3000){
		alert("不允许超过3000字符");
		return false;
	}
	//_content = _content.replace("|||", "&bar;&bar;&bar");
	var data = {
			'content':_content,
			'sourceLang':lang,
			'targetLang': targetLang,
			"alignmentInfo": "true"
	   };
	if(lang.substr(0,2) == targetLang){
		var str = $('.animated').val();
		var reg=new RegExp("\n","g"); 
		var str= str.replace(reg,"<br/>"); 
		//console.info(str + '7');
		$("#trans_target").html(str);
	}else if(lang == "auto"){
		detect();
	}else{
		$.ajax({url:"translate.php",data:data,async:true,type:"post",success:function(data){
			show(data);
	  }})
	}
}

/*按钮变灰触发的事件*/
function sel_lan_right(){
	var sourcelang = $('input[name="from"]:checked').val();
	$('#ui-id-10 li').each(function(key,val){
		var lang = $(this).children().attr("data-lang");
		var new_key = key + 11;
		if(sourcelang == 'zh' || sourcelang == 'en' || sourcelang == 'auto' || source == 'zh' ||  source == 'en'){
			if((sourcelang == 'zh' && lang == 'jp') || (source == 'zh' && lang == 'jp')){
				$(this).children().replaceWith('<span data-lang='+lang+' id="ui-id-'+new_key+'" class="ui-corner-all" tabindex="-1" role="menuitem">'+ $(this).children().html()+'</span>');
			}else{
				$(this).children().replaceWith('<a data-lang='+lang+' href="javascript:void(0);"  id="ui-id-'+new_key+'" class="ui-corner-all" tabindex="-1" role="menuitem">'+ $(this).children().html()+'</a>');
			}
	   }else{
	       $(this).children().replaceWith('<span data-lang='+lang+' id="ui-id-'+new_key+'" class="ui-corner-all" tabindex="-1" role="menuitem">'+ $(this).children().html()+'</span>');
	   }
	   
   });
   //右边文本框获得菜单里语言并赋值
	$("#ui-id-10 a").click(function(){
		$(this).addClass("hover-select").parent().siblings().find("a").removeAttr("class");
		var content = $(this).text();//获得菜单所对应的值
		var lang = $(this).attr("data-lang");//获得对应的语言
		$("#radioset2").find('label:last').prev().attr("id","to_"+lang);  
		$("#radioset2").find('label:last').prev().val(lang);
		$("#radioset2").find('label:last').attr("for","to_"+lang);
		$("#radioset2").find('label:last').find(".ui-button-text").text(content);
		$("#radioset2").find('label:last').prev().prop('checked',true).button("refresh");
	})
   
}
/*英中领域     滑动事件*/
/*英中领域     滑动事件*/
function onShow(obj){
	$('.lang_menu').css('display','block');
	$(".lang_menu").attr("lang",$(obj).parent().prev().val());
}
function onHidden(obj){
	$('.lang_menu').css('display','none');
}
function onShowMenu(obj){
	$('.lang_menu').css('display','block');
}
function onHiddenMenu(obj){
	$('.lang_menu').css('display','none');
}
