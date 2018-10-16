<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("法律法规查询");
include_once("inc/header.inc.php");
?>
<BODY class="bodycolor" topmargin="5">

<table width="100%">
  <tr>
    <td class="Big"><!--<img src="/images/menu/infofind.gif" align="absmiddle">--><span class="big3"> <?=_("法律法规查询")?></span>
    </td>
  </tr>
</table>

<table width="500" class="TableBlock" align="center">
  <tr>
    <td class="TableHeader" align="center">
      <b><?=_("法律法规主目录")?></b>
    </td>
  </tr>
  <tr>
    <td class="TableData" align="center">
        <a href="flgswenshu.php"><?=_("法律格式文书库")?></a>
    </td>
  </tr>
  <tr>
    <td class="TableData" align="center">
        <a href="hyhetong.php"><?=_("行业合同样板库")?></a>
    </td>
  </tr>
  <tr>
    <td class="TableData" align="center">
        <a href="gzwenshu.php"><?=_("公司常用文书库")?></a>
    </td>
  </tr>
</table>
　　
</body>
</html>