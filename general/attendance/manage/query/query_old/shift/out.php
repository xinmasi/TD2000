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
 if($DEPARTMENT1!="ALL_DEPT")
	  $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);

?>
<!------------------------------------- �����¼ ------------------------------->


<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�����¼")?></span>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='export_out.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_("���������¼")?>" name="button">
    </td>
  </tr>
</table>
<table class="TableList" width="100%">  
<?
if($DEPARTMENT1!="ALL_DEPT")
{
	 $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
   $WHERE_STR=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
}

 $query = "SELECT * from ATTEND_OUT,USER,USER_EXT,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID and ATTEND_OUT.USER_ID=USER.USER_ID and USER_EXT.USER_ID=USER.USER_ID and USER_EXT.DUTY_TYPE='99' and to_days(SUBMIT_TIME)>=to_days('$DATE1') and to_days(SUBMIT_TIME)<=to_days('$DATE2') and ALLOW='1' order by DEPT_NO,USER_NO,USER_NAME";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $OUT_COUNT=0;
  $TITLE_IS_NO=mysql_num_rows($cursor);
 if($TITLE_IS_NO>0)
 {
 ?>
     <tr class="TableHeader">
        <td width="5%" align="center" nowrap><?=_("����")?></td>
        <td width="5%" align="center" nowrap><?=_("����")?></td>
        <td width="8%" align="center" nowrap><?=_("����ʱ��")?></td>
        <td width="33%" align="center" class="AutoNewline"><?=_("���ԭ��")?></td>
        <td width="7%" align="center" nowrap><?=_("�Ǽ�")?>IP</td>
        <td width="6%" align="center" nowrap><?=_("�������")?></td>
        <td width="7%" align="center" nowrap><?=_("���ʱ��")?></td>
        <td width="6%" align="center" nowrap><?=_("����ʱ��")?></td>
        <td width="3%" align="center" nowrap><?=_("ʱ��")?></td>
        <td width="6%" align="center" nowrap><?=_("�������Ա")?></td>
        <td width="6%" align="center" nowrap><?=_("������Ա")?></td>
        <td width="6%" align="center" nowrap><?=_("����ʱ��")?></td>
        <td width="5%" align="center" nowrap><?=_("״̬")?></td>
        <td width="9%" align="center" nowrap><?=_("����")?></td>
    </tr>
 <?
 }
 while($ROW=mysql_fetch_array($cursor))
 {
   $AGENT_NAME="";
   $USER_ID=$ROW["USER_ID"];
   $USER_NAME=$ROW["USER_NAME"];
   $OUT_TYPE=$ROW["OUT_TYPE"];
   $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
   $SUBMIT_DATE=substr($SUBMIT_TIME,0,10);
   $OUT_TIME1=$ROW["OUT_TIME1"];
   $OUT_TIME2=$ROW["OUT_TIME2"];
   $CREATE_DATE=$ROW["CREATE_DATE"]; 
   $ALLOW=$ROW["ALLOW"];
   $STATUS=$ROW["STATUS"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $HANDLE_TIME=$ROW["HANDLE_TIME"]=="0000-00-00 00:00:00" ? "" : $ROW["HANDLE_TIME"];
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $LEADER_ID=$ROW["LEADER_ID"];
   $AGENT=$ROW["AGENT"];
   $OUT_ID=$ROW["OUT_ID"]; 
   
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
    
   if(!is_dept_priv($DEPT_ID))
      continue;

   $OUT_COUNT++;

   if($ALLOW=="0" && $STATUS=="0")
      $ALLOW_DESC=_("������");
   else if($ALLOW=="1" && $STATUS=="0")
      $ALLOW_DESC="<font color=green>"._("����׼")."</font>";
   else if($ALLOW=="2" && $STATUS=="0")
      $ALLOW_DESC="<font color=red>"._("δ��׼")."</font>";
   else if($ALLOW=="1" && $STATUS=="1")
      $ALLOW_DESC=_("�ѹ���");  

   $DEPT_ID=intval($DEPT_ID);
   $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_DEPT_NAME=$ROW["DEPT_NAME"];

    if($OUT_COUNT>=1)
    {
  $ALL_HOURS3 = floor((strtotime($OUT_TIME2)-strtotime($OUT_TIME1)) / 3600);
  $HOUR13 = (strtotime($OUT_TIME2)-strtotime($OUT_TIME1)) % 3600;
  $MINITE3 = floor($HOUR13 / 60);
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$USER_DEPT_NAME?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td nowrap align="center"><?=$CREATE_DATE?></td>      
      <td class="AutoNewline" width="400"><?=$OUT_TYPE?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center"><?=$SUBMIT_DATE?></td>
      <td nowrap align="center"><?=$OUT_TIME1?></td>
      <td nowrap align="center"><?=$OUT_TIME2?></td>
      <td nowrap align="center"><?=$ALL_HOURS3._("Сʱ").$MINITE3._("��")?></td>
      
      <td nowrap align="center"><?=$AGENT_NAME?></td>
      <td nowrap align="center"><?=$LEADER_NAME?></td>
      <td nowrap align="center"><?=$HANDLE_TIME?></td>
      <td nowrap align="center"><?=$ALLOW_DESC?></td>
      <td nowrap align="center">
	<?
    if($_SESSION["LOGIN_USER_PRIV"]==1)
    {
    ?>
            <a href="delete.php?MYACTION=out&OUT_ID=<?=$OUT_ID?>&DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&USER_ID=<?=$USER_ID?>&SUBMIT_TIME=<?=urlencode($SUBMIT_TIME)?>"><?=_("ɾ��")?></a>
    <?
    }
    ?>
          </td>
        </tr>
    <?
     }
 }
 if($OUT_COUNT<=0)
 {
	 message("",_("�������¼"));  
 }
?>
</table>
<br>
</body>
</html>