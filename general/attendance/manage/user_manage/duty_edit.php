<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$query = "SELECT * from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $POST_PRIV=$ROW["POST_PRIV"];

if($_SESSION["LOGIN_USER_PRIV"]!=1 && $POST_PRIV!=1)
{
   Message ("",_("无上下班登记修改权限"));
   exit;
}

$HTML_PAGE_TITLE = _("上下班登记修改");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">



<body class="attendance">

<?
 //---- 取规定上下班时间 -----
 $DUTY_TYPE=intval($DUTY_TYPE);
 $query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $DUTY_NAME=$ROW["DUTY_NAME"];

    $DUTY_TIME1=$ROW["DUTY_TIME1"];
    $DUTY_TIME2=$ROW["DUTY_TIME2"];
    $DUTY_TIME3=$ROW["DUTY_TIME3"];
    $DUTY_TIME4=$ROW["DUTY_TIME4"];
    $DUTY_TIME5=$ROW["DUTY_TIME5"];
    $DUTY_TIME6=$ROW["DUTY_TIME6"];

    $DUTY_TYPE1=$ROW["DUTY_TYPE1"];
    $DUTY_TYPE2=$ROW["DUTY_TYPE2"];
    $DUTY_TYPE3=$ROW["DUTY_TYPE3"];
    $DUTY_TYPE4=$ROW["DUTY_TYPE4"];
    $DUTY_TYPE5=$ROW["DUTY_TYPE5"];
    $DUTY_TYPE6=$ROW["DUTY_TYPE6"];
 }

 $query = "SELECT * from USER where USER_ID='$USER_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $USER_NAME=$ROW["USER_NAME"];
    $DEPT_ID=$ROW["DEPT_ID"];
 }
?>

<h5 class="attendance-title"><?=_("上下班登记修改")?> - <?=$USER_NAME?> - <?=format_date($SOME_DATE)?></h5>
<br>

<?
Message(_("注意"),_("输入的时间格式要形如 12:12:12，不填写则代表未登记"));
?>

<form action="duty_update.php" name="form1">
<table class="table table-middle table-bordered" align="center" >
  <tr class="">
<?
 $COUNT_TIMES=0;
 if($DUTY_TIME1!="")
 {
 	  $COUNT_TIMES++;
    if($DUTY_TYPE1=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?> (<?=$DUTY_TIME1?>)</td>
<?
 }
 if($DUTY_TIME2!="")
 {
 	  $COUNT_TIMES++;
    if($DUTY_TYPE2=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?> (<?=$DUTY_TIME2?>)</td>
<?
 }
 if($DUTY_TIME3!="")
 {
 	  $COUNT_TIMES++;
    if($DUTY_TYPE3=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?> (<?=$DUTY_TIME3?>)</td>
<?
 }
 if($DUTY_TIME4!="")
 {
 	  $COUNT_TIMES++;
    if($DUTY_TYPE4=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?> (<?=$DUTY_TIME4?>)</td>
<?
 }
 if($DUTY_TIME5!="")
 {
 	  $COUNT_TIMES++;
    if($DUTY_TYPE5=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?> (<?=$DUTY_TIME5?>)</td>
<?
 }
 if($DUTY_TIME6!="")
 {
 	  $COUNT_TIMES++;
    if($DUTY_TYPE6=="1")
       $DUTY_TYPE_DESC=_("上班");
    else
       $DUTY_TYPE_DESC=_("下班");
?>
    <td nowrap align="center"><?=$DUTY_TYPE_DESC?> (<?=$DUTY_TIME6?>)</td>
  </tr>

<?
    }
    $DEPT_ID=intval($DEPT_ID);
    $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
       $USER_DEPT_NAME=$ROW["DEPT_NAME"];

    if($USER_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="">
<?
 //---- 第1组 -----
 if($DUTY_TIME1!="")
 {
    $query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$SOME_DATE') and REGISTER_TYPE='1'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $REGISTER_TIME1=$ROW["REGISTER_TIME"];
       $REGISTER_TIME1=strtok($REGISTER_TIME1," ");
       $REGISTER_TIME1=strtok(" ");
    }

?>
      <td nowrap align="center">
      <input name="REGISTER_TIME1" value="<?=$REGISTER_TIME1?>" class="input-small" size="8">
      </td>
<?
 }

 //---- 第2组 -----
 if($DUTY_TIME2!="")
 {
    $query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$SOME_DATE') and REGISTER_TYPE='2'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $REGISTER_TIME2=$ROW["REGISTER_TIME"];
       $REGISTER_TIME2=strtok($REGISTER_TIME2," ");
       $REGISTER_TIME2=strtok(" ");

    }

?>
      <td nowrap align="center">
      <input name="REGISTER_TIME2" value="<?=$REGISTER_TIME2?>" class="input-small" size="8">
      </td>
<?
 }

 //---- 第3组 -----
 if($DUTY_TIME3!="")
 {
    $query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$SOME_DATE') and REGISTER_TYPE='3'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $REGISTER_TIME3=$ROW["REGISTER_TIME"];
       $REGISTER_TIME3=strtok($REGISTER_TIME3," ");
       $REGISTER_TIME3=strtok(" ");
    }

?>
      <td nowrap align="center">
      <input name="REGISTER_TIME3" value="<?=$REGISTER_TIME3?>" class="input-small" size="8">
      </td>
<?
 }

 //---- 第4组 -----
 if($DUTY_TIME4!="")
 {
    $query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$SOME_DATE') and REGISTER_TYPE='4'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $REGISTER_TIME4=$ROW["REGISTER_TIME"];
       $REGISTER_TIME4=strtok($REGISTER_TIME4," ");
       $REGISTER_TIME4=strtok(" ");
    }

?>
      <td nowrap align="center">
      <input name="REGISTER_TIME4" value="<?=$REGISTER_TIME4?>" class="input-small" size="8">
      </td>
<?
 }

 //---- 第5组 -----
 if($DUTY_TIME5!="")
 {
    $query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$SOME_DATE') and REGISTER_TYPE='5'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $REGISTER_TIME5=$ROW["REGISTER_TIME"];
       $REGISTER_TIME5=strtok($REGISTER_TIME5," ");
       $REGISTER_TIME5=strtok(" ");
    }
?>
      <td nowrap align="center">
      <input name="REGISTER_TIME5" value="<?=$REGISTER_TIME5?>" class="input-small" size="8">
      </td>
<?
 }

 //---- 第6组 -----
 if($DUTY_TIME6!="")
 {
    $query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$SOME_DATE') and REGISTER_TYPE='6'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $REGISTER_TIME6=$ROW["REGISTER_TIME"];
       $REGISTER_TIME6=strtok($REGISTER_TIME6," ");
       $REGISTER_TIME6=strtok(" ");
    }
?>
      <td nowrap align="center">
      <input name="REGISTER_TIME6" value="<?=$REGISTER_TIME6?>" class="input-small" size="8">
      </td>
<?
 }
?>
   </tr>
   <tr class="">  
     <td><?=_("修改原因")?></td>
     <td colspan="<?=$COUNT_TIMES-1?>">     	 
     	 <textarea name="REMARK_EDIT" class="" cols="40" rows="2"><?=$REMARK_EDIT?></textarea>
     </td>
   </tr>
</table>

<br>
<div align="center">
  <input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
  <input type="hidden" name="SOME_DATE" value="<?=$SOME_DATE?>">
  <input type="submit"  value="<?=_("保存")?>" class="btn btn-primary">&nbsp;&nbsp;
  <input type="button"  value="<?=_("返回")?>" class="btn" onClick="history.back();">
</div>
</form>

</body>
</html>