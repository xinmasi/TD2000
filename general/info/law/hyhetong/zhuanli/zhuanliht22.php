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
      <div align="center">ר��������</div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p> һ���ֽ������������Ҫ��������£�</p>
      <p >1����������ר�������顢ʵ������ר�������顢������ר���������ָ�ʽ��</p>
        <p >2����дʱӦ�ϸ��ո�ʽ�����������ע�����������и���Ҫ��</p>
        <p >3����ר��������Ӧ���ͷ���Ҫ������������ļ���</p>
        <p >4��ר��Ҫ���鼰�й�ר�������ļ�Ӧ������ר�����ύ��</p>
        <p >������ʽ��</p>
        <p >����ʽһ��</p>
        <p align=center style='
text-align:center;line-height:22.0pt;
'>����ר��������</p>
        
      <p >�밴�ձ����桰���ע�������ȷ��д�������<span>&nbsp;&nbsp;&nbsp; </span>�˿�������ר������д</p>
      <table align="center" border="1">
        <tr> 
          <td width=65 rowspan=2 > 
            <p >�ݷ���</p>
            <p >����</p>
          </td>
          <td colspan=3 rowspan=2 valign=top >&nbsp; </td>
          <td colspan=2 valign=top > 
            <p >������ţ�������</p>
          </td>
        </tr>
        <tr> 
          <td colspan=2 valign=top > 
            <p >�ڷְ��ύ��</p>
          </td>
        </tr>
        <tr> 
          <td width=65 rowspan=2 > 
            <p >�޷�����</p>
          </td>
          <td colspan=3 rowspan=2 valign=top >&nbsp; </td>
          <td colspan=2 valign=top > 
            <p >��������</p>
          </td>
        </tr>
        <tr> 
          <td colspan=2 valign=top > 
            <p >��</p>
          </td>
        </tr>
        <tr> 
          <td width=65 rowspan=6 > 
            <p >��������</p>
          </td>
          <td colspan=5 valign=top > 
            <p >���������ƣ�����<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              </span>���������ڵع���</p>
          </td>
        </tr>
        <tr> 
          <td colspan=5 valign=top > 
            <p >��������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>��ַ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              ��ϵ������</p>
          </td>
        </tr>
        <tr> 
          <td colspan=5 valign=top > 
            <p >����������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              </span>���������ڵع���</p>
          </td>
        </tr>
        <tr> 
          <td colspan=5 valign=top > 
            <p >��������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>��ַ</p>
          </td>
        </tr>
        <tr> 
          <td colspan=5 valign=top > 
            <p >����������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              </span>���������ڵع���</p>
          </td>
        </tr>
        <tr> 
          <td colspan=5 valign=top > 
            <p >��������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>��ַ</p>
          </td>
        </tr>
        <tr> 
          <td width=65 rowspan=3 > 
            <p >��ר����</p>
            <p >�����</p>
          </td>
          <td colspan=5 valign=top > 
            <p >����<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              </span>�����������</p>
          </td>
        </tr>
        <tr> 
          <td colspan=5 valign=top > 
            <p >��������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>��ַ</p>
          </td>
        </tr>
        <tr> 
          <td colspan=5 valign=top > 
            <p >����������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              </span>&nbsp;������֤���&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; �绰</p>
          </td>
        </tr>
        <tr> 
          <td width=65 rowspan=2 > 
            <p >�����</p>
            <p >����</p>
          </td>
          <td colspan=5 valign=top > 
            <p >���ص�λ<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              </span>��ַ</p>
          </td>
        </tr>
        <tr> 
          <td colspan=5 valign=top > 
            <p >��������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              </span>���ر��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              ��������</p>
          </td>
        </tr>
        <tr> 
          <td width=65 rowspan=2 valign=top > 
            <p >��ְ�</p>
            <p >����</p>
          </td>
          <td colspan=3 valign=top > 
            <p >ԭ�������</p>
          </td>
          <td width=61 rowspan=4 > 
            <p >��</p>
            <p >��ɥʧ</p>
            <p >��ӱ��</p>
            <p >������</p>
            <p >����</p>
          </td>
          <td width=65 rowspan=4 > 
            <p class=MsoBodyText style='line-height:15.0pt;'>�������й������������ϵĹ���չ�������״�չ��</p>
            <p class=MsoBodyText style='line-height:15.0pt;'>�����ڹ涨��ѧ����������������״η���</p>
            <p style='line-height:15.0pt;'>��</p>
          </td>
        </tr>
        <tr> 
          <td colspan=3 valign=top > 
            <p >ԭ��������</p>
          </td>
        </tr>
        <tr> 
          <td width=65 rowspan=4 valign=top > 
            <p >��Ҫ��</p>
            <p >����</p>
            <p >Ȩ��</p>
            <p >��</p>
          </td>
          <td width=92 valign=top > 
            <p >����������</p>
          </td>
          <td width=48 valign=top > 
            <p >���������</p>
          </td>
          <td width=66 valign=top > 
            <p >�����������</p>
          </td>
        </tr>
        <tr> 
          <td width=92 rowspan=3 valign=top >&nbsp; </td>
          <td width=48 rowspan=3 valign=top >&nbsp; </td>
          <td width=66 rowspan=3 valign=top >&nbsp; </td>
        </tr>
        <tr> 
          <td width=61 > 
            <p >�ѱ���</p>
            <p >����</p>
            <p style='line-height:16.0pt;'>&nbsp;</p>
          </td>
          <td width=65 height=95 >����ר����������漰�����ش����棬�����ܴ���</td>
        </tr>
        <tr> 
          <td colspan=2 valign=top > 
            <p >��Ȩ��Ҫ������ÿ��<span>&nbsp;&nbsp; </span>��</p>
            <p >˵����<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>ÿ��&nbsp;&nbsp;&nbsp; 
              ��</p>
            <p >˵���鸽ͼ<span>&nbsp;&nbsp; </span>ÿ��&nbsp;&nbsp;&nbsp; ��</p>
          </td>
        </tr>
        <tr> 
          <td width=65 valign=top > 
            <p >�ӷ���</p>
            <p >����</p>
          </td>
          <td colspan=5 valign=top >&nbsp; </td>
        </tr>
        <tr> 
          <td colspan=3 valign=top > 
            <p >��</p>
            <p >�����ļ��嵥</p>
            <p >1��������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
            <p >2��Ȩ��Ҫ����&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
            <p >3��˵����&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
            <p >4��˵���鸽ͼ&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
            <p >5��˵����ժҪ&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
            <p >6��ժҪ��ͼ&nbsp;&nbsp;&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
          </td>
          <td colspan=2 valign=top > 
            <p >��</p>
            <p >�����ļ��嵥</p>
            <p >��ר������ί����</p>
            <p >�����ü���������</p>
            <p >�����������ļ�����</p>
            <p >��</p>
            <p >��</p>
            <p >��</p>
          </td>
          <td width=65 valign=top > 
            <p >��</p>
            <p >ר��������</p>
          </td>
        </tr>
        <tr> 
          <td colspan=6 valign=top > 
            <p >��<span>&nbsp; </span>�����˻�������ǩ��</p>
          </td>
        </tr>
        <tr> 
          <td colspan=6 valign=top > 
            <p >��<span>&nbsp; </span>�ռ��˵�ַ������</p>
            ������������ �ռ��˵�ַ���ռ������� </td>
      </table>
    </td>
    </tr>
</table>

<br>
<table width="500" class="TableBlock" align="center">
  <tr>
    <td height="54" valign="top" class="TableData">        
        <br clear=ALL>
        <p align=center style='
text-align:center;line-height:22.0pt;
'>���ע������</p>
        <p style='line-height:24.0pt;'>һ�����뷢��ר����Ӧ���ύ����ר�������顢Ȩ��Ҫ���顢˵���顢�и�ͼ��Ӧͬʱ�ύ��ͼ��ժҪ��ͼ��ժҪ�������ļ�Ӧ��һʽ���ݡ�һ��Ϊԭ����һ��Ϊ��ӡ����</p>
        <p style='line-height:24.0pt;'>��������Ӧʹ��������д����������Ӧ�����ֻ���ӡˢ���ּ�Ϊ��ɫ�����������������ͳһ����ʱ��Ӧͬʱע��ԭ�ġ�</p>
        <p style='line-height:24.0pt;'>���������з���ڹ������ѡ��ʹ�ã����з�����������ݵģ�Ӧ�ڷ����ڱ��ϡ��̡��š�</p>
        <p style='line-height:24.0pt;'>�ġ����������е�ַ�������ڵ�ַӦд��ʡ��ֱϽ�л��������������С������ֵ������ƺ����Լ��������롣����˵�ַӦд�������ݣ��С��أ���</p>
        <p style='line-height:24.0pt;'>�塢���˵����</p>
        <p style='line-height:24.0pt;'>1������ڢ١��ڢڡ��ڢۡ��ڢܡ��ڢ�����ר������д��</p>
        <p style='line-height:24.0pt;'>2������ڢݡ��ڢ����������ƣ�Ӧ����ȷ�����ó���25���֡�</p>
        <p style='line-height:24.0pt;'>3������ڢ���������Ӧ���ǶԱ�������ʵ�����ص㿯���������Թ��׵���Ȼ�ˡ����������������ϵ�Ӧ���������ҡ������϶���������д�����������д����޷���Ч����</p>
        <p style='line-height:24.0pt;'>4������ڢ����������ǵ�λ�ģ�Ӧ��д��λȫ�ƣ����빫��������һ�¡�������Ϊ���ˣ�Ӧ��д������ʵ����������д�����ȡ�������Ϊ�������δί��ר�����������Ӧ��ָ��һ��Ϊ�����ˣ�����д�ڵ�һ����������λ�á�������Ϊ��λ����Ӧ����д��λ��ϵ��������</p>
        <p style='line-height:24.0pt;'>5������ڢ�����Ӧ��д����ר����ע���ר������������Ʋ�ע��ע����롣ר���������ָ���Ĵ����˲��ó������ˣ����б�����һ��רְ�����˲�ע��������֤��š�</p>
        <p style='line-height:24.0pt;'>6�������漰΢���﷢��ר���ģ���Ӧ����д����ڢ��������ύ����֤���ʹ��֤����</p>
        <p style='line-height:24.0pt;'>7������������ְ�����ʱ����Ӧ��д����ڢ�����</p>
        <p style='line-height:24.0pt;'>8��������Ҫ��������߱�������Ȩ�ģ���Ӧ��д����ڢ�����</p>
        <p style='line-height:24.0pt;'>9��������Ҫ��ɥʧ��ӱ�Կ����ڵģ���Ӧ��д����ڢ������������������������ύ֤���ļ���</p>
        <p style='line-height:24.0pt;'>10��������Ҫ���ܴ���ģ���Ӧ��д����ڢ�����</p>
        <p style='line-height:24.0pt;'>11��������Ӧ����ʵ���ύ���ļ����ơ�������ҳ����ȷ��д�����<span style='font-size:
12.0pt;font-family:����'>��</span>���ڢԡ��ڢ�����ר���ֽ���ʵ�յ��ļ�����������[��ʵ��</p>
        <p style='line-height:24.0pt;'>12�������������������ѣ��������ύ�����ļ���ͬʱ�ύ���ü��������鼰�й�֤���ļ���</p>
        <p style='line-height:24.0pt;'>13��������ί��ר����������ģ��ڱ���ڢ���Ӧ�Ǵ����I�۹��¡�������δί��ר����������ģ�����ڢ���Ӧ��ȫ��������ǩ�ֻ���£�������Ϊ��λ�ģ�Ӧ�ǵ�λ���¡�������ԭ���е������˻�ר�����������ǩ�ֻ���²��ø�ӡ��</p>
        <p style='line-height:24.0pt;'>14������ڢ�������д�ռ��˵�ַ��������ί��ר����������ģ��ռ��˵�ַӦдר����������ĵ�ַ�����ƣ��ռ���д��[����������δί��ר����������ģ��ռ��˵�ַӦд��һ���������ˣ������ˣ��ĵ�ַ����������Ϊ��λ���ڵ�ַ���滹Ӧд��λ���ƣ��ռ���Ӧд��һ���������ˣ������ˣ���������������Ϊ��λ��Ӧд����λ��ϵ��������</p>
        <p style='line-height:24.0pt;'>15�������ˡ������ˡ�Ҫ������Ȩ���������ݱ�����д����ʱ��Ӧʹ��ר����ͳһ�ƶ��ĸ�ҳ��д��</p>
        <p style='line-height:24.0pt;'>16��������Ӧ�����յ�����֪ͨ��֮����������������������ڽ�������ѣ�������ӡˢ�ѣ������븽�ӷѡ�������Ҫ������Ȩ�ģ�Ӧ���ڽ�������ѵ�ͬʱ��������ȨҪ��ѡ�</p>
        <p style='line-height:24.0pt;'>17��һ��ר�������Ȩ��Ҫ�󣨰�������Ȩ��Ҫ��ʹ���Ȩ��Ҫ����������10��ģ��ӵ�11��Ȩ��Ҫ����ÿ��Ȩ���������ո��ӷ�20Ԫ��һ��ר�������˵����ҳ����������ͼҳ��������30ҳ�ģ��ӵ�31ҳ��ÿҳ���ո��ӷ�15Ԫ������300ҳ�ģ��ӵ�301ҳ��ÿҳ���ո��ӷ�30Ԫ��</p>
        <p style='line-height:24.0pt;'>18������ר�����ÿ���ͨ���ʾֻ����л㸶��Ҳ����ֱ����ר���ֽ��ɡ����л㸶�ġ�ר���֡��������У��й����������к�������̫ƽׯ�������˺ţ�144005��63���ʾֻ㸶�ı����к�����������������·���Ź���֪ʶ��Ȩ��ר���ַ��ù��������ʱӦ�ڻ㵥��д���������ơ������������ơ������˺�����š�����ʹ�õ�㡣</p>
        <p >����ʽ����</p>
        <p align=center style='
text-align:center;line-height:22.0pt;
'>ʵ������ר��������</p>
        <p ><span
style='position:absolute;z-index:6;left:0px;width:3px;height:40px'><img width=3 height=40
src="../../%D0%D0%D2%B5%BA%CF%CD%AC%D1%F9%B0%E5%BF%E2/%D7%A8%C0%FB%BA%CF%CD%AC/./%D7%A8%C0%FB%C7%EB%C7%F3%CA%E9.files/image004.gif" v:shapes="_x0000_s1033"></span><span
style='position:absolute;z-index:5;left:0px;width:3px;height:40px'><img width=3 height=40
src="../../%D0%D0%D2%B5%BA%CF%CD%AC%D1%F9%B0%E5%BF%E2/%D7%A8%C0%FB%BA%CF%CD%AC/./%D7%A8%C0%FB%C7%EB%C7%F3%CA%E9.files/image005.gif" v:shapes="_x0000_s1031"></span><span
style='position:absolute;z-index:7;left:0px;width:219px;height:3px'><img width=219 height=3
src="../../%D0%D0%D2%B5%BA%CF%CD%AC%D1%F9%B0%E5%BF%E2/%D7%A8%C0%FB%BA%CF%CD%AC/./%D7%A8%C0%FB%C7%EB%C7%F3%CA%E9.files/image006.gif" v:shapes="_x0000_s1041"></span>�밴�ձ����桰���ע�������ȷ��д�������<span>&nbsp;&nbsp;&nbsp; 
          </span>�˿�������ר������д</p>
        <table border=1 cellspacing=0 cellpadding=0 width=607>
          <tr> 
            <td width=79 rowspan=2 > 
              <p >��ʵ����</p>
              <p >������</p>
            </td>
            <td width=312 colspan=4 rowspan=2 valign=top >&nbsp; </td>
            <td width=216 colspan=4 valign=top > 
              <p >������ţ�ʵ�����ͣ�</p>
            </td>
          </tr>
          <tr> 
            <td width=216 colspan=4 valign=top > 
              <p >�ڷְ��ύ��</p>
            </td>
          </tr>
          <tr> 
            <td width=79 rowspan=2 > 
              <p >�������</p>
            </td>
            <td width=312 colspan=4 rowspan=2 valign=top >&nbsp; </td>
            <td width=216 colspan=4 valign=top > 
              <p >��������</p>
            </td>
          </tr>
          <tr> 
            <td width=216 colspan=4 valign=top > 
              <p >��</p>
            </td>
          </tr>
          <tr> 
            <td width=79 rowspan=6 > 
              <p >��������</p>
            </td>
            <td width=528 colspan=8 valign=top > 
              <p >���������ƣ�����<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>���������ڵع���</p>
            </td>
          </tr>
          <tr> 
            <td width=528 colspan=8 valign=top > 
              <p >��������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>��ַ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                ��ϵ������</p>
            </td>
          </tr>
          <tr> 
            <td width=528 colspan=8 valign=top > 
              <p >����������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>���������ڵع���</p>
            </td>
          </tr>
          <tr> 
            <td width=528 colspan=8 valign=top > 
              <p >�������� <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>��ַ</p>
            </td>
          </tr>
          <tr> 
            <td width=528 colspan=8 valign=top > 
              <p >����������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>���������ڵع���</p>
            </td>
          </tr>
          <tr> 
            <td width=528 colspan=8 valign=top > 
              <p >��������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>��ַ</p>
            </td>
          </tr>
          <tr> 
            <td width=79 rowspan=3 > 
              <p >��ר����</p>
              <p >�����</p>
            </td>
            <td width=528 colspan=8 valign=top > 
              <p >����<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>�����������</p>
            </td>
          </tr>
          <tr> 
            <td width=528 colspan=8 valign=top > 
              <p >��������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>��ַ</p>
            </td>
          </tr>
          <tr> 
            <td width=528 colspan=8 valign=top > 
              <p >����������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>������֤���&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; �绰</p>
            </td>
          </tr>
          <tr> 
            <td width=79 rowspan=2 > 
              <p >��ְ�</p>
              <p >����</p>
            </td>
            <td width=528 colspan=8 valign=top > 
              <p >ԭ�������</p>
            </td>
          </tr>
          <tr> 
            <td width=528 colspan=8 valign=top > 
              <p >ԭ��������</p>
            </td>
          </tr>
          <tr> 
            <td width=79 rowspan=4 valign=top > 
              <p >��Ҫ��</p>
              <p >����</p>
              <p >Ȩ��</p>
              <p >��</p>
            </td>
            <td width=104 valign=top > 
              <p >���������</p>
            </td>
            <td width=124 valign=top > 
              <p >�����������</p>
            </td>
            <td width=99 valign=top > 
              <p >����������</p>
            </td>
            <td width=74 rowspan=3 > 
              <p >��</p>
              <p >��ɥʧ</p>
              <p >��ӱ��</p>
              <p >������</p>
              <p >����</p>
            </td>
            <td width=127 rowspan=3 > 
              <p class=MsoBodyText style='line-height:15.0pt;'>�������й������������ϵĹ���չ�������״�չ��</p>
              <p class=MsoBodyText style='line-height:15.0pt;'>�����ڹ涨��ѧ����������������״η���</p>
              <p style='line-height:15.0pt;'>��</p>
            </td>
            <td width=0 height=35 ></td>
          </tr>
          <tr> 
            <td width=104 rowspan=3 valign=top >&nbsp; </td>
            <td width=124 rowspan=3 valign=top >&nbsp; </td>
            <td width=99 rowspan=3 valign=top >&nbsp; </td>
            <td width=0 height=29 ></td>
          </tr>
          <tr> 
            <td width=0 height=29 ></td>
          </tr>
          <tr> 
            <td width=201 colspan=3 > 
              <p style='line-height:14.0pt;'>��</p>
              <p style='line-height:16.0pt;'>Ȩ��Ҫ������ÿ��<span>&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>��</p>
              <p style='line-height:16.0pt;'>˵����<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>ÿ��&nbsp;&nbsp;&nbsp; ��</p>
              <p style='line-height:16.0pt;'>˵���鸽ͼ<span>&nbsp;&nbsp; </span>ÿ��&nbsp;&nbsp;&nbsp; 
                ��</p>
            </td>
            <td width=0 height=95 ></td>
          </tr>
          <tr> 
            <td width=79 valign=top > 
              <p >��ʵ����</p>
              <p >������</p>
            </td>
            <td width=528 colspan=8 valign=top >&nbsp; </td>
            <td width=0 height=30 ></td>
          </tr>
          <tr> 
            <td width=247 colspan=3 valign=top > 
              <p >��</p>
              <p >�����ļ��嵥</p>
              <p >1��������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
              <p >2��Ȩ��Ҫ����&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; 
                ҳ</p>
              <p >3��˵����&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
              <p >4��˵���鸽ͼ&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; 
                ҳ</p>
              <p >5��˵����ժҪ&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; 
                ҳ</p>
              <p >6��ժҪ��ͼ&nbsp;&nbsp;&nbsp; ��&nbsp; 
                ÿ��&nbsp;&nbsp; ҳ</p>
            </td>
            <td width=215 colspan=4 valign=top > 
              <p >��</p>
              <p >�����ļ��嵥</p>
              <p >��ר������ί����</p>
              <p >�����ü���������</p>
              <p >�����������ļ�����</p>
              <p >��</p>
              <p >��</p>
              <p >��</p>
            </td>
            <td width=145 valign=top > 
              <p >��</p>
              <p >ר��������</p>
            </td>
            <td width=0 height=30 ></td>
          </tr>
        </table>
        <p align=center >���ע������</p>
        <p >һ������ʵ������ר����Ӧ���ύʵ������ר�������顢Ȩ��Ҫ���顢˵���顢˵���鸽ͼ��ժҪ��ժҪ��ͼ�������ļ�Ӧ��һʽ���ݡ�һ��Ϊԭ����һ��Ϊ��ӡ����</p>
        <p >��������Ӧʹ��������д����������Ӧ�����ֻ���ӡˢ���ּ�Ϊ��ɫ�����������������ͳһ����ʱ��Ӧͬʱע��ԭ�ģ�</p>
        <p class=MsoBodyTextIndent>���������з���<span
style='font-family:����'>��</span>�������ѡ��ʹ�ã����з�����������ݵģ�Ӧ�ڷ����ڱ��ϡ��̡��š�</p>
        <p >�ġ����������е�ַ�������ڵ�ַӦд��ʡ��ֱϽ�л������������С������ֵ������ƺ��룬���������롣����˵�ַӦд�������ݣ��С��أ���</p>
        <p >�塢���˵����</p>
        <p >1������ڢ١��ڢڡ��ڢۡ��ڢ�����ר������д��</p>
        <p >2������ڢݡ��ڢ���ʵ���������ƣ�Ӧ����ȷ�����ó���25���֡�</p>
        <p >3������ڢ��������Ӧ���ǶԱ�ʵ�����͵�ʵ�����ص����������Թ��׵���Ȼ�ˡ���������������ϵ�Ӧ���������ҡ������϶���������д����������д����޷���Ч����</p>
        <p >4������ڢ����������ǵ�λ�ģ�Ӧ��д��λȫ�ƣ����빫��������һ�¡�������Ϊ���ˣ�Ӧ��д������ʵ����������д�����ȡ�������Ϊ�������δί��ר�����������Ӧ��ָ��һ��Ϊ�����ˣ�����д�ڵ�һ����������λ�á�������Ϊ��λ��ϵ������</p>
        <p >5������ڢ�����Ӧ��д����ר����ע���ר������������Ʋ�ע��ע����롣ר���������ָ�������˲��ó������ˣ����б�����һ��רְ�����룬��ע��������֤��š�</p>
        <p >6������������ְ�����ʱ����Ӧ��д����ڢ�����</p>
        <p >7��������Ҫ��������߱�������Ȩ�ģ���Ӧ��д����ڢ�����</p>
        <p >8��������Ҫ��ɥʧ��ӱ�Կ����ڵģ���Ӧ��д����ڢ���������������2�������ύ֤���ļ���</p>
        <p >9��������Ӧ����ʵ���ύ���ļ����ơ�������ҳ����ȷ��д�����<span style='font-size:
12.0pt;font-family:����'>��</span>���ڢҡ��ڢ�����ר���ֽ���ʵ���ļ������������ʵ��</p>
        <p >10�������������������ѣ��������ύ�����ļ���ͬʱ�ύ���ü��������鼰�й�֤���ļ���</p>
        <p >11��������ί��ר����������ģ��ڱ���ڢ���Ӧ�Ǵ���������¡�������δί��ר�������ģ�����ڢ���Ӧ��ȫ��������ǩ�ֻ���£�������Ϊ��λ�ģ�Ӧ�ǵ�λ���¡�������ԭ���е������˻�ר�����������ǩ�ֻ���²��ø�ӡ��</p>
        <p >12������ڢ�������д�ռ��˵�ַ��������ί��ר����������ģ��ռ��˵�ַӦдר�����������ַ�����ƣ��ռ���д������������δί��ר����������ģ��ռ��˵�ַӦд��һ���������ˣ������ˣ��ĵ�ַ����������Ϊ��λ���ڵ�ַ���滹Ӧд��λ���ƣ��ռ���Ӧд��һ���������ˣ������ˣ���������������Ϊ��λ��Ӧд����λ��ϵ��������</p>
        <p >13������ˡ������ˡ�Ҫ���������������ݱ�����д����ʱ��Ӧʹ��ר����ͳһ�ƶ��ĸ�ҳ��д��</p>
        <p >14��������Ӧ�����յ�����֪ͨ��֮����������������������ڽ�������Ѻ����븽�ӷѡ�������Ҫ������Ȩ�ģ�Ӧ���ڽ�������ѵ�ͬʱ��������ȨҪ��ѡ�</p>
        <p >15��һ��ר�������Ȩ��Ҫ�󣨰�������Ȩ��Ҫ��ʹ�������Ҫ����������10��ģ��ӵ�11��Ȩ��Ҫ����ÿ��Ȩ��ġ�����ո��ӷ�20Ԫ��һ��ר�������˵����ҳ����������ͼ����������30ҳ�ģ��ӵ�31ҳ��ÿҳ���ո��ӷ�15Ԫ������300ҳ�ģ��ӵ�301ҳ��ÿҳ���ո��ӷ�30Ԫ��</p>
        <p >16������ר�����ÿ���ͨ���ʾֻ����л㸶��Ҳ����ֱ����ר���ֽ��ɡ����л㸶��ר���֣��������У��й��������б����к�����̫ƽׯ�������˺ţ�144005��63���ʾֻ㸶�ı���ȷ������������������·���Ź���֪ʶ��Ȩ��ר���ַ��ù��������ʱӦ�ڻ㵥��д���������ơ������������ơ������˺�����š�����ʹ�õ�㡣</p>
        <p >����ʽ����</p>
        <p align=center style='
text-align:center;line-height:22.0pt;
'><span style='position:absolute;z-index:10;left:0px;
width:207px;height:3px'><img width=207 height=3 src="../../%D0%D0%D2%B5%BA%CF%CD%AC%D1%F9%B0%E5%BF%E2/%D7%A8%C0%FB%BA%CF%CD%AC/./%D7%A8%C0%FB%C7%EB%C7%F3%CA%E9.files/image007.gif"
v:shapes="_x0000_s1042"></span><span style='font-size:14.0pt;
font-family:���Ŀ���;
"Times New Roman"'>������ר��������</span></p>
        <p ><span
style='position:relative;z-index:9'><span style='left:0px;
position:absolute;left:587px;top:-1px;width:3px;height:44px'><img width=3
height=44 src="../../%D0%D0%D2%B5%BA%CF%CD%AC%D1%F9%B0%E5%BF%E2/%D7%A8%C0%FB%BA%CF%CD%AC/./%D7%A8%C0%FB%C7%EB%C7%F3%CA%E9.files/image008.gif" v:shapes="_x0000_s1040"></span></span><span
style='position:relative;z-index:8'><span style='left:0px;
position:absolute;left:383px;top:-1px;width:3px;height:44px'><img width=3
height=44 src="../../%D0%D0%D2%B5%BA%CF%CD%AC%D1%F9%B0%E5%BF%E2/%D7%A8%C0%FB%BA%CF%CD%AC/./%D7%A8%C0%FB%C7%EB%C7%F3%CA%E9.files/image009.gif" v:shapes="_x0000_s1038"></span></span>�밴�ձ����桰���ע�������ȷ��д�������<span>&nbsp;&nbsp;&nbsp; 
          </span>�˿�������ר������д</p>
        
<table border=1 cellspacing=0 cellpadding=0 width=601 height="2061">
  <tr> 
            <td width=79 rowspan=2 > 
              <p class=MsoBodyText >��ʹ�ø�</p>
              <p class=MsoBodyText >�����</p>
              <p >�ƵĲ�</p>
              <p >Ʒ����</p>
            </td>
            <td width=312 colspan=5 rowspan=2 valign=top >&nbsp; </td>
            <td width=209 colspan=4 valign=top > 
              <p >��</p>
              <p >����ţ������ƣ�</p>
            </td>
          </tr>
          <tr> 
            <td width=209 colspan=4 valign=top > 
              <p >��</p>
              <p >�ְ��ύ��</p>
            </td>
          </tr>
          <tr> 
            <td width=79 rowspan=2 > 
              <p >�������</p>
            </td>
            <td width=312 colspan=5 rowspan=2 valign=top >&nbsp; </td>
            <td width=209 colspan=4 valign=top > 
              <p >��������</p>
            </td>
          </tr>
          <tr> 
            <td width=209 colspan=4 valign=top > 
              <p >��</p>
            </td>
          </tr>
          <tr> 
            <td width=79 rowspan=6 > 
              <p >��������</p>
            </td>
            <td width=521 colspan=9 > 
              <p >���������ƣ�����<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>���������ڵع���</p>
            </td>
            <td width=0 height=45 ></td>
          </tr>
          <tr> 
            <td width=521 colspan=9 > 
              <p >��������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>��ַ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                ��ϵ������</p>
            </td>
            <td width=0 height=45 ></td>
          </tr>
          <tr> 
            <td width=521 colspan=9 > 
              <p >����������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>���������ڵع���</p>
            </td>
            <td width=0 height=45 ></td>
          </tr>
          <tr> 
            <td width=521 colspan=9 > 
              <p >��������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>��ַ</p>
            </td>
            <td width=0 height=45 ></td>
          </tr>
          <tr> 
            <td width=521 colspan=9 > 
              <p >����������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>���������ڵع���</p>
            </td>
            <td width=0 height=45 ></td>
          </tr>
          <tr> 
            <td width=521 colspan=9 > 
              <p >��������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>��ַ</p>
            </td>
            <td width=0 height=45 ></td>
          </tr>
          <tr> 
            <td width=79 rowspan=3 > 
              <p >��ר����</p>
              <p >�����</p>
            </td>
            <td width=521 colspan=9 > 
              <p >����<span>&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�����������</p>
            </td>
            <td width=0 height=45 ></td>
          </tr>
          <tr> 
            <td width=521 colspan=9 > 
              <p >��������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>��ַ</p>
            </td>
            <td width=0 height=45 ></td>
          </tr>
          <tr> 
            <td width=521 colspan=9 > 
              <p >����������<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                </span>������֤���&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; �绰</p>
            </td>
            <td width=0 height=45 ></td>
          </tr>
          <tr> 
            <td width=79 rowspan=2 > 
              <p >��ְ�</p>
              <p >����</p>
            </td>
            <td width=521 colspan=9 > 
              <p >ԭ�������</p>
            </td>
            <td width=0 height=45 ></td>
          </tr>
          <tr> 
            <td width=521 colspan=9 > 
              <p >ԭ��������</p>
            </td>
            <td width=0 height=45 ></td>
          </tr>
          <tr> 
            <td width=79 rowspan=4 valign=top > 
              <p >��Ҫ��</p>
              <p >����</p>
              <p >Ȩ��</p>
              <p >��</p>
            </td>
            <td width=119 valign=top > 
              <p >�����������</p>
            </td>
            <td width=121 colspan=3 valign=top > 
              <p >����������</p>
            </td>
            <td width=106 valign=top > 
              <p >���������</p>
            </td>
            <td width=74 rowspan=4 > 
              <p >��</p>
              <p >��ɥʧ</p>
              <p >��ӱ��</p>
              <p >������</p>
              <p >����</p>
            </td>
            <td width=101 rowspan=4 > 
              <p class=MsoBodyText style='line-height:15.0pt;'>�������й������������ϵĹ���չ�������״�չ��</p>
              <p class=MsoBodyText style='line-height:15.0pt;'>�����ڹ涨��ѧ����������������״η���</p>
              <p style='line-height:15.0pt;'>��</p>
            </td>
            <td width=0 height=35 ></td>
          </tr>
          <tr> 
            <td width=119 rowspan=3 valign=top >&nbsp; </td>
            <td width=121 colspan=3 rowspan=3 valign=top >&nbsp; </td>
            <td width=106 rowspan=3 valign=top >&nbsp; </td>
            <td width=0 height=29 ></td>
          </tr>
          <tr> 
            <td width=0 height=29 ></td>
          </tr>
          <tr> 
            <td width=0 height=95 ></td>
          </tr>
          <tr> 
            <td width=218 colspan=3 valign=top > 
              <p style='line-height:14.0pt;'>��</p>
              <p >ʹ�ø������ƵĲ�Ʒ����</p>
            </td>
            <td width=382 colspan=7 valign=top >&nbsp; </td>
            <td width=0 height=30 ></td>
          </tr>
          <tr> 
            
    <td width=277 colspan=4 valign=top height="474" > 
      <p >��</p>
              <p >�����ļ��嵥</p>
              <p >1��������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
              <p >2��ͼ����Ƭ&nbsp;&nbsp;&nbsp; ��&nbsp; 
                ÿ��&nbsp;&nbsp; ҳ</p>
              <p >3����Ҫ˵��&nbsp;&nbsp;&nbsp; ��&nbsp; 
                ÿ��&nbsp;&nbsp; ҳ</p>
            </td>
            
    <td width=189 colspan=4 valign=top height="474" > 
      <p >��</p>
              <p >�����ļ��嵥</p>
       
    </TD>
  </TR>���� 
</table>

    </TD>
  </TR>���� 
</table>

<?Button_Back_Law();?></body>

</html>