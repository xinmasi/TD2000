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
-->
</STYLE>

<BODY class="bodycolor">
<BR>
<table width="500" class="TableBlock" align="center">
  <tr> 
	<td height="27" class="TableHeader"> 
	  <div align="center">����ר��������</font></div>
	</td>
  </tr>
  <tr> 
	<td height="54" valign="top" class="TableData"> 
	  <p><br>
		�����밴�ձ����桰���ע�������ȷ��д�������&nbsp;&nbsp;&nbsp; �˿�������ר������д</p>
	  <table width="600" border="0" cellspacing="0" cellpadding="0" height="22" align="center">
		<tr> 
		  <td width="379">&nbsp;</td>
		  <td width="221"> 
			<p style='line-height:14.0pt;'>���ռ��˵�ַ</p>
			<p style='line-height:14.0pt;'>���ռ�������</p>
		  </td>
		</tr>
	  </table>
	  <table border=1 cellspacing=0 cellpadding=0 width=599 align="center">
		<tr> 
		  <td width=79 rowspan=2 > 
			<p >�ݷ���</p>
			<p >����</p>
		  </td>
		  <td width=311 colspan=4 rowspan=2 valign=top >&nbsp; </td>
		  <td width=209 colspan=3 valign=top > 
			<p >������ţ�������</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=209 colspan=3 valign=top > 
			<p >�ڷְ��ύ��</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=79 rowspan=2 > 
			<p >�޷�����</p>
		  </td>
		  <td width=311 colspan=4 rowspan=2 valign=top >&nbsp; </td>
		  <td width=209 colspan=3 valign=top > 
			<p >��������</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=209 colspan=3 valign=top > 
			<p >��</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=79 rowspan=6 > 
			<p >��������</p>
		  </td>
		  <td width=520 colspan=7 valign=top > 
			<p >���������ƣ�����&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			  ���������ڵع���</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=520 colspan=7 valign=top > 
			<p >��������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ��ַ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			  ��ϵ������</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=520 colspan=7 valign=top > 
			<p >����������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			  ���������ڵع���</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=520 colspan=7 valign=top > 
			<p >��������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ��ַ</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=520 colspan=7 valign=top > 
			<p >����������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			  &nbsp;&nbsp;&nbsp;���������ڵع���</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=520 colspan=7 valign=top > 
			<p >��������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ��ַ</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=79 rowspan=3 > 
			<p >��ר����</p>
			<p >�����</p>
		  </td>
		  <td width=520 colspan=7 valign=top > 
			<p >����&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			  �����������</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=520 colspan=7 valign=top > 
			<p >��������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ��ַ</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=520 colspan=7 valign=top > 
			<p >����������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			  ������֤���&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; �绰</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=79 rowspan=2 > 
			<p >�����</p>
			<p >����</p>
		  </td>
		  <td width=520 colspan=7 valign=top > 
			<p >���ص�λ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			  ��ַ</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=520 colspan=7 valign=top > 
			<p >��������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			  ���ر��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
			  ��������</p>
		  </td>
		  <td width=0 height=34 ></td>
		</tr>
		<tr> 
		  <td width=79 rowspan=2 valign=top > 
			<p >��ְ�</p>
			<p >����</p>
		  </td>
		  <td width=311 colspan=4 valign=top > 
			<p >ԭ�������</p>
		  </td>
		  <td width=74 rowspan=4 > 
			<p >��</p>
			<p >��ɥʧ</p>
			<p >��ӱ��</p>
			<p >������</p>
			<p >����</p>
		  </td>
		  <td width=134 rowspan=4 > 
			<p  style='line-height:15.0pt;'><span
  style='font-family:����;"Times New Roman"'>�������й������������ϵĹ���չ�������״�չ��</span></p>
			<p  style='line-height:15.0pt;'><span
  style='font-family:����;"Times New Roman"'>�����ڹ涨��ѧ����������������״η���</span></p>
			<p style='line-height:15.0pt;'>��</p>
		  </td>
		  <td width=0 >&nbsp; </td>
		</tr>
		<tr> 
		  <td width=311 colspan=4 valign=top > 
			<p >ԭ��������</p>
		  </td>
		  <td width=0 height=29 ></td>
		</tr>
		<tr> 
		  <td width=79 rowspan=4 valign=top > 
			<p >��Ҫ��</p>
			<p >����</p>
			<p >Ȩ��</p>
			<p >��</p>
		  </td>
		  <td width=96 valign=top > 
			<p >����������</p>
		  </td>
		  <td width=103 valign=top > 
			<p >���������</p>
		  </td>
		  <td width=112 valign=top > 
			<p >�����������</p>
		  </td>
		  <td width=0 height=29 ></td>
		</tr>
		<tr> 
		  <td width=96 rowspan=3 valign=top >&nbsp; </td>
		  <td width=103 rowspan=3 valign=top >&nbsp; </td>
		  <td width=112 rowspan=3 valign=top >&nbsp; </td>
		  <td width=0 height=29 ></td>
		</tr>
		<tr> 
		  <td width=74 > 
			<p >�ѱ���</p>
			<p >����</p>
		  </td>
		  <td width=134 > 
			<p style='line-height:16.0pt;'>����ר����������漰�����ش����棬�����ܴ���</p>
		  </td>
		  <td width=0 height=95 ></td>
		</tr>
		<tr> 
		  <td width=209 colspan=3 valign=top > 
			<p >��Ȩ��Ҫ������ÿ��&nbsp;&nbsp; ��</p>
			<p >˵����&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ÿ��&nbsp;&nbsp;&nbsp; ��</p>
			<p >˵���鸽ͼ&nbsp;&nbsp; ÿ��&nbsp;&nbsp;&nbsp; ��</p>
		  </td>
		  <td width=0 height=30 ></td>
		</tr>
		<tr> 
		  <td width=79 valign=top > 
			<p >�ӷ���</p>
			<p >����</p>
		  </td>
		  <td width=520 colspan=7 valign=top >&nbsp; </td>
		  <td width=0 height=30 ></td>
		</tr>
		<tr> 
		  <td width=247 colspan=3 valign=top >&nbsp; </td>
		  <td width=199 colspan=3 valign=top >&nbsp; </td>
		  <td width=153 valign=top >&nbsp; </td>
		  <td width=0 height=8 ></td>
		</tr>
		<tr> 
		  <td width=247 colspan=3 valign=top > 
			<p >��</p>
			<p >�����ļ��嵥</p>
			<p >1��������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
			<p >2��Ȩ��Ҫ����&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
			<p >3��˵����&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
			<p >4��˵���鸽ͼ&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
			<p >5��˵����ժҪ&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
			<p >6��ժҪ��ͼ&nbsp;&nbsp;&nbsp; ��&nbsp; ÿ��&nbsp;&nbsp; ҳ</p>
		  </td>
		  <td width=199 colspan=3 valign=top > 
			<p >��</p>
			<p >�����ļ��嵥</p>
			<p >��ר������ί����</p>
			<p >�����ü���������</p>
			<p >�����������ļ�����</p>
			<p >��</p>
			<p >��</p>
			<p >��</p>
		  </td>
		  <td width=153 valign=top > 
			<p >��</p>
			<p >ר��������</p>
		  </td>
		  <td width=0 height=30 ></td>
		</tr>
		<tr> 
		  <td width=599 colspan=8 valign=top > 
			<p >��&nbsp; �����˻�������ǩ��</p>
		  </td>
		  <td width=0 height=30 ></td>
		</tr>
		<tr> 
		  <td width=599 colspan=8 valign=top > 
			<p >��&nbsp; �ռ��˵�ַ������</p>
			<table cellpadding=0 cellspacing=0 align=left>
			  <tr> 
				<td width=0 height=9 ></td>
				<td width=207 ></td>
				<td width=9 ></td>
				<td width=146 ></td>
			  </tr>
			  <tr> 
				<td height=116 ></td>
				<td rowspan=2 width=207 height=117 align=left valign=top bgcolor=white > 
				  <table cellpadding=0 cellspacing=0 width="100%">
					<tr> 
					  <td > 
						<p>&nbsp; ������������</p>
					  </td>
					</tr>
				  </table>
				</td>
				<td ></td>
				<td width=146 height=116 align=left valign=top bgcolor=white >&nbsp;</td>
			  </tr>
			  <tr> 
				<td height=1 ></td>
			  </tr>
			</table>
			  </td>
		  <td width=0 height=30 ></td>
		</tr>
		<tr height=0> 
		  <td width=79 ></td>
		  <td width=96 ></td>
		  <td width=72 ></td>
		  <td width=31 ></td>
		  <td width=112 ></td>
		  <td width=56 ></td>
		  <td width=18 ></td>
		  <td width=134 ></td>
		  <td width=0 height=30 ></td>
		</tr>
	  </table>
	</td>
  </tr>
</table>
<?Button_Back_Law();?></body>
</html>
