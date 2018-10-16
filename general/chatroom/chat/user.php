<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("聊天室用户列表");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/person_info.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script Language=JavaScript>

function createxmlhttp()
{
  xmlhttpobj = false;
  try{
  xmlhttpobj = new XMLHttpRequest;//IE以外的浏览器
  }catch(e){
  try{
  xmlhttpobj=new ActiveXObject("MSXML2.XMLHTTP");
  }catch(e2){
  try{
  xmlhttpobj=new ActiveXObject("Microsoft.XMLHTTP");
  }catch(e3){
  xmlhttpobj = false;
  }
  }
  }
  return xmlhttpobj;  
}



function getAjax(CHAT_ID,USER_NAME,USER_IP)
{
	var CHAT_ID=CHAT_ID;
	var USER_NAME=USER_NAME;
	<?
		if(MYOA_IS_UN==1) {
		?>
		USER_NAME=encodeURI(USER_NAME);
		<?
		}
	?>
	var USER_IP=USER_IP;
	var xmlhttpobj = createxmlhttp();
	if(xmlhttpobj)
	{
		xmlhttpobj.open('get',"user_get.php?CHAT_ID="+CHAT_ID+"&USER_NAME="+USER_NAME+"&USER_IP="+USER_IP,true);
		xmlhttpobj.send(null);
		xmlhttpobj.onreadystatechange=function()
		{
			if(xmlhttpobj.readyState==4)
			{
				if(xmlhttpobj.status==200)
				{
					var htmlxxx = xmlhttpobj.responseText;
					document.getElementById("loadAjaxTips").innerHTML=htmlxxx;
				}
				else
				{
					document.getElementById("loadAjaxTips").innerHTML="<font color='red'><?=_("加载错误")?></font>";
				}
			}
			else
			{
				document.getElementById("loadAjaxTips").innerHTML="";
			}
		} 
	}
}
</script>

<body bgcolor="#F1FAF5" class="small"  onLoad="getAjax('<?=$CHAT_ID?>','<?=$USER_NAME?>','<?=$USER_IP?>')">
<script>
window.setInterval(function()
{
	getAjax('<?=$CHAT_ID?>','<?=$USER_NAME?>','<?=$USER_IP?>');
},3000);
</script>

<div align="left" style="margin-top:6px;margin-left:6px;" >
<span id="loadAjaxTips" ></span>
</div>
</body>
</html>