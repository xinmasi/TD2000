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
      <div align="center"><?=_("������װ����")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="jianzhu/jianzhu01.php"><?=_("���蹤����ƺ�ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu02.php"><?=_("������װ���̳а���ͬ��һ��")?></a></li>
        <li><a href="jianzhu/jianzhu05.php"><?=_("������װ���̳а���ͬ������")?></a></li>
        <li><a href="jianzhu/jianzhu30.php"><?=_("������װ���̳а���ͬ������")?></a></li>
        <li><a href="jianzhu/jianzhu46.php"><?=_("������װ���̳а���ͬ���ģ�")?></a></li>
        <li><a href="jianzhu/jianzhu03.php"><?=_("���蹤���������غ�ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu04.php"><?=_("���蹤�̲�Ǩ���ݺ�ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu06.php"><?=_("���̽�������ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu07.php"><?=_("�ۿ�ʩ����ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu08.php"><?=_("������װ�����б���")?></a></li>
        <li><a href="jianzhu/jianzhu09.php"><?=_("������װ���̿����ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu10.php"><?=_("������װ���̼�����ѯ��ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu12.php"><?=_("�����蹤��ʩ����ͬ�������͡����蹤��ʩ����ͬЭ�������ʹ��˵��")?></a></li>
        <li><a href="jianzhu/jianzhu13.php"><?=_("������װ���̷ְ���ͬ��һ��")?></a></li>
        <li><a href="jianzhu/jianzhu14.php"><?=_("������װ���̷ְ���ͬ���� ��")?></a></li>
        <li><a href="jianzhu/jianzhu15.php"><?=_("������װ������ƺ�ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu16.php"><?=_("������ľ�������̺�ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu17.php"><?=_("��װ���̺�ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu18.php"><?=_("���̷ְ���ͬ��ʽ")?></a></li>
        <li><a href="jianzhu/jianzhu19.php"><?=_("�������̳а���ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu20.php"><?=_("ú������װ���̳а���ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu21.php"><?=_("���蹤��ʩ����ͬ����")?></a></li>
        <li><a href="jianzhu/jianzhu22.php"><?=_("����װ�ι���ʩ����ͬ�����ֱ���")?></a></li>
        <li><a href="jianzhu/jianzhu23.php"><?=_("����װ�ι���ʩ����ͬ�����ֱ���")?></a></li>
        <li><a href="jianzhu/jianzhu24.php"><?=_("���蹤�̳а���ͬЭ������")?></a></li>
        <li><a href="jianzhu/jianzhu25.php"><?=_("���蹤�̳а���ͬ��һ��")?></a></li>
        <li><a href="jianzhu/jianzhu39.php"><?=_("���蹤�̳а���ͬ������")?></a></li>
        <li><a href="jianzhu/jianzhu26.php"><?=_("ҵ������ѯ����ʦ��׼����Э����")?></a></li>
        <li><a href="jianzhu/jianzhu27.php"><?=_("���蹤�̿����ͬ��һ��")?></a></li>
        <li><a href="jianzhu/jianzhu28.php"><?=_("���蹤�̿����ͬ������")?></a></li>
        <li><a href="jianzhu/jianzhu29.php"><?=_("���蹤�̿�����ƺ�ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu31.php"><?=_("���ʣ£ϣ�Ͷ�ʺ�ͬ��ʽ")?></a></li>
        <li><a href="jianzhu/jianzhu32.php"><?=_("����ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu33.php"><?=_("���⽨�����̳а���ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu34.php"><?=_("���ʳа����̺�ͬ��ʽ")?></a></li>
        <li><a href="jianzhu/jianzhu35.php"><?=_("���ʹ��̳а���ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu36.php"><?=_("���蹤��ʩ����ͬ��ʾ���ı���")?></a></li>
        <li><a href="jianzhu/jianzhu37.php"><?=_("�ۿڹ���ʩ����ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu38.php"><?=_("���蹤�̿������о���ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu40.php"><?=_("������̳а���ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu41.php"><?=_("����ʩ���������޺�ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu42.php"><?=_("���̽�������ͬ��׼����")?></a></li>
        <li><a href="jianzhu/jianzhu43.php"><?=_("���蹤��ʩ����ͬ����")?></a></li>
        <li><a href="jianzhu/jianzhu44.php"><?=_("���蹤��ʩ����ͬЭ������")?></a></li>
        <li><a href="jianzhu/jianzhu45.php"><?=_("�������蹤��ʩ����ͬ")?></a></li>
        <li><a href="jianzhu/jianzhu47.php"><?=_("���蹤�̿�����ƺ�ͬ")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>