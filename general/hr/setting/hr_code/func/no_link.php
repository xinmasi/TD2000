<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script Language="JavaScript">
function delete_code(CODE_ID,CODE_NAME)
{
 var msg = sprintf("<?=_("ȷ��Ҫɾ�������� '%s' ��")?>", CODE_NAME);
 if(window.confirm(msg))
 {
  URL="delete_no.php?CODE_ID="+CODE_ID;
  location=URL;
 }
}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("��������б�")?></span>
    </td>
  </tr>
</table>

<?
 $query = "SELECT * from HR_CODE where PARENT_NO=''";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $CODE_NO =$ROW["CODE_NO"];
    $CODE_NO_STR.=$CODE_NO.",";
 }
?>

<table class="TableBlock" align="center">

<?
 $query = "SELECT * from HR_CODE where PARENT_NO!='' and not find_in_set(PARENT_NO,'$CODE_NO_STR')";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $COUNT++;
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
           <a href="edit_no.php?CODE_ID=<?=$CODE_ID?>"> <?=_("�༭")?></a>&nbsp;&nbsp;
<?
if($CODE_FLAG!="0")
{
?>
           <a href="javascript:delete_code('<?=$CODE_ID?>','<?=$CODE_NAME?>');"> <?=_("ɾ��")?></a>
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
   Message("",_("û�ж������Ĵ���"));
?>
<br>

<div align="center">
<input type="button" value="<?=_("����")?>" class="BigButton" onclick="location='../blank.php'">
</div>

</body>
</html>