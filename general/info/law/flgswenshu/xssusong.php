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
        <li><a href="xssusong/xinshiws00.php"><?=_("��������״")?></a></li>
        <li><a href="xssusong/xinshiws01.php"><?=_("���¸���������״")?></a></li>
        <li><a href="xssusong/xinshiws02.php"><?=_("���´��״")?></a></li>
        <li><a href="xssusong/xinshiws03.php"><?=_("��������״")?></a></li>
        <li><a href="xssusong/xinshiws04.php"><?=_("�������߰�������״")?></a></li>
        <li><a href="xssusong/xinshiws05.php"><?=_("��Ȩί����")?></a></li>
        <li><a href="xssusong/xinshiws06.php"><?=_("����ָ���绤��")?></a></li>
        <li><a href="xssusong/xinshiws07.php"><?=_("����Ϊ�����˵��α绤��ί��Э��")?></a></li>
        <li><a href="xssusong/xinshiws08.php"><?=_("��ʦ�����Ѻ����������������")?></a></li>
        <li><a href="xssusong/xinshiws09.php"><?=_("�����ռ�����ȡ֤��������")?></a></li>
        <li><a href="xssusong/xinshiws10.php"><?=_("֪֤ͨ�˳�ͥ������")?></a></li>
        <li><a href="xssusong/xinshiws11.php"><?=_("���¼���������������")?></a></li>
        <li><a href="xssusong/xinshiws12.php"><?=_("��������������")?></a></li>
        <li><a href="xssusong/xinshiws13.php"><?=_("���ǿ�ƴ�ʩ������")?></a></li>
        <li><a href="xssusong/xinshiws14.php"><?=_("ȡ������������")?></a></li>
        <li><a href="xssusong/xinshiws15.php"><?=_("����������")?></a></li>
        <li><a href="xssusong/xinshiws16.php"><?=_("����֪ͨ��")?></a></li>
        <li><a href="xssusong/xinshiws17.php"><?=_("����߽�ڹ�֪ͨ��")?></a></li>
        <li><a href="xssusong/xinshiws18.php"><?=_("�绤��ʦ���ġ�ժ�������ư����������顢�����Լ������ϵǼǱ�")?></a></li>
        <li><a href="xssusong/xinshiws19.php"><?=_("���������߾���������")?></a></li>
        <li><a href="xssusong/xinshiws20.php"><?=_("����������������ĸ���������")?></a></li>
        <li><a href="xssusong/xinshiws21.php"><?=_("����������")?></a></li>
        <li><a href="xssusong/xinshiws22.php"><?=_("����ȡ֤������")?></a></li>
        <li><a href="xssusong/xinshiws23.php"><?=_("��ͥ�绤��")?></a></li>
        <li><a href="xssusong/xinshiws24.php"><?=_("�رܸ���������")?></a></li>
        <li><a href="xssusong/xinshiws25.php"><?=_("�����Ѻ���������������")?></a></li>
        <li><a href="xssusong/xinshiws26.php"><?=_("���̡�����������")?></a></li>
        <li><a href="xssusong/xinshiws27.php"><?=_("�ر�������")?></a></li>
        <li><a href="xssusong/xinshiws28.php"><?=_("���ǿ�ƴ�ʩ������")?></a></li>
        <li><a href="xssusong/xinshiws29.php"><?=_("�ظ�״")?></a></li>
        <li><a href="xssusong/xinshiws30.php"><?=_("��ʦ�����Ѻ���������˵ĺ�")?></a></li>
        <li><a href="xssusong/xinshiws31.php"><?=_("�����ල������")?></a></li>
        <li><a href="xssusong/xinshiws32.php"><?=_("����ָ���绤��")?></a></li>
        <li><a href="xssusong/xinshiws33.php"><?=_("Ϊ�����������ṩ���ɰ���ί��Э��")?></a></li>
        <li><a href="xssusong/xinshiws34.php"><?=_("���°�������ί��Э��")?></a></li>
        <li><a href="xssusong/xinshiws35.php"><?=_("���±绤ί��Э��")?></a></li>
        <li><a href="xssusong/xinshiws36.php"><?=_("���±绤��Ȩί����")?></a></li>
        <li><a href="xssusong/xinshiws37.php"><?=_("���±绤��ʦ��������")?></a></li>
        <li><a href="xssusong/xinshiws38.php"><?=_("��ʦ�������������°������쵥")?></a></li>
        <li><a href="xssusong/xinshiws39.php"><?=_("Ϊ�����������ṩ���ɰ�����ʦ��������")?></a></li>
        <li><a href="xssusong/xinshiws40.php"><?=_("֪֤ͨ�˳�ͥ������")?></a></li>
        <li><a href="xssusong/xinshiws41.php"><?=_("�����ռ�����ȡ֤��������")?></a></li>
        <li><a href="xssusong/xinshiws42.php"><?=_("ȡ������������")?></a></li>
        <li><a href="xssusong/xinshiws43.php"><?=_("���´�����Ȩί����")?></a></li>
        <li><a href="xssusong/xinshiws44.php"><?=_("��������״")?></a></li>
        <li><a href="xssusong/xinshiws45.php"><?=_("��������״")?></a></li>
        <li><a href="xssusong/xinshiws46.php"><?=_("���¸���������״")?></a></li>
        <li><a href="xssusong/xinshiws47.php"><?=_("���¼���������������")?></a></li>
        <li><a href="xssusong/xinshiws48.php"><?=_("���´����")?></a></li>
        <li><a href="xssusong/xinshiws49.php"><?=_("��ʦ����������ר��֤�������£�")?></a></li>
        <li><a href="xssusong/xinshiws50.php"><?=_("��������������")?></a></li>
        <li><a href="xssusong/xinshiws51.php"><?=_("�������߰�������״")?></a></li>
        <li><a href="xssusong/xinshiws52.php"><?=_("��������״")?></a></li>
        <li><a href="xssusong/xinshiws53.php"><?=_("���¿���������")?></a></li>
        <li><a href="xssusong/xinshiws54.php"><?=_("���´��״")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>