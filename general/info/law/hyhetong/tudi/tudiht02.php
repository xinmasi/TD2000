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
      <div align="center">��������ʹ��Ȩ���ú�ͬ</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> ����������ʹ��Ȩ������ú�ͬ��</p>
      <p> ����<BR> 
������һ��������ͬ˫�������ˣ�<BR>
        �������÷����л����񹲺͹��ߣߣߣߣ�ʡ����������ֱϽ�У��ߣߣߣߣߣ��У��أ����ع����֣����¼�Ƽ׷�����������ַ�ߣߣߣߣߣߣߣ���������ߣߣߣߣߣߣ����������ˣ������ߣߣߣߣߣ�ְ��ߣߣߣߣߣߡ�<BR>
        �������÷����ߣߣߣߣߣߣߣߣߣ����¼���ҷ�����������ַ�ߣߣߣߣߣߣ���������ߣߣߣߣߣߣ����������ˣ������ߣߣߣߣߣ�ְ��ߣߣߣߣߣߣߣߡ�<BR>
        �������ݡ��л����񹲺͹������������ʹ��Ȩ���ú�ת����������������ʮ�����涨��ԭ�Ի�����ʽȡ�õ�����ʹ��Ȩ����ת�ã����⣻��Ѻ������ǩ������ʹ��Ȩ���ú�ͬ���ҷ�����ۣ����⣻��Ѻ���ߣߣߣߣߣ߷�������ߣߣߣߣ߲��ַ���������ʹ�÷�Χ�ڣ�����Ӧ������������ʹ��Ȩ��֮ת�ã����⣻��Ѻ����˫��������Ը��ƽ�ȡ��г���ԭ�򣬾���Э�̣���������ͬ��<BR>
        �����ڶ������ߣߣߣߣߣߣ߷���ʹ������λ�ڣߣߣߣߣߣߣߣ����õ����Ϊ�ߣߣߣߣߣߣߡ���λ����������Χ�籾��ͬ��ͼ��ʾ����ͼ�Ѿ��ס���˫��ȷ�ϡ�<BR> 
�����ҷ����и÷���ʹ�÷�Χ�����صĹ�������ʹ��֤��������׼�ļ��������кϷ�������ʹ��Ȩ��<BR>
        �������������ҷ����ۣ����⣻��Ѻ���ߣߣߣߣߣ������������׷��������ص����Ϊ�ߣߣߣߣߣߣߣ߷������õ���������ߣߣߣߣ�ƽ���ס����򣺵������������ҷ����ۣ����⣻��Ѻ���ߣߣߣߣ߷���¥��ߣߣߣ߲㣬��������ߣߣߣ�ƽ���ף�Ϊ�ߣߣߣߣ߷����ܽ�������ģߣߣߣߣߣߣ����׷��������ص����Ϊ�ߣߣߣ߷������õ�����ģߣߣߣߣ������ߣߣߣߣ�ƽ���ס���<BR>
        ��������ͬ���³��õ�����ʹ��Ȩ��ߣߣߣߣ߷���ʹ�÷�Χ��δ���õ�����ʹ��ȨΪһ���壬ϵ���еģ����ɷָ�ģ����ڸ��������໥��������������ҪΪ�Է��ṩ������������<BR> 
����������������ʹ��Ȩ���õ�����Ϊ�ߣߣ��꣬�ԣߣߣߣߣߣߣߣߣ����㡣<BR>
        �������������ߣߣߣߣ߷���ʹ�÷�Χ�ڵ����أ���ԭ��׼�ļ�Ϊ�ߣߣߣߣߣߣ��õأ��ҷ��밴����ȷ������;�����й滮�ͽ���Ҫ��ʹ�����ء�<BR>
        �����ҷ���ı�������;���������ü׷�ͬ�⣬���µ�������ʹ��Ȩ���ý�ǩ������ʹ��Ȩ���ú�ͬ������������Ǽ�������<BR>
        �������������ҷ�������ͬ�涨��׷�֧������ʹ��Ȩ���ý�����ʹ�÷��Լ��������ת��ʱ��������ֵ�ѣ�˰����<BR>
        ����������������ͬ���µ�����ʹ��Ȩ���ý�Ϊÿƽ���ףߣߣߣ�Ԫ����ң��ܶ�Ϊ�ߣߣߣߣߣ�Ԫ����ҡ�<BR> 
��������ͬ��˫��ǩ�ֺ�ߣߣߣ����ڣ��ҷ������ֽ�֧Ʊ���ֽ���׷�֧��ռ���ý��ܶ�<BR> 
�����ߣߣߣߣߣ����ƣߣߣߣ�Ԫ�������Ϊ���к�ͬ�Ķ���<BR>
        ������ͬǩ�ֺ�ߣ����ڣ����ҷ����۷�����ߣߣ����ڣ��ҷ�Ӧ����������������ʹ��Ȩ���ý�������δȫ��֧���ģ��׷���Ȩ�����ͬ����������ΥԼ�⳥��<BR>
        ���������ҷ�����ߣߣߣ߷�����ÿ��Ӧ�����ģߣߣߣߣߣ��ֽ����ý����ֽ���ȫ�����ý�Ϊֹ����˫����Ѻ�ߣߣߣ߷�����ߣߣߣ����ڣ��Ե�Ѻ��������ֽ���������������ʹ��Ȩ���ý�������δȫ��֧���ģ��׷���Ȩ�����ͬ����������ΥԼ�⳥����<BR>
        �����ڰ������ҷ���֧��ȫ������ʹ��Ȩ���ý��ߣߣߣ����ڣ���������ʹ��Ȩ�ǼǱ���Ǽǣ���ȡ����ʹ��֤��ȡ������ʹ��Ȩ��<BR>
        �����ھ������ҷ�ͬ��ӣߣߣߣ��꿪ʼ���涨�����������ʹ�÷ѣ����ɵ�ʱ��Ϊ����ߣߣߣ��£ߣߣ��ա�����ʹ�÷�ÿ��ÿƽ����Ϊ�ߣߣߣ�Ԫ����ҡ�<BR>
        ������ʮ��������ͬ���й涨�⣬�ҷ�Ӧ�ڱ���ͬ�涨�ĸ����ջ򸶿���֮ǰ������ͬҪ��֧����һ�з��û���׷����������ʺţ��������ƣ��ߣߣߣߣ����Уߣߣߣ߷��У��ʻ��ţߣߣߣߡ�<BR>
        �����׷������ʺ����б����Ӧ�ڱ����ߣߣߣߣ�������������ʽ֪ͨ�ҷ������ڼ׷�δ��ʱ֪ͨ��������������ڸ�����������κ��ӳ��շѣ��ҷ������е�ΥԼ���Ρ�<BR> 
������ʮһ�����ҷ�������ͬȡ�õ�����ʹ��Ȩ�����������йع涨����ת�á����⡢��Ѻ��<BR>
        �������������˳��ۣ����⣻��Ѻ���ߣߣߣߣ߲��ַ���ʱ�����ͬʱת�ã����⣻��Ѻ����������ʹ������������ۣ����⣻��Ѻ������ռ�ߣߣߣ߷����ܽ������������ͬ������ʹ��Ȩ��<BR>
        ������ʮ����������ʹ��Ȩת�ã����⣻��Ѻ��ʱ��Ӧ��ǩ��ת�ú�ͬ�����޺�ͬ����Ѻ��ͬ�����ڷ���������ͬ����������ʹ��Ȩת�ã����⣻��Ѻ���½ڣ�������Υ�����ҷ��ɷ���ı���ͬ�涨�������չ涨����ת�ã����⣻��Ѻ���Ǽǡ�<BR>
        ������ʮ���������س������޽���������ʹ���߿����������ڣ�������׷�ǩ�����ú�ͬ��֧������ʹ��Ȩ���ý𣬲������Ǽǡ�����ʹ���߲����������ڵģ�����ͬ���µ�����ʹ��Ȩ�����Ͻ��������������������Ȩ�ɹ����޳��ջأ�����ʹ����Ӧ��������ʹ��֤�����չ涨����ע���Ǽǡ�<BR>
        ������ʮ���������һ��δ�����к�ͬ���µ�����Ӧ����ΪΥ������ͬ��ΥԼ������һ���յ�����˵��ΥԼ�����֪ͨ��Ӧ�ڣߣߣߣ����ھ�����ΥԼ����ߣߣߣ�����û�о�����ΥԼ��Ӧ����һ���⳥ΥԼ�����һ��ֱ�ӺͿ�Ԥ������ʧ��<BR>
        ������ʮ����������ҷ����ܰ�ʱ֧���κ�Ӧ�����������֮����ÿ�հ�Ӧ���ɷ��õģߣߣߣߣ��������ɽ�<BR> 
������ʮ����������ͬ������Ч�������͡����м�����Ľ�������л����񹲺͹����ɵı����͹�Ͻ��<BR>
        ������ʮ��������ִ�б���ͬ�������飬���������Э�̽����Э�̲��ɣ����������ٲû����ٲû����й�ϽȨ������Ժ���ߡ�<BR>
        ������ʮ����������ͬ�ڣߣߣߣ���ߣߣ��£ߣߣ������й��ߣߣߣ�ʡ����������ֱϽ�У����أ�ǩ����<BR> 
������ʮ����������ͬδ�����ˣ�����˫��Լ������Ϊ��ͬ������<BR> 
�����׷����л����񹲺͹��ߣߣߣ�ʡ�������ҷ����ߣߣߣߣߣ��£�<BR> 
��������������ֱϽ�У��ߣߣߣ�<BR> 
�����У��أ����ع����֣��£�<BR> 
�������������ˣߣߣߣߣߣ�ǩ�֣��������������ˣߣߣߣߣߣ�ǩ�֣�<BR>
        ������ע�����Գ��ۡ����⡢��Ѻ�����������Ļ�������ʹ��Ȩת�á����⡢��Ѻ�����չ涨Ҳ�벹ǩ����ʹ��Ȩ���ú�ͬ���ɲ��ձ���ͬ���ڵس��ú�ͬ��ʽ�ⶨ����<BR> 
����<BR> 
����<BR> 
�������� </p>
    </td>
  </tr>
  <tr>
    <td height="27" valign="top" class="TableData"> 
      
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>