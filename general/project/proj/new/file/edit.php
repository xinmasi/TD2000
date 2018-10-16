<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("编辑文件夹");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.SORT_NAME.value=="")
   { alert("<?=_("文件夹名称不能为空！")?>");
     return (false);
   }

   return (true);
}
</script>


<body class="bodycolor" onload="document.form1.SORT_NAME.focus();">

<?
 $query = "SELECT * from PROJ_FILE_SORT where SORT_ID='$SORT_ID'";
 $cursor= exequery(TD::conn(),$query);

 if($ROW=mysql_fetch_array($cursor))
 {
    $SORT_NO=$ROW["SORT_NO"];
    $SORT_NAME=$ROW["SORT_NAME"];
    $SORT_TYPE=$ROW["SORT_TYPE"];
 }
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("编辑文件夹")?></span>
    </td>
  </tr>
</table>

<br>
<table class="TableBlock" width="450" align="center">
  <form action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"> <?=_("排序号：")?></td>
      <td class="TableData">
        <input type="text" name="SORT_NO" size="20" maxlength="20" class="BigInput" value="<?=$SORT_NO?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("文件夹名称：")?></td>
      <td class="TableData">
        <input type="text" name="SORT_NAME" size="25" maxlength="100" class="BigInput" value="<?=$SORT_NAME?>">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="hidden" value="<?=$SORT_ID?>" name="SORT_ID">
        <input type="hidden" value="<?=$PROJ_ID?>" name="PROJ_ID">
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?PROJ_ID=<?=$PROJ_ID?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>