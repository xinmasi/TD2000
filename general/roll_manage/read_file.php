<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("�鿴�ļ�");
include_once("inc/header.inc.php");
?>




<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<?
$FILE_ID=intval($FILE_ID);
$query="select * from RMS_FILE where FILE_ID='$FILE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$FILE_CODE=$ROW["FILE_CODE"];
    $FILE_SUBJECT=$ROW["FILE_SUBJECT"];
    $FILE_TITLE=$ROW["FILE_TITLE"];
    $FILE_TITLE0=$ROW["FILE_TITLE0"];
    $SEND_UNIT=$ROW["SEND_UNIT"];
    $SEND_DATE=$ROW["SEND_DATE"];
    $SECRET=$ROW["SECRET"];
    $URGENCY=$ROW["URGENCY"];
    $FILE_TYPE=$ROW["FILE_TYPE"];
    $FILE_KIND=$ROW["FILE_KIND"];
    $FILE_PAGE=$ROW["FILE_PAGE"];
    $PRINT_PAGE=$ROW["PRINT_PAGE"];
    $REMARK=$ROW["REMARK"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    $DOWNLOAD=$ROW["DOWNLOAD"];
    $PRINT=$ROW["PRINT"];
    
    if($PRINT!="1"&&$DOWNLOAD!="1")
      $OP_FLAG="00";
   
    if($PRINT=="1"&&($DOWNLOAD=="1"||$DOWNLOAD==""))
      $OP_FLAG="11";
   
    if($OP_FLAG=="")
      $OP_FLAG=$DOWNLOAD.$PRINT;
}

?>
<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�鿴�ļ�")?></span>
    </td>
  </tr>
</table>

<table class="TableList" width="85%"  align="center">
  <form enctype="multipart/form-data" action="update.php"  method="post" name="form1">
  <TR>
      <TD class="TableData"><?=_("�ļ��ţ�")?></TD>
      <TD class="TableData"><?=$FILE_CODE?></TD>
      <TD class="TableData"><?=_("�ļ�����ʣ�")?></TD>
      <TD class="TableData"><?=$FILE_SUBJECT?></TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("�ļ����⣺")?></TD>
      <TD class="TableData"><?=$FILE_TITLE?></TD>
      <TD class="TableData"><?=_("�ļ������⣺")?></TD>
      <TD class="TableData"><?=$FILE_TITLE0?></TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("���ĵ�λ��")?></TD>
      <TD class="TableData"><?=$SEND_UNIT?></TD>
      <TD class="TableData"><?=_("�������ڣ�")?></TD>
      <TD class="TableData"><?=$SEND_DATE?></TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("�ܼ���")?></TD>
      <TD class="TableData"><?=get_code_name($SECRET,"RMS_SECRET")?></TD>
      <TD class="TableData"><?=_("�����ȼ���")?></TD>
      <TD class="TableData"><?=get_code_name($URGENCY,"RMS_URGENCY")?></TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("�ļ����ࣺ")?></TD>
      <TD class="TableData"><?=get_code_name($FILE_TYPE,"RMS_FILE_TYPE")?></TD>
      <TD class="TableData"><?=_("�������")?></TD>
      <TD class="TableData"><?=get_code_name($FILE_KIND,"RMS_FILE_KIND")?></TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("�ļ�ҳ����")?></TD>
      <TD class="TableData"><?=$FILE_PAGE?></TD>
      <TD class="TableData"><?=_("��ӡҳ����")?></TD>
      <TD class="TableData"><?=$PRINT_PAGE?></TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("��ע��")?></TD>
      <TD class="TableData" colSpan=3><?=$REMARK?></TD>
   </TR>
    <tr class="TableData">
      <td nowrap><?=_("�����ĵ���")?></td>
      <td nowrap colSpan=3>
<?
      if($ATTACHMENT_ID=="")
         echo _("�޸���");
      else
      {
          echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,$OP_FLAG);   	
      }
?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
        <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="javascript:window.close();">
      </td>
    </tr>
  </table>
</form>

</body>
</html>