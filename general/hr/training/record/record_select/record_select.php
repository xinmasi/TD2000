<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("选择计划名称");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/menu_button.js"></script>
<script Language="JavaScript">
var parent_window;
if(parent.dialogArguments)
	parent_window = parent.dialogArguments.document;
else
	parent_window = parent.opener.document;

//var parent_window = parent.dialogArguments;
function staff_user_select(T_PLAN_NO,T_PLAN_NAME,T_JOIN_PERSON,T_JOIN_PERSON_NAME,T_INSTITUTION_NAME)
{	
	parent_window.form1.T_PLAN_NO.value=T_PLAN_NO;
	parent_window.form1.T_PLAN_NAME.value=T_PLAN_NAME;
	parent_window.form1.T_JOIN_PERSON.value=T_JOIN_PERSON;
	parent_window.form1.T_JOIN_PERSON_NAME.value=T_JOIN_PERSON_NAME;
	parent_window.form1.T_INSTITUTION_NAME.value=T_INSTITUTION_NAME;
    parent.close();
}

</script>

<body class="bodycolor">
<?
$query = "SELECT T_PLAN_NO,T_PLAN_NAME,T_JOIN_DEPT,T_JOIN_PERSON,T_INSTITUTION_NAME from  HR_TRAINING_PLAN where ASSESSING_STATUS='1' and T_PLAN_NAME like '%$KEY_WORD%' order by T_PLAN_NO limit 0,50";
$cursor= exequery(TD::conn(),$query);
$NAME_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{  
   $NAME_COUNT++;
   $T_PLAN_NO=$ROW["T_PLAN_NO"];
   $T_PLAN_NAME=$ROW["T_PLAN_NAME"];
   $T_JOIN_DEPT=td_trim($ROW["T_JOIN_DEPT"]);
   $T_JOIN_PERSON=$ROW["T_JOIN_PERSON"];   
   $T_INSTITUTION_NAME=$ROW["T_INSTITUTION_NAME"];
   $T_JOIN_PERSON_NAME=GetUserNameById($T_JOIN_PERSON);
   $USER_NAME="";
   if($T_JOIN_DEPT=="ALL_DEPT")
   {
     $query1="select USER_ID,USER_NAME from USER";
   }
   else
   {
 	 $query1="select USER_ID,USER_NAME from USER where find_in_set(DEPT_ID,'$T_JOIN_DEPT')";
   }
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW1=mysql_fetch_array($cursor1))
   {
       $USER_ID.=$ROW1["USER_ID"].",";
       $USER_NAME.=$ROW1["USER_NAME"].",";
   }
   $T_JOIN_PERSON.=$USER_ID;
   $T_JOIN_PERSON_NAME.=$USER_NAME;
   if($NAME_COUNT==1)
   {
?>

<table class="TableList"  width="95%" align="center">
<?
   }
?>
<tr class="TableControl">
  <td class="menulines" align="center" onClick="javascript:staff_user_select('<?=$T_PLAN_NO?>','<?=$T_PLAN_NAME?>','<?=$T_JOIN_PERSON?>','<?=$T_JOIN_PERSON_NAME?>','<?=$T_INSTITUTION_NAME?>')" style="cursor:hand"><?=$T_PLAN_NAME?>_<?=$T_PLAN_NO?></a></td>
</tr>
<?
   $NAME_COUNT++;
}

if($NAME_COUNT==0)
   Message(_("提示"),_("无此培训计划"));
else
{
?>
<thead class="TableControl">
  <th bgcolor="#d6e7ef" align="center"><b><?=_("选择培训计划（最多显示50条）")?></b></th>
</thead>
</table>

<?
}
?>

</body>
</html>
