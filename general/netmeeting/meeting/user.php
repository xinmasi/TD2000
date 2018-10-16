<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("网络会议用户列表");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function createxmlhttp()
{
	xmlhttpobj = false;
	try
	{
		xmlhttpobj = new ActiveXObject;
	}
	catch(e)
	{
		try
		{
			xmlhttpobj=new ActiveXObject("MSXML2.XMLHTTP");
		}
		catch(e2)
		{
			try
			{
				xmlhttpobj=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e3)
			{
				xmlhttpobj = false;
			}
		}
	}
	return xmlhttpobj; 
}
function getAjax(MEET_ID,USER_NAME,USER_IP)
{
	var MEET_ID=MEET_ID;
	var USER_NAME=USER_NAME;
	var USER_IP=USER_IP;
	var xmlhttpobj = createxmlhttp();
	if(xmlhttpobj)
	{
		xmlhttpobj.open('get',"user_get.php?MEET_ID="+MEET_ID+"&USER_NAME="+USER_NAME+"&USER_IP="+USER_IP,true);
		xmlhttpobj.send(null);
		xmlhttpobj.onreadystatechange=function()
		{
			if(xmlhttpobj.readystate==4)
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

<body bgcolor="#F1FAF5"  class="small" onLoad="getAjax('<?=$CHAT_ID?>','<?=$USER_NAME?>','<?=$USER_IP?>')">
<script>
window.setInterval(function(){getAjax('<?=$MEET_ID?>','<?=$USER_NAME?>','<?=$USER_IP?>');},3000);

</script>
<div style="margin-top:6px;margin-left:6px;">
<span id="loadAjaxTips"></span>
</body>
</html>