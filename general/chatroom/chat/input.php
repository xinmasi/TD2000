<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("聊天室");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="inc.js"></script>
<script>

function say_to(id,name)
{
	if(id=="")
   	return;
	if(id=="ALL_USER")
	{
   	document.form1.TO_ID.remove(1);
    	return;
	}
	for (i=0;i<document.form1.TO_ID.options.length; i++)
	{
   	if(document.form1.TO_ID.options(i).value!="ALL_USER")
      	document.form1.TO_ID.remove(i);
   }
   var my_option = document.createElement("OPTION");
   my_option.text=name;
   my_option.value=id;
   document.form1.TO_ID.add(my_option);
   document.form1.TO_ID.selectedIndex=1;
   document.form1.TO_NAME.value=name;
   document.form1.MESSAGE.focus();
} 
function load_do()
{
	say_to('<?=$TO_ID?>','<?=$TO_NAME?>')
	document.form1.TO_NAME.value=document.form1.TO_ID.options(document.form1.TO_ID.selectedIndex).text;
	document.form1.MESSAGE.focus();
	getAjax('<?=$CHAT_ID?>');  
}
function change_to()
{
	document.form1.TO_NAME.value=document.form1.TO_ID.options(document.form1.TO_ID.selectedIndex).text;
	document.form1.MESSAGE.focus();
}

</script>


<body class="bodycolor" LEFTMARGIN="0" RIGHTMARGIN="0" onload="load_do();">

<form name="form1" action="input.php" method="post">
<table class="TableBlock" width="100%">
   <tr class="TableHeader">
      <td>
        <?=_("对象")?>
        <select name="TO_ID" title="<?=_("点击右边列表中的名字来指定")?>" onChange="change_to();" class="smallselect">
          <option value="ALL_USER"><?=_("所有人")?></option>
        </select>&nbsp;
        <?=_("字色")?>
        <select name="COLOR" onChange="document.form1.MESSAGE.focus();" class="smallselect">
          <option style="COLOR: #000000" value="0" <?if($COLOR==0)echo "selected";?>><?=_("黑色")?></option>
          <option style="COLOR: #7EC0EE" value="1" <?if($COLOR==1)echo "selected";?>><?=_("淡蓝")?></option>
          <option style="COLOR: #0088FF" value="2" <?if($COLOR==2)echo "selected";?>><?=_("海蓝")?></option>
          <option style="COLOR: #0000ff" value="3" <?if($COLOR==3)echo "selected";?>><?=_("草蓝")?></option>
          <option style="COLOR: #000088" value="4" <?if($COLOR==4)echo "selected";?>><?=_("深蓝")?></option>
          <option style="COLOR: #8800FF" value="5" <?if($COLOR==5)echo "selected";?>><?=_("蓝紫")?></option>
          <option style="COLOR: #AB82FF" value="6" <?if($COLOR==6)echo "selected";?>><?=_("紫色")?></option>
          <option style="COLOR: #FF88FF" value="7" <?if($COLOR==7)echo "selected";?>><?=_("紫金")?></option>
          <option style="COLOR: #FF00FF" value="8" <?if($COLOR==8)echo "selected";?>><?=_("红紫")?></option>
          <option style="COLOR: #FF0088" value="9" <?if($COLOR==9)echo "selected";?>><?=_("玫红")?></option>
          <option style="COLOR: #FF0000" value="10" <?if($COLOR==10)echo "selected";?>><?=_("大红")?></option>
          <option style="COLOR: #F4A460" value="11" <?if($COLOR==11)echo "selected";?>><?=_("棕色")?></option>
          <option style="COLOR: #CC9999" value="12" <?if($COLOR==12)echo "selected";?>><?=_("浅褐")?></option>
          <option style="COLOR: #888800" value="13" <?if($COLOR==13)echo "selected";?>><?=_("卡其")?></option>
          <option style="COLOR: #888888" value="14" <?if($COLOR==14)echo "selected";?>><?=_("铁灰")?></option>
          <option style="COLOR: #CCCCCC" value="15" <?if($COLOR==15)echo "selected";?>><?=_("古黑")?></option>
          <option style="COLOR: #90E090" value="16" <?if($COLOR==16)echo "selected";?>><?=_("绿色")?></option>
          <option style="COLOR: #008800" value="17" <?if($COLOR==17)echo "selected";?>><?=_("橄榄")?></option>
          <option style="COLOR: #008888" value="18" <?if($COLOR==18)echo "selected";?>><?=_("灰蓝")?></option>
        </select>&nbsp;
        <label for="QUIET"><?=_("悄悄话")?></label><input type="checkbox" name="QUIET" id="QUIET" onclick="document.form1.MESSAGE.focus();" <?if($QUIET=="on")echo checked;?>>&nbsp;
     </td>
  </tr>
  <tr class="TableHeader">
     <td>
        <?=_("内容")?>
        <input type="text" name="MESSAGE" size="80" id="message" maxlength="100" class="smallInput" onkeydown="if(event.keyCode==13){testhasSubmit();return false;}" >
        <input type="button" name="button111" id="button111" value="<?=_("发言")?>" class="smallButton" onclick="testhasSubmit()"  >&nbsp;&nbsp;&nbsp;
        <a href="#" onclick=window.open('history.php?CHAT_ID=<?=$CHAT_ID?>','oa_sub_window','height=500,width=600,status=0,toolbar=no,menubar=no,location=no,left=300,top=200,scrollbars=yes,resizable=yes')><b><font size=2><?=_("查看历史记录")?></font></b></a>
        <input type="hidden" name="CHAT_ID" value="<?=$CHAT_ID?>">
        <input type="hidden" name="TO_NAME" value="<?=$TO_NAME?>">
        <input type="hidden" name="USER_NAME" value="<?=$USER_NAME?>">
        <br>
        <br>
     </td>
  </tr>
</table>

</form>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script language="javascript">
    function testhasSubmit()
    {    
    	  
    	   var to_id=document.form1.TO_ID.value;
    	   var color=document.form1.COLOR.value;
    	   if(document.form1.QUIET.checked)
    	   	quiet="on";
    	   else
    	   	quiet="";
    		var message=document.form1.MESSAGE.value;
    		var chat_id=document.form1.CHAT_ID.value;
    		var to_name = document.form1.TO_NAME.value;
    		var user_name=document.form1.USER_NAME.value;
    		var url="input.php";
    		var postStr="MESSAGE="+message+"&CHAT_ID="+chat_id+"&TO_NAME="+to_name+"&USER_NAME="+user_name+"&QUIET="+quiet+"&TO_ID="+to_id+"&COLOR="+color;
    		var ajax = false;
    		 url=encodeURI(url); 
   		 url=encodeURI(url); 
    		if(window.XMLHttpRequest)
    		{ 
         	ajax = new XMLHttpRequest();
            if (ajax.overrideMimeType) 
            {
               ajax.overrideMimeType("text/xml");
            }
         }
         else if (window.ActiveXObject) 
         { 
         	try 
         	{
            	ajax = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch (e)
            {
            	try 
            	{
               	ajax = new ActiveXObject("Microsoft.XMLHTTP");
               } 
               catch (e) 
               {}
            }
         }
         if (!ajax)
         {
         	window.alert("<?=_("不能创建")?>XMLHttpRequest<?=_("对象实例")?>.");
        		return false;
         }
			ajax.open("POST", url, true); 
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
			
			ajax.onreadystatechange = function() 
			{ 
   			if (ajax.readyState == 4 && ajax.status == 200) 
   			{ 
   				
						getAjax(chat_id);
						var messageobj=document.getElementById('message');
						messageobj.value="";
 
   		   } 

    	  }
    	  ajax.send(postStr);
    		
    		
    }
</script>
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$MSG_FILE=MYOA_ATTACH_PATH."chatroom/".$CHAT_ID.".msg";
$STOP_FILE=MYOA_ATTACH_PATH."chatroom/".$CHAT_ID.".stp";
$MESSAGE=iconv("UTF-8",MYOA_CHARSET,$MESSAGE);
$USER_NAME=iconv("UTF-8",MYOA_CHARSET,$USER_NAME);
$TO_NAME=iconv("UTF-8",MYOA_CHARSET,$TO_NAME);
$TO_ID=iconv("UTF-8",MYOA_CHARSET,$TO_ID);
$QUIET=iconv("UTF-8",MYOA_CHARSET,$QUIET);
$COLOR=iconv("UTF-8",MYOA_CHARSET,$COLOR);
$MAX_FILE_SIZE=200000;
$FILE_SIZE=filesize($MSG_FILE);

if($FILE_SIZE>$MAX_FILE_SIZE)
{
	$MSG_ARRAY=file($MSG_FILE);
   $MSG_ARRAY_COUNT=count($MSG_ARRAY);
   $fp=td_fopen($MSG_FILE,"w");
   flock ($fp,2);
   for($I=$MSG_ARRAY_COUNT-5;$I<$MSG_ARRAY_COUNT;$I++)
   {
      $MSG_LINE.=substr($MSG_ARRAY[$I],0,strlen($MSG_ARRAY[$I])-2)."\n";
   }
   fwrite($fp,$MSG_LINE);
   fclose($fp);
}

$MSG=str_replace("\n"," ",$MESSAGE);
$MSG=str_replace("<","&lt",$MSG);
$MSG=str_replace(">","&gt",$MSG);
$MSG=stripslashes($MSG);

if($MSG!= "" && !file_exists($STOP_FILE))
{

	$COLOR_ARRAY = Array("000000", "7EC0EE", "0088FF", "0000ff", "000088", "8800FF", "AB82FF", "FF88FF","FF00FF", "FF0088", "FF0000", "F4A460", "CC9999", "888800", "888888", "CCCCCC", "90E090", "008800", "008888");
   $fp = td_fopen($MSG_FILE,  "a+");
   flock ($fp,2);
   $OUT_PUT="";
   if($QUIET=="on" && $TO_ID!="ALL_USER")
      $OUT_PUT.=$TO_ID.",".$_SESSION["LOGIN_USER_ID"]."@+#";
   $OUT_PUT.="<b><a href=javascript:parent.chat_input.say_to('".$_SESSION["LOGIN_USER_ID"]."','$USER_NAME');>$USER_NAME</a></b> ";
   if($TO_ID!="ALL_USER")
      $OUT_PUT.=_("对 <b><a href=javascript:parent.chat_input.say_to('$TO_ID','$TO_NAME');>$TO_NAME</a></b> ");

   if($QUIET=="on" && $TO_ID!="ALL_USER")
      $OUT_PUT.=_("悄悄地");

   $OUT_PUT.=_("说：<font color=#$COLOR_ARRAY[$COLOR]>$MSG</font> <font color=#888888>[$CUR_TIME]</font>  ");

   $OUT_PUT.="\n";
   fwrite($fp,$OUT_PUT);
   fclose($fp);
}
?>

</body>
</html>