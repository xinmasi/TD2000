<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�Ӱ������ѯ");
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
  //----------- �Ϸ���У�� ---------
  if($DATE1!="")
  {
    $TIME_OK=is_date($DATE1);

    if(!$TIME_OK)
    { Message(_("����"),_("��ʼ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if($DATE2!="")
  {
    $TIME_OK=is_date($DATE2);

    if(!$TIME_OK)
    { Message(_("����"),_("��ֹ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if(compare_date($DATE1,$DATE2)==1)
  { Message(_("����"),_("��ѯ����ʼ���ڲ������ڽ�ֹ����"));
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
  	 Message(_("����"),_("�����ڹ���Χ�ڵ��û�").$DEPT_ID);
     exit;
 }

 $CUR_DATE=date("Y-m-d",time());

 $query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $DAY_TOTAL=$ROW[0]+1;
?>
<!------------------------------------- �Ӱ��¼ ------------------------------->

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�Ӱ��¼")?></span><br>
    </td>
  </tr>
</table>

<?
 $query = "SELECT * from ATTENDANCE_OVERTIME where USER_ID='$USER_ID' and START_TIME>='$DATE1' and END_TIME<='$DATE2' and (ALLOW='1' or ALLOW='3') order by START_TIME";
 $cursor= exequery(TD::conn(),$query);
 $OVERTIME_COUNT=0;
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

    $APPROVE_NAME="";
    $query = "SELECT * from USER where USER_ID='$APPROVE_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $APPROVE_NAME=$ROW["USER_NAME"];

   if($STATUS=="0")
      $STATUS_DESC=_("δȷ��");
   else if($STATUS=="1")
      $STATUS_DESC=_("��ȷ��");
   if($ALLOW=='0')
      $STATUS_DESC=_("����");
   if($ALLOW=='2')
      $STATUS_DESC=_("����׼");

    if($OVERTIME_COUNT==1)
    {
?>

    <table class="TableList"  width="100%">

<?
    }
?>
    <tr class="TableData">
    	<td nowrap align="center"><?=$RECORD_TIME?></td>
      <td align="left" style="word-wrap:break-word;word-break:break-all;"><?=$OVERTIME_CONTENT?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center"><?=$START_TIME?></td>
      <td nowrap align="center"><?=$END_TIME?></td>
      <td nowrap align="center"><?=$APPROVE_NAME?></td>
      <td nowrap align="center"><?=$STATUS_DESC?></td>
    </tr>
<?
 }

 if($OVERTIME_COUNT>0)
 {
?>
    <thead class="TableHeader">
    	<td nowrap align="center"><?=_("����ʱ��")?></td>
      <td nowrap align="center"><?=_("�Ӱ�����")?></td>
      <td nowrap align="center"><?=_("�Ǽ�")?>IP</td>
      <td nowrap align="center"><?=_("�Ӱ࿪ʼʱ��")?></td>
      <td nowrap align="center"><?=_("�Ӱ����ʱ��")?></td>
      <td nowrap align="center"><?=_("������Ա")?></td>
      <td nowrap align="center"><?=_("״̬")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("�޼Ӱ��¼"));
?>
<br>
<div align="center">
	<input type="button"  class="BigButton" value="<?=_("�ر�")?>" onclick="window.close();">
</div>
</body>
</html>