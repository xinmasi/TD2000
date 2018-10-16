<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("上下班记录查询");
include_once("inc/header.inc.php");
?>


<script language="JavaScript">
function remark(USER_ID,REGISTER_TYPE,REGISTER_TIME)
{
  URL="../user_manage/remark.php?USER_ID="+USER_ID+"&REGISTER_TYPE="+REGISTER_TYPE+"&REGISTER_TIME="+REGISTER_TIME;
  myleft=(screen.availWidth-650)/2;
  window.open(URL,"formul_edit","height=250,width=450,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
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

$query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
  $DAY_TOTAL=$ROW[0]+1;

$MSG = sprintf(_("共%d天"), $DAY_TOTAL);
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle">
    <span class="big3"> <?=_("上下班查询结果")?> - [<?=format_date($DATE1)?> <?=_("至")?> <?=format_date($DATE2)?> <?=$MSG?>]</span><br>
    </td>
  </tr>
</table>

<br>

<?
$query = "SELECT * from SYS_PARA where PARA_NAME='NO_DUTY_USER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
  $NO_DUTY_USER=$ROW["PARA_VALUE"];

$query4 = "SELECT USER_EXT.DUTY_TYPE,USER.USER_NAME,USER.USER_ID,DEPARTMENT.DEPT_NAME from USER,USER_EXT,USER_PRIV,DEPARTMENT where not find_in_set(USER.USER_ID,'$NO_DUTY_USER') and USER_EXT.USER_ID=USER.USER_ID and USER_EXT.DUTY_TYPE='99' and DEPARTMENT.DEPT_ID = USER.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV order by DEPT_NO,PRIV_NO,USER_NO,USER_NAME";
$cursor4= exequery(TD::conn(),$query4);
$USER_COUNT=0;
while($ROW4=mysql_fetch_array($cursor4))
{
   $USER_COUNT++;
   $DUTY_TYPE=$ROW4["DUTY_TYPE"];
   $USER_NAME=$ROW4["USER_NAME"];
   $DEPT_NAME=$ROW4["DEPT_NAME"];
   $USER_ID=$ROW4["USER_ID"];  
   if($USER_COUNT==1)
   { 
?>
      <table class="TableList"  width="95%" align="center">
      <tr class="TableHeader">
        <td nowrap align="center"><?=_("姓名")?></td>
        <td nowrap align="center"><?=_("部门")?></td>
        <td nowrap align="center"><?=_("登记信息")?></td>
       </tr>
<?
   }
    	//查询考勤记录
    	$query1 = "SELECT * from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)>=to_days('$DATE1') and to_days(REGISTER_TIME)<=to_days('$DATE2')";
    	$cursor1= exequery(TD::conn(),$query1);
    	$LINE_COUNT=0;
    	while($ROW=mysql_fetch_array($cursor1))
    	{
    	  $LINE_COUNT++;
    	  $REGISTER_TIME=$ROW["REGISTER_TIME"];
    	  $REGISTER_IP=$ROW["REGISTER_IP"];
    	  $SXB=$ROW["SXB"];
    	  if($SXB=="1")
    	  	 $REGISTER_TIME=$REGISTER_TIME._("[上班]");
    	  else if($SXB=="2")
    	     $REGISTER_TIME=$REGISTER_TIME._("[下班]");   
?>
           <tr class="TableData">
             <td nowrap align="center"><?=$USER_NAME?></td>
             <td nowrap align="center"><?=$DEPT_NAME?></td>
             <td nowrap align="center"><?=$REGISTER_TIME?>(<?=$REGISTER_IP?>)</td>
           </tr>
<?
     }
}
?>
</table>
<?
if($USER_COUNT<='0')
{
   Message(_("提示"),_("没有找到要查询的记录！"));
}
Button_Back();
exit;
?>

</body>
</html>