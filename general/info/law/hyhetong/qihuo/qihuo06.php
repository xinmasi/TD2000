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
      <div align="center">������Э��</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p>һ���ֽ������������Ҫ��������£�</p>
      <div style='layout-grid:15.6pt'>
<p >1���������ˡ�</p>
        <p >2����������˾�����֡�</p>
        <p >3����������˾�ľ�Ӫ��Χ��</p>
        <p >4���ɱ��ܶ</p>
        <p >5�����������Ϲ��ķݶ</p>
        <p >6����������Ȩ������</p>
        <p >7����˾������</p>
        <p >8��ΥԼ���Ρ�</p>
        <p >9��Э����޸�����ֹ��</p>
        <p >������ʽ��</p>
        <p align=center style='
text-align:center;line-height:22.0pt;
'>��������<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>�ɷ����޹�˾�ķ�����Э����</p>
        <p >��Э�������µ�������<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>��ǩ��</p>
        <p >1��<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>�������ι�˾�����¼�Ƽ׷���</p>
        <p >���������ˣ�</p>
        <p >ס����<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>�绰��</p>
        <p >���棺<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�ʱࣺ</p>
        <p >2��<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>�������¼���ҷ���</p>
        <p >���������ˣ�</p>
        <p >ס����<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>�绰��</p>
        <p >���棺<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>�ʱࣺ </p>
        <p >3��<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>�������¼�Ʊ�����</p>
        <p >���������ˣ�</p>
        <p >ס����<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>�绰��</p>
        <p >���棺<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>�ʱࣺ </p>
        <p >4��<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>�������ι�˾�����¼�ƶ�����</p>
        <p >���������ˣ�</p>
        <p >ס����<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>�绰��</p>
        <p >���棺<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>&nbsp;�ʱࣺ</p>
        <p >5��<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>��ó�������ι�˾�����¼���췽��</p>
        <p >���������ˣ�</p>
        <p >ס����<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>�绰��</p>
        <p >���棺<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>�ʱࣺ</p>
        <p >���ڣ�</p>
        <p >1���������������˾�Ϊ�����й����ɺϷ���������ҵ���ˣ�</p>
        <p >2���������������˾�ͬ����Ϊ�������Է���ʽ��ͬ����<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>�ɷ����޹�˾��</p>
        <p >Ϊ��ȷ���������˵�Ȩ�����񣬸������������Ѻ�Э�̵Ļ�����ǩ������Э�飬�Թ�������ͬ���أ�</p>
        <p >1���ɷݹ�˾������</p>
        <p >2����ɷݹ��ܸſ�</p>
        <p >3�����������Ͻɹɱ��ķ�ʽ������</p>
        <p >4���ɷݹ�˾��ί��ĳ�����ְȨ</p>
        <p >5���ｨ����</p>
        <p >6����������Ȩ��������</p>
        <p >7��ΥԼ����</p>
        <p >8����ͬ���޸�����ֹ</p>
        <p >9������Ľ��</p>
        <p >10������</p>
        <p >�׷�����ǩ�£�</p>
        <p >���������ˣ�����Ȩ������ǩ�֣�</p>
        <p >�ҷ�����ǩ�£�</p>
        <p >���������ˣ�����Ȩ������ǩ�֣�</p>
        <p align=right >����<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></p>
        <p class=MsoBodyTextIndent>������һ����������ʱ�ο���</p>
        <p align=center style='
text-align:center;line-height:22.0pt;
'>�����ɷ����޹�˾������Э��</p>
        <p >��һ�������¸���������ͬ����Ϊ�������Է���ʽ��ͬ�������������ɷ��������ι�˾�����¼�ƹɷݹ�˾����������������ƽ����Ը���Ѻ�Э�̵Ļ����ϣ����ա��л����񹲺͹���˾�������������ɡ�����Ĺ涨ǩ����Э�飺</p>
        <p >1�������������ι�˾�����¼�Ƽ׷���</p>
        <p >���������ˣ�������</p>
        <p >סַ�������С���·������</p>
        <p >�绰��<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>���棺</p>
        <p >�ʱࣺ</p>
        <p >2��������ó��˾�����¼���ҷ���</p>
        <p >���������ˣ�������</p>
        <p >סַ�������С���·������</p>
        <p >�绰��<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>���棺</p>
        <p >�ʱࣺ</p>
        <p >3��������ѯ�������ι�˾�����¼�Ʊ�����</p>
        <p >���������ˣ�������</p>
        <p >סַ�������С���·������</p>
        <p >�绰��<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>���棺</p>
        <p >�ʱࣺ</p>
        <p >4�������Ƽ��������ι�˾�����¼�ƶ�����</p>
        <p >���������ˣ�������</p>
        <p >סַ�������С���·������</p>
        <p >�绰��<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���棺</p>
        <p >�ʱࣺ</p>
        <p >5��������˾�����¼���췽��</p>
        <p >���������ˣ�������</p>
        <p >סַ�������С���·������</p>
        <p >�绰��<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span>���棺</p>
        <p >�ʱࣺ</p>
        <p >�ڶ������ɷݹ�˾������Ϊ�������ɷ����޹�˾��</p>
        <p >סַ�������С���·������</p>
        <p >�ʱࣺ������������</p>
        <p >���������ɷݹ�˾�ڡ���ʡ�������������������׼�ľ�Ӫ��Χ�ڴ��»��</p>
        <p class=MsoBodyTextIndent>�ɷݹ�˾�ľ�Ӫ��Χ����ϩ���ұ������顢̼����李����͡�Һ������������Ʒ���������ۣ��豸��װ��ά��������ҵ��ʯ�ͻ�����Ʒ�����ڡ�</p>
        <p >���������ɷݹ�˾ע���ʱ�Ϊ�����<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>Ԫ��</p>
        <p >���������ɷݹ�˾�ɱ��ܶ���Ϊ<u><span>&nbsp;&nbsp;&nbsp; </span></u>�ɣ�ÿ����ֵ�����<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>Ԫ����Ϊ��ͨ�ɡ�</p>
        <p >���������ɷݹ�˾���������Ծ�Ӫ�Ծ��ʲ����ֽ���ʰ�65%�ı����۹ɡ��������˾����Ϲ��ɷݵ��������£�</p>
        <p >1���׷��Ծ������ľ�Ӫ�Ծ��ʲ�<u><span>&nbsp;&nbsp;&nbsp; 
          </span></u>Ԫ����65%�ı����۹�Ϊ<u><span>&nbsp;&nbsp;&nbsp; </span></u>�ɣ�ռ�ɷݹ�˾�ɱ��ܶ��<u><span>&nbsp;&nbsp;&nbsp; 
          </span></u>���ٷֱȣ���</p>
        <p >2���ҷ��Ծ������ľ�Ӫ�Ծ��ʲ�<span>&nbsp;&nbsp;&nbsp; 
          </span>Ԫ����65%�ı����۹�Ϊ<u><span>&nbsp;&nbsp;&nbsp; </span></u>�ɣ�ռ�ɷݹ�˾�ɱ��ܶ��<u><span>&nbsp;&nbsp;&nbsp; 
          </span></u>���ٷֱȣ���</p>
        <p >3���������ֽ�<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>Ԫ����65%�ı�����Ϊ<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></u>�ɣ�ռ�ɷݹ�˾�ɱ��ܶ�� &nbsp;&nbsp;&nbsp;���ٷֱȣ���</p>
        <p >4���������ֽ�<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>Ԫ����65%�ı�����Ϊ<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></u>�ɣ�ռ�ɷݹ�˾�ɱ��ܶ��<span>&nbsp;&nbsp;&nbsp; 
          </span>���ٷֱȣ���</p>
        <p >5���췽���ֽ�<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>Ԫ����65%�ı�����Ϊ<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></u>�ɣ�ռ�ɷݹ�˾�ɱ��ܶ��<u><span>&nbsp;&nbsp;&nbsp; 
          </span></u>���ٷֱȣ���</p>
        <p >����������������Ӧ��<u><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          </span></u>��<u><span>&nbsp;&nbsp;&nbsp; </span></u>��<u><span>&nbsp;&nbsp;&nbsp; 
          </span></u>��֮ǰ�����Ϲ��Ĺɿ�ȫ�����塣��ʵ����ʵģ������йزƲ�Ȩת���������Ի����ֽ���ʵģ������Ϲ��Ĺɿ����ɷݹ�˾��ί��ָ����ר���ʻ���</p>
        <p >�ڰ������ɷݹ�˾Ϊ���ô����Ĺɷ����޹�˾���ɶ��������Ϲ��ĹɷݶԹɷݹ�˾�е��������Σ���������ͷֵ����𣬹ɷݹ�˾����ȫ���ʲ��Թ�˾ծ��е����Ρ�</p>
        <p >�ھ�������������Ӧ��ʱ�ṩ����˾ע�������ȫ���ļ���</p>
        <p >��ʮ������������ί��һ��������ɹɷݹ�˾��ί�ᣬ���ɴ������ʱ���ѡ��һ����ί�����Ρ���ί��ȫȨ����ȫ�巢���˰���ɷݹ�˾ע����������</p>
        <p >��ʮһ�����ɷݹ�˾�������������������������</p>
        <p >1��Ƹ���й��н�������й�����</p>
        <p >2��������ɷݹ�˾�ĸ����ļ���</p>
        <p >3��Э����������֮��Ĺ�ϵ��</p>
        <p >4������ɷݹ�˾����������������������й����ܲ��Ż������һ�б�Ҫ����׼����ɺ�ͬ�⣻</p>
        <p >5��������ɷݹ�˾�����йص����ˡ�</p>
        <p >��ʮ�������ɷݹ�˾�ĳｨ�������ɼ׷��渶�����尴�й�ƾ֤���㡣�ɷݹ�˾���������󣬸÷����ɹɷݹ�˾�е����ɷݹ�˾��ʲ�������ʱ���ɸ������˰��Ϲ��ɷݱ�����̯��</p>
        <p >��ʮ�������������˳е��������Σ�</p>
        <p >1���ɷݹ�˾��������ʱ����������Ϊ�������ķ��ú�ծ���������Σ�</p>
        <p >2���ڹɷݹ�˾���������У����ڷ����˵Ĺ�ʧ��ʹ�ɷݹ�˾�ܵ���ʱ��Ӧ�е��⳥���Ρ�</p>
        <p >3����������ӦΪ��ί�������ɷݹ�˾����Ϊ�ṩ���ֱ��������</p>
        <p >4����������Ӧ����ڹɷݹ�˾����������Ӧ�ɸ���������ɵĹ�����</p>
        <p >��ʮ���������ڷ�������һ����ΥԼ��Ϊ����ɹɷݹ�˾�����ĳ��ӻ� �ܣ��ɸ÷������˸����⳥��������ֶ෽ΥԼ������ݸ���ʵ�ʹ�����������������е��⳥���Ρ�</p>
        <p >��ʮ������������������֮һ�ģ������޸ı�Э�飺</p>
        <p >1�����ڲ��ɿ����ķ�����Э������޸ģ�</p>
        <p >2�����������˺����޸ģ�</p>
        <p >3��һ����෽����������޸ģ���������û������ģ�</p>
        <p >4�����������</p>
        <p >��Э����޸ı���������ġ�</p>
        <p >��ʮ������������������֮һ�ģ�������ֹ��Э�飺</p>
        <p >1���йعɷݹ�˾��������ɣ�</p>
        <p >2�����������˺�����ֹ��</p>
        <p >3���������ɿ�����Э�������ֹ��</p>
        <p >4�����������</p>
        <p >��Э�����ֹ����������ġ�</p>
        <p >��ʮ��������ִ�б�Э�鷢����һ�����飬��������Ӧ�Ѻ�Э�̣����Э�̲��ܽ�����������˾���Ȩ��������м�����Ժ���ߡ�</p>
        <p >��ʮ��������Э��һʽʮ�ݣ��������˸�ִ���ݣ�����ͬ�ȷ���Ч����</p>
        <p >��ʮ��������Э���Ը������˷��������˻���Ȩ����ȫ��ǩ�ֲ�����֮������Ч��</p>
        <p >�׷��������£�</p>
        <p >����ǩ�֣�</p>
        <p >�ҷ��������£�</p>
        <p >����ǩ�֣�</p>
        <p >�����������£�</p>
        <p >����ǩ�֣�</p>
        <p >�����������£�</p>
        <p >����ǩ�֣�</p>
        <p >�췽�������£�</p>
        <p >����ǩ�֣�</p>
      </div>
      <p>����<BR>
        ����<BR>
        �������� </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body></html>