<?
include_once("inc/auth.inc.php");
$HTML_PAGE_TITLE = _("������ͼƬ");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

//$PIC_NAME = unescape($PIC_NAME);
$PIC_NAME = iconv('utf-8', MYOA_CHARSET, $PIC_NAME);

?>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script>
function CheckForm()
{
   if(document.form1.NEW_NAME.value=="<?=substr($PIC_NAME,0,-4)?>")
   {
      alert("<?=_("��ͼƬ����ԭͼƬ����ͬ")?>");
      document.form1.NEW_NAME.focus();
      return false;
   }

   if(document.form1.NEW_NAME.value=="")
   {
      alert("<?=_("��ͼƬ������Ϊ��")?>");
      document.form1.NEW_NAME.focus();
      return false;
   } 
   if(document.form1.NEW_NAME.value.indexOf("&")!=-1 || document.form1.NEW_NAME.value.indexOf("*")!=-1)
   {
      alert("<?=_("��ͼƬ�����ܺ��зǷ��ַ�")?>");
      document.form1.NEW_NAME.focus();
      return false;
   } 
   strArr=document.form1.NEW_NAME.value.split(" ");
   if(strArr.length!=1)
   {
      alert("<?=_("��ͼƬ���в����пո�")?>");
      document.form1.NEW_NAME.focus();
      return false;
   } 
   //check_pic_name();
    _get("check_pic_name.php","PIC_NAME=" + escape('<?=$PIC_NAME?>') + "&PIC_PATH=<?=$PIC_PATH?>&SUB_DIR=<?=$SUB_DIR?>&NEW_NAME="+ escape(document.form1.NEW_NAME.value) +"<?=substr($PIC_NAME,strrpos($PIC_NAME,"."));?>", function(req){
        if(req.status==200)
        {
          if(req.responseText=="+OK")
          {
              alert("<?=_("ͼƬ���Ѵ���")?>");
              document.form1.NEW_NAME.value="";
              document.form1.NEW_NAME.focus();
              return false;
          }
          else
              return true;
        }      
    },true);
}

</script>
<body class="bodycolor" onLoad="form1.NEW_NAME.focus();">
	
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big3"><img src="<?=MYOA_STATIC_SERVER?>/static/images/folder_edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"> <b><?=_("������ͼƬ��")?> <?=$PIC_NAME?></b></td>
  </tr>
</table>
<br>

<table class="TableBlock" width="90%" align="center">
  <form action="rename_action_submit.php" method="post" name="form1" onsubmit="return CheckForm();">
    <tr class="TableData">
      <td width="60"><?=_("ԭͼƬ����")?></td>
      <td><?=$PIC_NAME?></td>
    </tr>
    <tr class="TableData">
      <td width="80"><?=_("��ͼƬ����")?></td>
      <td><input type="text" class="BigInput" size="12" name="NEW_NAME"><?=substr($PIC_NAME,strrpos($PIC_NAME,"."));?></td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="5" nowrap>
        <input type="submit" name="submit" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();">
      </td>
    </tr>
  </table>
  <input type="hidden" name="PIC_PATH" value="<?=$PIC_PATH?>"> <? //echo $PIC_PATH;echo "<br>";?>
  <input type="hidden" name="SUB_DIR" value="<?=$SUB_DIR?>">   <? //echo $SUB_DIR;echo "<br>";?> 
  <input type="hidden" name="PIC_NAME" value="<?=$PIC_NAME?>"> <? //echo $PIC_NAME;echo "<br>";?> 
</form>
</body>
</html>