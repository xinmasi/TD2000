<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
// 主服务查询
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
	$QUERY_MASTER="";   
$HTML_PAGE_TITLE = _("消息推送设置");
include_once("inc/header.inc.php");
//查询邮件推送服务是否启用
$query="select USE_FLAG from OFFICE_TASK where TASK_CODE='msm_msg_auto_eml' ";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
	$USE_FLAG=$ROW["USE_FLAG"];
}
if($USE_FLAG!="1")
{
	Message(_("提示"),_("管理员未启用OA消息推送服务,请联系管理员在定时任务中启用"));
	exit;
}

?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/person_info/index.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/person_info.css">
<script>
function CheckForm()
{
	
	if(document.getElementById("IS_USE_EMAILSEND").checked)
	{	
		
		if(document.getElementById("EMAIL_GETBOX").value=="")
		{
			alert("<?=_("请设置消息接收邮箱")?>");
			return (false);
		}
		else
		{
			var emailExp = /[a-zA-Z0-9._%-]+@[a-zA-Z0-9._%-]+\.[a-zA-Z]{2,4}/;
			if(!document.getElementById("EMAIL_GETBOX").value.match(emailExp))
			{
				alert("<?=_("请输入有效的消息接收邮箱地址！")?>");
				return (false);
			}	
		}
	
	}
	form1.submit();
}
function change_type()
{
	if(document.getElementById("IS_USE_EMAILSEND").checked)
	{
	   document.getElementById("internet1").style.display='';
	  
	}
	else 
	{	   
	    document.getElementById("internet1").style.display='none';
    }
}
</script>
<body class="bodycolor">
	<?
	 //从user,user_ext 获取扩展信息
	$query_ext="select IS_USE_EMAILSEND,EMAIL_GETBOX from USER_EXT where UID='".$_SESSION["LOGIN_UID"]."' ";
	$cursor_ext= exequery(TD::conn(),$query_ext,$QUERY_MASTER);
	if($ROW_EXT=mysql_fetch_array($cursor_ext))
	{
	    $IS_USE_EMAILSEND=$ROW_EXT["IS_USE_EMAILSEND"];
	    $EMAIL_GETBOX=$ROW_EXT["EMAIL_GETBOX"];
	}
	?>
	<?
	//判断有无Internet邮箱设置
	$query="SELECT * from WEBMAIL where  USER_ID='".$_SESSION["LOGIN_USER_ID"]."' ";
	$cursor= exequery(TD::conn(),$query);
	$count=0;
	while($ROW=mysql_fetch_array($cursor))
	{
		$EMAIL_PASS=$ROW["EMAIL_PASS"];
		$EMAIL_PASS=td_authcode($EMAIL_PASS,"DECODE");
		if($EMAIL_PASS=="")
			continue;
		else
		{
			$count++;
			$DEFAULT_EMAIL=$ROW['EMAIL'];
		}
	}
	if($count==0)
	{
		Message(_("提示"),_("<div style='line-height:30px'>请首先设置好默认的Internet邮箱，并设置好发送服务器和邮箱密码，<a href='/general/email/webmail/' target='_blank'>点击进入Internet邮箱设置</a><br> 设置完毕后，<a href='index.php'>请点击刷新</a></div>"));
		exit;
	}
	if($EMAIL_GETBOX=="")
		$EMAIL_GETBOX=$DEFAULT_EMAIL;
	?>
	<table class="table table-bordered">
	    <thead>
	        <tr>
	        <td colspan="2"><?=_("消息推送设置")?></td>
	        </tr>
	    </thead>
		<form action="submit.php"  method="post" name="form1" onSubmit="return CheckForm();">
	
		<tr class="TableData">
			<td nowrap  width="200px"> <i class="iconfont">&#xe640;</i><?=_("是否启用OA消息推送：")?></td>
			<td nowrap >
				<label for="IS_USE_EMAILSEND" style=" display: inline; ">
					<input type="checkbox" name="IS_USE_EMAILSEND" id="IS_USE_EMAILSEND"  onClick="change_type()"  <? if($IS_USE_EMAILSEND==1)echo "checked"; ?>>
					<?=_("是")?>
				</label><span><?=_("(转发未读微讯、事务消息到以下Internet邮箱)")?></span>
			</td>
		</tr>
		<tr  class="TableData" id="internet1" <? if($IS_USE_EMAILSEND!=1){?> style="display:none" <?}?>>
			<td nowrap ><i class="iconfont">&#xe62a;</i><?=_("消息接收邮箱：")?></td>
			<td nowrap>
				<input type="text" name="EMAIL_GETBOX"  class='' id="EMAIL_GETBOX" size="40" value=<?=$EMAIL_GETBOX?> >  
			</td>
		</tr>
		<tr>
			<td nowrap  class="TableData" colspan="2" align="center" style="text-align:center"> 
			<input type="submit" value="<?=_("确定")?>" class="btn btn-primary">&nbsp;&nbsp;
			</td>
		</tr>
		</form>
	</table>
</body>
</html>
