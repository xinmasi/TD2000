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
      <div align="center"><?=_("���غ�ͬ")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="tudi/tudiht00.php"><?=_("����Ͷ����ҵ����ʹ�ú�ͬ����������ʹ��Ȩ��ͬ��")?></a></li>
        <li><a href="tudi/tudiht01.php"><?=_("��������ʹ��Ȩ���ú�ͬ����Ƭ�������س��ú�ͬ��")?></a></li>
        <li><a href="tudi/tudiht02.php"><?=_("��������ʹ��Ȩ���ú�ͬ����������ʹ��Ȩ������ú�ͬ��")?></a></li>
        <li><a href="tudi/tudiht03.php"><?=_("��������ʹ��Ȩ���ú�ͬ��һ�����ڵس��ú�ͬ��")?></a></li>
        <li><a href="tudi/tudiht04.php"><?=_("��������ʹ��Ȩ���ú�ͬ���������ڵس��ú�ͬ��")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>