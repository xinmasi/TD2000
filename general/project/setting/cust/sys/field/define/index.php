<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("自定义字段设置");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script Language="JavaScript">
function delete_code(CODE_ID,TABLENAME,FIELDNO,FUNC_NAME)
{
 var msg = sprintf("<?=_("确认要删除该自定义字段 '%s'-'%s' 吗？")?>", FUNC_NAME, TABLENAME);
 if(window.confirm(msg))
 {
  URL="delete.php?CODE_ID="+CODE_ID+"&FIELDNO=" + FIELDNO;
  location=URL;
 }
}
</script>


<body class="bodycolor">
<?
 //先取1级分类的所有信息
 $query = "SELECT * from SYS_CODE where CODE_ID='$CODE_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
   $CODE_NO =$ROW["CODE_NO"];
   $PARENT_NO =$ROW["PARENT_NO"];
 }

?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("自定义字段")?> - <?=$CODE_NAME?></span>
    </td>
  </tr>
</table>
<div align="center">
  <input type="button" value="<?=_("增加自定义字段")?>" class="BigButton" onclick="parent.code_edit.location='field_new.php?W_CODE_NO=<?=$CODE_NO?>&CODE_ID=<?=$CODE_ID?>&CODE_NAME=<?=$CODE_NAME?>';">
</div>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <a name="bottom"><?=_("自定义字段管理")?></span>
    </td>
  </tr>
</table>
<br />

<?
   $query = "SELECT COUNT(*) from PROJ_FIELDSETTING where TYPE_CODE_NO='$CODE_ID' order by ORDERNO";
   $cursor = exequery(TD::conn(),$query);
   $ROW = mysql_fetch_array($cursor);
   
   if($ROW[0] <= 0){
      Message(_("提示"),_("该类型项目尚无自定义字段！"));
      exit;      
   }
?>
<table class="TableList" width="95%" align="center">
     <tr class="TableHeader" align="center">
      <td width="50" align="center"><?=_("排序号")?></td>
      <td align="center"><?=_("字段名称")?></td>
      <td width="70" align="center"><?=_("类型")?></td>
      <td width="70" align="center"><?=_("查询字段")?></td>
      <td align="center"><?=_("操作")?></td>
     </tr>
<?
   $query = "SELECT * from PROJ_FIELDSETTING where TYPE_CODE_NO='$CODE_ID' order by ORDERNO";
   $cursor = exequery(TD::conn(),$query);
 while($ROW = mysql_fetch_array($cursor))
 {
    $FIELDNO =$ROW["FIELDNO"];
    $FIELDNAME =$ROW["FIELDNAME"];
    $ORDERNO =$ROW["ORDERNO"];
    $STYPE =$ROW["STYPE"];
    $ISQUERY =$ROW["ISQUERY"];
    
    if($STYPE=="T")
       $STYPE=_("单行输入框");
    else if($STYPE=="MT")
       $STYPE=_("多行输入框");
    else if($STYPE=="D")
       $STYPE=_("下拉菜单");
    else if($STYPE=="R")
       $STYPE=_("单选框");
    else if($STYPE=="C")
       $STYPE=_("复选框");
    else
       $STYPE=_("未知");
       
    if($ISQUERY=="1")
       $ISQUERY=_("是");
    else
       $ISQUERY=_("否");
?>
        <tr class="TableData">
          <td nowrap align="center"><?=$ORDERNO?></td>
          <td><?=$FIELDNAME?></td>
          <td nowrap align="center"><?=$STYPE?></td>
          <td nowrap align="center"><?=$ISQUERY?></td>
          <td nowrap align="center">
           <a href="field_new.php?CODE_ID=<?=$CODE_ID?>&FIELDNO=<?=$FIELDNO?>&CODE_NAME=<?=$CODE_NAME?>""> <?=_("编辑")?></a>&nbsp;
           <a href="javascript:delete_code('<?=$CODE_ID?>','<?=$FIELDNAME?>','<?=$FIELDNO?>','<?=$CODE_NAME?>');"> <?=_("删除")?></a>
          </td>
        </tr>
<?
    }//while
?>
</table>
</body>
</html>