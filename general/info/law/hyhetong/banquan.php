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
      <div align="center"><?=_("��Ȩ������Ȩ")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="banquan/banquanht00.php"><?=_("ͼ��Լ���ͬ")?></a></li>
        <li><a href="banquan/banquanht01.php"><?=_("ͼ������ͬ")?></a></li>
        <li><a href="banquan/banquanht02.php"><?=_("�Էѳ���ͼ���ͬ")?></a></li>
        <li><a href="banquan/banquanht03.php"><?=_("��־�ʷ���ͬ")?></a></li>
        <li><a href="banquan/banquanht04.php"><?=_("�����������֤Э���飨�������û�ֱ��ǩ����")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>