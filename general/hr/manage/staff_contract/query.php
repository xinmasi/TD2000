<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("��ͬ��Ϣ��ѯ");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<body class="bodycolor" topmargin="5">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("��ͬ��Ϣ��ѯ")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="search.php"  method="post" name="form1" >
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("��λԱ����")?></td>
      <td class="TableData">
        <input type="text" name="STAFF_NAME1" size="13" class="BigStatic" value="">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("��ͬ��ţ�")?></td>
      <td class="TableData">
        <INPUT type="text"name="STAFF_CONTRACT_NO" class=BigInput size="13" value="<?=$STAFF_CONTRACT_NO?>">
      </td>
   </tr>
   <tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("��ͬ���ͣ�")?></td>
      <td class="TableData">
        <select name="CONTRACT_TYPE" style="background: white;" title="<?=_("��ͬ���Ϳ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
          <option value=""><?=_("��ͬ����")?>&nbsp&nbsp&nbsp;</option>
          <?=hrms_code_list("HR_STAFF_CONTRACT1","")?>
        </select>
      </td>
   </tr>
    
    <tr>
      <td nowrap class="TableData"> <?=_("��ͬǩ�����ڣ�")?></td>
      <td class="TableData">
        <input type="text" id="start_time1" name="MAKE_CONTRACT1" size="10" maxlength="10" class="BigInput" value="<?=$MAKE_CONTRACT1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="MAKE_CONTRACT2" size="10" maxlength="10" class="BigInput" value="<?=$MAKE_CONTRACT2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time1\')}'})"/>       
      </td>
    </tr>
        <tr>
      <td nowrap class="TableData"> <?=_("���õ������ڣ�")?></td>
      <td class="TableData">
        <input type="text" id="start_time2" name="TRAIL_OVER_TIME1" size="10" maxlength="10" class="BigInput" value="<?=$TRAIL_OVER_TIME1?>" onClick="WdatePicker()"/> 
        <?=_("��")?>
        <input type="text" name="TRAIL_OVER_TIME2" size="10" maxlength="10" class="BigInput" value="<?=$TRAIL_OVER_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time2\')}'})"/> 
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ͬ�������ڣ�")?></td>
      <td class="TableData">
        <input type="text" id="start_time3" name="CONTRACT_END_TIME1" size="10" maxlength="10" class="BigInput" value="<?=$CONTRACT_END_TIME1?>" onClick="WdatePicker()"/> 
        <?=_("��")?>
        <input type="text" name="CONTRACT_END_TIME2" size="10" maxlength="10" class="BigInput" value="<?=$CONTRACT_END_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time3\')}'})"/>     
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ͬ������ڣ�")?></td>
      <td class="TableData">
        <input type="text"id="start_time4" name="CONTRACT_REMOVE_TIME1" size="10" maxlength="10" class="BigInput" value="<?=$CONTRACT_REMOVE_TIME1?>" onClick="WdatePicker()"/> 
        <?=_("��")?>
        <input type="text" name="CONTRACT_REMOVE_TIME2" size="10" maxlength="10" class="BigInput" value="<?=$CONTRACT_REMOVE_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time4\')}'})"/>     
      </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"><?=_("��ͬǩԼ��˾��")?></td>
         <td class="TableData">
           <select name="CONTRACT_ENTERPRIES" style="background: white;" title="<?=_("��ͬǩԼ��˾���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
             <option value=""><?=_("��ͬǩԼ��˾")?>&nbsp;&nbsp;</option>
             <?=hrms_code_list("HR_ENTERPRISE","")?>
           </select>
         </td>
   </tr>
   
   <tr>
        <td nowrap class="TableData"><?=_("��ͬ�������ԣ�")?></td>
         <td class="TableData">
            <select name="CONTRACT_SPECIALIZATION" id="CONTRACT_SPECIALIZATION" onchange="TIME_LIMIT()">
               <option value=""><?=_("��ͬ��������")?></option>
                <option value="1"><?=_("�̶�����")?></option>
               <option value="2"><?=_("�޹̶�����")?></option>
               <option value="3"><?=_("�����һ����������Ϊ����")?></option>
           </select>
         </td>
   </tr>
   
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("��ѯ")?>" class="BigButton">
      </td>
    </tr>
  </form>
 </table>


</body>
</html>