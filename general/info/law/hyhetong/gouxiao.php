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
      <div align="center"><?=_("������ͬ")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="gouxiao/maimaiht03.php"><?=_("ũ����Ʒ������ͬ��һ��")?></a></li>
        <li><a href="gouxiao/maimaiht18.php"><?=_("ũ����Ʒ������ͬ������")?></a></li>
        <li><a href="gouxiao/maimaiht19.php"><?=_("ũ����Ʒ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht00.php"><?=_("��Ʒ��������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht01.php"><?=_("�ɽ�ȷ���飨Զ��")?></a></li>
        <li><a href="gouxiao/maimaiht02.php"><?=_("�̽۹�����ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht04.php"><?=_("��ʳ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht05.php"><?=_("���ֻ㣩��ó��ͬ�飨֮һ��")?></a></li>
        <li><a href="gouxiao/maimaiht06.php"><?=_("���ֻ㣩��ó��ͬ�飨֮����")?></a></li>
        <li><a href="gouxiao/maimaiht07.php"><?=_("���ֻ㣩��ó��ͬ�飨֮����")?></a></li>
        <li><a href="gouxiao/maimaiht08.php"><?=_("�����Ʒ������ͬ��һ��")?></a></li>
        <li><a href="gouxiao/maimaiht40.php"><?=_("�����Ʒ������ͬ������")?></a></li>
        <li><a href="gouxiao/maimaiht59.php"><?=_("�����Ʒ������ͬ������")?></a></li>
        <li><a href="gouxiao/maimaiht09.php"><?=_("�����Ʒ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht10.php"><?=_("�����Ʒ��Ӧ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht11.php"><?=_("�̲�Ʒ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht12.php"><?=_("�������������Ʒ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht13.php"><?=_("������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht14.php"><?=_("��Ӧ��ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht15.php"><?=_("���ۺ�ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht37.php"><?=_("������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht38.php"><?=_("�۹���ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht34.php"><?=_("�ۻ���ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht16.php"><?=_("��Ҷ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht17.php"><?=_("��ʳ�����г����ͽ��׺�ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht20.php"><?=_("ũ��������ԤԼ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht21.php"><?=_("ũ�������ӹ�����ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht22.php"><?=_("������ͬ��һ��")?></a></li>
        <li><a href="gouxiao/maimaiht60.php"><?=_("������ͬ������")?></a></li>
        <li><a href="gouxiao/maimaiht23.php"><?=_("������ͬ������")?></a></li>
        <li><a href="gouxiao/maimaiht62.php"><?=_("������ͬ���ģ�")?></a></li>
        <li><a href="gouxiao/maimaiht63.php"><?=_("������ͬ���壩")?></a></li>
        <li><a href="gouxiao/maimaiht39.php"><?=_("������ͬ������")?></a></li>
        <li><a href="gouxiao/maimaiht25.php"><?=_("������ͬ���ߣ�")?></a></li>
        <li><a href="gouxiao/maimaiht24.php"><?=_("���ʡ���ѧũҩ��ũĤ��Ʒ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht27.php"><?=_("����ȷ���飨һ��")?></a></li>
        <li><a href="gouxiao/maimaiht28.php"><?=_("����ȷ���飨����")?></a></li>
        <li><a href="gouxiao/maimaiht33.php"><?=_("����ȷ���飨����")?></a></li>
        <li><a href="gouxiao/maimaiht30.php"><?=_("ƾ������ۺ�ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht31.php"><?=_("ľ�Ĺ�������������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht32.php"><?=_("���ñ������Ĺ�����ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht35.php"><?=_("�޻�������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht36.php"><?=_("�̲�������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht41.php"><?=_("������Ʒ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht42.php"><?=_("ú������Ʒ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht43.php"><?=_("��Ǵ󽷸ɹ�����ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht44.php"><?=_("����Ʒ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht45.php"><?=_("�޻�������ͬ����������")?></a></li>
        <li><a href="gouxiao/maimaiht46.php"><?=_("ˮ��������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht47.php"><?=_("��𽻵�ҵ绯����Ʒ������ͬ��һ��")?></a></li>
        <li><a href="gouxiao/maimaiht26.php"><?=_("��𽻵�ҵ绯����Ʒ������ͬ������")?></a></li>
        <li><a href="gouxiao/maimaiht48.php"><?=_("�����ʵ�����ţ�����򡢼��ݹ�����ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht49.php"><?=_("�߲˶�����ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht50.php"><?=_("���֯Ʒ�����ֺ�ͬ��̨����ϸ��")?></a></li>
        <li><a href="gouxiao/maimaiht51.php"><?=_("������Ʒ�����ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht53.php"><?=_("ƾ������ۺ�ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht54.php"><?=_("ӡˢƷ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht55.php"><?=_("ľ�Ĺ���(����)��ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht56.php"><?=_("���Ķ�����ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht57.php"><?=_("ˮ��������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht58.php"><?=_("���촬����ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht64.php"><?=_("�ʵ�������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht65.php"><?=_("�����Ʒ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht66.php"><?=_("��Ҷ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht67.php"><?=_("��Ҷ������ͬ")?></a></li>
        <li><a href="gouxiao/maimaiht68.php"><?=_("���ϲ�����ͬ")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>