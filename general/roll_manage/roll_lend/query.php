<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("借阅查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>


<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/roll_manage.gif" align="absmiddle"><span class="big3"> <?=_("借阅查询")?></span>
    </td>
  </tr>
</table>
<form action="search2.php"  method="post" name="form1">
<table class="TableBlock" width="70%"  align="center">
  <TR>
      <TD class="TableData"><?=_("卷库名称：")?></TD>
      <TD class="TableData">
       <INPUT name="ROOM_NAME" id="ROOM_NAME" size=20 maxlength="100" dataType="Require" require="true" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("案卷名称：")?></TD>
      <TD class="TableData">
       <INPUT name="ROLL_NAME" id="ROLL_NAME" size=20 maxlength="100" class="BigInput">
      </TD>
  </TR>  
  <TR>
      <TD class="TableData"><?=_("文件号：")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_CODE" id="FILE_CODE" size=20 maxlength="100" dataType="Require" require="true" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("文件主题词：")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_SUBJECT" id="FILE_SUBJECT" size=20 maxlength="100" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("文件标题：")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_TITLE" id="FILE_TITLE" size=20 maxlength="100" dataType="Require" require="true" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("文件辅标题：")?></TD>
      <TD class="TableData">
       <INPUT name="FILE_TITLE0" id="FILE_TITLE0" size=20 maxlength="100" class="BigInput">
      </TD>
  </TR>
  <TR>
      <TD class="TableData"><?=_("发文单位：")?></TD>
      <TD class="TableData">
       <INPUT name="SEND_UNIT" id="SEND_UNIT" size=20 dataType="Require" require="true" class="BigInput">
      </TD>
      <TD class="TableData"><?=_("备注：")?></TD>
      <TD class="TableData">
        <input type="text" name="REMARK" size="20" maxlength="10" class="BigInput">
      </TD>
  </TR>
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
        <input type="submit" value="<?=_("查询")?>" class="BigButton">
      </td>
    </tr>  
  </table>
</form>

</body>
</html>