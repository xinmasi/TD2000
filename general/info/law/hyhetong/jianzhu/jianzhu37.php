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
      <div align="center">�ۿڹ���ʩ����ͬ ��ţ�</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p> �����������ƣ�<BR> 
�����׷���<BR> 
�����ҷ���<BR> 
����<BR> 
�����ꡡ���¡�����<BR> 
����<BR> 
����һ����ͬЭ����<BR> 
����<BR> 
�����׷��ߣߣߣߣߣߣ�Ϊ�˽��ߣߣߣߣߣߣߣ߹��̣��������ҷ��ߣߣߣߣߣߣ߶Ա����̵�Ͷ�꣬Ϊ��ȷ˫�����Ρ�Ȩ�������񣬾�˫��Э��ǩ������Э�飺<BR> 
����������Э�����е����ʺ�������������ᵽ�ĺ�ͬ�����й涨�ĺ�����ͬ��<BR> 
�������������ļ�����������ͬ���ɷָ�����壬���ļ��໥���䣬���в���ȷ��һ��֮���������д���������Ϊ׼��<BR> 
����������˫���̶��Ĳ���Э����ͬ���ھ�˫��ǩ��ı���¼��<BR> 
������������ͬЭ���飻<BR> 
����������˫��ǩ��ĺ�̸ͬ�б���¼��<BR> 
������������ͬר�����<BR> 
������������ͬͨ�����<BR> 
�����������б�֪ͨ�顢Ͷ������б��飻<BR> 
�����������뱾��ͬ�йص������ļ���<BR> 
������������ͬ�ܼۿ�Ϊ����ңߣߣߣ���Ԫ��<BR> 
������������ͬ���̵�ʩ����Ϊ�ߣ߸��£��죩�����ԣߣߣߣ���ߣ��£ߣ������ߣߣߣ���ߣ��£ߣ��ա�<BR> 
���������ס���˫���ڴ���Լ����֤���ձ���ͬ��Լ�����е������и��Ե�ȫ�����κ�����<BR> 
����������Э������˫�����������˻�����Ȩ�Ĵ�����ǩ�𲢼Ӹǹ��º���Ч�����̾��������պϸ񡢱�����������ͬ�ܼۿ���޽������ͬ��ֹ��<BR> 
����������Э�����������ݣ�˫����ִһ�ݣ�����ͬ�ȷ���Ч���������ߣ߷ݣ�˫����ִ�ߣ߷ݣ���������������һ��ʱ��������Ϊ׼��<BR> 
�����׷����������������������������������������ҷ���<BR> 
�������������ˣ����������������������������������������ˣ�<BR> 
�����򡡡���������������������������������������<BR> 
��������Ȩ�Ĵ����ˣ�������������������������������Ȩ�Ĵ����ˣ�<BR> 
����ǩ�����ڣ���������������������������������ǩ�����ڣ�<BR> 
������λ��ַ������������������������������������λ��ַ��<BR> 
������ϵ�绰������������������������������������ϵ�绰��<BR> 
�������棺���������������������������������������棺<BR> 
�����������룺���������������������������������������룺<BR> 
�����������У����������������������������������������У�<BR> 
�����ʺţ��������������������������������������ʺţ�<BR> 
����ǩԼ���ڣ�<BR> 
����ǩԼ�ص㣺<BR> 
����<BR> 
����������ͬͨ������<BR> 
����<BR> 
������һ�������ﶨ��<BR> 
�����ڱ���ͬ�У�����ͬר����������Լ���⣬�������ʺ�����Ӧ���б�����ָ���ĺ��壺<BR> 
���������׷���ָ�ɺ�ͬԼ���ľ��з��������ʸ��֧�����̼ۿ������ĵ����ˡ��ֳƷ�������<BR> 
���������׷�������ָ�ɼ׷���Ȩ�������׷����к�ͬ�����������Ĵ����ˡ�<BR> 
���������ҷ���ָ�ɺ�ͬԼ���ľ��га������ʸ񲢱��׷����ܳн�����ͬ���̵ĵ����ˡ��ֳƳа�����<BR> 
���������ҷ�������ָ���ҷ���Ȩ�������б���ͬ�Ĵ����ˡ��ֳ���Ŀ������<BR> 
�����������̼�����ָ�ܼ׷�ί�У�������Ӧ���̼������ʵȼ���ˮ�˹��̼�����λ���ڼ׷���Ȩ��Χ�ڶԱ����̵�ʩ�����еļල�������<BR> 
����������������ʦ��ָ�ɹ��̼�����λί�ɵĸ��𱾺�ͬʩ�������Ĵ����ˡ��ֳ�ʩ���ܼࡣ<BR> 
�������������ලվ��ָ������ͨ���ܲ�����Ȩ��ˮ�˹��������ල������<BR> 
����������Ƶ�λ��ָ�ܼ׷�ί�У�������Ӧ���ʵȼ����е�����ͬ������Ƶĵ�λ��<BR> 
�����������̣�ָ��ͬר������Լ���ĺ�ͬ��Χ�ڵ����ù��̺�Ϊ���ù��̷������ʱ�����Լ��������ڶԹ���ȱ�ݽ��д������޸����̡�<BR> 
����������������׼��ָ�����淶����׼�͹�̡�<BR> 
��������������ļ���ָ�ɼ׷��ṩ���ҷ��ύ�׷���׼�ģ�����ʩ��������ͼֽ�ͼ������ϡ�<BR> 
������������ͬ�ڣ�ָ�Ա���ͬ��Ч֮����������ͬ���̱�������������˫�������ͬ�ۿ�ʱֹ�������ڼ䣬����ʩ��׼���ڡ�ʩ���ں͹��̱����ڡ�<BR> 
����������ʩ���ڣ�ָ�Կ�������ָ���Ŀ���֮���������̿���֮��ֹ�����ޡ���׶����յĹ��̣��׶ι���Ϊ�׶����չ��̵�ʩ���ڡ�<BR> 
���������������ڣ�ָ�Ա����̿���֮������ı���ͬԼ���ı������ޡ�<BR> 
������������ͬ�ۿָ����ͬԼ���ķ�ʽ����������ģ�����֧���ҷ����պ�ͬҪ����ɹ������ݵļۿ��ܶ<BR> 
�������������޽�ָΪ����ͬ����������ɺ�ͬ��Χ���κ�ʩ��ȱ�ݵ��޸�������ͬ��Լ�������׷����ҷ����̿��п����Ŀ�<BR> 
�������������ɿ�����ָ��ս�������ҡ����з�������׹���Ǽ��ҷ�������ɵı�ը�ͻ��֣��Լ���ͬ��Լ���ȼ����ϵķ硢�ˡ�ѩ���ꡢ���𡢺�ˮ�����ͺ�Х�ȶԹ�������𺦵���Ȼ�ֺ���<BR> 
�����������������գ��ҷ�����ͬԼ���깤��׷��ƽ����̵����ա���׶ν����Ĺ��̣��׶�����Ϊ�ҷ�����ͬԼ���ֽ׶��깤��׷��ƽ����̵����ա�<BR> 
�����������죺ָ�����졣�ꡢ�¡��վ��Թ������㡣<BR> 
����������������ʽ��ָ�Ը���֪ͨ���ź�����Ҫ��ί�еȣ�������д�����ֻ�ӡˢ�ı�����ʽ�������籨���紫�ʹ��档<BR> 
�����ڶ�������ͬ�ļ�<BR> 
����������ͬ�ļ�����ͬ�ļ�������͡���Ϊ˵��������ͬר����������Լ���⣬����ɺͽ��ʹ������£�<BR> 
����������˫���̶��Ĳ���Э����ͬ���ھ�˫��ǩ��ı���¼��<BR> 
������������ͬЭ���飻<BR> 
����������˫��ǩ��ĺ�̸ͬ�б���¼��<BR> 
������������ͬר�����<BR> 
������������ͬͨ�����<BR> 
�����������б�֪ͨ�顢Ͷ������б��飻<BR> 
�����������뱾��ͬ�йص������ļ���<BR> 
��������ͬ�ļ����ֺ��������һ��ʱ���ڲ�Ӱ�칤�̽��ȵ�����£���˫��Э�̽������˫���������ͳһ���򰴱���ͬͨ������ڣ�������Լ���İ취������<BR> 
������������ļ����׷�Ӧ�ں�ͬǩ��󣱣����ڰ��պ�ͬר��������Լ���ķ������ҷ��ṩ���ͼֽ�����˵��������������ϡ�δ���׷�ͬ�⣬�ҷ����ý�����ļ��ṩ����������<BR> 
��������ͬ��Լ�����ֹ������ҷ�������ƣ��ҷ�Ӧ���й�����ļ���ͼֽ����ͬԼ����ʱ��ͷ����ύ�׷���׼�󷽿�ʵʩ��<BR> 
����������������ļ���Ϊ���㹤�̵���ȷʵʩ����ɶ���ȱ�ݵ��޸����׷����ں�ͬ��Χ�������������ļ���֪ͨ���ҷ�Ӧ���ܲ�ִ�С�<BR> 
��������������ͬ��Χ<BR> 
����������ͬ��Χ������ͬ��Ҫʵʩ�ľ��幤�����ݡ���Χ�����̽��޵ȣ��ں�ͬר��������Լ����<BR> 
������������������׼<BR> 
��������������׼��ʹ�ã�����ͬ���̵�ʩ����ִ�н�ͨ���䷢�ļ�����׼�Լ��׷����б���������ļ�����׼��Ҫ��<BR> 
��������������׼�����������ͨ���䷢�ļ�����׼����δ���ʺϱ������ص������ʱ����ʹ��ר��������Լ��������������׼�����׷�Ҫ��ʹ�ù��⼼����׼��Ӧ�ɼ׷������ṩ�����뱾�������̽ṹ��ʩ���������⣬û����Ӧ������׼ʱ��Ӧ�ɼ׷����������׼�������ҷ��������Ҫ���ʩ�����������Ӧ������׼�����׷����ͬ���ִ�С�<BR> 
���������������ԡ��������ϵ��ʽ<BR> 
�����������ԣ���ͬ�ļ�����д������Լ�˫���������ļ���ʹ�ú������ʹ����������������Ϊ��������ʱ��Ӧ�ں�ͬר��������Լ�������������ı���������ʱ���Ժ����ı�Ϊ׼��<BR> 
�����������棺����ͬ���õķ����ǹ��ҵķ��ɡ������ͷ��棬�Լ���ͨ���䲼���йع��ºͺ�ͬר��������Լ���ķ��档<BR> 
����������ϵ��ʽ������ͬʵʩ�����У������йظ�����һ����ϵ����������ʽΪ׼���ڽ���������£������Կ�ͷ��������Ӧ���º󣴣�Сʱ����������ʽ����ȷ�ϡ�<BR> 
�������������׷�������<BR> 
�����׷�Ӧ������ͬ�й�Լ���е��������Ρ�<BR> 
���������ṩ��������������������ء�ˮ�����ã����硢��ľ�⳥�����ݲ�Ǩ��������桢���¡��ܿա�ˮ�Ϻ�ˮ���ϰ���ȹ���������ͬר��������Լ����ʱ�䡢λ�á�����͸߳����ҷ��ṩʩ�����ء�<BR> 
���������ṩˮ���뽻ͨ���������𰴺�ͬר��������Լ����ʱ�䡢�ص㿪ͨ����ͨ����ʩ�����ؼ�ĵ�·���ṩ��ˮ�����硢ͨѶ����·�ӵ㼰ʩ��������ʱͣ��ˮ�򣬲���֤ʩ���ڼ�ĳ�ͨ��<BR> 
���������ṩʩ���������������غ��ߡ�����ͨ�桢����������֤����ͷ����������ʩ������ĸ������������ҷ��ṩ���������ط�Χ����ͼ��������ʱ�õص��걨������Э��������ҷ�ʩ���и��ŵ��ⲿ������<BR> 
���������ṩ�������ϣ�����ͬר������Լ����ʱ����������ҷ��ṩ���̵��ʱ����Լ�ˮ׼�㡢������Ƶ�ȼ������ϣ�����֯������ƽ��׼��ֳ����顣�׷������ṩ���������ϵ���ʵ�Ը����ҷ�����������ϵ������Ӧ�ø���<BR> 
�������������׷��������׷�Ӧ�ں�ͬЭ����ǩ�������������������ʹ��ͬԼ���ļ׷�Ȩ�����������к�ͬԼ���ļ׷����񣬲�������������֪ͨ�ҷ����׷����������ʱ��Ӧ��ǰ����֪ͨ�ҷ���<BR> 
�����������������ͬԼ�������޺Ͱ취���ҷ�֧�����̼ۿ<BR> 
����������������ָ�������ͬ��Լ������ʱ���ҷ���������ָ�ǩ��ͼֽ��ȷ�Ϲ��̽��ȱ�����������ι��̲������������ջ�ǩ��������<BR> 
���������������գ�������ͬԼ����ʱ��֯�������գ������������㡣<BR> 
�������������ҷ�������<BR> 
�����ҷ�Ӧ������ͬ�й�Լ���е��������Ρ�<BR> 
��������ʩ��׼��������ʩ���ֳ��Ĳ��ú���ʱ��ʩ��ʩ�����ڱ���ͬԼ�������������������ʦ�ύ��ϸ��ʩ����֯��ơ�ʩ�����ȼƻ����������뱨�档����ͬԼ����ʱ�䡢����������ʱ��λʩ���������豸��<BR> 
���������ύ���漰���������ݹ���ʩ���������������ʦ��ָ�ʱ���������ʦ�ύ���ι�������֪ͨ�����������Լ챨�桢�����������뱨�漰�����¹ʱ���ȣ�����ʱ�ύ�¶ȡ�����ʩ����ҵ�ƻ����ÿ�ƻ�������������ͳ�Ʊ���<BR> 
��������ȷ�����̽�����������������ļ���������׼����׼��ʩ����֯��Ƶ�Ҫ�����ʩ���Ͳ��ϼ��飬������ȫʩ��������֤��ϵ��<BR> 
���������ṩ���������б���򱾺�ͬר�������Լ����Ϊ��������ʦ���ֳ������ṩ�칫��ס�ޡ���ͨ��ͨѶ��������<BR> 
�����������𹤳̵ı����뱣�ޣ������깤�Ľ�������Ѱ�װ���豸���ڽ����׷�Ӧǰ���𱣻��������ڼ䷢���𻵣����ҷ��޳������޸�������׷���ǰʹ������𻵣��޸������ɼ׷��е������̿������պϸ���ں�ͬԼ���ı����ڼ䣬�������ҷ�������ɵ��κ�ȱ�ݣ��ҷ�Ӧ�޳��޸���<BR> 
�������������ҷ��������ں�ͬЭ����ǩ��ʱ��������������Ŀ���������ҷ�����Ӧ���й��һ�ͨ���䷢����Ŀ��������֤�鲢��פ�����ֳ����������б���ͬԼ�����ҷ�����͹�������ͬ���̵�ʩ�����ҷ����������ʱ��Ӧ�������ü׷�ͬ�Ⲣ��ǰ����֪ͨ��������ʦ��<BR> 
��������������������͹��£�ʩ��������Ӧ���������ĸ����͹��£��ر��ǽ�ͨ����������ȫ�����������������ȷ���ķ���͹��¡�<BR> 
�����������ܹ��̼��������ܼ�������ʦ�ڼ׷���Ȩ��Χ�����ݱ���ͬ��ʩ���ļල�͹�����ִ�м�������ʦ�����Ĺ���ָ��μӼ������̻�׷����ֵĹ��̻��顣<BR> 
����������ʱͣ����ʩ�����н��ʩ����������ʱͣ����ʩ�������йز�����׼��λ�úͷ�ʽͣ�š�<BR> 
������������û��Ʒ��������ȡһ�д�ʩ����ֹʩ���������豸�����ϵĳ�û����������ûʱ���Է�����ͨ����������������ҵ�ľ�Ӧ�����򺣼ಿ�ű��棬�����ø�����ϰ�ָʾ�ƣ�ֱ�����̹������Ϊֹ���������ķ��������η��е���<BR> 
�����ڰ��������̼���<BR> 
�����������̼�����Ϊȷ�����������밲ȫ������ʩ�����Ⱥ͹���Ͷ�ʣ��׷���ί�м�����λ�Թ���ʵʩ�������ҷ�Ӧ���ܼ�����<BR> 
����������������ʦ���׷�Ӧ�ڼ�������ʦ��פ�ֳ�ǰ������������ʦ��ְ���Ȩ����������ʽ֪ͨ�ҷ�����������ʦ�ڼ׷���Ȩ��Χ�ڣ����ݱ�ʩ����ͬ����������������ʹ����ְȨ��<BR> 
������������ʦ�ڼ׷���Ȩ��Χ����������һ����Ϊ������Ϊ�׷���Ϊ��<BR> 
������������ʦ��������һ����������ԱЭ���乤��������Ȩ������Ա���м�������ʦ�Ĳ���ְ�𡣼�������ʦҲ����ʱ���ش�����������Ȩ����������������Ȩ�볷��Ӧ��ǰ���챨��׷���֪ͨ�ҷ���<BR> 
�����ھ�����ʩ����<BR> 
���������������ҷ�Ӧ�ں�ͬר��������Լ���������ڣ����������ʦ�ύ�������뱨�棬����������ʦ��飬�����׷���׼�󣬼�������ʦӦ�ں�ͬר��������Լ���������ڷ���������ҷ�Ӧ�ڿ�������ָ���������ڿ�����<BR> 
�����������ڿ������ҷ���ʲ��ܰ��ڿ���ʱ��Ӧ�ڽӵ���������������������ʦ������ڿ������뱨�棬��������ʦӦ�ڽӵ��ҷ���������������𸴡�����������ʦ�ڣ�����δ��𸴣�����Ϊ��ͬ���ҷ�Ҫ�󣬿���������Ӧ˳�ӡ�����������ʦ��ͬ������Ҫ�󣬻��ҷ�δ�ڹ涨��ʱ����������ڿ���Ҫ���򿢹����ڲ���˳�ӡ�<BR> 
�����׷������ҷ�ͬ����������ʽ֪ͨ�ҷ�����Ƴٿ������ڣ���˸��ҷ���ɵ�ֱ�Ӿ�����ʧ���ɼ׷��е�������������Ӧ˳�ӡ�<BR> 
��������ʩ�����ӳ��������������ʱ������������ʦȷ�ϲ����׷���׼��ʩ���ڿ����ӳ���<BR> 
������������Ʊ���򹤳���������ɹ�������<BR> 
�������������ɿ�������������仯��ɹ�������<BR> 
�����������׷�ԭ����ɹ�������<BR> 
������������ͬר��������Լ�������������<BR> 
�����������������֮һʱ���ҷ�Ӧ�ڣ����ھ���������ݡ���������˷����ķ���֧�����������ʦ������棬��������ʦ���յ���������ڱ��׷�ȷ�ϲ����Դ𸴣���ȷ��ʩ�����ӳ��������ͷ��á�����������ʦ����δ��𸴣������Ϊ�ҷ���Ҫ���ѱ��׷�ȷ�ϡ�<BR> 
����������ԭ�򣬹��̲��ܰ��ڿ������ҷ��е�ΥԼ���Ρ�<BR> 
����������ͣʩ������ȷ�б�Ҫʱ����������ʦ����֪ͨ�ҷ���ͣ�Ź������ڣ���Сʱ�����ҷ�������崦��������ҷ�Ӧ����������ʦ��Ҫ����ͣʩ�����ҷ�����ʵ�˼�������ʦ�Ĵ�������󣬲��ڽӵ����������������֪ͨ�󣬲��ܼ���ʩ����<BR> 
����ͣ���������ҷ�ʱ��ͣ����ʧ���ҷ��е���ʩ���ڲ����ӳ��������ڼ�������ʦ��ָ������ͣ�������ڼ׷�ʱ���ҷ�ͣ���ľ�����ʧ�ɼ׷��е����ɴ�Ӱ���ʩ������Ӧ�ӳ���<BR> 
����������ǰ�������׷���ϣ��������ǰ���������ں�ͬר��������Լ����������������ڱ���ִͬ���У��׷�Ҫ�󹤳���ǰ������Ӧ���ҷ�����Э�̣���ǩ����ǰ����Э�飬�ҷ�Ӧ����Э���޶�ʩ�����ȼƻ�������������ʦ������ʵʩ��<BR> 
���������׶ι��ڣ����н׶ι���Ҫ��ģ��ں�ͬר��������Լ����<BR> 
������ʮ����ʩ����֯���<BR> 
��������ʩ����֯��Ƶ��ύ���ҷ�Ӧ�ں�ͬר��������Լ���������ڣ���ʩ����֯����ύ����������ʦ��顣ʩ����֯��Ƶ���Ҫ����Ӧ������ʩ�����ȼƻ���ʩ���������̼�˵�������Ϲ�Ӧ����顢ʩ������ƽ�沼�á�ʩ��������֤��ʩ��ʩ����Ա��ɡ�ʩ�������䱸��ʩ����ȫ��ʩ��ʩ���Ի���Ӱ��ı�����ʩ�ȡ�<BR> 
����ʩ�����ȼƻ�Ӧ��������ͼ�ؼ���·����Ҫ�������ͼ���п��ƣ���Ӧ����ÿ��Ԥ����ɵĹ�������������ȡ�<BR> 
��������ʩ����֯��Ƶ���������������ʦӦ���յ��ҷ�ʩ����֯��ƺ��������������������׷��������׷�Ӧ�ڽӵ�����������������������<BR> 
��������ʱ�����׷�����޸�������ҷ�Ӧ��ʩ����֯��ƽ����޸ĺͲ��䣬���ڽӵ�����������������������ʦ�ύ�޸ĺͲ��䱨�棬��������ʦȷ�Ϻ��ҷ�Ӧ��ȷ�Ϻ��ʩ����֯�����֯ʩ��������������ʦ���׷�δ�������޶���ʱ��������޸����������������Ϊ�ҷ�ʩ����֯����ѱ��׷���׼��<BR> 
��������ʩ�����ȼƻ����޶��������������ʦ��Ϊ����ʵ��ʩ�����Ȳ����Ͼ��׷���׼��ʩ�����ȼƻ�ʱ���ҷ�Ӧ����������ʦ��Ҫ���ʩ�����ȼƻ������޶����������֤�����ں�ͬԼ����ʩ��������ɵľ����ʩ������������ʦ��˲����׷���׼��ִ�С�<BR> 
������ʮһ������������<BR> 
���������������豸��<BR> 
�����������������ò��ϡ��豸���ں�ͬר��������Լ���ɼ׷���Ӧ�⣬�����ҷ�������ƺͼ�����׼��Ҫ�����вɹ������䡢����ͱ��ܡ�<BR> 
�����������������ڹ��̵Ĳ��ϡ��豸Ӧ�в�Ʒ�ϸ�֤�飬���̲���Ӧ�߱��ɾ�����Ӧ���ʵȼ��Ĳ��ϼ��鵥λ���ߵĲ���֤������鱨�档<BR> 
�������������Ϻ��豸����ʱ���ҷ�Ӧ֪ͨ��������ʦ�μ����գ���������ʦ��ȨҪ�󸴼죬������ƺͼ�����׼Ҫ�󲻷��Ĳ��Ϻ��豸����������ʦ��Ȩ�ܾ����գ������ҷ�����ʩ���ֳ����ڲ���ʹ�ú��豸��װ�����У���������ʦ��Ȩ��ʱ���ͼ��飬�ҷ�Ӧ�ṩ��ҪЭ���������鷢�ֲ��ϻ��豸���ϸ�ʱ���������ϻ��豸�������ڱ����̣��ҷ�Ӧ���������޸������²ɹ������е��ɴ˷�����һ�з��ã�������������ϻ��豸����Ҫ�����ɼ׷��е��ɴ˷�����һ�з��ã�ʩ������Ӧ�ӳ���<BR> 
�����׷���Ӧ�Ĳ��ϡ��豸�Ĳ�Ʒ�ϸ�֤�鸱��Ӧ�����ҷ����׷�Ӧ�����ʸ���<BR> 
�������������Լ죺����ͬ���̸�����̡��ֲ�������ɺ��ҷ�Ӧ���������Լ졢�����Լ첻�ϸ�ʱӦ���з������򷵹��������ķ������ге��������ʩ���ڲ����ӳ����Լ�ϸ�����������ʦ�ύ�Լ챨�沢֪ͨ��������ʦ�������ա�<BR> 
�������������������飺<BR> 
������������Ҫ����̿���ǰ���ҷ�Ӧ�����ϡ��豸����Ա���������ʩ���������������ʦ���棬����������ʦͬ�����ܿ�����<BR> 
�����������ϵ�����ʩ����ɣ�����������ʦ���պϸ�ǩ�Ϻ��ҷ����ܽ����µ������ʩ����<BR> 
������������ʩ�������У��ҷ�Ӧ��ʱ���ܼ�������ʦ����ί����Ա�Բ��ϡ��������̵Ĳ����ļ�飬������������ʦ��ָ����з����������ҷ�ԭ����ɷ�����ʩ�������󣬷����������ҷ��е���ʩ���ڲ����ӳ��������������ʦ�Ĳ���ȷָ�����ҷ�������ʧ��ʩ�����������йط����ɼ׷��е���ʩ������Ӧ�ӳ���<BR> 
�����������ι������գ�<BR> 
�����������ҷ����Լ�ϸ�ǩ�����ι����Լ��¼����д���ι����������뵥���ڸ���ǰ����Сʱ��֪ͨ��������ʦ�������ա���������ʦ�ڽӵ��ҷ�֪ͨ����Сʱ�ڽ������գ�����������ʦ���պϸ������ռ�¼��ǩ�Ϻ��ҷ��ɽ��и��Ǻͼ���ʩ���������ղ��ϸ��ҷ�Ӧ����������ʦ��Ҫ�����Ĳ������������ա�<BR> 
�������������ҷ�δ�����ն����и��ǣ���������ʦ��ȨҪ������򿪿׼�飬�ɴ���ɵ���ʧ�����ҷ���������������ʦ�ڽӵ��������������֪ͨ����Сʱδ�ܽ������գ����ҷ����Լ�ϸ�������и��ǡ���������ʦ�º�Ӧ��ȷ�ϲ�����ǩ��������<BR> 
��������������������ʦ��Ϊȷ�����ǩ�����ղ����ǵ����ι��̽��и��飬�ҷ�ӦЭ�����顣�����������������ϸ��ɴ˶������һ�з����ɼ׷��е���Ӱ���ʩ���������ӳ����������������������ϸ��ҷ�Ӧ���з��������������涨�����������գ��ɴ������һ�з��ü�ʩ�����������ҷ�����<BR> 
����������������飺���顢�Լ�Ӧ��������׼��Ҫ���͵��߱���Ӧ���ʵȼ��ļ��鵥λ�����������飬����ʱ�������������ύ��������ʦ��顣��������ʦ��Ϊ��Ҫʱ����Ȩָ��ȡ���ͼ��鵥λ���졣<BR> 
�������������ȼ�������ʩ������Ӧ�ﵽ����ͬԼ���ļ�����׼��Ҫ���Լ���ͬר��������Լ���������ȼ���<BR> 
���������ҷ�ԭ��ʹ���������ﲻ��Լ���ȼ����׷���ȨҪ���ҷ������������������ҷ��е���ʩ���ڲ����ӳ������������Դﲻ��Լ���ȼ�ʱ���ҷ�Ӧ�е��⳥���Ρ�����׷�ԭ��ʹ���������ﲻ��Լ���ȼ����ҷ�Ӧ���ݼ׷�Ҫ����з������������׷��е��������������ã�����Ӧ�ӳ�ʩ���ڡ�<BR> 
�������������ල�������̵������ල���������ලվ���𣬼���˫����Ӧ������Թ��������ļල���顣<BR> 
������ʮ��������ͬ�ۿ���֧��<BR> 
����������ͬ�ۿ�ļ�����֧��������ͬ���̵ĳа���ʽ����ͬ�ۿ�ļ�����֧��Ӧ����ͬר��������Լ���İ취ִ�С�<BR> 
����������ͬ�ۿ�ĵ���������ͬר����������Լ���⣬������������֮һʱ��ͬ�ۿ����������<BR> 
��������������������ʦȷ�ϼ׷���׼�Ĺ�������������Ʊ����<BR> 
�������������һ�ط�������۹������ŷֲ��۸�ͷ��ʵ�����<BR> 
����������һ���ڷ��ҷ�ԭ����ɵ�ͣˮ��ͣ�硢ͣ���ۻ�������Сʱ��ʹ�ҷ��ܵ���ʧʱ��<BR> 
������������ͬԼ�������������������<BR> 
�����ҷ�Ӧ��������������󣱣����ڣ���������ԭ��ͽ��֪ͨ��������ʦ����������ʦ�ڽӵ��ҷ�֪ͨ����������ȷ�ϲ������׷���׼�𸴣��׷�Ӧ�ڼ�������ʦ�ӵ��ҷ�֪ͨ�󣱣��������Դ𸴣�����δ��𸴣�����Ϊ�ҷ���Ҫ���ѱ��׷���׼��<BR> 
������������Ԥ����֧�����׷�Ӧ����ͬר������Լ�������޺�������ҷ�֧������Ԥ���������ͬר��������Լ�������޺ͱ�����οۻء��׷�δ����֧ͬ��Ԥ����ʱ���ҷ�����Լ��Ԥ������գ��������׷������߸����֪ͨ�����׷��յ�֪ͨ���Բ��ܰ�Ҫ�󸶿�ҷ����ڷ���֪ͨ���������ͣʩ�����׷���Ӧ����֮�������ҷ�֧��Ӧ���������Ϣ�����е�ΥԼ���μ��ҷ���ͣ����ʧ��<BR> 
��������������ȷ�ϣ��ҷ�Ӧ����ͬר��������Լ�������ޣ����������ʦ�ύ����ɹ������ı�������������ʦ�յ��������������ǩ�ϡ�����������ʦ�ӵ�����������δ������飬�ҷ�������������Ϊ�ѱ���������ʦȷ�ϡ�<BR> 
��������������ʦ���ҷ����������������飬�ҷ�ӦЭ����������ʦ�����깤�������к�ʵ�������º˱������ҷ��ܾ�Э����������ʦ�����깤�������к�ʵ�����º˱������Լ�������ʦ��ʵ�Ĺ�����Ϊ׼��<BR> 
�����������ȿ�֧�����׷����ݺ�ͬר��������Լ����ʱ��Ͱ취�����ݼ�������ʦȷ�ϵĹ��������ҷ�֧�����̽��ȿ���׷��ں�ͬԼ����֧�����ں󣱣�����δ��֧�����ҷ�����׷������߸����֪ͨ���׷����յ��ҷ�֪ͨ���Բ��ܰ�Ҫ��֧�����ҷ����ڷ���֪ͨ���������ͣʩ�����׷��е�����֧������Ϣ��ΥԼ���μ��ҷ���ͣ����ʧ��<BR> 
������������֧�����̿�羭�ҷ�ͬ�Ⲣǩ�����ڸ���Э�飬�׷�������֧�����̼ۿ<BR> 
������ʮ��������Ʊ��<BR> 
���������׷�����ı�����׷��Թ��������Ʊ��ʱ��Ӧ����������ͽ���Ƶ�λ��飬��Ƶ�λ���ͬ����ɼ�������ʦ���ҷ��������֪ͨ���ҷ�Ӧ���ݱ��ͼֽ��ʵʩ����������֯ʩ����������漰��ƹ�ģ����Ʊ�׼�ĸı䣬��Ӧ�������ҷ�Э�̡�<BR> 
���������ҷ�����ı�����ҷ��������Ʊ��Ӧ���ü�������ʦ��ͬ�Ⲣ����Ƶ�λ��飬��Ƶ�λ���ͬ��󣬱��׷���׼�󷽿�ʵʩ�����ɴ˵��¹��̷���������ʩ���仯������˫��ӦЭ�̴��������ҷ�Ϊ������֯ʩ����Ϊ��ʩ�����㡢�����������ŵ�ԭ�������ı�������ӵķ������ҷ��е���<BR> 
�������������ɵı������ʩ�������������ͼֽ����������������Ƴ���ϴ���������ز��������ʱ����������ʦӦ�ڣ������������������ҷ�ȡ���޸�ͼֽ�󰴼�������ʦָ����֯ʩ�����ɴ����ӵķ����ɼ׷��е��������ʩ���������ӳ���<BR> 
��������ȷ������ۿ������������󣬰��������������������̵��ۺͼۿ<BR> 
������������ͬ���������ڱ�����̵ĵ��ۣ�����ͬ���еĵ��ۼ��㣻<BR> 
������������ͬ���������ڱ�����̵ĵ��ۣ��Դ˵�����Ϊ����ȷ��������ۣ�<BR> 
������������ͬ��û�����ú����Ƶĵ���ʱ���ɼ���˫���̶�������ۣ�<BR> 
������������ͬר������Լ�����������㷽����<BR> 
�������ɼ׷��е��ķ��ã��ҷ�Ӧ��˫���̶���ʱ���ڣ������������������ۿ����������ʦ��飬���׷�ͬ��������ͬ�ۿ�Ϳ������ڣ����׷���ͬ���ҷ�����ı���ۿӦ���ҷ�����󣱣��������ҷ�Э�̣���Э�̲��ɣ�˫�������빤����۹������Ųö���<BR> 
������ʮ������ת����ְ�<BR> 
��������ת�ã�����ͬһ��ǩ�𲻵�ת�á�<BR> 
���������ְ���<BR> 
��������������ͬ���̵����幤�̲��÷ְ���<BR> 
���������������ַ����ȷ��ְ�ʱ����ȡ�ü׷�ͬ�⡣�ҷ�Ӧ���ְ���ͬ�����ύ�׷�������<BR> 
�����������ҷ��Ա���ͬ���е���ȫ�����κ����񣬲��ְܷ���Ӱ�졣��������ʦ��Ȩ�Էְ���ʩ��������ʩ����ȫ��ʩ�����Ƚ��мල���ְ��˵��κ�ΥԼ�����������Ϊ�ҷ���ΥԼ�������<BR> 
������ʮ������ʩ����ȫ<BR> 
��������ʩ����ȫ���Σ��ҷ�Ӧ�����Һ��йز��ŵĹ涨����ǿʩ���ֳ���Ա�봬����ʩ����ȫ��������ʩ���ֳ��ķ�̨�����𡢷�������Ѵ�ͷ����Ȳ�ȡ�ϸ�İ�ȫ������ʩ�����е����ڴ�ʩ������ɵ��¹����κ���˷����ķ��á�<BR> 
����������ȫ�¹ʴ�����ʩ���ֳ������ش������¹�ʱ���ҷ���������ȡ��Ч�Ĵ�ʩ�⣬Ӧ�������¹�����ϱ��йز��Ų�֪ͨ�׷�����������ʦ���׷�ӦΪ�����ṩ��Ҫ��������<BR> 
����������ȫ��ʩ���ҷ��ڸ�ѹ�ߡ�ˮ�ϡ�ˮ�¼����¹��ߡ���ȼ���ױ��ضμ��к�������ʩ��ʱ��ʩ��ǰӦ�����ȫ������ʩ������������ʦ��飬�׷�ͬ���ʵʩ���׷���ͬ�ⲻ������ҷ�Ӧ�е��İ�ȫ���Ρ�������ʩ���ó���ͬר����������Լ���⣬���ҷ��е���ʩ�������У������ⲿ�����仯�������İ�ȫ��ʩ���ã��ɼ׷�����֧����<BR> 
������ʮ����������<BR> 
�����������̱��գ������̵ġ���������һ���պ͵����������ա���Ͷ���ں�ͬר��������Լ����<BR> 
����������Ա�������豸���գ������̵�ʩ����Ա�������豸�ı��գ����ҷ�����<BR> 
������ʮ���������ɿ���<BR> 
����������Ȼ�ֺ�����ͬר��������Լ���ȼ����ϵ���Ȼ�ֺ�Ҳ�����ɿ�����<BR> 
����������ʧ���棺���ɿ����������ҷ�ӦѸ�ٲ�ȡ��ʩ������������ʧ�����ڣ���Сʱ����׷�����������ʦͨ�����������������׷�����������ʦ�ύ��ʧ������������޸������Լ�ʩ��������ı��档<BR> 
�����������÷ֵ����򲻿ɿ������������������з��ã��ɼ���˫���ֱ�е���<BR> 
�������������̱��������ɼ׷��е���<BR> 
������������Ա��������������λ����<BR> 
�����������ҷ��豸�������ͻ�е�������ҷ��е���<BR> 
����������ʧ���������ɿ�����ɹ���ͣ�����ƻ�ʱ������˫������Э�̺��ɼ׷�������������������ಹ�����������ҷ�������ʩ��������ɵ���ʧ���ɱ����ڣ���涨Ӧ���ҷ��е��ķ��á��������޸����������κ�������ã��ɼ���˫����ǩ����Э���ͬǩ����¼Լ����<BR> 
������ʮ�������������������<BR> 
���������������룺�ҷ�Ӧ�ڹ����깤�Ҿ߱���������������������׷�����������ʦ�ύ���̿����������뱨��͹��̿������ϡ���������ʦ�ӵ���������Ϳ������Ϻ�����������飬����������������׷������������̼��������Ϸ���Ҫ�󣬼׷�Ӧ�ڽӵ����뱨�棱��������֯���գ������̻򿢹����ϴﲻ��Ҫ���ҷ�Ӧ����������ʦ�ĺ���Ҫ�󣬶Թ��̻򿢹������������޻򲹳䣬�������������գ��ɴ˶������ķ��ú�ʩ���ڵ��������ҷ���������������ʦ��׷�δ���ں�ͬ�涨�������ڶԹ��̼��������Ͻ���������֯���գ��ɴ˲����ľ�����ʧ�ɼ׷��е�������Ӧ�ӳ�ʩ���ڡ�<BR> 
�����������ճ���<BR> 
�����������׷������ҷ��ύ�Ŀ����������뱨��Ϳ������ϣ�ȷ������ʱ�䲢֪ͨ�йظ�����<BR> 
�����������׷���֯�йص�λ����ͬԼ�������ݣ��Թ��̽���ĸ������ڽ�����飬��ȡ�йص�λ�Թ��̹�������ơ�ʩ���ͼ�������Ļ㱨�����Ĺ��̿������ϣ������ֳ������Թ�����������ڵ�����������������<BR> 
���������������ලվ�Թ��������ȼ�����������<BR> 
���������������պϸ񣬼���˫���������̽���������<BR> 
�����������������պϸ񣬿�������Ϊ�ҷ��ͽ������������뱨������ڣ����޸ĺ���ܴﵽ�������պϸ�Ĺ��̣��俢������ӦΪ�޸ĺ�����׷����յ����ڡ�<BR> 
���������ֳ����������̿������պϸ�󣬳��׷�����Ҫ���⣬�ҷ�Ӧ����ͬר��������Լ�������޺�Ҫ������������ʩ���ֳ��������ҷ�Ӧ����ͬר�������Լ����׷�֧���йط��á�<BR> 
���������������ϣ�����ͬר����������Լ���⣬�ҷ��ύ�Ŀ�������Ӧ����������Ҫ���ݣ����������ύ�ķ����ں�ͬר��������Լ����<BR> 
��������������ͼֽ�����ϣ�<BR> 
�������������������������棻<BR> 
�����������׶����պ����ι����������ϣ�<BR> 
�������������̲��ϼ��豸������������ϣ�<BR> 
����������������λ�ƹ۲����ϣ�<BR> 
����������������������߶Ȳ������棻<BR> 
����������ʩ�����棻<BR> 
�����������������㱨�棨�����꿢��������ύ����<BR> 
���������������㣺<BR> 
�����������������պϸ���ҷ�Ӧ�ڣ���������׷��ύ���㱨�棬�����������㡣�׷��ӵ����㱨��󣱣��������ȷ�ϣ�����ȷ�Ϻ����ڽ�Ӧ֧���ҷ��Ĺ��̿�֧�����ҷ������׷��ӵ����㱨��󣱣�����δ�����ȷ�ϣ��ҷ��ύ�Ľ��㱨������Ϊ�ѱ��׷�ȷ�ϡ�<BR> 
�����������׷���ȷ�Ͻ��㱨�������δ�����̿�֧�����ҷ�����ȷ�Ͻ��㱨���ڣ�����ʩ����ҵ�����мƻ��������������ҷ�֧����Ƿ���̿������Ϣ�����е�ΥԼ���Ρ�<BR> 
������ʮ���������̱���<BR> 
���������������ޣ������ڴӹ��̿���֮�����㡣ˮ�����̱�����Ϊһ�������迣���̲��豣���ڣ��������̵ı�������ר��������Լ�����׶����չ��̵ı����ڴӽ׶����տ���֮�����㡣<BR> 
���������������Σ��ҷ��ڹ��̱������������η������ҷ�ԭ����ɵ��κι���ȱ�ݻ��𻵣����޷������ҷ��е�����׷�ʹ�ò������������ҷ�ԭ����ɵ��𻵣��ҷ�ӦЭ���޸��������ɼ׷��е���<BR> 
�����������ޣ��������ڣ���Ӧ���ҷ�����ķ������ݣ��ҷ�Ӧ�ڽӵ�����֪ͨ�󣱣����ڿ�ʼʵʩ���ޣ����ڼ���˫���̶���ʱ����������ϣ�����׷���Ȩί��������λ����Ա������������������ҷ��е���<BR> 
�����������޽𣺱��޽�������ں�ͬר��������Լ�����׷�Ӧ�ڱ������������ڣ������޽�Ͱ���ͬר������Լ�����ʼ������Ϣһ�𷵻��ҷ���<BR> 
�����ڶ�ʮ�������顢ΥԼ���������⳥<BR> 
�����������飺˫����ִ�к�ͬ�����з�������ʱ��ӦЭ�̽�����������ܲ��ŵ��⣬��Э�̼�������Ч����ѡ�����·�ʽ֮һ������<BR> 
�������������й�ϽȨ�ĺ�ͬ�ٲû��������ٲã�<BR> 
�������������й�ϽȨ������Ժ���ߡ�<BR> 
�������鷢���󣬳�˫����ͬ��ͣ���⣬˫����Ӧ�������к�ͬ��������ΪΥԼ��<BR> 
��������ΥԼ���κ�һ��������ͬ�����Լ������κ����񣬾�ΪΥԼ��ΥԼ��Ӧ�Է��⳥��ΥԼ���Է���ɵ�ֱ�Ӿ�����ʧ����һ��ΥԼʹ��ͬ��������ʱ����һ����Ҫ����ֹ������ͬ����Ӧ��ǰ������֪ͨΥԼ�����ɴ���ɵľ�����ʧ��ΥԼ���е���<BR> 
�����������⣺��׷�ΥԼ��δ�ܼ�ʱ����������ҷ������ʧ�Լ����������¼�����ʱ���ҷ��ɰ����¹涨��׷����⣺<BR> 
������������Ҫ��������¼������󣲣����ڣ���׷��ύ�������롣<BR> 
�����������������������󣱣����ڣ���׷��ύ���ⱨ�棬��ϸ˵���������ɣ��ṩͬ�ڼ�¼�����������ɿ�֤�ݣ�������������ӳ�ʩ���ڼ���������ݺͷ�����<BR> 
�����������׷��ӵ����ⱨ��󣱣����ڣ���ʱ���е��鲢���������Ͻ�����ˣ�����ȨҪ���ҷ���һ�������������ɺ�֤�ݡ����ҷ���Ҫ���ṩ�������Ϻ󣲣����ڣ��׷�Ӧ�����ҷ���ȷ�𸴡�����ȷ�ϵ�����������׷�����ƻ���<BR> 
�������������ҷ�δ�ܰ������涨��ʱ����������롢���ⱨ��򲹳����ϣ��׷��ɲ�������������׷�δ�������涨�������ڽ�����˻�ȷ�ϣ���Ϊ�ҷ�����Ҫ���ѱ��׷�ȷ�ϡ�<BR> 
�������������ҷ��Լ׷��Ĵ������飬�ɰ������ڣ���ִ�С�<BR> 
�������������׷���Ȩ��������ʦ�������¼�������ˣ�Ӧ�ں�ͬר��������˵�����������涨������ʱ�޲��䡣<BR> 
���������⳥��������������֮һʱ���׷��ɰ���ͬר��������Լ���İ취�ͱ�׼Ҫ���ҷ��⳥��<BR> 
����������ʩ�������ﲻ����ͬר������Լ���ȼ���<BR> 
�������������ҷ�ԭ��������ʩ���ڣ�<BR> 
����������ʩ�������ҷ�ԭ����ɹ����ش���ʧ��<BR> 
�����ڶ�ʮһ��������<BR> 
������������������ͬר����������Լ���⣬�����������֮һʱ���׷��ɰ���ͬר��������Լ���ı�׼�ͷ����������ҷ�������<BR> 
��������������ʩ���������ں�ͬר������Լ���ĵȼ���<BR> 
�������������׷�Ҫ����ǰ������<BR> 
�����ڶ�ʮ��������ͬ���ж�����ֹ<BR> 
����������ͬ�жϣ��������ߵı仯����������˫��֮���ԭ���¹���ͣ���򻺽���ʹ��ͬ���ܼ������У���Ϊ��ͬ�жϡ���ͬ�жϺ��ҷ�Ӧ������������ɹ��̺��ѹ����ϡ��豸�ı����͹��̵��ƽ����������׷���Ҫ��ʩ����������Ա�����ֳ����׷�ӦΪ�ҷ������ֳ��ṩ��Ҫ��������������ͬԼ��֧�����깤�̼ۿ���⳥�ҷ����ͬ�ж�����ɵ�ֱ�Ӿ�����ʧ��<BR> 
����������ͬ��ֹ������ͬ�ڹ��̿������պϸ��ҷ��������ƽ��׷��Ϳ���������Ϻ󣬳��йر��������⣬����������ֹ����������������˫�����屣�޽�󣬱���������ֹ��<BR> 
����<BR> 
����������ͬר������<BR> 
����<BR> 
������ͬר�������ǶԺ�ͬͨ������Ĳ��䡢���ƻ���廯��Ӧ���պ�ͬͨ��������ͬһ����һ���Ķ������⡣<BR> 
���������ͬͨ���������ͬר������֮���в�һ��֮�����Ժ�ͬר������Ϊ׼��<BR> 
������һ�������ﶨ��<BR> 
�����ڶ�������ͬ�ļ�<BR> 
����������ͬ�ļ���<BR> 
������ͬ��ɺͽ��ʹ���Ĳ������ġ�<BR> 
������������ļ���<BR> 
�����������׷��ṩ����ļ��ķ�����<BR> 
�����������ҷ����𲿷����ʱ���ύ����ļ�ʱ�䡢������<BR> 
��������������ͬ��Χ<BR> 
����������ͬ��Χ���г�����ͬ��Χ�ڵĹ��̵ص㡢���ݡ���ģ��������ĳ߶ȣ�������Ľṹ��ʽ������ȷ��ͬ��Χ��ˮ�����ѳ���ˮ�硢��������е���迣�Ȳ�ͬרҵ��ʩ�����ݡ�<BR> 
������������������׼<BR> 
��������������׼�������<BR> 
���������ڱ����̵�����������׼��Ӧ�÷�Χ��<BR> 
���������������ԡ��������ϵ��ʽ<BR> 
�����������ԣ�<BR> 
�����������ԡ�<BR> 
�����������棺<BR> 
�����������÷��档<BR> 
�������������׷�������<BR> 
���������ṩ����������<BR> 
�����׷����ҷ��ṩʩ�����ص�ʱ�䡢λ�á�����͸̡߳�<BR> 
���������ṩˮ���뽻ͨ������<BR> 
�����׷��ṩˮ���硢��ͨ��ͨѶ�������ͽ����ص㼰ʱ�䣬�׷��ṩʩ����������ʱͣ��ˮ���λ�ú�ʱ�䡣<BR> 
���������ṩ�������ϣ�<BR> 
�����׷��ṩ���ּ������ϵ�ʱ����������似�����ס��ֳ������ʱ��Ͱ취��<BR> 
�������������ҷ�������<BR> 
��������ʩ��׼����<BR> 
����Ϊ�������䱸����Ҫʩ���������豸�Ĺ�������������ֳ���ʱ�䡣<BR> 
���������ṩ������<BR> 
����Ϊ��������ʦ�ṩ�������嵥��<BR> 
�����ڰ��������̼���<BR> 
�����������̼�����<BR> 
����������λ���ơ�<BR> 
����������������ʦ��<BR> 
������������ʦ��ְ���Ȩ�ޡ�<BR> 
�����ھ�����ʩ����<BR> 
��������������<BR> 
�����������ҷ��ύ�������뱨���ʱ�ޡ�<BR> 
���������������������ʱ�ޡ�<BR> 
��������ʩ�����ӳ���<BR> 
����ʩ���ڿ����ӳ������������<BR> 
���������׶ι��ڣ�<BR> 
�����׶ι��ڵ�Ҫ��<BR> 
������ʮ����ʩ����֯���<BR> 
��������ʩ����֯��Ƶ��ύ��<BR> 
�����ҷ��ύʩ����֯��Ƶ�ʱ�䡣<BR> 
������ʮһ������������<BR> 
���������������豸��<BR> 
�����׷��ṩ�������豸���嵥����Ӧ��ʽ��<BR> 
�������������ȼ���<BR> 
����Լ�������ȼ���<BR> 
�������������ල��<BR> 
���������ලվ���ơ�<BR> 
������ʮ��������ͬ�ۿ���֧��<BR> 
����������ͬ�ۿ�ļ�����֧����<BR> 
��������������ͬ�ĳа���ʽ��<BR> 
������������ͬ�ۿ�ļ�����֧���취��<BR> 
����������ͬ�ۿ�ĵ�����<BR> 
�����������Ե�����������ƻ򲹳䣻<BR> 
���������������ľ��巽����<BR> 
������������Ԥ����֧����<BR> 
��������������Ԥ��֧����ʱ�䡢���ͷ�����<BR> 
��������������Ԥ����ֿ۵�ʱ�䡢���ͱ����ͷ�����<BR> 
��������������ȷ�ϣ�<BR> 
�����ҷ��ύ������������ʱ�䡣<BR> 
�����������ȿ�֧����<BR> 
�������̽��ȿ�֧����ʱ��Լ����ʵʩ�취�Լ����ȿ�����֧������Ϣ��<BR> 
������ʮ��������Ʊ��<BR> 
��������ȷ������ۿ<BR> 
����ȷ������ۿ��ԭ��ͱ�����۵��������㷽����<BR> 
������ʮ������ת����ְ�<BR> 
������ʮ������ʩ����ȫ<BR> 
����������ȫ��ʩ��<BR> 
����������ʩ���õĳе���<BR> 
������ʮ����������<BR> 
�����������̱��գ�<BR> 
����Ͷ�����ޡ����շ�Χ��Ͷ���������⳥����ͱ��շ��óе���<BR> 
������ʮ���������ɿ���<BR> 
����������Ȼ�ֺ���<BR> 
��������ͬͨ�����������⣬���ɿ���������������Ȼ�ֺ���<BR> 
�����������ߣߣߣߣߣ߼����ϳ����ߣߣߣߣ���Ĵ�磻<BR> 
������������Ч���߳����ߣߣߣߣߣ��׵Ĳ��ˣ�<BR> 
�����������ߣߣߣߣߣߣߺ������ϳ����ߣߣߣ���Ĵ��ꣻ<BR> 
�����������Ҷȣߣߣߣߣߣߣ����ϵĵ���<BR> 
��������������������<BR> 
������ʮ�������������������<BR> 
���������ֳ�������<BR> 
�������������ҷ������峡��Ҫ���ҷ�����ʱ�䡣<BR> 
�����������ҷ�δ��Ҫ���峡�����룬����׷��⳥�İ취��<BR> 
���������������ϣ�<BR> 
�����������Կ����������ݵĲ�����޸ģ�<BR> 
�����������ҷ��ύ�������ϵķ�����<BR> 
������ʮ���������̱���<BR> 
���������������ޣ�<BR> 
�����Թ��̱������޵�Լ�����޸ġ�<BR> 
�����������޽�<BR> 
�������������޽�����<BR> 
�����������׷����ñ��޽�����ʡ�<BR> 
�����ڶ�ʮ�������顢ΥԼ���������⳥<BR> 
�����������⣺<BR> 
�����׷���ȷ��Ȩ��������ʦ����˷�ʽ��<BR> 
���������⳥��<BR> 
�����⳥�İ취�ͱ�׼��<BR> 
�����ڶ�ʮһ��������<BR> 
��������������<BR> 
�����������Խ�������Ĳ�����޸ģ�<BR> 
�����������Ը��ֽ������������׼�ͽ���������Լ����<BR> 
�����ڶ�ʮ��������ͬ���ж�����ֹ<BR> 
����<BR> 
����<BR> 
�������� </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>