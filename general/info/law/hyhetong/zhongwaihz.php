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
      <div align="center"><?=_("������ʺ�ͬ")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="zhongwaihz/heziht23.php"><?=_("���ʺ�ͬ")?></a></li>
        <li><a href="zhongwaihz/heziht00.php"><?=_("���ʾ�Ӫ��ͬ")?></a></li>
        <li><a href="zhongwaihz/heziht20.php"><?=_("������Ӫ��ҵ��ͬ")?></a></li>
        <li><a href="zhongwaihz/heziht21.php"><?=_("���������˾�³�")?></a></li>
        <li><a href="zhongwaihz/heziht01.php"><?=_("������ʾ�Ӫ��ҵ��ͬ")?></a></li>
        <li><a href="zhongwaihz/heziht18.php"><?=_("������ʾ�Ӫ��ҵ�³�")?></a></li>
        <li><a href="zhongwaihz/heziht04.php"><?=_("���������Ӫ��ҵ��ͬ")?></a></li>
        <li><a href="zhongwaihz/heziht05.php"><?=_("��ҵ���ͬ�ο���ʽ������һ��")?></a></li>
        <li><a href="zhongwaihz/heziht30.php"><?=_("��ҵ���ͬ�ο���ʽ��")?></a><a href="zhongwaihz/heziht30.php"><?=_("������")?></a><a href="zhongwaihz/heziht30.php">��</a></li>
        <li><a href="zhongwaihz/heziht02.php"><?=_("��ҵ���ͬ�ο���ʽ����������")?></a></li>
        <li><a href="zhongwaihz/heziht25.php"><?=_("��ҵ���ͬ�ο���ʽ�������ģ�")?></a></li>
        <li><a href="zhongwaihz/heziht31.php"><?=_("��ҵ���ͬ�ο���ʽ�������壩")?></a></li>
        <li><a href="zhongwaihz/heziht07.php"><?=_("�������ͬ�ο���ʽ������һ��")?></a></li>
        <li><a href="zhongwaihz/heziht06.php"><?=_("�������ͬ�ο���ʽ����������")?></a></li>
        <li><a href="zhongwaihz/heziht27.php"><?=_("�������ͬ�ο���ʽ������һ��")?></a></li>
        <li><a href="zhongwaihz/heziht19.php"><?=_("�������ͬ�ο���ʽ����������")?></a></li>
        <li><a href="zhongwaihz/heziht03.php"><?=_("�����������������ʾ�Ӫ��ͬ")?></a></li>
        <li><a href="zhongwaihz/heziht08.php"><?=_("����������ʾ�Ӫ��ҵ��ͬ���������죩")?></a></li>
        <li><a href="zhongwaihz/heziht09.php"><?=_("����������ʾ�Ӫ��ҵ��ͬ��Ƥ����Ʒ��")?></a></li>
        <li><a href="zhongwaihz/heziht10.php"><?=_("����������ʾ�Ӫ��ҵ��ͬ����������")?></a></li>
        <li><a href="zhongwaihz/heziht11.php"><?=_("����������ʾ�Ӫ��ҵ��ͬ��������Ʒ��")?></a></li>
        <li><a href="zhongwaihz/heziht14.php"><?=_("����������ʾ�Ӫ��ҵ��ͬ����¯������")?></a></li>
        <li><a href="zhongwaihz/heziht29.php"><?=_("����������ʾ�Ӫ��ҵ��ͬ����ͷ������")?></a></li>
        <li><a href="zhongwaihz/heziht32.php"><?=_("����������ʾ�Ӫ��ҵ��ͬ��ҽҩ��")?></a></li>
        <li><a href="zhongwaihz/heziht12.php"><?=_("Ͷ��������������ҵ��ͬ������")?></a></li>
        <li><a href="zhongwaihz/heziht13.php"><?=_("��������������Ӳ������Ʒ���ʾ�Ӫ��ҵ��ͬ")?></a></li>
        <li><a href="zhongwaihz/heziht28.php"><?=_("��������������к�ͬ")?></a></li>
        <li><a href="zhongwaihz/heziht15.php"><?=_("������ʾ�Ӫ��ҵ��ͬ�������ݰ���")?></a></li>
        <li><a href="zhongwaihz/heziht16.php"><?=_("Ͷ�������������к�ͬ")?></a></li>
        <li><a href="zhongwaihz/heziht17.php"><?=_("�������ͬ�ο���ʽ��������")?></a></li>
        <li><a href="zhongwaihz/heziht24.php"><?=_("ҽҩ���ͬ�ο���ʽ��������")?></a></li>
        <li><a href="zhongwaihz/heziht22.php"><?=_("����ũ����Ʒ������Ӫ��ͬ")?></a></li>
        <li><a href="zhongwaihz/heziht26.php"><?=_("Ͷ�������������޹�˾��ͬ")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>