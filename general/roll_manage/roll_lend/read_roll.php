<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�鿴����");
include_once("inc/header.inc.php");
?>



<?
$ROLL_ID=intval($ROLL_ID);
$query="select * from RMS_ROLL where ROLL_ID='$ROLL_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $ROLL_CODE=$ROW["ROLL_CODE"];
    $ROLL_NAME=$ROW["ROLL_NAME"];
    $ROOM_ID=$ROW["ROOM_ID"];
    $YEARS=$ROW["YEARS"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $DEPT_ID=$ROW["DEPT_ID"];
    $CATEGORY_NO=$ROW["CATEGORY_NO"];
    $DEADLINE=$ROW["DEADLINE"];
    $SECRET=$ROW["SECRET"];
    $CATALOG_NO=$ROW["CATALOG_NO"];
    $ARCHIVE_NO=$ROW["ARCHIVE_NO"];
    $BOX_NO=$ROW["BOX_NO"];
    $MICRO_NO=$ROW["MICRO_NO"];
    $CERTIFICATE_KIND=$ROW["CERTIFICATE_KIND"];
    $CERTIFICATE_START=$ROW["CERTIFICATE_START"];
    $CERTIFICATE_END=$ROW["CERTIFICATE_END"];
    $ROLL_PAGE=$ROW["ROLL_PAGE"];
    $BORROW=$ROW["BORROW"];
    $EDIT_DEPT=$ROW["EDIT_DEPT"];    
    $REMARK=$ROW["REMARK"];    
	
}
?>
<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�鿴����")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="85%"  align="center">
  <form enctype="multipart/form-data" action="update.php"  method="post" name="form1">
  <TR>
      <TD class="TableData"><?=_("�� �� �ţ�")?></TD>
      <TD class="TableData"><?=$ROLL_CODE?></TD>
      <TD class="TableData"><?=_("�������ƣ�")?></TD>
      <TD class="TableData"><?=$ROLL_NAME?></TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("������⣺")?></TD>
      <TD class="TableData">
<?
 $ROOM_ID=intval($ROOM_ID);
 $query="SELECT * FROM RMS_ROLL_ROOM WHERE ROOM_ID='$ROOM_ID'";
 $cursor= exequery(TD::conn(),$query);
 $ROW=mysql_fetch_array($cursor);
 $ROOM_NAME=$ROW["ROOM_NAME"];
 echo $ROOM_NAME;
?>	
      </TD>
      <TD class="TableData"><?=_("��������")?></TD>
      <TD class="TableData"><?=$YEARS?></TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("��ʼ���ڣ�")?></TD>
      <TD class="TableData"><?=$BEGIN_DATE?></TD>
      <TD class="TableData"><?=_("��ֹ���ڣ�")?></TD>
      <TD class="TableData"><?=$END_DATE?></TD>
  </TR>
   <TR>
      <TD class="TableData"><?=_("�������ţ�")?></TD>
      <TD class="TableData">
<?
	 $DEPT_ID=intval($DEPT_ID);
	 $query="SELECT * FROM DEPARTMENT WHERE DEPT_ID='$DEPT_ID'";
	 $cursor= exequery(TD::conn(),$query);
	 $ROW=mysql_fetch_array($cursor);
	 $DEPT_NAME=$ROW["DEPT_NAME"];
	 echo $DEPT_NAME;
?>
	</select>
      </TD>
      <TD class="TableData"><?=_("���ƻ�����")?></TD>
      <TD class="TableData"><?=$EDIT_DEPT?></TD>
  </TR>
   <TR>
      <TD class="TableData"><?=_("�������ޣ�")?></TD>
      <TD class="TableData"><?=$DEADLINE?></TD>
      <TD class="TableData"><?=_("�����ܼ���")?></TD>
      <TD class="TableData"><?=get_code_name($SECRET,"RMS_SECRET")?></TD>
  </TR>
   <TR>
      <TD class="TableData"><?=_("ȫ �� �ţ�")?></TD>
      <TD class="TableData"><?=$CATEGORY_NO?></TD>
      <TD class="TableData"><?=_("Ŀ ¼ �ţ�")?></TD>
      <TD class="TableData"><?=$CATALOG_NO?></TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("�����ݺţ�")?></TD>
      <TD class="TableData"><?=$ARCHIVE_NO?></TD>
      <TD class="TableData"><?=_("������ţ�")?></TD>
      <TD class="TableData"><?=$BOX_NO?></TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("�� ΢ �ţ�")?></TD>
      <TD class="TableData"><?=$MICRO_NO?></TD>
      <TD class="TableData"><?=_("ƾ֤���")?></TD>
      <TD class="TableData"><?=get_code_name($CERTIFICATE_KIND,"RMS_CERTIFICATE_KIND")?>
     </TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("ƾ֤���(��)��")?></TD>
      <TD class="TableData"><?=$CERTIFICATE_START?></TD>
      <TD class="TableData"><?=_("ƾ֤���(ֹ)��")?></TD>
      <TD class="TableData"<?=$CERTIFICATE_END?></TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("ҳ    ����")?></TD>
      <TD class="TableData"><?=$ROLL_PAGE?></TD>
      <TD class="TableData"><?=_("����������")?></TD>
      <TD class="TableData"></TD>
  </TR>
  <TR>
      <TD nowrap class="TableData"><?=_("��ע��")?></TD>
      <TD class="TableData" colSpan=3><?=$REMARK?></TD>
   </TR>
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
        <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="javascript:window.close();">
      </td>
    </tr>
  </table>
</form>

</body>
</html>