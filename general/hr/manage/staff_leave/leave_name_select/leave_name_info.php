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
function add_leave_name(USER_ID,USER_NAME)
{
  parent_window.load_name(USER_ID,USER_NAME);
  parent.close();
}

</script>


<body class="bodycolor" onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" onclick="borderize_on1(event)">
<?
$query = "SELECT USER_ID, USER_NAME from USER where DEPT_ID='0' and USER_NAME like '%$KEY_WORD%' order by USER_NAME limit 0,50";
$cursor= exequery(TD::conn(),$query);
$NAME_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{  
	 $NAME_COUNT++;
   $USER_ID=$ROW["USER_ID"];
   $USER_NAME=$ROW["USER_NAME"];   
   
   if($NAME_COUNT==1)
   {
?>

<table class="TableList"  width="95%" align="center">
<?
   }
?>
<tr class="TableControl">
  <td class="menulines" align="center" onClick="javascript:add_leave_name('<?=$USER_ID?>','<?=$USER_NAME?>')" style="cursor:hand"><?=$USER_NAME?>_<?=$USER_ID?></a></td>
</tr>
<?
   $NAME_COUNT++;
}

if($NAME_COUNT==0)
   Message(_("提示"),_("无离职人员信息"));
else
{
?>
<thead class="TableControl">
  <th bgcolor="#d6e7ef" align="center"><b><?=_("选择离职者姓名（最多显示50条）")?></b></th>
</thead>
</table>

<?
}
?>

</body>
</html>
