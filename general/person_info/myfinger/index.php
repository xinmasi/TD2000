<?
include_once("inc/auth.inc.php");
include_once("inc/finger/config.php");
include_once("inc/utility_all.php");

$query = "SELECT USING_FINGER from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $USING_FINGER=$ROW["USING_FINGER"];


$HTML_PAGE_TITLE = _("�����ҵ�ָ����Ϣ");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script>
function save()
{
	var USING_FINGER=$("#USING_FINGER").attr("checked")?1:0;
	$.get('update.php?USING_FINGER='+USING_FINGER,function(data){if(data=="") $('#result').html('<?=_("����ɹ���")?>');else $('#result').html(data);})
}
function getInfo(URL)
{
	var result = $.ajax({
    url: URL,
    cache: false,
    async: false
    }).responseText;
  return result;
}

function goAction(act)
{
	  if(act=="Enroll")
	  {
  		var isReg = getInfo("enroll.php?USER_ID=<?=$_SESSION["LOGIN_USER_ID"]?>");
  		
			if(isReg=="<?=_("�û��Ѵ���")?>")
				$("#result").html(isReg);	
			else if(isReg==0)
			{
				$("#result").html("<?=_("ע���û��ɹ���")?>");
				var obj=new ActiveXObject("<?=$OcxObject?>.XFPEnrollExportX");
        obj.SetAgentInfo(getInfo("/inc/finger/getAgentInfo.php"));
        var fingerData = obj.EnrollByPwdExport("<?=$_SESSION["LOGIN_USER_ID"]?>","","",0,0,"<?=$AuthUser?>","<?=$AuthPwd?>");
        if(fingerData!="")
           $.post("enroll.php",{"USER_ID":"<?=$_SESSION["LOGIN_USER_ID"]?>","fingerData":fingerData},function(msg){if(msg==0) $("#result").html("<?=_("ע���û��ɹ���")?>");else $("#result").html("<?=_("���󣡴���ţ�")?>"+msg);});
			}
			else
				$("#result").html("<?=_("ע��ʧ�ܣ�������룺")?>"+isReg);	
	  }
    else if(act=="Delete")
    {
    	var result = getInfo("delete.php?USER_ID=<?=$_SESSION["LOGIN_USER_ID"]?>");
  		if(result==0)
  		  $("#result").html("<?=_("ɾ���û��ɹ���")?>");
  		else
  			$("#result").html("<?=_("ɾ���û�ʧ�ܣ��������")?>"+result);
    }
    else if(act=="Modify")
    {
    	var isReg = getInfo("enroll.php?USER_ID=<?=$_SESSION["LOGIN_USER_ID"]?>");
    	if(isReg=="<?=_("�û��Ѵ���")?>")
  		{
  			var strEnrollFingers = getInfo("./getFingerInfo.php?USER_ID=<?=$_SESSION["LOGIN_USER_ID"]?>");
      	var obj=new ActiveXObject("<?=$OcxObject?>.XFPEnrollExportX");
        obj.SetAgentInfo(getInfo("/inc/finger/getAgentInfo.php"));
        var fingerData = obj.EnrollByPwdExport("<?=$_SESSION["LOGIN_USER_ID"]?>",strEnrollFingers,"",0,0,"<?=$AuthUser?>","<?=$AuthPwd?>");

        if(fingerData!="")
           $.post("enroll.php",{"USER_ID":"<?=$_SESSION["LOGIN_USER_ID"]?>","fingerData":fingerData},function(msg){
           	if(msg==0)
           	  $("#result").html("<?=_("�޸�ָ�Ƴɹ���")?>");
           	else
           		$("#result").html("<?=_("�޸�ָ��ʧ�ܣ�������룺")?>"+msg); 
           	});
      }
      else
      	$("#result").html("<?=_("�û����������ȴ����û�")?>");
    }
    else if(act=="Test")
    {
    	var msg = getInfo("/inc/finger/isexisted.php?USER_ID=<?=$_SESSION["LOGIN_USER_ID"]?>");
      if(msg==1)
        $("#result").html("<?=_("����δ��ָ��ϵͳ��ע���û���")?>");
      else if(msg==2)
        $("#result").html("<?=_("����δע���κ�ָ����Ϣ��")?>");
      else
      {
        var obj=new ActiveXObject("<?=$OcxObject?>.XFPAuthenExportX");
        obj.SetAgentInfo(getInfo("/finger/getAgentInfo.php"));
        var authData=obj.AuthenDlgExport("<?=$_SESSION["LOGIN_USER_ID"]?>",msg,0);
        if(authData!="")
        {
           $.post("/inc/finger/auth.php",{"USER_ID":"<?=$_SESSION["LOGIN_USER_ID"]?>","authData":authData},function(result){
           	if(result==0)
           		$("#result").html("<?=_("���Խ������֤ͨ����")?>");
           	else
           		$("#result").html("<?=_("���Խ������֤δͨ����")?>");
            });
        }
      }	
    }
}
</script>


<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/login.gif" align="absmiddle"><span class="big3"> <?=_("�����ҵ�ָ����Ϣ")?></span><br>
    </td>
  </tr>
</table>

<table class="TableList" width="500" align="center">
<form method="post" action="update.php" name="form1" >
<tr class="Big">
	<td class="TableContent" width="120"><b><?=_("�û�����")?></b></td>
	<td class="TableData"><b><?=$_SESSION["LOGIN_USER_ID"]?></b></td>
</tr>
<!--
<tr class="Big">
	<td class="TableContent" width="120"><b><?=_("�Ƿ���ָ����֤��")?></b></td>
	<td class="TableData"><input type="checkbox" id="USING_FINGER" <?if($USING_FINGER==1) echo "checked";?>><label for="USING_FINGER"><?=_("ѡ��������")?></label></td>
</tr>
-->
<tr class="Big">
	<td class="TableContent" width="120"><b><?=_("�����")?></b></td>
	<td class="TableData">
	<input class="BigButton" type="button" value="<?=_("ע��ָ��")?>" onclick="goAction('Enroll')">
	<input class="BigButton" type="button" value="<?=_("�޸�ָ��")?>" onclick="goAction('Modify')">
	<input class="BigButton" type="button" value="<?=_("ɾ��ָ��")?>" onclick="goAction('Delete')">
	<input class="BigButton" type="button" value="<?=_("ָ�Ʋ���")?>" onclick="goAction('Test')">
	</td>
</tr>
<tr class="Big">
	<td class="TableContent" width="120"><b><?=_("���������")?></b></td>
    <td class="TableData">
    <div id="result"></div>
    </td>
</tr>
<tr align="center" >
    <td class="TableControl" colspan="2" >
    	<input type="button" value="<?=_("����")?>" class='BigButton' onclick="save();">
      <input type="button" value="<?=_("����")?>" class='BigButton' onclick="history.back();">
    </td>
</tr>

</table>
</body>
</html>
