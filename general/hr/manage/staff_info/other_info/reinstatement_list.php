<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("Ա����ְ����");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
</script>

<body class="bodycolor">
<?
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
$PAGE_START=intval($PAGE_START);

if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_STAFF_REINSTATEMENT where REINSTATEMENT_PERSON='$USER_ID' ";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("��ְ��Ϣ")?></span>&nbsp;
    </td>
<?
if($TOTAL_ITEMS>0)
{
?>    
    <td align="right" valign="bottom" class="small1"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></td>
<?
}
?>
    </tr>
</table>
  <div align="center">
     <input type="button" value="<?=_("�½�Ա����ְ��Ϣ")?>" class="BigButton" onClick="window.open('reinstatement_new.php?USER_ID=<?=$USER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');">
  </div>
  <br>
<?
if($TOTAL_ITEMS>0)
{
?>  
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("��ְ��Ա")?></td>
      <td nowrap align="center"><?=_("��ְ����")?></td>
      <td nowrap align="center"><?=_("�⸴ְ����")?></td>
      <td nowrap align="center"><?=_("���ʻָ�����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
  </tr>
<?
}
else
{
   Message("",_("��Ա����ְ��Ϣ��¼"));	
}

$query = "SELECT * from HR_STAFF_REINSTATEMENT where REINSTATEMENT_PERSON='$USER_ID' order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$REINSTATEMENT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $REINSTATEMENT_COUNT++;

   $REINSTATEMENT_ID=$ROW["REINSTATEMENT_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $REAPPOINTMENT_TIME_PLAN=$ROW["REAPPOINTMENT_TIME_PLAN"];
   $REAPPOINTMENT_TYPE=$ROW["REAPPOINTMENT_TYPE"];
   $REINSTATEMENT_PERSON=$ROW["REINSTATEMENT_PERSON"];
   $FIRST_SALARY_TIME=$ROW["FIRST_SALARY_TIME"];
   $ADD_TIME=$ROW["ADD_TIME"];
   
	 $REAPPOINTMENT_TYPE=get_hrms_code_name($REAPPOINTMENT_TYPE,"HR_STAFF_REINSTATEMENT");
	 
?>
   <tr class="TableData">
      <td nowrap align="center"><?=substr(GetUserNameById($REINSTATEMENT_PERSON),0,-1)?></td>
      <td nowrap align="center"><?=$REAPPOINTMENT_TYPE?></td>
      <td nowrap align="center"><?=$REAPPOINTMENT_TIME_PLAN?></td>
      <td nowrap align="center"><?=$FIRST_SALARY_TIME?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('reinstatement_detail.php?REINSTATEMENT_ID=<?=$REINSTATEMENT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("��ϸ��Ϣ")?></a>&nbsp;
      <a href="javascript:;" onClick="window.open('reinstatement_modify.php?REINSTATEMENT_ID=<?=$REINSTATEMENT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("�༭")?></a>&nbsp;
      </td>
   </tr>
<?
}
?>
</table>
</body>

</html>
