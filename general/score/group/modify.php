<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
$GROUP_ID=intval($GROUP_ID);
  $query="select * from SCORE_GROUP where GROUP_ID='$GROUP_ID' ";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
    $GROUP_NAME=$ROW["GROUP_NAME"];
    $GROUP_DESC=$ROW["GROUP_DESC"];
    $GROUP_REFER=$ROW["GROUP_REFER"];
    $TO_ID=$ROW["TO_ID"];
    if($TO_ID=="ALL_DEPT")
    	$TO_NAME=_("ȫ�岿��");
    else
    	$TO_NAME=GetDeptNameById($TO_ID);
    $PRIV_ID=$ROW["PRIV_ID"];
    $PRIV_NAME=GetPrivNameById($PRIV_ID);
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=GetUserNameById($USER_ID);
  }

$HTML_PAGE_TITLE = _("����ָ�꼯");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.GROUP_NAME.value=="")
   { alert("<?=_("����ָ�꼯���Ʋ��ܿգ�����")?>");
     return (false);
   }
   return (true);
}
</script>



<body class="bodycolor" >
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif" WIDTH="22" HEIGHT="20" align="absmiddle"> <span class="big3"><?=_("����ָ�꼯����")?></span>
    </td>
  </tr>
</table>

<br>
 <table width="60%" align="center" class="TableBlock">
  <form action="update.php?GROUP_ID=<?=$GROUP_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"><span style="color: red;">*</span><?=_("����ָ�꼯���ƣ�")?></td>
      <td class="TableData">
         <INPUT type="text"name="GROUP_NAME" class=BigInput size="20" value="<?=$GROUP_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ָ�꼯������")?></td>
      <td class="TableData">
        <textarea name="GROUP_DESC" cols="45" rows="5" class="BigInput"><?=$GROUP_DESC?></textarea>
      </td>
    </tr>
     <tr>
      <td nowrap class="TableData"><?=_("�趨��������ģ�飺")?></td>
      <td class="TableData">
       <input type="checkbox" name="DIARY" <? if(find_id($GROUP_REFER,"DIARY")) echo "checked" ?> id="DIARY_ID"><label for="DIARY_ID"><?=_("���˹�����־")?></label>&nbsp;&nbsp;<input type="checkbox" name="CALENDAR" <? if(find_id($GROUP_REFER,"CALENDAR")) echo "checked" ?> id="CALENDAR_ID"><label for="CALENDAR_ID"><?=_("�����ճ̰���")?></label>    
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData">&nbsp;<?=_("���������ã�")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
        <textarea cols=40 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('6')"><?=_("���")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData">&nbsp;<?=_("����Ա���ã�")?></td>
      <td class="TableData">
        <input type="hidden" name="USER_ID" value="<?=$USER_ID ?>">
        <textarea cols=40 name="USER_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$USER_NAME ?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('123','6','USER_ID', 'USER_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('USER_ID', 'USER_NAME')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData">&nbsp;<?=_("����ɫ���ã�")?></td>
      <td class="TableData">
        <input type="hidden" name="PRIV_ID" value="<?=$PRIV_ID ?>">
        <textarea cols=40 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$PRIV_NAME ?></textarea>   	
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('6','PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a><br>
        <?=_("����ָ�꼯ʹ�÷�Χȡ���š���Ա�ͽ�ɫ�Ĳ���")?>
      </td>
    </tr>
    <tfoot align="center" class="TableFooter">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("ȷ��")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php?CUR_PAGE=<?=$CUR_PAGE?>'">
      </td>
    </tfoot>
  </table>
</form>

</body>
</html>