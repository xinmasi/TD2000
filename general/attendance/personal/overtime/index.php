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

$HTML_PAGE_TITLE = _("加班登记");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">

<script>
function CheckForm()
{

   if(document.form1.START_TIME.value=="" || document.form1.END_TIME.value=="")
   { alert("<?=_("加班起止时间不能为空！")?>");
     return (false);
   }

   if(document.form1.OVERTIME_CONTENT.value=="")
   { alert("<?=_("加班内容不能为空！")?>");
     return (false);
   }

   return (true);
}
function overtime_confirm(OVERTIME_ID,RECORD_TIME)
{
 <?
  if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
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
    msg='<?=_("确认要删除该加班信息吗？")?>';
    
    if(window.confirm(msg))
    {
        URL="delete.php?RECORD_TIME=" + RECORD_TIME;
        window.location=URL;
    }
}
</script>


<body class="bodycolor attendance">

    <h5 class="attendance-title"><span class="big3"> <?=_("加班登记")?></span></h5><br>
<br>
<div align="center">
<div align="center">
<input type="button"  value="<?=_("加班登记")?>" class="btn btn-primary" onClick="location='new/';" title="<?=_("新建加班登记")?>">&nbsp;&nbsp;
<input type="button"  value="<?=_("加班历史记录")?>" class="btn" onClick="location='history.php';" title="<?=_("查看过往的加班记录")?>">
<br>

<br>
<table class="table table-bordered" >
     <tr class="TableData">
       <th nowrap align="center"><?=_("申请时间")?></th>
        <th nowrap align="center"><?=_("审批人")?></th>
        <th nowrap align="center"><?=_("加班内容")?></th>
        <th nowrap align="center"><?=_("开始时间")?></th>
        <th nowrap align="center"><?=_("结束时间")?></th>
        <th nowrap align="center"><?=_("加班时长")?></th>
        <th nowrap align="center"><?=_("状态")?></th>
        <th nowrap align="center"><?=_("操作")?></th>
     </tr>
<?
//修改事务提醒状态--yc
update_sms_status('6',0);

//---- 查看加班登记情况 -----
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
        $ALLOW_DESC=_("待审批");
    else if($ALLOW=="1")
        $ALLOW_DESC="<font color=green>"._("已批准")."</font>";
    else if($ALLOW=="2")
        $ALLOW_DESC= "<font color=\"red\">"._("未批准")."</font>";
    else if($ALLOW=="3")
        $ALLOW_DESC=_("待确认");
 ?>
    <tr class="TableData">
      <td nowrap align="center"><?=$RECORD_TIME?></td>
<?
$is_run_hook=is_run_hook("OVERTIME_ID",$OVERTIME_ID);
      if($is_run_hook!=0)
      {
?>
      <td nowrap align="center"><a href="javascript:;" onclick="form_view('<?=$is_run_hook?>')"><?=_("查看流程")?></a></td>
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
         echo _("<font color=blue>确认意见：$CONFIRM_VIEW</font>");
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
         echo _("<font color=blue>未准原因：$REASON</font>");
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
    <a href="delete.php?RECORD_TIME=<?=$RECORD_TIME?>"><?=_("删除")?></a>
<?
         }
     }
     else
     {
?>
    <a href="edit.php?OVERTIME_ID=<?=$OVERTIME_ID?>"><?=_("修改")?></a>
    <a href="javascript:delete_alert('<?=$RECORD_TIME?>');"><?=_("删除")?></a>
<?
     }
    }
    else if($ALLOW=="1" )
    {
     if(find_id($TYPE_PRIV,6)) //检查该模块是否允许手机提醒
       {
?>
      <input type="checkbox" name="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>" id="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>" <?if(find_id($SMS2_REMIND,"6")) echo "checked";?>><label for="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>"><?=_("发送手机短信提醒")?></label>
<?
       }
?>
       <a href=javascript:overtime_confirm('<?=$OVERTIME_ID?>','<?=urlencode($RECORD_TIME)?>');><?=_("加班确认")?></a>
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
		 Message("",_("没有加班记录"));
		 exit;
	}
?>
</table>

</div>
</body>
</html>