<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("���������ѯ");
include_once("inc/header.inc.php");
?>


<style>
.AutoNewline
{
  word-break: break-all;/*����*/
}
</style>


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

 $CUR_DATE=date("Y-m-d",time());

 $query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $DAY_TOTAL=$ROW[0]+1;
?>
<!------------------------------------- �����¼ ------------------------------->


<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�����¼")?></span>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='export_evection.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_("���������¼")?>" name="button">
    </td>
  </tr>
</table>

<?
$is_manager = 0;

if($DEPARTMENT1!="ALL_DEPT")
{
	$DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
	$WHERE_STR=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
	
	$sql = "SELECT DEPT_ID FROM hr_manager WHERE find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_MANAGER) and DEPT_ID = '$DEPARTMENT1'";
	$cursor1 = exequery(TD::conn(),$sql);
	if($arr=mysql_fetch_array($cursor1))
	{
		$is_manager = 1;
	} 
}

 $query = "SELECT * from ATTEND_EVECTION,USER,USER_EXT,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID  ".$WHERE_STR." and ATTEND_EVECTION.USER_ID=USER.USER_ID and USER_EXT.USER_ID=USER.USER_ID and USER_EXT.DUTY_TYPE!='99' and ((to_days(EVECTION_DATE1)>=to_days('$DATE1') and to_days(EVECTION_DATE1)<=to_days('$DATE2')) or (to_days(EVECTION_DATE2)>=to_days('$DATE1') and to_days(EVECTION_DATE2)<=to_days('$DATE2')) or (to_days(EVECTION_DATE1)<=to_days('$DATE1') and to_days(EVECTION_DATE2)>=to_days('$DATE2'))) and ALLOW='1' order by DEPT_NO,USER_NO,USER_NAME";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $EVECTION_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $AGENT_NAME="";
   $USER_ID=$ROW["USER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $USER_NAME=$ROW["USER_NAME"];
   $EVECTION_ID=$ROW["EVECTION_ID"];
   $EVECTION_DATE1=$ROW["EVECTION_DATE1"];
   $EVECTION_DATE1=strtok($EVECTION_DATE1," ");
   $EVECTION_DATE2=$ROW["EVECTION_DATE2"];
   $EVECTION_DATE2=strtok($EVECTION_DATE2," ");
   $EVECTION_DEST=$ROW["EVECTION_DEST"];
   $STATUS=$ROW["STATUS"];
   $ALLOW=$ROW["ALLOW"];
   $HANDLE_TIME=$ROW["HANDLE_TIME"]=="0000-00-00 00:00:00" ? "" : $ROW["HANDLE_TIME"];
   $AGENT=$ROW["AGENT"];
   $RECORD_TIME=$ROW["RECORD_TIME"]=="0000-00-00 00:00:00" ? $EVECTION_DATE1 : $ROW["RECORD_TIME"];   
   
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $LEADER_ID=$ROW["LEADER_ID"];

    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $LEADER_NAME=$ROW["USER_NAME"];
       
    if($AGENT != $USER_ID && $AGENT != "")
    {
       $query2 = "SELECT * from USER where USER_ID='$AGENT'";
       $cursor2= exequery(TD::conn(),$query2);
       if($ROW2=mysql_fetch_array($cursor2))
          $AGENT_NAME=$ROW2["USER_NAME"];
    }

   if(!is_dept_priv($DEPT_ID) && $is_manager !=1)
      continue;

   $EVECTION_COUNT++;

  	if($ALLOW=="0" && $STATUS=="1")
     	$ALLOW_DESC=_("������");
  	else if($ALLOW=="1" && $STATUS=="1")
     	$ALLOW_DESC="<font color=green>"._("����׼")."</font>";
  	else if($ALLOW=="2" && $STATUS=="1")
     	$ALLOW_DESC="<font color=red>"._("δ��׼")."</font>";
  	else if($ALLOW=="1" && $STATUS=="2")
     	$ALLOW_DESC=_("�ѹ���");
   $DEPT_ID=intval($DEPT_ID);
   $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_DEPT_NAME=$ROW["DEPT_NAME"];


    if($EVECTION_COUNT==1)
    {
?>

    <table class="TableList" width="95%">

<?
    }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$USER_DEPT_NAME?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td nowrap align="center"><?=$RECORD_TIME?></td>      
      <td class="AutoNewline" width="400" align="center"><?=$EVECTION_DEST?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center"><?=$EVECTION_DATE1?></td>
      <td nowrap align="center"><?=$EVECTION_DATE2?></td>
      <td nowrap align="center"><?=$AGENT_NAME?></td>
      <td nowrap align="center"><?=$LEADER_NAME?></td>
      <td nowrap align="center"><?=$HANDLE_TIME?></td>
      <td nowrap align="center"><?=$ALLOW_DESC?></td>
      <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
      	<a href="delete_evection.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&EVECTION_ID=<?=$EVECTION_ID?>"><?=_("ɾ��")?></a>
<?
}
?>
      	</td>
    </tr>
<?
 }

 if($EVECTION_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("����ʱ��")?></td>
      <td nowrap align="center"><?=_("����ص�")?></td>
      <td nowrap align="center"><?=_("�Ǽ�")?>IP</td>
      <td nowrap align="center"><?=_("��ʼ����")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("�������Ա")?></td>
      <td nowrap align="center"><?=_("������Ա")?></td>
      <td nowrap align="center"><?=_("����ʱ��")?></td>
      <td nowrap align="center"><?=_("״̬")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </thead>
    </table>
<?
 }
 else
    message("",_("�޳����¼"));
?>

<br>

<br>
</body>
</html>