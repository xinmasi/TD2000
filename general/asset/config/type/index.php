<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("工作资产类别设置");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.TYPE_NO.value=="")
   { alert("<?=_("排序号不能为空！")?>");
     return (false);
   }

   if(document.form1.TYPE_NAME.value=="")
   { alert("<?=_("类型名称不能为空！")?>");
     return (false);
   }
}

function delete_type(TYPE_ID)
{
 msg='<?=_("确认要删除该资产类别？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?TYPE_ID=" + TYPE_ID;
  window.location=URL;
 }
}


function delete_all()
{
 msg='<?=_("确认要删除所有资产类别吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete_all.php";
  window.location=URL;
 }
}
</script>


<body class="bodycolor" onload="document.form1.TYPE_NO.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("添加资产类别")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="450" align="center" >
  <form action="add.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData"><?=_("排序号：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_NO" class="BigInput" size="5" maxlength="10">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("资产类别名称：")?></td>
    <td nowrap class="TableData">
        <input type="text" name="TYPE_NAME" class="BigInput" size="25" maxlength="25">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("添加")?>" class="BigButton" title="<?=_("添加资产类别")?>" name="button">
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理资产类别")?></span>
    </td>
  </tr>
</table>

<br>
<div align="center">

<?
 //============================ 显示已定义规则 =======================================
 $query = "SELECT * from CP_ASSET_TYPE order by TYPE_NO";
 $cursor= exequery(TD::conn(),$query);

 $TYPE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $TYPE_COUNT++;
    $TYPE_ID=$ROW["TYPE_ID"];
    $TYPE_NO=$ROW["TYPE_NO"];
    $TYPE_NAME=$ROW["TYPE_NAME"];

    if($TYPE_COUNT==1)
    {
?>

    <table class="TableList" width="450">

<?
    }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$TYPE_NO?></td>
      <td nowrap align="center"><?=$TYPE_NAME?></td>
      <td nowrap align="center" width="80">
      <a href="edit.php?TYPE_ID=<?=$TYPE_ID?>"> <?=_("编辑")?></a>
      <a href="javascript:delete_type('<?=$TYPE_ID?>');"> <?=_("删除")?></a>
      </td>
    </tr>
<?
 }

 if($TYPE_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("排序号")?></td>
      <td nowrap align="center"><?=_("类别名称")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    <thead class="TableControl">
      <td nowrap align="center" colspan="4">
      <input type="button" class="BigButton" OnClick="javascript:delete_all();" value="<?=_("全部删除")?>">
      </td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("尚未定义资产类别"));
?>

</div>

</body>
</html>