<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("ѡ��ƻ�����");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/menu_button.js" type="text/javascript" charset="utf-8"></script>
<script Language="JavaScript">
var parent_window;
if(parent.dialogArguments)
	parent_window = parent.dialogArguments.document;
else
	parent_window = parent.opener.document;
	
function staff_user_select(T_PLAN_NO,T_PLAN_NAME)
{	
	parent_window.form1.TO_ID.value=T_PLAN_NO;
	parent_window.form1.TO_NAME.value=T_PLAN_NAME;
	parent.close();
}

</script>
<body class="bodycolor">
<?

$query = "SELECT T_PLAN_NO,T_PLAN_NAME from HR_TRAINING_PLAN where T_PLAN_NAME like '%$KEY_WORD%' order by T_PLAN_NO limit 0,50";
$cursor= exequery(TD::conn(),$query);
$NAME_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{  
	 $NAME_COUNT++;
   $T_PLAN_NO=$ROW["T_PLAN_NO"];
   $T_PLAN_NAME=$ROW["T_PLAN_NAME"];
   
   if($NAME_COUNT==1)
   {
?>

<table class="TableList"  width="95%" align="center">
<?
   }
?>
<tr class="TableControl">
  <td class="menulines" align="center" onClick="javascript:staff_user_select('<?=$T_PLAN_NO?>','<?=$T_PLAN_NAME?>')" style="cursor:hand"><?=$T_PLAN_NAME?>_<?=$T_PLAN_NO?></a></td>
</tr>
<?
   $NAME_COUNT++;
}

if($NAME_COUNT==0)
   Message(_("��ʾ"),_("�޴���ѵ�ƻ�"));
else
{
?>
<thead class="TableControl">
  <th bgcolor="#d6e7ef" align="center"><b><?=_("ѡ����ѵ�ƻ��������ʾ50����")?></b></th>
</thead>
</table>

<?
}
?>

</body>
</html>
