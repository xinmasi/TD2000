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
      <div align="center">��ƿ���Ͼ�Ӫ��ͬ</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p> ����<BR> 
����Ϊ�˳�ַ�����ҵ�����ٽ�����,��������,���Ϲ�Ӧ,���پ��õ�����,��<BR> 
��������ƿ���պ͹�Ӧ����,֧Ԯ"�Ļ�"����,��˫��Э��ͬ��,ǩ�����Ͼ�Ӫ��ͬ,<BR> 
������ͬ����ִ��.������������������������������������������������������������������������ <BR> 
������һ��,���Ͼ�Ӫ��λ:����������������������������������������������������<BR> 
�������ʻ��չ�˾(��ƿ�̵�)������������������������������(�׷�)������������<BR> 
����·�ֵ����´�������������������������������������������(�ҷ�)������������<BR> 
�����ڶ���,���Ͼ�Ӫ��ҵ����:������������������������������������������������<BR> 
���������ʻ��չ�˾��������·��ƿ���Ͼ�Ӫ�ꡡ����������������������������������<BR> 
����������,��֯�쵼:��������������������������������������������������������<BR> 
�����׷�������,�ҷ���ʮ��(���ֵ����´�������) �����Ӫ��ҵ��Ӫ����ί<BR> 
����Ա��,��������һ��,������������,ίԱ���ְ����:����������������������������������<BR> 
����1.����Ӫ��ʵ���쵼�ͼල.���������������������������������������������� <BR> 
����2.������Ӫ����������������Ҫ��Ա��ѡ��͵���.�������������������������� <BR> 
����3.�о�������Ӫ��ҵ���ش�Ӫ����.�������������������������������������� <BR> 
����4.��Ӫ������������������ҷ�����.�������������������������������������� <BR> 
����������,����˫���Ĺ���ְ��.����������������������������������������������<BR> 
�����׷�:������������������������������������������������������������������ <BR> 
����1.����ҵ��Ӫ����Ҫ,�ṩ��ƿר�Ӫ�������ʽ�.������������������������<BR> 
����2.���վ�Ӫ���ߺͼ۸�����.���������������������������������������������� <BR> 
����3.�����ճ�ҵ�񸨵�.���������������������������������������������������� <BR> 
�����ҷ�:������������������������������������������������������������������ <BR> 
����1.ȫ�渺���������,��Ż����͸��ּӹ����乤�ߵİ���.��������������������<BR> 
����2.����������ʹ����Ա.�������������������������������������������������� <BR> 
����3.��֯���ճ�����ҵ����.���������������������������������������������� <BR> 
����������,��Ӫ��Χ:��������������������������������������������������������<BR> 
����1.����ƿ.�鲣��.��������������������������������������������������������<BR> 
����2.��Ȩ���չ涨�����ڴ��������Ӫ�����չ�ҵ��.�������������������������� <BR> 
����3.��Ȩ��ӪXXXX�����Ĺ���ҵ��.���������������������������������������� <BR> 
����4.������׼,��Ȩ��ʡ���⿪չ����ƿ�ľ�Ӫҵ��.����������������������������<BR> 
����5.����ִ᳹�к�ͬ��,����ǩ���ĺ�ͬ,�뾭�й�������������ǩ֤����<BR> 
����Ч.�� <BR> 
����������,�۸�Ȩ��:��������������������������������������������������������<BR> 
����1.��Ӫ��ҵ�����������С��,�������ʻ��չ�˾��ۿƵĹ���ָ���½��й���. <BR> 
����2.ִ��ȫ��ͳһ�涨���չ���,������,���ۼ�.���������������������������� <BR> 
����3.��ִ��ͳһ���ۼ۵Ļ�����,��Ӫ��ɸ����г�������仯�͵������,��Ȩ�ڲ�����10%�ĸ�����Χ��,���е����۸�.���������������������������������������� <BR> 
����4.��XXXX������չҵ��,Ӧ���ӵ��ؼ۸����,���ò�ȡ�߼�������չҵ��.�� <BR> 
����5.�������ò�ȡΥ��ͳһ�۸���ֶ�,�������������Ӫ���Ļ���ҵ��.����������<BR> 
����������,��������������������������������������������������������������� <BR> 
����1.���������Ĳ�������ƶ�.���������������������������������������������� <BR> 
����2.�����ϼ��涨,���¡����ȱ౨��,��,����,����,�ƻ�,���й��쵼<BR> 
��������. <BR> 
����3.�ϸ�ִ�вƾ�����,�����Ҳ�˰���Ź涨,֧���������.������������������ <BR> 
����4.��Ŀ���,����׼ȷ.����������������������������������������������������<BR> 
����5.���������ίԱ���ᱨ�����������.������������������������������������ <BR> 
�����ڰ���,��Ա��ӯ������:��������������������������������������������������<BR> 
����1.��Ӫ��Ĺ�����Ա,Ҫ���ž����Լ��ԭ���ʵ�ʳ���,������Ա.��ҵ<BR> 
���������仯��Ҫ����ʱ,�������ίԱ����׼,ԭ���ϴӴ�ҵ��������ѡ.���������������� <BR> 
����2.�׷���פ��Ӫ��ĸ���Ա,�乤�ʺ�һ�д���,���ɼ׷�����,��פ��Ա,<BR> 
�������ô���Ӫ����ȡ��������.������������������������������������������������������ <BR> 
����3.��Ӫ�깤����Աƽ��ÿ���¹��ʲ��ó���50Ԫ,Ҫ������������,���״�<BR> 
����С,ȷ���������.������������������������������������������������������������������<BR> 
����4.��Ӫ��Ĺ�����Ա���Ͷ�������Ʒ�����������Ҫ���չ�Ӫ��ҵ��׼ִ��.���� <BR> 
����5.��Ӫ�겻ִ�й��ҹ涨���Ͷ���������,������Ա�������⹤��,��Ӫ��Ҫ�������ԭ��,����������������Ӫ��ҵίԱ���о����ϼ���׼,��һ���Դ���.���ô������а������߱������ҷֵ�.���������������������������������������������������� <BR> 
����6.��Ӫ��ʵ��ÿ�½���һ��,��Ӫ����������,��ȡ����ͳ��İ취,ʵ�������߷ֳ�,���ҷ����߳�,�׷�������,����ֳɺ�,˫�����԰��ϼ��涨����<BR> 
��������˰�������Ͻ�.������ʽ�仯��������ԭ������������,����˫������������ֳɱ����ֵ�. <BR> 
����7.��Ӫ��ֻ�������ճ���Ӫ��Ʒ�йص�ֱ�ӷ��á���ӷ����Լ�����ίԱ��˶��������칫����.���������������֧����̯���õ�ʱ��,�����涨�ۻ�,��׷���й���Ա����.�������������������������������������������������������������������� <BR> 
����8.δ������ίԱ����׼�ľ�Ӫ,����Υ���г������ͼ۸���������ɵ���ʧ,�ɴ��¾�Ӫ����ֱ�Ӹ����˸���,���ܴӷ�������֧.����������������������������������<BR> 
����9.��Ӫ��ҵֻ�������ίԱ����׼�ڱ���ҵ���¾�Ӫ������Ա�������ʺ͹��ҹ涨�Ľ�����׼,��������ͱ�������ҷ����дӷֵõ������н��,������Ӫ��ҵ����֧��.������������������������������������������������������������������������ <BR> 
����10.��Ӫ���ھ�Ӫ�з��������¾�������ɵľ�����ʧ,Ӧ����Ӫ��ҵ����.���� <BR> 
�����ھ���,����˫����Ӫʱ���� �ꡡ�¡��������ꡡ����.��˫����ͬ���<BR> 
��������Ӫ,��������Ӫ��ͬ,������ִ��ԭ��ͬ����.�������������������������������������� <BR> 
������ʮ��,����ͬ��ִ������,����һ����Ҫ�����ͬ����,��������������ǰ����������,��˫��ͬ���ִ��,����˫��ͬ��,�����õ���ΥԼ.����,��Υ<BR> 
����Լһ���е�����,˫�����һʱ����ͳһ,�ɱ���ǩ֤���زþ�.������������������������������ <BR> 
������ʮһ��,����ͬ˫��ǩ�º�,��˫���ϼ���׼�ͺ�ͬ��������ǩ֤֮������Ч,�����涨����������ҵ�Ǽ�,��ȡӪҵִ��,�Լ�����˰��Ǽ�����.������������������<BR> 
������ʮ����,��ҵ��ֹ��Ӫ��,�����Ʒ������Ŀ����,���,�ɼ׷����д���.�� <BR> 
������ʮ����,����ͬ������һʽ����,����˫����ִһ��,�������ɷ�,�ͽ���<BR> 
�����ز��ű���.���������������������������������������������������������������������� <BR> 
�����׷����������������ˡ������������ܲ��š����������������ˡ���������������<BR> 
�����ҷ����������������ˡ������������ܲ��š����������������ˡ���������������<BR> 
�������� </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>