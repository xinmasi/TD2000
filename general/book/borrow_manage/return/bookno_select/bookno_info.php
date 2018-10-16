<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("选择图书");
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

function add_bookno(BOOK_NO,BOOK_NAME)
{
	parent_window.form1.BOOK_NO.value="";
  parent_window.form1.BOOK_NO.value=BOOK_NO;
  parent.close();
}

</script>


<body class="bodycolor" onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" onclick="borderize_on1(event)">
<?
$query1 = "SELECT MANAGE_DEPT_ID from BOOK_MANAGER where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGER_ID)";
$cursor1= exequery(TD::conn(),$query1);
while($ROW1=mysql_fetch_array($cursor1))
   $MANAGE_DEPT_ID.=$ROW1["MANAGE_DEPT_ID"];
   
$query = "SELECT BOOK_ID,BOOK_NO,DEPT,OPEN,BOOK_NAME from BOOK_INFO where ((BOOK_NO like '%$BOOK_NO%') or (BOOK_NAME like '%$BOOK_NO%')) order by BOOK_NO limit 0,50";
$cursor= exequery(TD::conn(),$query);
$BOOK_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{  
   $BOOK_ID=$ROW["BOOK_ID"];
   $BOOK_NO=$ROW["BOOK_NO"];
   $DEPT=$ROW["DEPT"];
   $OPEN=$ROW["OPEN"];   
   $BOOK_NAME=$ROW["BOOK_NAME"];
   $BOOK_NAME = str_replace("\r","",$BOOK_NAME);
   $BOOK_NAME = str_replace("\n","",$BOOK_NAME);
   
   $OPEN_ARR=explode(";", $OPEN);
   if ($_SESSION["LOGIN_USER_PRIV"]!=1)
   {
   	if (!find_id($OPEN_ARR[0], $_SESSION["LOGIN_DEPT_ID"]) && !find_id($OPEN_ARR[1], $_SESSION["LOGIN_USER_ID"]) && !find_id($OPEN_ARR[2], $_SESSION["LOGIN_USER_PRIV"]) && $OPEN_ARR[0]!="ALL_DEPT")
   	   continue;
   }
   
   
   
   //if(!find_id($MANAGE_DEPT_ID,$DEPT) && $MANAGE_DEPT_ID!="ALL_DEPT")
     // continue;
      
   $BOOK_COUNT++;
   
   if($BOOK_COUNT==1)
   {
?>

<table class="TableList"  width="95%" align="center">
<?
    }
?>
<tr class="TableControl">
  <td class="menulines" align="center" onClick="javascript:add_bookno('<?=$BOOK_NO?>','<?=$BOOK_NAME?>')" style="cursor:hand"><?=$BOOK_NO?>(<?=$BOOK_NAME?>)</a></td>
</tr>
<?
   $BOOK_COUNT++;
}

if($BOOK_COUNT==0)
   Message(_("提示"),_("没有图书信息"));
else
{
?>
<thead class="TableControl">
  <th bgcolor="#d6e7ef" align="center"><b><?=_("选择图书编号（最多显示50条）")?></b></th>
</thead>
</table>

<?
}
?>

</body>
</html>
