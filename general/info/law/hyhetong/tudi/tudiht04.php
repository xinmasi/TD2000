<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>
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
<BR>
<table width="500" class="TableBlock" align="center">
  <tr> 
    <td height="27" class="TableHeader">
      <div align="center">��������ʹ��Ȩ���ú�ͬ������</font></div>
    </td>
  </tr>
  <tr>
    <td height="27" valign="top" class="TableData"> 
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> ���ڵس��ú�ͬ��</p>
      <p> ����<BR> 
������һ��������ͬ˫��������<BR> 
�������÷����л����񹲺͹��ߣ�ʡ����������ֱϽ�У��ߣ��У��أ����ع����֣����¼�Ƽ׷�����<BR> 
����������ַ�ߣߣߣߣ���������ߣߣ����������ˣ������ߣߣ�ְ��ߣߣߡ�<BR> 
�������÷����ߣߣߣߣߣߣ����¼���ҷ�����������ַ�ߣߣߣߣߣߣ���������ߣߣ����������ˣ�<BR> 
���������ߣߣ�ְ��ߣߣߣߡ�<BR>
        �������ݡ��л����񹲺͹������������ʹ��Ȩ���ú�ת�����а취�������ߣߣ�ʡ��������ʹ��Ȩ����ת��ʵʩ�취���͹����йع涨��˫������ƽ�ȡ���Ը���г���ԭ�򣬶�������ͬ��<BR>
        �����ڶ������׷����ݱ���ͬ�������ص�ʹ��Ȩ����������Ȩ���л����񹲺͹���������Դ������������������ʩ����������ʹ��Ȩ���÷�Χ��<BR>
        �������������ҷ����ݱ���ͬ���õ�����ʹ��Ȩ��ʹ�������ڣ������йع涨����ת�á����⡢��Ѻ�������������û��<BR>
        �����ҷ�����������ʹ��Ȩ��Χ�������еĿ��������á���Ӫ���صĻ��Ӧ�����л����񹲺͹����ɡ����漰�ߣߣߣ�ʡ����������ֱϽ�У����йع涨������������ṫ�����棬��Ϸ�Ȩ���ܷ��ɱ�������<BR>
        �������������׷����ø��ҷ��ĵؿ�λ�ڣߣߣߣߣ����Ϊ�ߣߣߣ�ƽ���ס���λ����������Χ�籾��ͬ��ͼ��ʾ����ͼ�Ѿ��ס���˫��ǩ��ȷ�ϡ�<BR>
        ����������������ͬ���µ�����ʹ��Ȩ��������Ϊ�ߣ��꣬�԰䷢�õؿ�ġ��л����񹲺͹���������ʹ��֤��֮�����㡣<BR>
        ����������������ͬ���µĳ��õؿ飬������׼������滮�ǽ���ߣߣߣߣ���Ŀ����ע�����ݾ������������<BR>
        �����ڳ��������ڣ�����ı䱾��ͬ�涨��������;��Ӧ��ȡ�ü׷��ͳ��й滮�������ܲ�����׼�������йع涨��������ʹ��Ȩ���ú�ͬ����������ʹ��Ȩ���ý𣬲���������ʹ��Ȩ�Ǽ�������<BR>
        ����������������ͬ����������ʹ���������Ǳ���ͬ����ɲ��֣��뱾��ͬ����ͬ�ȷ���Ч�����ҷ�ͬ�ⰴ������ʹ��������ʹ�����ء�<BR>
        �����ڰ������ҷ�ͬ�ⰴ��ͬ�涨��׷�֧������ʹ��Ȩ���ý�����ʹ�÷��Լ��ҷ��������ת��ʱ��������ֵ�ѣ�˰����<BR>
        �����ھ������õؿ������ʹ��Ȩ���ý�Ϊÿƽ���ףߣ�Ԫ����ң���Ԫ���Ԫ�ȣ����ܶ�Ϊ�ߣߣ�Ԫ����ң���Ԫ���Ԫ�ȣ���<BR>
        ������ʮ��������ͬ��˫��ǩ�ֺ�ߣߣ����ڣ��ҷ������ֽ�֧Ʊ���ֽ���׷��ɸ�����ʹ��Ȩ���ý��ܶ�ģߣߣ����ƣߣ�Ԫ����ң���Ԫ���Ԫ�ȣ���Ϊ���к�ͬ�Ķ���<BR>
        �����ҷ�Ӧ��ǩ������ͬ�󣶣����ڣ�֧����ȫ������ʹ��Ȩ���ý����ڣߣ�����δȫ��֧���ģ��׷���Ȩ�����ͬ�����������ҷ��⳥��<BR>
        ������ʮһ�����ҷ�����׷�֧��ȫ������ʹ��Ȩ���ý��ߣߣ����ڣ����չ涨��������ʹ��Ȩ�Ǽ���������ȡ���л����񹲺͹���������ʹ��֤����ȡ������ʹ��Ȩ��<BR>
        ������ʮ�������ҷ�ͬ��ӣߣ��꿪ʼ���������涨�����������ʹ�÷ѣ�����ʱ��Ϊ����ߣ��£ߣ��ա�����ʹ�÷���ÿ��ÿƽ����Ϊ�ߣߣ�Ԫ����ң���Ԫ���Ԫ�ȣ���<BR>
        ������ʮ�������ҷ�ͬ������Ԫ����Ԫ�ȣ���׷�֧������ʹ��Ȩ���ý��������á���ע�����ݾ����������<BR>
        ������Ԫ����Ԫ�ȣ�������ҵıȼۣ��Ժ�ͬǩ�������й������������ֹ���������ۺ������۵��м�ֵ���㡣<BR>
        ������ʮ����������ͬ���й涨�⣬�ҷ�Ӧ�ڱ���ͬ�涨�ĸ����ջ򸶿���֮ǰ������ͬҪ��֧���ķ��û���׷��������ʺ��ڡ��������ƣ��ߣߣ����Уߣߣ߷��У��ʻ��ţߣߣߡ�<BR>
        �����׷������ʻ����б����Ӧ�ڱ����ߣ����ڣ���������ʽ֪ͨ�ҷ������ڼ׷�δ��ʱ֪ͨ��������������ڸ�����������κ��ӳ��շѣ��ҷ������е�ΥԼ���Ρ�<BR>
        ������ʮ����������ͬ�涨�ĳ������޽������׷���Ȩ�޳��ջس��õؿ��ʹ��Ȩ���õؿ��Ͻ����Ｐ��������������ȨҲ�ɼ׷��޳�ȡ�á�����ʹ����Ӧ����������ʹ��֤�������չ涨������ʹ��Ȩ�����Ǽ�������<BR>
        �����ҷ��������ʹ�øõؿ飬��������ǰ�ߣߣ�������׷��ύ���������飬����ȷ�����µ�����ʹ��Ȩ�������޺ͳ��ý�������������׷�ǩ�����ں�ͬ�������°�������ʹ��Ȩ�Ǽ�������<BR> 
������ʮ�������κ�һ�������������ɿ����������޹����������������к�ͬ���񲻲������Ρ�<BR> 
�����������ȡһ�б�Ҫ�Ĳ��ȴ�ʩ�Լ�����ɵ���ʧ��<BR>
        �������в��ɿ�����һ����Ӧ�ڣߣߣ�Сʱ�ڽ��¼���������ż���籨���紫���棩��������ʽ֪ͨ��һ���������¼�������ߣ����ڣ�����һ���ύ��ͬ�������л򲿷ֲ��������Լ���Ҫ�����������ɵı��档<BR>
        ������ʮ����������ҷ����ܰ�ʱ֧���κ�Ӧ�����������֮����ÿ�հ�Ӧ���ɷ��õģߣߣߣ��������ɽ�<BR> 
������ʮ�������ҷ�δ����ͬ�涨���������겻Ͷ�ʽ���ģ��׷���Ȩ�޳��ջ�����ʹ��Ȩ��<BR>
        ������ʮ����������ڼ׷��Ĺ�ʧ��ʹ�ҷ�����ռ������ʹ��Ȩ,�򱾺�ͬ���µ�����ʹ��Ȩ��������Ӧ��Ӧ����.ͬʱ�׷�Ӧ�е��ҷ��ɴ˶���ɵ�һ�о�����ʧ.<BR> 
�����ڶ�ʮ��������ͬ������Ч�������͡����м�����Ľ�������л����񹲺͹����ɵı����͹�Ͻ��<BR>
        �����ڶ�ʮһ������ִ�б���ͬ�������飬������˫��Э�̽����Э�̲��ɵģ��������ٲû����ٲû����й�ϽȨ������Ժ���ߡ�<BR>
        �����ڶ�ʮ����������ͬҪ���������֪ͨ��ͨѶ�������Ժ��ַ�ʽ���ݣ�����ʵ���յ�����Ч��˫���ĵ�ַӦΪ��<BR> 
�����׷��������������������������������ҷ���<BR> 
�����������ƣߣߣ����������������������������ƣߣߣ�<BR> 
����������ַ�ߣߣߣ�������������������������ַ�ߣߣߣ�<BR> 
������������ߣߣߣ���������������������������ߣߣߣ�<BR> 
�����绰����ߣߣ����������������������绰����ߣߣ�<BR> 
�����硡�����ߣߣ����������������������硡�����ߣߣ�<BR> 
������������ߣߣ�����������������������������ߣߣ�<BR> 
�����籨�Һţߣߣ����������������������籨�Һţߣߣ�<BR> 
����<BR> 
�����κ�һ���ɱ������֪ͨ��ͨѶ��ַ���ڱ����ߣ�����Ӧ���µĵ�ַ��֪��һ����<BR> 
�����ڶ�ʮ����������ͬ��˫������������ǩ�ֺ���Ч��<BR>
        �����ڶ�ʮ����������ͬ���ãߣߣ�����������д���������־���ͬ�ȷ���Ч���������������в�����������Ϊ׼����ͬ����������һʽ�ߣߣ߷ݣ�˫����ִ�ߣߣ߷ݡ�<BR> 
�����ڶ�ʮ����������ͬ�ڣߣ���ߣ��£ߣ������й��ߣ�ʡ����������ֱϽ�У��ߣ��У��أ�ǩ����<BR> 
�����ڶ�ʮ����������ͬδ�����ˣ�����˫��Լ������Ϊ��ͬ������<BR> 
����<BR> 
�����׷����л����񹲺͹��ߣ�ʡ�ߣ��ң��ߣߣߣ��£�<BR> 
��������������ֱϽ�У��ߣ�<BR> 
�����У��أ����ع����֣��£�<BR> 
�������������ˣ��ߣߣ�ǩ�֣����������ˣߣߣ�ǩ�֣�<BR> 
����<BR> 
�����ߣ���ߣ��£ߣ��ա�����������������<BR> 
����<BR> 
��������ʹ������<BR> 
�������ڵ���Ŀ��<BR> 
����<BR> 
����һ����׮����<BR>
        ��������������������ʹ��Ȩ���ú�ͬ�������¼�Ʊ���ͬ����ʽǩ����ߣ����ڣ��ߣ��У��أ����ع����֣���ͬ�õ�����ͼ������������ʾ����ĸ��յ��׮������˶������˫�����õغ���ͼ��ǩ���϶�����׮���õ������Ʊ���������˽�ԸĶ�����׮�����ƻ����ƶ�ʱ��Ӧ��ʱ����ߣߣߣ��У��أ����ع����֣������������衣<BR> 
����������������Ҫ��<BR> 
�������������õ������õغ���ͼ�˽�������Ӧ��������Ҫ��<BR> 
�������������彨��������ʹ涨Ϊ�ߣߣߣ�<BR> 
��������������������ߣߣߣߣߣ�<BR> 
���������������ݻ��ʣ���������ܶȣ��ߣߣߣ�<BR> 
�������������������ʣ������ܶȣ��ߣߣߣߣߣ�<BR> 
�����������ܽ�������������ߣߣߣߣߣߣߣߣ�ƽ���ף�<BR> 
����������������������ߣ�ƽ���ߣߣߣߣ߲㣻<BR> 
�����������̻����ʣߣߣߣߣߣߣߣߣߣߣߣߣ�<BR> 
������������������ߣߣߣߣߣߣߣߣߣߣߣߣ�<BR> 
�������������н��������ƾ�Ӧ���Ϲ������н�����Ʊ�׼����̵Ĺ涨��<BR> 
������ע�����ݾ����������<BR> 
�����������湤��<BR> 
�������������õ��߱�ʾͬ�����õغ��߷�Χ��һ���������й��湤�̣���ͬ������ṩʹ�á�<BR> 
�����������ߣߣߣ߶�λ����<BR> 
����������С������ͣ����<BR> 
�������������г���<BR> 
��������������ң�����վ��<BR> 
������ע�����ݾ����������<BR>
        �����������õ��߱�ʾͬ�����������й��̿�������߷�Χ�ڵĹ滮λ�ý����ͨ�������������κβ�����<BR> 
�����������ߣߣߣߣߣߣߣߣߣߣߣߣ�<BR> 
�����������ߣߣߣߣߣߣߣߣߣߣߣߣ�<BR> 
�����ġ���ơ�ʩ��������<BR>
        ���������������߷�Χ�ڵĽ�����ơ�������;�ȱ��������������Ҫ���漰��ͨ�����ߡ��������������˷������������⣬���뱨���й����ܲ����������裬���ɵط������ɳ������������ɴ���������һ�з��þ����õ��߸�����<BR>
        �������������õ�����ǩ������֮ͬ����ߣߣߣ�����Ӧ����׼�Ĺ滮���ͼֽ��ʩ�����ͼֽ����ʩ�������ڣߣ���ߣ��£ߣ���ǰ����ɵĽ�������������ڣߣߣߣ�ƽ���ס�<BR>
        ������������ģ��ġ�����ĸ��ӹ��̣��䶯��ʩ��ʱ�䰴����Ҫ����������ѵģ��õ�������Ӧ���뽨�����޽���֮��ǰ�ߣ����ڣ����У��أ����ع���������㹻�����ӽ����룬�������ڲ��ó����ߣ��ա�<BR> 
�������������õ���Ӧ�ڣߣߣߣ���ߣ��£ߣ�����ǰ�������ܲ��ɿ���Ӱ���߳��⣩�����ڿ���<BR>
        �����ߣߣߣ��յģ��У��أ����ع���������Ȩ�ջ�����ʹ��Ȩ��ע���䡶��������ʹ��֤��������Ͻ������޳���������С�<BR> 
�����塢����ά�޻<BR> 
�������������õ����õغ��߷�Χ�ڽ��н��輰ά�޻ʱ������Χ��������ʩӦ�е������ΰ�����<BR>
        ��������������������Ʒ����������������ʯ�����������ȣ�������ռ���ƻ�����ͼ��������ؼ���ʩ��<BR> 
����������ʱռ��������·��Ӧ�����й���������׼��<BR>
        ����������ʱʹ�ú����������أ�Ӧ����õ���Э�̣���������δ��׼���أ�Ӧ���У��أ����ع���������׼�������涨�������ط��á�<BR> 
����������δ���йز�����׼�������ڹ����õ����㵹�������κβ��ϻ�����κι��̻��<BR>
        �����������õ��߱���ȷ������ʹ�÷�Χ�ڵ���ˮ������������컷������й���Ӧ�пɿ����ų���������������Χ�Ļ�����<BR>
        ����������������ʹ�������ڣ��õ��߶Ըõض��ڵ����г���������ʩ����Ӧ���Ʊ����������𻵣�����Ӧ�е��޸����̵�һ�з��á�<BR> 
�������������õ��߲��ÿ��١��������ھ����ڵضε����ء�<BR>
        ���������������˽�������ά�޹���֮ǰ���õ��߱�������ضλ����ڵضι��е�������ˮ��������ˮ�����ܣ������¡������Լ�������ʩ��λ�ã������йز��ųʱ�����������ʩ�ļƻ����õ�����δ����׼֮ǰ�����ö�����������Ҫ�ĵ������������װ��ķ��ã������õ��߸���<BR> 
����������ˮ������<BR> 
�������������õ����������ˮ��Ӧ��������ˮ��˾ǩ����ˮ��ͬ��<BR> 
�������������õ���������õ磬Ӧ���й����ǩ�������ͬ��<BR> 
�������������õ��߽�ˮ���ӵ缰����·�ڣ�������þ����и���<BR> 
�����ߡ��ල���<BR>
        ������������������ʹ���ڼ䣬�У��أ����ع���������Ȩ���õ��ߺ��߷�Χ�ڵ�����ʹ��������м��ල���õ��߲��þܾ������ӡ�<BR>
        �������������ڵ��߲������κ�����ռ�ú��߷�Χ��������أ������ѷ���Ʒ�����ĵȣ������򣬰�Υ��ռ�ش�����<BR> 
�������������õ������õط�Χ�ڣ�Ӧ���涨��������;�͹������ͼֽ��Ҫ����н��衣<BR>
        �������������õ��߶��õط�Χ�ڵĽ����δ���滮������׼��������������Ľ����ؽ��������йز�����Ȩ������ָ�ԭ״�������ܲ�ִ�еģ���ǿ��ִ�У�����������õ���֧����<BR> 
����<BR> 
����<BR> 
����<BR> 
����<BR> 
����<BR> 
�������� </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>