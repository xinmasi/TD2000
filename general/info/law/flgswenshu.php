<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>
<style type="text/css">
<!--a            { text-decoration: none; font-size: 9pt; color: black; font-family: ���� }
.text        { font-size: 9pt; font-family: ���� }
.text1       { color: #0000A0; font-size: 11pt; font-family: ���� }
.text2       { color: #008080; font-size: 9pt; font-family: ���� }
.text3       { color: #0F8A91; font-size: 11pt; font-family: ���� }
.l100        { line-height: 14pt; font-size: 9pt }
td           { font-family: ����; font-size: 9pt; line-height: 13pt }
input        { font-size: 9pt; font-family: ���� }
p            { font-size: 9pt; font-family: ���� }
--></style>

<BODY class="bodycolor">

<BR>
<table width="500" class="TableBlock" align="center">
  <tr> 
    <td height="27" class="TableHeader"> 
      <div align="center"><?=_("���ɸ�ʽ�����")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="flgswenshu/gzwenshu.php"><?=_("��֤����")?></a></li>
        <li><a href="flgswenshu/gjpeichang.php"><?=_("�����⳥��")?></a></li>
        <li><a href="flgswenshu/lvshiwenshu.php"><?=_("��ʦר��")?></a></li>
        <li><a href="flgswenshu/mscaijue.php"><?=_("���²þ���")?></a></li>
        <li><a href="flgswenshu/mspanjue.php"><?=_("�����о���")?></a></li>
        <li><a href="flgswenshu/mssusong.php"><?=_("������������")?></a></li>
        <li><a href="flgswenshu/xscaiding.php"><?=_("���²ö���")?></a></li>
        <li><a href="flgswenshu/xspanjue.php"><?=_("�����о���")?></a></li>
        <li><a href="flgswenshu/xssusong.php"><?=_("������������")?></a></li>
        <li><a href="flgswenshu/xzsusong.php"><?=_("����������")?></a></li>
        <li><a href="flgswenshu/zcwenshu.php"><?=_("�ٲ�����")?></a></li>
      </ol>
      </td>
  </tr>
</table>
���� 
<br><center><input type="button" class="BigButton" value="<?=_("����Ŀ¼")?>" onclick="location='index.php';"></center><br>
</body>
</html>



