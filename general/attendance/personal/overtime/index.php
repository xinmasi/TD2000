<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/flow_hook.php");
$connstatus = ($connstatus) ? true : false;
$query="select * from SYS_PARA where PARA_NAME='SMS_REMIND'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $PARA_VALUE=$ROW["PARA_VALUE"];
//$SMS2_REMIND=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND_TMP=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND=substr($SMS2_REMIND_TMP,0,strpos($SMS2_REMIND_TMP,"|"));

$HTML_PAGE_TITLE = _("�Ӱ�Ǽ�");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">

<script>
function CheckForm()
{

   if(document.form1.START_TIME.value=="" || document.form1.END_TIME.value=="")
   { alert("<?=_("�Ӱ���ֹʱ�䲻��Ϊ�գ�")?>");
     return (false);
   }

   if(document.form1.OVERTIME_CONTENT.value=="")
   { alert("<?=_("�Ӱ����ݲ���Ϊ�գ�")?>");
     return (false);
   }

   return (true);
}
function overtime_confirm(OVERTIME_ID,RECORD_TIME)
{
 <?
  if(find_id($TYPE_PRIV,6)) //����ģ���Ƿ������ֻ�����
  {
?>
	if(document.all("LEAVE_SMS2_REMIND"+OVERTIME_ID).checked)
	MOBILE_FLAG=1;
  else
<?
  }
?>
  MOBILE_FLAG=0;
  URL="overtime_back_edit.php?OVERTIME_ID="+OVERTIME_ID+"&RECORD_TIME="+RECORD_TIME+"&MOBILE_FLAG="+MOBILE_FLAG;
  window.location=URL;
}

function form_view(RUN_ID)
{
window.open("/general/workflow/list/print/?RUN_ID="+RUN_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}
function delete_alert(RECORD_TIME)
{
    msg='<?=_("ȷ��Ҫɾ���üӰ���Ϣ��")?>';
    
    if(window.confirm(msg))
    {
        URL="delete.php?RECORD_TIME=" + RECORD_TIME;
        window.location=URL;
    }
}
</script>


<body class="bodycolor attendance">

    <h5 class="attendance-title"><span class="big3"> <?=_("�Ӱ�Ǽ�")?></span></h5><br>
<br>
<div align="center">
<div align="center">
<input type="button"  value="<?=_("�Ӱ�Ǽ�")?>" class="btn btn-primary" onClick="location='new/';" title="<?=_("�½��Ӱ�Ǽ�")?>">&nbsp;&nbsp;
<input type="button"  value="<?=_("�Ӱ���ʷ��¼")?>" class="btn" onClick="location='history.php';" title="<?=_("�鿴�����ļӰ��¼")?>">
<br>

<br>
<table class="table table-bordered" >
     <tr class="TableData">
       <th nowrap align="center"><?=_("����ʱ��")?></th>
        <th nowrap align="center"><?=_("������")?></th>
        <th nowrap align="center"><?=_("�Ӱ�����")?></th>
        <th nowrap align="center"><?=_("��ʼʱ��")?></th>
        <th nowrap align="center"><?=_("����ʱ��")?></th>
        <th nowrap align="center"><?=_("�Ӱ�ʱ��")?></th>
        <th nowrap align="center"><?=_("״̬")?></th>
        <th nowrap align="center"><?=_("����")?></th>
     </tr>
<?
//�޸���������״̬--yc
update_sms_status('6',0);

//---- �鿴�Ӱ�Ǽ���� -----
$OVERTIME_COUNT=0;
$CUR_DATE=date("Y-m-d",time());
$query = "SELECT * from ATTENDANCE_OVERTIME where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='0'order by RECORD_TIME";
$cursor= exequery(TD::conn(),$query, $connstatus);
while($ROW=mysql_fetch_array($cursor))
{
    $OVERTIME_COUNT++;

    $OVERTIME_ID=$ROW["OVERTIME_ID"];
    $USER_ID=$ROW["USER_ID"];
    $APPROVE_ID=$ROW["APPROVE_ID"];
    $RECORD_TIME=$ROW["RECORD_TIME"];
    $START_TIME=$ROW["START_TIME"];
    $END_TIME=$ROW["END_TIME"];
    $OVERTIME_HOURS=$ROW["OVERTIME_HOURS"];
    $OVERTIME_CONTENT=$ROW["OVERTIME_CONTENT"];
    $CONFIRM_VIEW=$ROW["CONFIRM_VIEW"];
    $ALLOW=$ROW["ALLOW"];
    $STATUS=$ROW["STATUS"];
    $REASON=$ROW["REASON"];

    $USER_NAME="";
    $query = "SELECT * from USER where USER_ID='$APPROVE_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
        $USER_NAME=$ROW["USER_NAME"];

    $OVERTIME_CONTENT=str_replace("<","&lt",$OVERTIME_CONTENT);
    $OVERTIME_CONTENT=str_replace(">","&gt",$OVERTIME_CONTENT);
    $OVERTIME_CONTENT=gbk_stripslashes($OVERTIME_CONTENT);

    if($ALLOW=="0")
        $ALLOW_DESC=_("������");
    else if($ALLOW=="1")
        $ALLOW_DESC="<font color=green>"._("����׼")."</font>";
    else if($ALLOW=="2")
        $ALLOW_DESC= "<font color=\"red\">"._("δ��׼")."</font>";
    else if($ALLOW=="3")
        $ALLOW_DESC=_("��ȷ��");
 ?>
    <tr class="TableData">
      <td nowrap align="center"><?=$RECORD_TIME?></td>
<?
$is_run_hook=is_run_hook("OVERTIME_ID",$OVERTIME_ID);
      if($is_run_hook!=0)
      {
?>
      <td nowrap align="center"><a href="javascript:;" onclick="form_view('<?=$is_run_hook?>')"><?=_("�鿴����")?></a></td>
<?
      }
      else
      {
?>
      <td nowrap align="center"><?=$USER_NAME?></td>
<?
      }
?>
      <td style="word-break:break-all" align="left" align="center">
 <?
     echo $OVERTIME_CONTENT;
     if($CONFIRM_VIEW!="")
      {
         echo "<br>";
         echo _("<font color=blue>ȷ�������$CONFIRM_VIEW</font>");
      }
 ?>
      </td>
      <td nowrap align="center"><?=$START_TIME?></td>
      <td nowrap align="center"><?=$END_TIME?></td>
      <td nowrap align="center"><?=$OVERTIME_HOURS?></td>
      <td nowrap align="center">
 <?
     echo $ALLOW_DESC;
     if($REASON!="")
      {
         echo "<br>";
         echo _("<font color=blue>δ׼ԭ��$REASON</font>");
      }
 ?>
      </td>
      <td nowrap align="center">
      <?
    if($ALLOW=="0" || $ALLOW=="2")
    {
    	if($is_run_hook!=0)
      {
    	   $query2 = "SELECT * from FLOW_RUN where RUN_ID='$is_run_hook' and DEL_FLAG='0'";
         $cursor2= exequery(TD::conn(),$query2);
         if(!$ROW2=mysql_fetch_array($cursor2))
         {
?>
    <a href="delete.php?RECORD_TIME=<?=$RECORD_TIME?>"><?=_("ɾ��")?></a>
<?
         }
     }
     else
     {
?>
    <a href="edit.php?OVERTIME_ID=<?=$OVERTIME_ID?>"><?=_("�޸�")?></a>
    <a href="javascript:delete_alert('<?=$RECORD_TIME?>');"><?=_("ɾ��")?></a>
<?
     }
    }
    else if($ALLOW=="1" )
    {
     if(find_id($TYPE_PRIV,6)) //����ģ���Ƿ������ֻ�����
       {
?>
      <input type="checkbox" name="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>" id="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>" <?if(find_id($SMS2_REMIND,"6")) echo "checked";?>><label for="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>"><?=_("�����ֻ���������")?></label>
<?
       }
?>
       <a href=javascript:overtime_confirm('<?=$OVERTIME_ID?>','<?=urlencode($RECORD_TIME)?>');><?=_("�Ӱ�ȷ��")?></a>
<?
    }
?>


    </td>
    </tr>
<?
 }
if($OVERTIME_COUNT>0)
	{
	?>
	 
	<?
	}else
	{
		 Message("",_("û�мӰ��¼"));
		 exit;
	}
?>
</table>

</div>
</body>
</html>