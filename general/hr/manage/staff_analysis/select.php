<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("ͳ�Ʒ���");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
function sel_change()
{
  var e1=document.getElementsByName("SUMFIELD").item(2);
  var e2=document.getElementsByName("SUMFIELD").item(10);
  if(e1.checked && !e2.checked)
  {
  	 document.getElementsByName("AGE").style.display="";
  	 document.getElementsByName("AGE1").style.display="none";
  }
  else if(e2.checked && ! e1.checked)
  	{
  	 document.getElementsByName("AGE1").style.display="";
  	 document.getElementsByName("AGE").style.display="none";
		}
  else
  	{
  	 document.getElementsByName("AGE").style.display="none";
  	 document.getElementsByName("AGE1").style.display="none";
  	}
}

function leave_change()
{
  var el=document.getElementsByName("SUMFIELD").item(1);
  if(el.checked)
  	 document.getElementsByName("LEAVE").style.display="";
  else
  	 document.getElementsByName("LEAVE").style.display="none";
}
function clk_submit()
{
	var all_clk=document.getElementsByName("MAP_TYPE");
	for(var i=0;i<all_clk.length;i++)
	{
		if(all_clk[i].checked)
    {
    	 var e_action = "";
    	 if(i==0)
    	    e_action = "analysis.php?CHOSE=1";
    	 else if(i==1)
    	 	  e_action = "analysis.php?CHOSE=2";
    	 else if(i==2)
    	 	  e_action = "analysis.php?CHOSE=3";
    	 //else
    	 	 // e_action = "line.php";
       document.form1.action=e_action;
       document.form1.submit();
       break;
    }

	}
}
</script>


<body class="bodycolor">

<form action="analysis.php" method="post" name="form1" target="tu_main">
<table align="center" width="100%" class="TableBlock">
<?
if($MODULE=="HR_INFO")
{
?>
    <tr>
      <td nowrap class="TableContent" width="80" ><?=_("������Ϣ��")?></td>
      <td class="TableData" nowrap   colspan="3">
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked onClick="sel_change()"><label for="SUMFIELD1"><?=_("ѧ��")?></label>&nbsp;
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD11' value="11" onClick="sel_change()"><label for="SUMFIELD11"><?=_("ѧλ")?></label>&nbsp;
         <input type="radio" name='SUMFIELD' id='SUMFIELD2' value="2" onclick="sel_change()"><label for="SUMFIELD2"><?=_("����")?></label>&nbsp;
         <span style="display:none" id="AGE">
         	<input type="text" name="AGE_RANGE" size="10" maxlength="100" class="BigInput" value="0-25,26-30,31-35,36-40,41-45,46-50,51-55,56-60">
         </span>
         <input type="radio" name='SUMFIELD' id='SUMFIELD3' value="3" onClick="sel_change()"><label for="SUMFIELD3"><?=_("�Ա�")?></label>&nbsp;
         <input type="radio" name='SUMFIELD' id='SUMFIELD4' value="4" onClick="sel_change()"><label for="SUMFIELD4"><?=_("������ò")?></label>&nbsp;
         <input type="radio" name='SUMFIELD' id='SUMFIELD9' value="5" onClick="sel_change()"><label for="SUMFIELD9"><?=_("��ְ״̬")?></label>&nbsp;
         <input type="radio" name='SUMFIELD' id='SUMFIELD5' value="6" onClick="sel_change()"><label for="SUMFIELD5"><?=_("����")?></label>&nbsp;
         <input type="radio" name='SUMFIELD' id='SUMFIELD6' value="7" onClick="sel_change()"><label for="SUMFIELD6"><?=_("ְ��")?></label>&nbsp;
         <input type="radio" name='SUMFIELD' id='SUMFIELD7' value="8" onClick="sel_change()"><label for="SUMFIELD7"><?=_("Ա������")?></label>&nbsp;
         <input type="radio" name='SUMFIELD' id='SUMFIELD8' value="9" onClick="sel_change()"><label for="SUMFIELD8"><?=_("���뱾��λʱ��")?></label>&nbsp;
         <input type="radio" name='SUMFIELD' id='SUMFIELD10' value="10" onClick="sel_change()"><label for="SUMFIELD10"><?=_("����λ����")?></label>&nbsp;
         <span style="display:none" id="AGE1">
         	<input type="text" name="AGE_RANGE1" size="10" maxlength="100" class="BigInput" value="0-1,2-3,4-6,7-9,10-12,13-15,16-18,19-21,22-30">
         </span>
      </td>
    </tr>
<?
}
if($MODULE=="HR_HT")
{
?>
    <tr>
      <td nowrap class="TableContent" width="80" ><?=_("������Ϣ��")?></td>
      <td class="TableData" nowrap   colspan="3">
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked><label for="SUMFIELD1"><?=_("��ͬ����")?></label>&nbsp;
         <input type="radio" name='SUMFIELD' id='SUMFIELD2' value="2" ><label for="SUMFIELD2"><?=_("��ͬ״̬")?></label>&nbsp;
         <input type="radio" name='SUMFIELD' id='SUMFIELD3' value="3" ><label for="SUMFIELD3"><?=_("��ͬ����")?></label>&nbsp;
         <input type="radio" name='SUMFIELD' id='SUMFIELD4' value="4" ><label for="SUMFIELD3"><?=_("ǩ������")?></label>&nbsp;
      </td>
    </tr>
<?
}
if($MODULE=="HR_JC")
{
?>
    <tr>
      <td nowrap class="TableContent" width="80" ><?=_("������Ϣ��")?></td>
      <td class="TableData" nowrap   colspan="3">
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked><label for="SUMFIELD1"><?=_("������Ŀ")?></label>&nbsp;
         <input type="radio" name='SUMFIELD' id='SUMFIELD2' value="2" ><label for="SUMFIELD2"><?=_("��������")?></label>&nbsp;
      </td>
    </tr>
<?
}
if($MODULE=="HR_ZZ")
{
?>
    <tr>
      <td nowrap class="TableContent" width="80" ><?=_("������Ϣ��")?></td>
      <td class="TableData" nowrap   colspan="3">
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked><label for="SUMFIELD1"><?=_("֤������")?></label>&nbsp;
         <input type="radio" name='SUMFIELD' id='SUMFIELD2' value="2" ><label for="SUMFIELD2"><?=_("֤��״̬")?></label>&nbsp;
      </td>
    </tr>

<?
}
if($MODULE=="HR_XX")
{
?>
    <tr>
      <td nowrap class="TableContent" width="80" ><?=_("������Ϣ��")?></td>
      <td class="TableData" nowrap   colspan="3">
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked><label for="SUMFIELD1"><?=_("����ѧλ")?></label>&nbsp;
      </td>
    </tr>

<?
}
if($MODULE=="HR_JL")
{
?>
    <tr>
      <td nowrap class="TableContent" width="80" ><?=_("������Ϣ��")?></td>
      <td class="TableData" nowrap   colspan="3">
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked><label for="SUMFIELD1"><?=_("���ڲ���")?></label>&nbsp;
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD2' value="2" ><label for="SUMFIELD2"><?=_("����ְλ")?></label>&nbsp;
      </td>
    </tr>

<?
}
if($MODULE=="HR_JN")
{
?>
    <tr>
      <td nowrap class="TableContent" width="80" ><?=_("������Ϣ��")?></td>
      <td class="TableData" nowrap   colspan="3">
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked><label for="SUMFIELD1"><?=_("��������")?></label>&nbsp;
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD2' value="2" ><label for="SUMFIELD2"><?=_("���ܼ���")?></label>&nbsp;
      </td>
    </tr>

<?
}
if($MODULE=="HR_GX")
{
?>
    <tr>
      <td nowrap class="TableContent" width="80" ><?=_("������Ϣ��")?></td>
      <td class="TableData" nowrap   colspan="3">
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked><label for="SUMFIELD1"><?=_("�뱾�˹�ϵ")?></label>&nbsp;
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD2' value="2" ><label for="SUMFIELD2"><?=_("������ò")?></label>&nbsp;
      </td>
    </tr>

<?
}
if($MODULE=="HR_DD")
{
?>
    <tr>
      <td nowrap class="TableContent" width="80" ><?=_("������Ϣ��")?></td>
      <td class="TableData" nowrap   colspan="3">
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked><label for="SUMFIELD1"><?=_("��������")?></label>&nbsp;
      </td>
    </tr>

<?
}
if($MODULE=="HR_LZ")
{
?>
    <tr>
      <td nowrap class="TableContent" width="80" ><?=_("������Ϣ��")?></td>
      <td class="TableData" nowrap   colspan="3">
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked><label for="SUMFIELD1"><?=_("��ְ����")?></label>&nbsp;
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD2' value="2" onclick="leave_change()"><label for="SUMFIELD2"><?=_("��ְ���")?></label>&nbsp;
         <span style="display:none" id="LEAVE">
         	<input type="text" name="LEAVE_RANGE" size="20" maxlength="100" class="BigInput" value="1990-1995,1996-2000,2001-2005,2006-2008,2009-2011,2012-2014">
         </span>
      </td>
    </tr>

<?
}
if($MODULE=="HR_FZ")
{
?>
    <tr>
      <td nowrap class="TableContent" width="80" ><?=_("������Ϣ��")?></td>
      <td class="TableData" nowrap   colspan="3">
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked><label for="SUMFIELD1"><?=_("��ְ����")?></label>&nbsp;
      </td>
    </tr>

<?
}
if($MODULE=="HR_ZC")
{
?>
    <tr>
      <td nowrap class="TableContent" width="80" ><?=_("������Ϣ��")?></td>
      <td class="TableData" nowrap   colspan="3">
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked><label for="SUMFIELD1"><?=_("��ȡ��ʽ")?></label>&nbsp;
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD2' value="2" ><label for="SUMFIELD2"><?=_("��ȡְ��")?></label>&nbsp;
      </td>
    </tr>

<?
}
if($MODULE=="HR_GH")
{
?>
    <tr>
      <td nowrap class="TableContent" width="60" ><?=_("������Ϣ��")?></td>
      <td class="TableData" nowrap   colspan="3">
      	 <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked><label for="SUMFIELD1"><?=_("�ػ�����")?></label>&nbsp;
      </td>
    </tr>

<?
}
?>
    <tr>
      <td nowrap class="TableContent" width="60"> <?=_("ͳ��ͼ��")?></td>
      <td class="TableData" nowrap>
         <input type="radio" name='MAP_TYPE' id='MAP_TYPE1' onClick="clk_submit()" value="1" checked><label for="MAP_TYPE1"><?=_("��ͼ")?></label>&nbsp;
         <input type="radio" name='MAP_TYPE' id='MAP_TYPE2' onClick="clk_submit()" value="2"><label for="MAP_TYPE2"><?=_("��״ͼ")?></label>&nbsp;
         <input type="radio" name='MAP_TYPE' id='MAP_TYPE3' onClick="clk_submit()" value="3"><label for="MAP_TYPE3"><?=_("ͳ�Ʊ�")?></label>&nbsp;
        <!-- <input type="radio" name='MAP_TYPE' id='MAP_TYPE3' value="3"><label for="MAP_TYPE3"><?=_("��״ͼ")?></label>&nbsp;      	-->
      </td>
      <td class="TableContent" width="80"> <?=_("�������ţ�")?></td>
      <td class="TableData" >
      	<input type="hidden" name="TO_ID">
		    <textarea cols=20 name=TO_NAME rows="2" style="margin-bottom:-5px" class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
      	<input type="hidden" name="MODULE" value="<?=$MODULE?>">
        <input type="button" value="<?=_("ȷ��")?>"  onClick="clk_submit()" class="BigButton">&nbsp;&nbsp;
    </tr>
    </table>
</form>
</body>
</html>
