<?
include_once("inc/auth.inc.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("代码设置");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script Language="JavaScript">
function delete_code(CODE_ID,FUNC_NAME)
{
 var msg = sprintf("<?=_("确认要删除代码项 '%s' 吗？")?>", FUNC_NAME);
 if(window.confirm(msg))
 {
  URL="delete.php?CODE_ID=" + CODE_ID;
  location=URL;
 }
}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("代码项设置")?></span>
    </td>
  </tr>
</table>

<?
 $query = "SELECT * from HR_CODE where CODE_ID='$CODE_ID'";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 if($ROW=mysql_fetch_array($cursor))
 {
    $CODE_NO =$ROW["CODE_NO"];
    $CODE_NAME =$ROW["CODE_NAME"];
 }
 
 $PARENT_NO=$CODE_NO;
?>

<table class="TableBlock" align="center">
     <tr class="TableHeader" align="center">
      <td nowrap title="<?=$CODE_NAME?>" colspan="2">
        &nbsp;&nbsp;<b><?=$CODE_NAME?></b>&nbsp;&nbsp;
      </td>
     </tr>
     <tr>
      <td class="TableControl" align="center" colspan="2">
        <input type="button" value="<?=_("增加代码项")?>" class="BigButton" onclick="location='new.php?PARENT_NO=<?=$PARENT_NO?>';">
      </td>
     </tr>
<?
    $query1 = "SELECT * from HR_CODE where PARENT_NO='$PARENT_NO' order by CODE_ORDER";
    $cursor1= exequery(TD::conn(),$query1, $connstatus);

    while($ROW=mysql_fetch_array($cursor1))
    {
      $CODE_ID =$ROW["CODE_ID"];
      $CODE_NO =$ROW["CODE_NO"];
      $CODE_NAME =$ROW["CODE_NAME"];
      $CODE_FLAG=$ROW["CODE_FLAG"];
?>
        <tr class="TableData">
          <td nowrap title="<?=$CODE_NAME?>" >
            &nbsp;<b><?=$CODE_NO?>&nbsp;&nbsp;<?=$CODE_NAME?></b>&nbsp;
          </td>
          <td nowrap>&nbsp;
           <a href="edit.php?CODE_ID=<?=$CODE_ID?>"> <?=_("编辑")?></a>&nbsp;&nbsp;
<?
if($CODE_FLAG!="0")
{
?>
           <a href="javascript:delete_code(<?=$CODE_ID?>,'<?=$CODE_NAME?>');"> <?=_("删除")?></a>
<?
}
?>
          </td>
        </tr>

<?
    }//while
?>
    </table>
<br>

<div align="center">
<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
</div>

</body>
</html>