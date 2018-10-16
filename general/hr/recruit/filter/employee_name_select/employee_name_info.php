<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("选择应聘者姓名");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/menu_button.js"></script>
<script Language="JavaScript">
var parent_window;
if(parent.dialogArguments)
{
    parent_window = parent.dialogArguments;
}
else
{
    parent_window = parent.opener;
}
function add_employee_name(EXPERT_ID,EMPLOYEE_NAME)
{
	parent_window.form1.EXPERT_ID.value=EXPERT_ID;
  parent_window.form1.EMPLOYEE_NAME.value=EMPLOYEE_NAME;
  parent_window.read_info(EXPERT_ID);
  parent.close();
}

</script>


<body class="bodycolor" onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" onclick="borderize_on1(event)">
<?
$query = "SELECT EXPERT_ID,EMPLOYEE_NAME from HR_RECRUIT_POOL where (EXPERT_ID like '%$KEY_WORD%' OR EMPLOYEE_NAME like '%$KEY_WORD%') and WHETHER_BY_SCREENING='0' order by EXPERT_ID limit 0,50 ";
$cursor= exequery(TD::conn(),$query);
$NAME_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{  
	 $NAME_COUNT++;
   $EXPERT_ID=$ROW["EXPERT_ID"];
   $EMPLOYEE_NAME=$ROW["EMPLOYEE_NAME"];   
   
   if($NAME_COUNT==1)
   {
?>

<table class="TableList"  width="95%" align="center">
<?
   }
?>
<tr class="TableControl">
  <td class="menulines" align="center" onClick="javascript:add_employee_name('<?=$EXPERT_ID?>','<?=$EMPLOYEE_NAME?>')" style="cursor:hand"><?=$EMPLOYEE_NAME?>_<?=$EXPERT_ID?></a></td>
</tr>
<?
   $NAME_COUNT++;
}

if($NAME_COUNT==0)
   Message(_("提示"),_("人才库无相应人才信息"));
else
{
?>
<thead class="TableControl">
  <th bgcolor="#d6e7ef" align="center"><b><?=_("选择应聘者姓名（最多显示50条）")?></b></th>
</thead>
</table>

<?
}
?>

</body>
</html>
