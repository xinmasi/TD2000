<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
   if(document.form1.SUBJECT.value=="" && document.form1.CONTENT.value=="" && document.form1.ATTACHMENT_NAME.value=="" && document.form1.BEGIN_DATE.value=="" && document.form1.END_DATE.value==""&&document.form1.TYPE.value=="")
   { alert("<?=_("��ָ������һ������������")?>");
     return (false);
   }

   return true;
}

</script>


<body class="bodycolor" onLoad="document.form1.SUBJECT.focus();">

<?
$BOARD_ID =intval($BOARD_ID);
 //------- ��������Ϣ -------
 $query = "SELECT * from BBS_BOARD where BOARD_ID='$BOARD_ID'";
 $cursor = exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
   $BOARD_NAME = $ROW["BOARD_NAME"];

   $BOARD_NAME=str_replace("<","&lt",$BOARD_NAME);
   $BOARD_NAME=str_replace(">","&gt",$BOARD_NAME);
//   $BOARD_NAME=stripslashes($BOARD_NAME);
   $CATEGORY=$ROW["CATEGORY"];
   if($CATEGORY!="")
	  $TYPE_ARRAY=explode(",",$CATEGORY);
 }
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="title_list"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><a href="index.php"> <?=_("������")?></a> &raquo; <a href="board.php?BOARD_ID=<?=$BOARD_ID?>"><?=$BOARD_NAME?></a> &raquo; <?=_("��������")?><br>
    </td>
  </tr>
</table>

<br>
<form action="search.php" name="form1" onSubmit="return CheckForm();">
<table class="TableBlock" width="450" align="center">
<tr class="TableData">
      <td nowrap align="center"><?=_("���ࣺ")?></td>
      <td nowrap style="border-right:none;">
        <select name="TYPE" class="BigSelect">
        	<option value="" selected></option>
    			<?
            if(is_array($TYPE_ARRAY) && !empty($TYPE_ARRAY))
            {
                foreach($TYPE_ARRAY as $TYPE)
                {
                  if($TYPE!="")
                    {
                    ?>
                    <option value="<?=$TYPE?>"><?=$TYPE?></option>
                    <?
                  }
                }
            }
    			?>
        </select>
      </td>
  </tr>
  <tr class="TableData">
      <td nowrap align="center"><?=_("����������֣�")?></td>
      <td nowrap style="border-right:none;"><input type="text" name="SUBJECT" class="BigInput" size="30"></td>
  </tr>
  <tr class="TableData">
      <td nowrap align="center"><?=_("���ݻ�ظ��������֣�")?></td>
      <td nowrap style="border-right:none;"><input type="text" name="CONTENT" class="BigInput" size="30"></td>
  </tr>
   
  <tr class="TableData">
      <td nowrap align="center"><?=_("�����������֣�")?></td>
      <td nowrap style="border-right:none;"><input type="text" name="ATTACHMENT_NAME" class="BigInput" size="30"></td>
  </tr>
  <tr class="TableData">
      <td nowrap align="center"> <?=_("�������ڣ�")?></td>
      <td class="TableData" style="border-right:none;">
        <input type="text" name="BEGIN_DATE" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
        <?=_("��")?>&nbsp;
        <input type="text" name="END_DATE" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
        
      </td>
  </tr>    
  <tr>
      <td nowrap class="TableControl" colspan="2" align="center" style="border:none;background:#fff">
          <input type="hidden" name="BOARD_ID" value="<?=$BOARD_ID?>">
          <input type="submit" value="<?=_("����")?>" class="BigButton" title="<?=_("������������")?>" name="button">&nbsp;&nbsp;
          <input type="button" value="<?=_("����")?>" class="BigButton" onClick="history.back();">
      </td>
  </tr>
</table>
</form>

</body>
</html>