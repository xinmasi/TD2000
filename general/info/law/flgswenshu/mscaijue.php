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
      <div align="center"><?=_("���²þ���")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="mscaijue/mscaijue00.php"><?=_("���²ö��飨����ִ���ã�")?></a></li>
        <li><a href="mscaijue/mscaijue01.php"><?=_("���²ö��飨�����ߴ����ã�")?></a></li>
        <li><a href="mscaijue/mscaijue02.php"><?=_("���²ö��飨׼���׼�����ã�")?></a></li>
        <li><a href="mscaijue/mscaijue03.php"><?=_("���²ö��飨��ֹ���ս������ã�")?></a></li>
        <li><a href="mscaijue/mscaijue04.php"><?=_("���²ö��飨���󷢻������ã�")?></a></li>
        <li><a href="mscaijue/mscaijue05.php"><?=_("���²ö��飨��ԭ�󲵻����ߵ����߰����ã�")?></a></li>
        <li><a href="mscaijue/mscaijue06.php"><?=_("���²ö��飨��ԭ������������߰����ã�")?></a></li>
        <li><a href="mscaijue/mscaijue07.php"><?=_("���²ö��飨����֧���������ã�")?></a></li>
        <li><a href="mscaijue/mscaijue08.php"><?=_("���²ö��飨�Թ�ϽȨ����������߰����ã�")?></a></li>
        <li><a href="mscaijue/mscaijue09.php"><?=_("���²ö��飨׼���׼���������ã�")?></a></li>
        <li><a href="mscaijue/mscaijue10.php"><?=_("���²ö��飨�ս��ر�����ã�")?></a></li>
        <li><a href="mscaijue/mscaijue11.php"><?=_("���²ö��飨ָ���¼���Ժ�������������ã�")?></a></li>
        <li><a href="mscaijue/mscaijue12.php"><?=_("���²ö��飨��Ժ�������������ã�")?></a></li>
        <li><a href="mscaijue/mscaijue13.php"><?=_("���²ö��飨���ݿ��߻��������������ã�")?></a></li>
        <li><a href="mscaijue/mscaijue14.php"><?=_("���²ö��飨����Ʋ���ȫ�ã�")?></a></li>
        <li><a href="mscaijue/mscaijue15.php"><?=_("���²ö��飨���ϲƲ���ȫ�ã�")?></a></li>
        <li><a href="mscaijue/mscaijue16.php"><?=_("���²ö��ã���ǰ�Ʋ���ȫ�ã�")?></a></li>
        <li><a href="mscaijue/mscaijue17.php"><?=_("���²ö��飨���������ã�")?></a></li>
        <li><a href="mscaijue/mscaijue18.php"><?=_("���²ö��飨�Թ�ϽȨ�������İ����ã�")?></a></li>
        <li><a href="mscaijue/mscaijue19.php"><?=_("���²ö��飨�������������ã�")?></a></li>
        <li><a href="mscaijue/mscaijue20.php"><?=_("���²ö��飨�սṫʾ�߸�����ã�")?></a></li>
        <li><a href="mscaijue/mscaijue21.php"><?=_("���²ö��飨�սᶽ�ٳ����ã�")?></a></li>
        <li><a href="mscaijue/mscaijue22.php"><?=_("���²ö��飨����ִ���ٲòþ��ã�")?></a></li>
        <li><a href="mscaijue/mscaijue23.php"><?=_("���²ö��飨��ծ���������Ʋ���ծ�ã�")?></a></li>
        <li><a href="mscaijue/mscaijue24.php"><?=_("���²ö��飨��ծȨ�������Ʋ���ծ�ã�")?></a></li>
        <li><a href="mscaijue/mscaijue25.php"><?=_("���²ö��飨����ִ�й�֤ծȨ�����ã�")?></a></li>
        <li><a href="mscaijue/mscaijue26.php"><?=_("���²ö��飨��ȡǿ��ִ�д�ʩ�ã�")?></a></li>
        <li><a href="mscaijue/mscaijue27.php"><?=_("���²ö��飨��ֹ���ս�ִ�в��������ã�")?></a></li>
        <li><a href="mscaijue/mscaijue28.php"><?=_("���²ö��飨��ֹ�����ս�ִ���������������ã�")?></a></li>
        <li><a href="mscaijue/mscaijue29.php"><?=_("���²ö��飨�������������еı����ã�")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>