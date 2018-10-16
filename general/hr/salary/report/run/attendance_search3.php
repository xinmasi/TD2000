<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("考勤情况查询");
include_once("inc/header.inc.php");
?>


<script>
function out_edit(OUT_ID)
{
 URL="out_edit.php?OUT_ID="+OUT_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"out_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function leave_edit(LEAVE_ID)
{
 URL="leave_edit.php?LEAVE_ID="+LEAVE_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"leave_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function evection_edit(EVECTION_ID)
{
 URL="evection_edit.php?EVECTION_ID="+EVECTION_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"evection_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}


</script>


<body class="bodycolor">

<?
  //----------- 合法性校验 ---------
  if($DATE1!="")
  {
    $TIME_OK=is_date($DATE1);

    if(!$TIME_OK)
    { Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if($DATE2!="")
  {
    $TIME_OK=is_date($DATE2);

    if(!$TIME_OK)
    { Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if(compare_date($DATE1,$DATE2)==1)
  { Message(_("错误"),_("查询的起始日期不能晚于截止日期"));
    Button_Back();
    exit;
  }

 $query = "SELECT * from USER where USER_ID='$USER_ID'";
 $cursor= exequery(TD::conn(),$query);
 $LINE_COUNT=0;
 if($ROW=mysql_fetch_array($cursor))
 {
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $query_1="select DUTY_TYPE from USER_EXT where USER_ID='$USER_ID'";
    $cursor_1=exequery(TD::conn(),$query_1);
    if($row1=mysql_fetch_array($cursor_1))
    	$DUTY_TYPE=$ROW["DUTY_TYPE"];
    $DEPT_ID=$ROW["DEPT_ID"];
 }

 if(!is_dept_priv($DEPT_ID) && $_SESSION["LOGIN_USER_PRIV"]!=1)
 {
  	 Message(_("错误"),_("不属于管理范围内的用户").$DEPT_ID);
     exit;
 }

 $CUR_DATE=date("Y-m-d",time());

 $query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $DAY_TOTAL=$ROW[0]+1;
?>
<!------------------------------------- 出差记录 ------------------------------->


<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("出差记录")?></span><br>
    </td>
  </tr>
</table>


<?
 $query = "SELECT * from ATTEND_EVECTION where USER_ID='$USER_ID' and ((to_days(EVECTION_DATE1)>=to_days('$DATE1') and to_days(EVECTION_DATE1)<=to_days('$DATE2')) or (to_days(EVECTION_DATE2)>=to_days('$DATE1') and to_days(EVECTION_DATE2)<=to_days('$DATE2')) or (to_days(EVECTION_DATE1)<=to_days('$DATE1') and to_days(EVECTION_DATE2)>=to_days('$DATE2'))) and ALLOW='1'";
 $cursor= exequery(TD::conn(),$query);
 $EVECTION_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $EVECTION_COUNT++;

   $REGISTER_IP=$ROW["REGISTER_IP"];
   $EVECTION_ID=$ROW["EVECTION_ID"];
   $EVECTION_DATE1=$ROW["EVECTION_DATE1"];
   $EVECTION_DATE1=strtok($EVECTION_DATE1," ");

   $EVECTION_DATE2=$ROW["EVECTION_DATE2"];
   $EVECTION_DATE2=strtok($EVECTION_DATE2," ");

   $EVECTION_DEST=$ROW["EVECTION_DEST"];

   $LEADER_ID=$ROW["LEADER_ID"];
   $STATUS=$ROW["STATUS"];
   $REASON=$ROW["REASON"];

    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$USER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $LEADER_NAME=$ROW["USER_NAME"];



   if($STATUS=="1")
      $STATUS=_("在外");
   else
      $STATUS=_("归来");


    if($EVECTION_COUNT==1)
    {
?>

    <table class="TableList"  width="100%">

<?
    }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$EVECTION_DEST?></td>
      <td align="left" style="word-wrap:break-word;word-break:break-all;"><?=$REASON?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center"><?=$EVECTION_DATE1?></td>
      <td nowrap align="center"><?=$EVECTION_DATE2?></td>
      <td nowrap align="center"><?=$LEADER_NAME?></td>
      <td nowrap align="center"><?=$STATUS?></td>
      <td nowrap align="center"></td>
    </tr>
<?
 }

 if($EVECTION_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("出差地点")?></td>
      <td nowrap align="center"><?=_("出差原因")?></td>
      <td nowrap align="center"><?=_("登记")?>IP</td>
      <td nowrap align="center"><?=_("开始日期")?></td>
      <td nowrap align="center"><?=_("结束日期")?></td>
      <td nowrap align="center"><?=_("审批人员")?></td>
      <td nowrap align="center"><?=_("状态")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("无出差记录"));
?>

<br>
<div align="center">
	<input type="button"  class="BigButton" value="<?=_("关闭")?>" onclick="window.close();">
</div>
</body>
</html>