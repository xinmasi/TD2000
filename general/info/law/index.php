<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���ɷ����ѯ");
include_once("inc/header.inc.php");
?>
<BODY class="bodycolor" topmargin="5">

<table width="100%">
  <tr>
    <td class="Big"><!--<img src="/images/menu/infofind.gif" align="absmiddle">--><span class="big3"> <?=_("���ɷ����ѯ")?></span>
    </td>
  </tr>
</table>

<table width="500" class="TableBlock" align="center">
  <tr>
    <td class="TableHeader" align="center">
      <b><?=_("���ɷ�����Ŀ¼")?></b>
    </td>
  </tr>
  <tr>
    <td class="TableData" align="center">
        <a href="flgswenshu.php"><?=_("���ɸ�ʽ�����")?></a>
    </td>
  </tr>
  <tr>
    <td class="TableData" align="center">
        <a href="hyhetong.php"><?=_("��ҵ��ͬ�����")?></a>
    </td>
  </tr>
  <tr>
    <td class="TableData" align="center">
        <a href="gzwenshu.php"><?=_("��˾���������")?></a>
    </td>
  </tr>
</table>
����
</body>
</html>