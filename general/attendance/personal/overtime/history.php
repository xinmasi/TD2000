<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�Ӱ���ʷ��¼");
include_once("inc/header.inc.php");
?>




<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<body class="bodycolor attendance">
    <h5 class="attendance-title"><span class="big3"> <?=_("�Ӱ���ʷ��¼")?></span></h5><br>
<br>
<div align="center">
<?
 //---- �Ӱ���ʷ��¼ -----
 $OVERTIME_COUNT=0;
 $query = "SELECT * from ATTENDANCE_OVERTIME where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='1' order by RECORD_TIME desc";
 $cursor= exequery(TD::conn(),$query);
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

    $CONFIRM_TIME=$ROW["CONFIRM_TIME"];
    $CONFIRM_VIEW=$ROW["CONFIRM_VIEW"];
    $ALLOW=$ROW["ALLOW"];
    $STATUS=$ROW["STATUS"];
    $REGISTER_IP=$ROW["REGISTER_IP"];
    $REASON=$ROW["REASON"];
    
    $USER_NAME="";
    $query = "SELECT * from USER where USER_ID='$APPROVE_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $USER_NAME=$ROW["USER_NAME"];

    $OVERTIME_CONTENT=str_replace("<","&lt",$OVERTIME_CONTENT);
    $OVERTIME_CONTENT=str_replace(">","&gt",$OVERTIME_CONTENT);
    $OVERTIME_CONTENT=stripslashes($OVERTIME_CONTENT);

    if($ALLOW=="3"&&$STATUS=="1")
       $ALLOW_DESC=_("��ȷ��");
    
    if($OVERTIME_COUNT==1)
    {
?>

    <table class="table table-bordered"  width="95%">
        <thead class="">
            <td nowrap align="center"><?=_("����ʱ��")?></td>
            <td nowrap align="center"><?=_("������Ա")?></td>
            <td nowrap align="center"><?=_("�Ӱ�����")?></td>
            <td nowrap align="center"><?=_("��ʼʱ��")?></td>
            <td nowrap align="center"><?=_("����ʱ��")?></td>
            <td nowrap align="center"><?=_("�Ӱ�ʱ��")?></td>
            <td nowrap align="center"><?=_("ȷ��ʱ��")?></td>
            <td nowrap align="center"><?=_("״̬")?></td>
        </thead>
<?
    }

    if($OVERTIME_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";

?>
    <tr class="">
      <td nowrap align="center"><?=$RECORD_TIME?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td nowrap align="center">
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
      <td nowrap align="center"><?=$CONFIRM_TIME?></td>
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
      
    </tr>
<?
 }

 if($OVERTIME_COUNT>0)
 {
?>
    
    </table>
<?
 }
 else
    message("",_("����ʷ��¼"));
?>

</div>
<br><br>
<div align="center">
  <input type="button"  value="<?=_("������ҳ")?>" class="btn" onClick="location='index.php';">
</div>
</body>
</html>