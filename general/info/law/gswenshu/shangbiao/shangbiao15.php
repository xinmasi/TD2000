<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?><style type="text/css">
<!--a            { text-decoration: none; font-size: 9pt; color: black; font-family: ���� }
.text        { font-size: 9pt; font-family: ���� }
.text1       { color: #0000A0; font-size: 11pt; font-family: ���� }
.text2       { color: #008080; font-size: 9pt; font-family: ���� }
.text3       { color: #0F8A91; font-size: 11pt; font-family: ���� }
.l100        { line-height: 14pt; font-size: 9pt }
td           { font-family: ����; font-size: 9pt; line-height: 13pt }
input        { font-size: 9pt; font-family: ���� }
p            { font-size: 9pt; font-family: ���� }
--></style><BODY class="bodycolor">
<BR>
<table width="500" class="TableBlock" align="center">
  <tr> 
    <td height="27" class="TableHeader">
      <div align="center">ע���̱�ʹ����ɺ�ͬ</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p>һ���ֽ������������Ҫ��������£�</p>
      <div style='layout-grid:15.6pt'>
<p >1��ע���̱�ʹ����ɺ�ͬ��ʹ�õ����Ʊ������̱�ע��֤�ϵ��̱�ע���˵�����һ�£���Ʒ������Ҳ�������ڸ�ע���̱�˶�ʹ�õ���Ʒ��Χ֮�ڡ�</p>
        <p >2���ں�ͬ��Ӧ�������ȷ�ض���˫����Ȩ�������񣬰������ʹ�õ����ޡ���Ʒ�ķ�Χ����������֧����ʽ�ȡ�</p>
        <p >3��Ӧ���ա��̱귨�����йع涨����ȷ����˼ල��Ʒ�����ͱ�����˱�֤��Ʒ�����Ĵ�ʩ��</p>
        <p >4��������˫���������ں�ͬ��Լ��ΥԼ���εĳе�����ͬ���׵Ľ����ʽ�ȡ�</p>
        <p >5��ע���̱�ʹ����ɺ�ͬӦ��һʽ��ݣ�������˫������һ�ݣ�һ�ݱ����̱�ֱ����������ݷֱ��ͽ�������˫�����ڵص��ؼ���������������ش�顣</p>
        <p >6����������ϡ��л����񹲺͹���ͬ�������йع涨��</p>
        <p >������ʽ��</p>
        <p align=center style='
text-align:center;line-height:22.0pt;
'>ע���̱�ʹ����ɺ�ͬ</p>
        <p >����ˣ����³Ƽ׷���</p>
        <p >������ˣ����³��ҷ���</p>
        <p >���׷����ҷ�Э�̣����Э�����£�</p>
        <p >1���׷�����ҷ���<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </u>��<u>&nbsp;&nbsp;&nbsp; </u>��<u>&nbsp;&nbsp;&nbsp; </u>����<u>&nbsp;&nbsp;&nbsp; 
          </u>��<u>&nbsp;&nbsp;&nbsp; </u>����<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </u>��Ʒ��ʹ�ü׷�ע��ĵ�<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u>��<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </u>�̱ꣻ</p>
        <p >2���ҷ�Ӧ�����׷����ʹ�ñ���<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </u>Ԫ��֧���취Ϊ<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </u></p>
        <p >3���׷�����/������<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </u>��<u>&nbsp;&nbsp;&nbsp; </u>��<u>&nbsp;&nbsp;&nbsp; </u>����<u>&nbsp;&nbsp;&nbsp; 
          </u>��<u>&nbsp;&nbsp;&nbsp; </u>������<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </u>����ʹ�û���ɵ�����ʹ������ע���̱ꣻ</p>
        <p >4���׷���֤<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </u></p>
        <p >5���ҷ���֤<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </u></p>
        <p >����ͬһʽ<span>&nbsp;&nbsp;&nbsp; </span>�ݣ�������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          �أ��С�����������������ִ�顣</p>
        <p >��<span>&nbsp; </span>�������´���&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          ��&nbsp; �������´���</p>
        <p >�����ˣ����´���<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>�����ˣ���ǩ�£�</p>
        <p >ǩ��ʱ�䣺<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>��&nbsp;&nbsp;&nbsp; ��&nbsp;&nbsp;&nbsp; ��&nbsp;&nbsp;&nbsp; ǩ��ʱ�䣺&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          ��&nbsp;&nbsp;&nbsp; ��&nbsp;&nbsp;&nbsp; ��</p>
        <p >ǩ���ص㣺<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>ǩ���ص㣺</p>
      </div>
      <p>����<BR>
        ����<BR>
        �������� </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body></html>