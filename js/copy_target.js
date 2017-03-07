/*!
Translate function for GTC
*/
/*
* swf复制，兼容所有浏览器
* 
*/
  $(function(){
          $('#cp-btn').zclip({
                  path : 'js/ZeroClipboard.swf',
                  copy : function(){
                           return $('#trans_target').text();
                  },
                  afterCopy: function(){//复制成功
                           alert("已复制，您可以使用Ctrl+V粘贴！");
                  }
           });
  });



















