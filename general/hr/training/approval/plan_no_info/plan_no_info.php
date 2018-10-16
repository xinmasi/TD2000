<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

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
function add_plan_no(T_PLAN_NO,T_PLAN_NAME)
{
	parent_window.form1.T_PLAN_NO.value=T_PLAN_NO;
	parent_window.form1.T_PLAN_NAME.value=T_PLAN_NAME;
  parent.close();
}

</script>


<body class="bodycolor" onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" onclick="borderize_on1(event)">
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
  <td class="menulines" align="center" onClick="javascript:add_plan_no('<?=$T_PLAN_NO?>','<?=$T_PLAN_NAME?>')" style="cursor:hand"><?=$T_PLAN_NAME?>/<?=$T_PLAN_NO?></a></td>
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
