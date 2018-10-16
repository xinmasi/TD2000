<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("选择计划名称");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/menu_button.js" type="text/javascript" charset="utf-8"></script>
<script Language="JavaScript">
var parent_window;
if(parent.dialogArguments)
	parent_window = parent.dialogArguments.document;
else
	parent_window = parent.opener.document;
	
function staff_user_select(USER_ID,USER_NAME)
{	
	parent_window.form1.STAFF_USER_ID.value=USER_ID;
	parent_window.form1.STAFF_USER_NAME.value=USER_NAME;
  	parent.close();	
}

</script>
<body class="bodycolor">
<?

$query = "SELECT USER_ID,USER_NAME from USER where USER_NAME like '%$KEY_WORD%' order by USER_ID limit 0,50";
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
  <td class="menulines" align="center" onClick="javascript:staff_user_select('<?=$USER_ID?>','<?=$USER_NAME?>')" style="cursor:hand"><?=$USER_NAME?>_<?=$USER_ID?></a></td>
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
