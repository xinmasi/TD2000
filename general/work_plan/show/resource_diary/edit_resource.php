<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("�޸Ľ�����־");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
  if(document.form1.PERCENT.value=="")
  {
   	 alert("<?=_("�������Ĺ�������")?>");
     return (false);
  }
  if(parseInt(document.form1.PERCENT.value) < 0 || parseInt(document.form1.PERCENT.value) > 100)
  {
   	 alert("<?=_("��ɰٷֱ�ֵ��0��100֮�䡣")?>");
     return (false);
  }  
  if(parseFloat(document.form1.PERCENT.value) < parseFloat(document.form1.PERCENT_MAX.value))
  {
   	 alert("<?=_("���Ȱٷֱ���ֵ����С����һ�ε���ֵ")?>");
     return (false);
  }
  if(parseFloat(document.form1.PERCENT.value) >= parseFloat(document.form1.PERCENT_MIN.value))
  {
   	 alert("<?=_("���Ȱٷֱ���ֵ���ܴ��ڵ�����һ�ε���ֵ")?>");
     return (false);
  }
  document.form1.OP.value="1";
  return (true);
}

function sendForm()
{
  if(CheckForm())
     document.form1.submit();
}

function upload_attach()
{
  if(CheckForm())
  {
  	 document.form1.OP.value="0";
     document.form1.submit();
  }
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
     URL="delete_attach.php?DETAIL_ID1=<?=$DETAIL_ID?>&PLAN_ID=<?=$PLAN_ID?>&FLAG="+1+"&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
     window.location=URL;
  }
}

</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�޸Ľ�����־")?></span>
    </td>
  </tr>
</table>
<br>

<form action="update_resource.php"  method="post" name="form1" enctype="multipart/form-data">
<table class="TableBlock" width="95%" align="center" >
   <tr>
    <td nowrap class="TableContent" width="90"><?=_("��ǰʱ�䣺")?></td>
<?
$CUR_TIME=date("Y-m-d H:i:s",time());

//��ôδ���
$query1 = "SELECT MAX(PERCENT) AS PERCENT_M from WRESOURCE_DETAIL where  WRITER='".$_SESSION["LOGIN_USER_ID"]."' and AUTO_DETAIL < '$DETAIL_ID'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW1=mysql_fetch_array($cursor1))
   $PERCENT_MAX=$ROW1["PERCENT_M"];

$query1 = "SELECT MIN(PERCENT) AS PERCENT_M from WRESOURCE_DETAIL where  WRITER='".$_SESSION["LOGIN_USER_ID"]."' and AUTO_DETAIL > '$DETAIL_ID'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW1=mysql_fetch_array($cursor1))
   $PERCENT_MIN=$ROW1["PERCENT_M"];

//��ǰ��־
$query = "SELECT * from WRESOURCE_DETAIL where AUTO_DETAIL='$DETAIL_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$RPERSON_ID=$ROW["RPERSON_ID"];
	$PROGRESS=$ROW["PROGRESS"];
	$PERCENT =$ROW["PERCENT"];
	$WRITE_TIME =$ROW["WRITE_TIME"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
}
?>
    <td class="TableData">
      <input type="text" name="WRITE_TIME" size="19" maxlength="100" readonly class="BigStatic" value="<?=$WRITE_TIME?>">
    </td>
   </tr>
   <tr>
     <td nowrap class="TableContent"> <?=_("��ɰٷֱȣ�")?></td>
     <td class="TableData" colspan="1">
       <input type="text" name="PERCENT" size="2" class="BigInput" value="<?=$PERCENT?>"><font size="3">%</font>  <?=_("��ע������������������İٷֱȣ�")?>
     </td>
   </tr>
   <tr>
     <td nowrap class="TableContent"> <?=_("�������飺")?></td>
     <td class="TableData" colspan="1">
       <textarea name="PROGRESS" class="BigInput" cols="45" rows="5"><?=$PROGRESS?></textarea>
     </td>
   </tr>
       <tr>
      <td nowrap class="TableContent"><?=_("�����ĵ���")?></td>
      <td nowrap class="TableData">
<?
      if($ATTACHMENT_ID=="")
         echo _("�޸���");
      else
         echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,0,1,1,1,1,1,0,0);
?>
      </td>
    </tr>
    <tr height="25">
      <td nowrap class="TableContent"><?=_("����ѡ��")?></td>
      <td class="TableData">
         <script>ShowAddFile();</script>
      </td>
    </tr>
   <!--  <tr>
      <td nowrap class="TableContent"><?=_("�Ƿ�д�빤����־��")?></td>
      <td class="TableData">
      	<input type="checkbox" name="WRITE_IN_WORK" id="WRITE_IN_WORK">(<?=_("ע�⣺��ѡ�Ὣ��������д�빤����־��")?>)
      	</td>
    </tr>--> 
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="hidden" value="<?=$DETAIL_ID?>" name="DETAIL_ID">
      <input type="hidden" value="<?=$PLAN_ID?>" name="PLAN_ID">
      <input type="hidden" value="<?=$RPERSON_ID?>" name="RPERSON_ID">
      <input type="hidden" name="OP" value="">
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      <input type="hidden" name="PERCENT_MAX" value="<?=$PERCENT_MAX?>">
      <input type="hidden" name="PERCENT_MIN" value="<?=$PERCENT_MIN?>">
      <input type="button" value="<?=_("ȷ��")?>" class="BigButton" onclick="sendForm();">&nbsp;&nbsp;
      <input type="button" value="<?=_("����")?>" class="BigButton" onclick="history.back();">
    </td>
</table>
</form>

</body>
</html>