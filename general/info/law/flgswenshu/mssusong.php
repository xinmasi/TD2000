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
      <div align="center"><?=_("������������")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="mssusong/susong00.php"><?=_("����������")?></a></li>
        <li><a href="mssusong/susong01.php"><?=_("���´����")?></a></li>
        <li><a href="mssusong/susong02.php"><?=_("����������")?></a></li>
        <li><a href="mssusong/susong03.php"><?=_("���ߴ����")?></a></li>
        <li><a href="mssusong/susong04.php"><?=_("��������ִ����")?></a></li>
        <li><a href="mssusong/susong05.php"><?=_("���ϱ�ȫ������")?></a></li>
        <li><a href="mssusong/susong06.php"><?=_("��ҵ�����Ʋ���ծ�����飨1��")?></a></li>
        <li><a href="mssusong/susong07.php"><?=_("��ҵ�����Ʋ���ծ�����飨2��")?></a></li>
        <li><a href="mssusong/susong08.php"><?=_("֧��������������")?></a></li>
        <li><a href="mssusong/susong09.php"><?=_("֧����������")?></a></li>
        <li><a href="mssusong/susong10.php"><?=_("֤�ݱ�ȫ������")?></a></li>
        <li><a href="mssusong/susong11.php"><?=_("���·�����")?></a></li>
        <li><a href="mssusong/susong12.php"><?=_("����ִ��������")?></a></li>
        <li><a href="mssusong/susong13.php"><?=_("���ϲƲ���ȫ������")?></a></li>
        <li><a href="mssusong/susong14.php"><?=_("�������ϲƲ���ȫ������")?></a></li>
        <li><a href="mssusong/susong15.php"><?=_("���غ�����ǰ�Ʋ���ȫ������")?></a></li>
        <li><a href="mssusong/susong16.php"><?=_("���󷢻���������Ʋ�����������")?></a></li>
        <li><a href="mssusong/susong17.php"><?=_("�����¹���������������")?></a></li>
        <li><a href="mssusong/susong18.php"><?=_("������ǰ�Ʋ���ȫ������")?></a></li>
        <li><a href="mssusong/susong19.php"><?=_("�Ʋ���ȫ������")?></a></li>
        <li><a href="mssusong/susong20.php"><?=_("��������״")?></a></li>
        <li><a href="mssusong/susong21.php"><?=_("֧����������")?></a></li>
        <li><a href="mssusong/susong22.php"><?=_("����ִ�е�����")?></a></li>
        <li><a href="mssusong/susong23.php"><?=_("���·���״")?></a></li>
        <li><a href="mssusong/susong24.php"><?=_("��������״")?></a></li>
        <li><a href="mssusong/susong25.php"><?=_("��������״")?></a></li>
        <li><a href="mssusong/susong26.php"><?=_("�������ϴ����")?></a></li>
        <li><a href="mssusong/susong27.php"><?=_("�������ϴ�����Ȩί����")?></a></li>
        <li><a href="mssusong/susong28.php"><?=_("��������ί�д���Э��")?></a></li>
        <li><a href="mssusong/susong29.php"><?=_("���������Ժ�о�������")?></a></li>
        <li><a href="mssusong/susong30.php"><?=_("�����˲μ���������������")?></a></li>
        <li><a href="mssusong/susong31.php"><?=_("����֤��������")?></a></li>
        <li><a href="mssusong/susong32.php"><?=_("����������")?></a></li>
        <li><a href="mssusong/susong33.php"><?=_("��ʾ�߸�������")?></a></li>
        <li><a href="mssusong/susong34.php"><?=_("��Ͻ����������")?></a></li>
        <li><a href="mssusong/susong35.php"><?=_("�ر�������")?></a></li>
        <li><a href="mssusong/susong36.php"><?=_("���³���������")?></a></li>
        <li><a href="mssusong/susong37.php"><?=_("��������������")?></a></li>
        <li><a href="mssusong/susong38.php"><?=_("�Ʋ���ծ������")?></a></li>
        <li><a href="mssusong/susong39.php"><?=_("ǿ��ִ��������")?></a></li>
        <li><a href="mssusong/susong40.php"><?=_("�϶��Ʋ�����������")?></a></li>
        <li><a href="mssusong/susong41.php"><?=_("�϶�������������Ϊ����������")?></a></li>
        <li><a href="mssusong/susong42.php"><?=_("�϶���������������Ϊ����������")?></a></li>
        <li><a href="mssusong/susong43.php"><?=_("��ǰ�Ʋ���ȫ������")?></a></li>
        <li><a href="mssusong/susong44.php"><?=_("���ϲƲ���ȫ������")?></a></li>
        <li><a href="mssusong/susong45.php"><?=_("����ִ��������")?></a></li>
        <li><a href="mssusong/susong46.php"><?=_("����ʧ��������")?></a></li>
        <li><a href="mssusong/susong47.php"><?=_("��������������")?></a></li>
        <li><a href="mssusong/susong48.php"><?=_("��������������")?></a></li>
        <li><a href="mssusong/susong49.php"><?=_("֤�ݱ�ȫ������")?></a></li>
        <li><a href="mssusong/susong50.php"><?=_("֧����������")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>