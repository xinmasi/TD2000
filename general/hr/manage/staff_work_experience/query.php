<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("工作经历信息查询");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
function do_export()
{
  document.form1.action='export.php';
  document.form1.submit();
}
function do_search()
{
  document.form1.action='search.php';
  document.form1.submit();
}
</script>

<body class="bodycolor">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("工作经历信息查询")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="#"  method="post" name="form1">
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("单位员工：")?></td>
      <td class="TableData">
        <input type="text" name="STAFF_NAME1" size="15" class="BigStatic" readonly value="">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
    <td nowrap class="TableData" width="100"> <?=_("担任职务：")?></td>
      <td class="TableData">
        <input type="text" name="POST_OF_JOB" size="15" maxlength="200" class="BigInput" value="<?=$POST_OF_JOB?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("工作单位：")?></td>
      <td class="TableData">
        <input type="text" name="WORK_UNIT" size="34" maxlength="200" class="BigInput" value="<?=$WORK_UNIT?>">
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData" width="100"> <?=_("行业类别：")?></td>
      <td class="TableData">
        <input type="text" name="MOBILE" size="34" maxlength="200" class="BigInput" value="<?=$MOBILE?>">
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData" width="100"> <?=_("工作内容：")?></td>
      <td class="TableData">
        <input type="text" name="WORK_CONTENT" size="34" maxlength="200" class="BigInput" value="<?=$WORK_CONTENT?>">
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData" width="100"> <?=_("主要业绩：")?></td>
      <td class="TableData">
        <input type="text" name="KEY_PERFORMANCE" size="34" maxlength="200" class="BigInput" value="<?=$KEY_PERFORMANCE?>">
      </td>
   </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="button" value="<?=_("查询")?>" class="BigButton" onclick="do_search()">&nbsp;&nbsp;
        <input type="button" value="<?=_("导出")?>" class="BigButton" onclick="do_export()">&nbsp;&nbsp;
      </td>
    </tr>
  </form>
 </table>


</body>
</html>