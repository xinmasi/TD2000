<?
include_once("inc/auth.inc.php");

//��ȡ�½�ͼƬĿ¼·��������
$query = "SELECT PIC_NAME,PIC_PATH from PICTURE where PIC_ID='$PIC_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $PIC_NAME=$ROW["PIC_NAME"];
   $PIC_PATH=$ROW["PIC_PATH"];
}
else
   exit;

if(strstr($SUB_DIR,"..") || strstr($SUB_DIR,"./"))
 {
    Message(_("����"),_("�������зǷ��ַ���"));
    Button_Back();
    exit;
}

//��ǰĿ¼·��
if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
   $CUR_DIR = $PIC_PATH.$CUR_DIR_RELAT;
else
   $CUR_DIR = $PIC_PATH."/".$CUR_DIR_RELAT;
if(stristr($CUR_DIR,".."))
{
    Message(_("����"),_("�������зǷ��ַ���"));
    Button_Back();
    exit;
}

$HTML_PAGE_TITLE = _("�������ļ���");
include_once("inc/header.inc.php");
?>



<body class="bodycolor" onLoad="form1.NEW_FOLDER_NAME.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big3"><img src="<?=MYOA_STATIC_SERVER?>/static/images/folder_edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"> <b><?=_("�������ļ���")?></b></td>
  </tr>
</table>
<br>

<table class="TableBlock" width="90%" align="center">
  <form action="update_name.php"  method="post" name="form1">
    <tr class="TableData">
      <td width="80"><?=_("���ļ�������")?></td>
      <td><input type="text" class="BigInput" size="20" name="NEW_FOLDER_NAME"></td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="5" nowrap>
        <input type="submit" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();">
      </td>
    </tr>
  </table>
  <input type="hidden" name="CUR_DIR_RELAT" value="<?=$CUR_DIR_RELAT?>">
  <input type="hidden" name="PIC_ID" value="<?=$PIC_ID?>">
  <input type="hidden" name="SUB_DIR" value="<?=$SUB_DIR?>">    
</form>
</body>
</html>