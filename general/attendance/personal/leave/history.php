<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("请假历史记录");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<body class="bodycolor attendance">
    <h5 class="attendance-title"><span class="big3"> <?=_("请假登记")?></span></h5><br>
<br>

<div align="center">

<?
 //---- 请假历史记录 -----
 $LEAVE_COUNT=0;

 $query = "SELECT * from ATTEND_LEAVE where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='2' order by LEAVE_DATE1 desc";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $LEAVE_COUNT++;

    $LEAVE_ID=$ROW["LEAVE_ID"];
    $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
    $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
    $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
    $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"]; 
    $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");       
    $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];
    $LEADER_ID=$ROW["LEADER_ID"];
    $RECORD_TIME =$ROW["RECORD_TIME"];
    $DESTROY_TIME =$ROW["DESTROY_TIME"];
    
    $STATUS=$ROW["STATUS"];
    $ALLOW=$ROW["ALLOW"];
    if($ALLOW=="3"&&$STATUS=="2")
       $ALLOW_DESC=_("已销假");
    $LEADER_NAME="";
    $query1 = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
       $LEADER_NAME=$ROW["USER_NAME"];

    if($LEAVE_COUNT==1)
    {
?>

    <table class="table table-bordered" width="95%">
        <tr class="">
          <th nowrap align="center"><?=_("请假原因")?></th>
          <th nowrap align="center"><?=_("申请时间")?></th>
          <th nowrap align="center"><?=_("请假类型")?></th>
          <th nowrap align="center"><?=_("占年休假")?></th>
          <th nowrap align="center"><?=_("审批人员")?></th>
          <th nowrap align="center"><?=_("开始时间")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></th>
          <th nowrap align="center"><?=_("结束时间")?></th>
          <th nowrap align="center"><?=_("销假时间")?></th>
          <th nowrap align="center"><?=_("状态")?></th>
    <!--      <td nowrap align="center"><?=_("操作")?></td>-->
        </tr>
<?
    }

    if($LEAVE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";

?>
    <tr >
      <td align="center"><?=$LEAVE_TYPE?></td>
      <td align="center"><?=$RECORD_TIME?></td>
      <td align="center"><?=$LEAVE_TYPE2?></td>
      <td nowrap align="center"><?=$ANNUAL_LEAVE?><?=_("天")?></td>
      <td nowrap align="center"><?=$LEADER_NAME?></td>
      <td nowrap align="center"><?=$LEAVE_DATE1?></td>
      <td nowrap align="center"><?=$LEAVE_DATE2?></td>
<?
if($RECORD_TIME >=$LEAVE_DATE2)
{
?>
	 <td nowrap align="center"><?=$LEAVE_DATE2?></td>
<?
}
else
{
?>
      <td nowrap align="center"><?=$DESTROY_TIME?></td>
<?
}
?>
      <td nowrap align="center"><?=$ALLOW_DESC?></td>
<!--       <td nowrap align="center"><a href="edit.php?LEAVE_ID=<?=$LEAVE_ID?>" title="<?=_("提醒：修改提交后，需要重新审批")?>"><?=_("修改")?></a></td>-->
    </tr>
<?
 }

 if($LEAVE_COUNT>0)
 {
?>
    </table>
<?
 }
 else
    message("",_("无历史记录"));
?>

</div>
<br><br>
<div align="center">
  <input type="button"  value="<?=_("返回上页")?>" class="btn" onClick="location='index.php';">
</div>
</body>
</html>