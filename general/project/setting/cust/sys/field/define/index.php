<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�Զ����ֶ�����");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script Language="JavaScript">
function delete_code(CODE_ID,TABLENAME,FIELDNO,FUNC_NAME)
{
 var msg = sprintf("<?=_("ȷ��Ҫɾ�����Զ����ֶ� '%s'-'%s' ��")?>", FUNC_NAME, TABLENAME);
 if(window.confirm(msg))
 {
  URL="delete.php?CODE_ID="+CODE_ID+"&FIELDNO=" + FIELDNO;
  location=URL;
 }
}
</script>


<body class="bodycolor">
<?
 //��ȡ1�������������Ϣ
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�Զ����ֶ�")?> - <?=$CODE_NAME?></span>
    </td>
  </tr>
</table>
<div align="center">
  <input type="button" value="<?=_("�����Զ����ֶ�")?>" class="BigButton" onclick="parent.code_edit.location='field_new.php?W_CODE_NO=<?=$CODE_NO?>&CODE_ID=<?=$CODE_ID?>&CODE_NAME=<?=$CODE_NAME?>';">
</div>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <a name="bottom"><?=_("�Զ����ֶι���")?></span>
    </td>
  </tr>
</table>
<br />

<?
   $query = "SELECT COUNT(*) from PROJ_FIELDSETTING where TYPE_CODE_NO='$CODE_ID' order by ORDERNO";
   $cursor = exequery(TD::conn(),$query);
   $ROW = mysql_fetch_array($cursor);
   
   if($ROW[0] <= 0){
      Message(_("��ʾ"),_("��������Ŀ�����Զ����ֶΣ�"));
      exit;      
   }
?>
<table class="TableList" width="95%" align="center">
     <tr class="TableHeader" align="center">
      <td width="50" align="center"><?=_("�����")?></td>
      <td align="center"><?=_("�ֶ�����")?></td>
      <td width="70" align="center"><?=_("����")?></td>
      <td width="70" align="center"><?=_("��ѯ�ֶ�")?></td>
      <td align="center"><?=_("����")?></td>
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
       $STYPE=_("���������");
    else if($STYPE=="MT")
       $STYPE=_("���������");
    else if($STYPE=="D")
       $STYPE=_("�����˵�");
    else if($STYPE=="R")
       $STYPE=_("��ѡ��");
    else if($STYPE=="C")
       $STYPE=_("��ѡ��");
    else
       $STYPE=_("δ֪");
       
    if($ISQUERY=="1")
       $ISQUERY=_("��");
    else
       $ISQUERY=_("��");
?>
        <tr class="TableData">
          <td nowrap align="center"><?=$ORDERNO?></td>
          <td><?=$FIELDNAME?></td>
          <td nowrap align="center"><?=$STYPE?></td>
          <td nowrap align="center"><?=$ISQUERY?></td>
          <td nowrap align="center">
           <a href="field_new.php?CODE_ID=<?=$CODE_ID?>&FIELDNO=<?=$FIELDNO?>&CODE_NAME=<?=$CODE_NAME?>""> <?=_("�༭")?></a>&nbsp;
           <a href="javascript:delete_code('<?=$CODE_ID?>','<?=$FIELDNAME?>','<?=$FIELDNO?>','<?=$CODE_NAME?>');"> <?=_("ɾ��")?></a>
          </td>
        </tr>
<?
    }//while
?>
</table>
</body>
</html>