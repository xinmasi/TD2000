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
      <div align="center"><?=_("���պ�ͬ")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="baoxian/baoxianht00.php"><?=_("�й������չ�˾��������Ͷ����")?></a></li>
        <li><a href="baoxian/baoxianht01.php"><?=_("���ڴ������յ�")?></a></li>
        <li><a href="baoxian/baoxianht02.php"><?=_("�й������չ�˾��������Ͷ����")?></a></li>
        <li><a href="baoxian/baoxianht03.php"><?=_("�й������չ�˾�����������յ�")?></a></li>
        <li><a href="baoxian/baoxianht04.php"><?=_("�й������չ�˾�Ʋ����յ�")?></a></li>
        <li><a href="baoxian/baoxianht05.php"><?=_("�й������չ�˾����������䱣�յ�")?></a></li>
        <li><a href="baoxian/baoxianht06.php"><?=_("�Ʋ�һ����Ͷ��������")?></a></li>
        <li><a href="baoxian/baoxianht07.php"><?=_("�й������չ�˾XXX�ֹ�˾��ţ����Ͷ����")?></a></li>
        <li><a href="baoxian/baoxianht08.php"><?=_("���ڴ�������Ͷ����")?></a></li>
        <li><a href="baoxian/baoxianht09.php"><?=_("���ڻ���������Ͷ����")?></a></li>
        <li><a href="baoxian/baoxianht10.php"><?=_("�й������չ�˾����������䱣�յ�")?></a></li>
        <li><a href="baoxian/baoxianht11.php"><?=_("�Ʋ�һ���ձ��յ�")?></a></li>
        <li><a href="baoxian/baoxianht12.php"><?=_("����ˮ·����·�������䱣��ƾ֤")?></a></li>
        <li><a href="baoxian/baoxianht13.php"><?=_("�й������չ�˾�Ʋ���Ͷ��������")?></a></li>
        <li><a href="baoxian/baoxianht14.php"><?=_("�й������չ�˾�������ձ��յ�")?></a></li>
        <li><a href="baoxian/baoxianht15.php"><?=_("�й������չ�˾��������װ������Ͷ����")?></a></li>
        <li><a href="baoxian/baoxianht16.php"><?=_("��ͯ���պ�ͬ")?></a></li>
        <li><a href="baoxian/baoxianht17.php"><?=_("�й������չ�˾��ҵ�Ʋ�����Ͷ����")?></a></li>
        <li><a href="baoxian/baoxianht18.php"><?=_("�й������չ�˾��������װ���̱��յ�")?></a></li>
        <li><a href="baoxian/baoxianht19.php"><?=_("�й������չ�˾�Ʋ���Ͷ��������")?></a></li>
        <li><a href="baoxian/baoxianht20.php"><?=_("��ҵ�Ʋ����յ�")?></a></li>
        <li><a href="baoxian/baoxianht21.php"><?=_("��ҵ�Ʋ�����Ͷ����")?></a></li>
        <li><a href="baoxian/baoxianht22.php"><?=_("�й������չ�˾��������һ���ձ��յ�")?></a></li>
        <li><a href="baoxian/baoxianht23.php"><?=_("�������������˺����ձ��յ�")?></a></li>
        <li><a href="baoxian/baoxianht24.php"><?=_("�������ٱ��պ�ͬ")?></a></li>
        <li><a href="baoxian/baoxianht25.php"><?=_("�������������˺����պ�ͬ")?></a></li>
        <li><a href="baoxian/baoxianht26.php"><?=_("�������������˺�����Ͷ����")?></a></li>
        <li><a href="baoxian/baoxianht27.php"><?=_("�������������˺���������")?></a></li>
        <li><a href="baoxian/baoxianht28.php"><?=_("�й������չ�˾XXX�ֹ�˾��ţ���ձ��յ�")?></a></li>
        <li><a href="baoxian/baoxianht29.php"><?=_("�й������չ�˾��ҵ�Ʋ����յ�")?></a></li>
        <li><a href="baoxian/baoxianht30.php"><?=_("�Ʋ����պ�ͬ��ʽ�����⣩")?></a></li>
        <li><a href="baoxian/baoxianht31.php"><?=_("�й������չ�˾���䱣��ƾ֤")?></a></li>
        <li><a href="baoxian/baoxianht32.php"><?=_("�й������չ�˾��ͥ�Ʋ����յ�")?></a></li>
        <li><a href="baoxian/baoxianht33.php"><?=_("���壨���ˣ����������˺����ո��������˺�ҽ�Ʊ�����������")?></a></li>
        <li><a href="baoxian/baoxianht34.php"><?=_("�Ʋ����պ�ͬ")?></a></li>
        <li><a href="baoxian/baoxianht35.php"><?=_("�й������չ�˾XXX�ֹ�˾��������װ���̱��յ�")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>