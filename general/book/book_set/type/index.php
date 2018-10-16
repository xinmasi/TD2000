<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("图书类别管理");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.TYPE_NAME.value=="")
   { alert("<?=_("类别名称不能为空！")?>");
     return (false);
   }
}

function delete_type(TYPE_ID)
{
 msg='<?=_("确认要删除该类别吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?TYPE_ID=" + TYPE_ID;
  window.location=URL;
 }
}
</script>

<body class="bodycolor" onload="document.form1.TYPE_NAME.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("添加图书类别")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock"  width="400"  align="center" >
  <form action="add.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData"><?=_("类别名称：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_NAME" class="BigInput" size="25" maxlength="100">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("添加")?>" class="BigButton" title="<?=_("添加类别")?>" name="button">
    </td>
  </form>
</table>

<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("图书类别管理")?></span>
    </td>
  </tr>
</table>

<br>
<div align="center">

<?
 //============================ 显示已定义类别 =======================================
 $query = "select * from BOOK_TYPE order by TYPE_ID";
 $cursor= exequery(TD::conn(),$query);

 $TYPE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $TYPE_COUNT++;
    $TYPE_ID=$ROW["TYPE_ID"];
    $TYPE_NAME=$ROW["TYPE_NAME"];

    if($TYPE_COUNT==1)
    {
?>

    <table class="TableList" width="400">

<?
    }
    if($TYPE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";

?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$TYPE_NAME?></td>
      <td nowrap align="center" width="80">
      <a href="edit.php?TYPE_ID=<?=$TYPE_ID?>"> <?=_("编辑")?></a>
      <a href="javascript:delete_type(<?=$TYPE_ID?>);"> <?=_("删除")?></a>
      </td>
    </tr>
<?
 }

 if($TYPE_COUNT>0)
 {
?>
    <tr class="TableControl">
      <td colspan="4" align="center">
        &nbsp;
      </td>
    </tr>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("图书类别")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("尚未定义"));
?>

</div>

</body>
</html>