<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
// �������ѯ
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
	$QUERY_MASTER="";   
$HTML_PAGE_TITLE = _("��Ϣ��������");
include_once("inc/header.inc.php");
//��ѯ�ʼ����ͷ����Ƿ�����
$query="select USE_FLAG from OFFICE_TASK where TASK_CODE='msm_msg_auto_eml' ";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
	$USE_FLAG=$ROW["USE_FLAG"];
}
if($USE_FLAG!="1")
{
	Message(_("��ʾ"),_("����Աδ����OA��Ϣ���ͷ���,����ϵ����Ա�ڶ�ʱ����������"));
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
			alert("<?=_("��������Ϣ��������")?>");
			return (false);
		}
		else
		{
			var emailExp = /[a-zA-Z0-9._%-]+@[a-zA-Z0-9._%-]+\.[a-zA-Z]{2,4}/;
			if(!document.getElementById("EMAIL_GETBOX").value.match(emailExp))
			{
				alert("<?=_("��������Ч����Ϣ���������ַ��")?>");
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
	 //��user,user_ext ��ȡ��չ��Ϣ
	$query_ext="select IS_USE_EMAILSEND,EMAIL_GETBOX from USER_EXT where UID='".$_SESSION["LOGIN_UID"]."' ";
	$cursor_ext= exequery(TD::conn(),$query_ext,$QUERY_MASTER);
	if($ROW_EXT=mysql_fetch_array($cursor_ext))
	{
	    $IS_USE_EMAILSEND=$ROW_EXT["IS_USE_EMAILSEND"];
	    $EMAIL_GETBOX=$ROW_EXT["EMAIL_GETBOX"];
	}
	?>
	<?
	//�ж�����Internet��������
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
		Message(_("��ʾ"),_("<div style='line-height:30px'>���������ú�Ĭ�ϵ�Internet���䣬�����ú÷��ͷ��������������룬<a href='/general/email/webmail/' target='_blank'>�������Internet��������</a><br> ������Ϻ�<a href='index.php'>����ˢ��</a></div>"));
		exit;
	}
	if($EMAIL_GETBOX=="")
		$EMAIL_GETBOX=$DEFAULT_EMAIL;
	?>
	<table class="table table-bordered">
	    <thead>
	        <tr>
	        <td colspan="2"><?=_("��Ϣ��������")?></td>
	        </tr>
	    </thead>
		<form action="submit.php"  method="post" name="form1" onSubmit="return CheckForm();">
	
		<tr class="TableData">
			<td nowrap  width="200px"> <i class="iconfont">&#xe640;</i><?=_("�Ƿ�����OA��Ϣ���ͣ�")?></td>
			<td nowrap >
				<label for="IS_USE_EMAILSEND" style=" display: inline; ">
					<input type="checkbox" name="IS_USE_EMAILSEND" id="IS_USE_EMAILSEND"  onClick="change_type()"  <? if($IS_USE_EMAILSEND==1)echo "checked"; ?>>
					<?=_("��")?>
				</label><span><?=_("(ת��δ��΢Ѷ��������Ϣ������Internet����)")?></span>
			</td>
		</tr>
		<tr  class="TableData" id="internet1" <? if($IS_USE_EMAILSEND!=1){?> style="display:none" <?}?>>
			<td nowrap ><i class="iconfont">&#xe62a;</i><?=_("��Ϣ�������䣺")?></td>
			<td nowrap>
				<input type="text" name="EMAIL_GETBOX"  class='' id="EMAIL_GETBOX" size="40" value=<?=$EMAIL_GETBOX?> >  
			</td>
		</tr>
		<tr>
			<td nowrap  class="TableData" colspan="2" align="center" style="text-align:center"> 
			<input type="submit" value="<?=_("ȷ��")?>" class="btn btn-primary">&nbsp;&nbsp;
			</td>
		</tr>
		</form>
	</table>
</body>
</html>
