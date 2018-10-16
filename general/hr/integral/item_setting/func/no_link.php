<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("积分项设置");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script Language="JavaScript">
function delete_code(ITEM_ID,ITEM_NAME)
{
 var msg = sprintf("<?=_("确认要删除积分项 '%s' 吗？")?>", FUNC_NAME);
 if(window.confirm(msg))
 {
  URL="delete_no.php?ITEM_ID="+ITEM_ID;
  location=URL;
 }
}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("错误积分项列表")?></span>
    </td>
  </tr>
</table>

<?
 $query = "SELECT * from integral_item where PARENT_ITEM=''";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $ITEM_NO =$ROW["ITEM_NO"];
    $ITEM_NO_STR.=$ITEM_NO.",";
 }
?>

<table class="TableBlock" align="center">

<?
 $query = "SELECT * from integral_item where PARENT_ITEM!='' and not find_in_set(PARENT_ITEM,'$ITEM_NO_STR')";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $COUNT++;
    $ITEM_ID =$ROW["ITEM_ID"];
    $ITEM_NO =$ROW["ITEM_NO"];
    $ITEM_NAME =$ROW["ITEM_NAME"];
    $ITEM_EXT=unserialize($ROW["ITEM_EXT"]);
		if(is_array($ITEM_EXT) && $ITEM_EXT[MYOA_LANG_COOKIE] != "")
			 $ITEM_NAME = $ITEM_EXT[MYOA_LANG_COOKIE];
    
    $ITEM_FLAG=$ROW["ITEM_FLAG"];
?>
        <tr class="TableData">
          <td nowrap title="<?=$ITEM_NAME?>" >
            &nbsp;<b><?=$ITEM_NO?>&nbsp;&nbsp;<?=$ITEM_NAME?></b>&nbsp;
          </td>
          <td nowrap>&nbsp;
           <a href="edit_no.php?ITEM_ID=<?=$ITEM_ID?>"> <?=_("编辑")?></a>&nbsp;&nbsp;
<?
if($ITEM_FLAG!="0")
{
?>
           <a href="javascript:delete_code('<?=$ITEM_ID?>','<?=$ITEM_NAME?>');"> <?=_("删除")?></a>
<?
}
?>
          </td>
        </tr>

<?
 }//while
?>
    </table>

<?
if($COUNT==0)
   Message("",_("没有定义错误的积分项"));
?>
<br>

<div align="center">
<input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='../blank.php'">
</div>

</body>
</html>