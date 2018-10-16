<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("�½����µ�����Ϣ");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
   if(document.form1.TRANSFER_PERSON.value=="")
   {
      alert("<?=_("��ѡ�������Ա��")?>");
      return (false);
   }
   if(document.form1.TRAN_DEPT_BEFORE.value=="")
   {
      alert("<?=_("����ǰ���ڲ��Ų���Ϊ�գ�")?>");
      return (false);
   }
   if(document.form1.TRAN_DEPT_AFTER.value=="")
   {
      alert("<?=_("���������ڲ��Ų���Ϊ�գ�")?>");
      return (false);
   }
   if(document.form1.TRANSFER_DATE.value!="" && document.form1.TRANSFER_EFFECTIVE_DATE.value!="" && document.form1.TRANSFER_DATE.value > document.form1.TRANSFER_EFFECTIVE_DATE.value)
   {
      alert("<?=_("������Ч���ڲ���С�ڵ������ڣ�")?>");
      return (false);
   }
 return (true);
}
function upload_attach()
{
  if(CheckForm())
   {
     document.form1.submit();
   }
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?TRANSFER_ID=<?=$TRANSFER_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}

function LoadDialogWindowTSfer(URL, parent, loc_x, loc_y, width, height)
{
  if(window.showModalDialog)//window.open(URL);
     window.showModalDialog(URL,parent,"edge:raised;scroll:1;status:0;help:0;resizable:1;dialogWidth:"+width+"px;dialogHeight:"+height+"px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px",true);
  else
     window.open(URL,"load_dialog_win","height="+height+",width="+width+",status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
}
function SelectUserSingleTSfer(MODULE_ID,TO_ID, TO_NAME, MANAGE_FLAG, FORM_NAME)
{
    URL="user_select_single/?MODULE_ID="+MODULE_ID+"&TO_ID="+TO_ID+"&TO_NAME="+TO_NAME+"&MANAGE_FLAG="+MANAGE_FLAG+"&FORM_NAME="+FORM_NAME;

    var loc_y = (window.screen.height-30-350)/2; //��ô��ڵĴ�ֱλ��;
    var loc_x = (window.screen.width-10-400)/2; //��ô��ڵ�ˮƽλ��;

    LoadDialogWindow(URL,self,loc_x, loc_y, 400, 350);
}
function read_position(var_value)
{
	_get("dept_ajax.php","TRANSFER_PERSON="+var_value, show_position);
}
function show_position(req)
{
   if(req.status==200)
   {
      document.getElementById("TRAN_POSITION_BEFORE").value=req.responseText;
   }

}
</script>

<body class="bodycolor" topmargin="5">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½����µ�����Ϣ")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="add.php"  method="post" name="form1" onSubmit="return CheckForm();">
<table class="TableBlock" width="80%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("������Ա��")?></td>
	  <td class="TableData" >
        <input type="hidden" name="TRANSFER_PERSON" value="<?=$TRANSFER_PERSON?>">
        <input cols="30" name="TRANSFER_PERSON_NAME" rows="3" class="BigStatic" readonly=""  >&nbsp;
        <a href="javascript:;" class="orgAdd" onclick="SelectUserSingleTSfer('','TRANSFER_PERSON', 'TRANSFER_PERSON_NAME','1')"><?=_("ѡ��")?></a>
      </td>
      <td nowrap class="TableData"> <?=_("�������ͣ�")?></td>
      <td class="TableData" >
        <select name="TRANSFER_TYPE" style="background: white;" title="<?=_("�������Ϳ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
          <option value=""><?=_("��ѡ��")?></option>
          <?=hrms_code_list("HR_STAFF_TRANSFER","")?>
        </select>
      </td>
    </tr>
    <tr>
    	 <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
      <td class="TableData">
       <input type="text" name="TRANSFER_DATE" size="15" maxlength="10" class="BigInput" id="transfer_start_time" value="<?=$TRANSFER_DATE?>" onClick="WdatePicker()"/>
      </td>
    	<td nowrap class="TableData"><?=_("������Ч���ڣ�")?></td>
      <td class="TableData">
       <input type="text" name="TRANSFER_EFFECTIVE_DATE" size="15" maxlength="10" class="BigInput" value="<?=$TRANSFER_EFFECTIVE_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'transfer_start_time\')}'})"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ǰ��λ��")?></td>
      <td class="TableData">
        <INPUT type="text"name="TRAN_COMPANY_BEFORE" class=BigInput size="15" value="<?=$TRAN_COMPANY_BEFORE?>">
      </td>
      <td nowrap class="TableData"><?=_("������λ��")?></td>
      <td class="TableData">
        <INPUT type="text"name="TRAN_COMPANY_AFTER" class=BigInput size="15" value="<?=$TRAN_COMPANY_AFTER?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ǰְ��")?></td>
      <td class="TableData">
        <INPUT type="text"name="TRAN_POSITION_BEFORE" id="TRAN_POSITION_BEFORE" class=BigInput size="15" value="<?=$TRAN_POSITION_BEFORE?>">
      </td>
      <td nowrap class="TableData"><?=_("������ְ��")?></td>
      <td class="TableData">
        <INPUT type="text"name="TRAN_POSITION_AFTER" class=BigInput size="15" value="<?=$TRAN_POSITION_AFTER?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ǰ���ţ�")?></td>
      <td class="TableData" >
    	  <input type="hidden" name="TRAN_DEPT_BEFORE">
        <input type="text" name="TRAN_DEPT_BEFORE_NAME" value="" class=BigStatic size=15 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','TRAN_DEPT_BEFORE','TRAN_DEPT_BEFORE_NAME')"><?=_("ѡ��")?></a>
      </td>
      <td nowrap class="TableData"><?=_("�������ţ�")?></td>
      <td class="TableData">
    	  <input type="hidden" name="TRAN_DEPT_AFTER">
        <input type="text" name="TRAN_DEPT_AFTER_NAME" value="" class=BigStatic size=15 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','TRAN_DEPT_AFTER','TRAN_DEPT_AFTER_NAME')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><span ><?=_("�������ɫ��")?></td>
      <td nowrap class="TableData">
          <select name="role" style="background: white;">
              <option value="">��ѡ���ɫ</option>
      <?
        $query = "SELECT USER_PRIV,PRIV_NAME from  user_priv;";
        $cursor= exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
        {
            if($_SESSION["LOGIN_USER_PRIV"]=="1")
            {
      ?>
                <option value=<?=$ROW["USER_PRIV"]?>><?=$ROW["PRIV_NAME"]?>&nbsp&nbsp;</option>
      <?
            }
            else
            {
                $query2 = "SELECT * from  hr_role_manage WHERE FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',HR_ROLE_MANAGE);";
                $cursor2= exequery(TD::conn(),$query2);
                while($ROW2=mysql_fetch_array($cursor2))
                {
                    $NEW_NAME_ARRAY=  explode(',', $ROW2["HR_USER_PRIV"]);
                    if(in_array($ROW["USER_PRIV"],$NEW_NAME_ARRAY))
                    {
                        $NEW_NAME="USER_P".$ROW["USER_PRIV"];
                        $$NEW_NAME=1;
                    }
                }
            ?>
            <option value=<?=$ROW["USER_PRIV"]?> class="<?$NEW_NAME="USER_P".$ROW["USER_PRIV"]; if($$NEW_NAME != 1) echo _("xinxiyinchneg") ?>"><?=$ROW["PRIV_NAME"]?>&nbsp&nbsp;</option>;
            <?
            }
        }
      ?>
        </select>
        <span style="font-size: 12px;color: #666666;">(��ѡ���ɫ���û�������ԭʼ��ɫ)</span>
      </td>
      <td nowrap class="TableData"></td>
      <td nowrap class="TableData"></td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("������������")?></td>
      <td class="TableData" colspan=3>
        <textarea name="MATERIALS_CONDITION" cols="70" rows="3" class="BigInput" value=""><?=$MATERIALS_CONDITION?></textarea>
      </td>
    </tr>
   <tr>
      <td nowrap class="TableData"><?=_("��ע��")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="70" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
      </td>
    </tr>
    <tr height="25" id="attachment1">
      <td nowrap class="TableData" ><span id="ATTACH_LABEL"><?=_("�����ϴ���")?></span></td>
      <td class="TableData"colspan=3>
        <script>ShowAddFile();</script>
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("���ѣ�")?></td>
      <td class="TableData" colspan=3>
<?=sms_remind(56);?>
      </td>
     </tr>
     <tr id="EDITOR">
      <td class="TableData" colspan="4"> <?=_("����ԭ��")?>
<?
$editor = new Editor('TRAN_REASON') ;
$editor->Height = '200';
$editor->Config = array("EditorAreaStyles" => "body{font-size:20pt;}","model_type" => "14");
$editor->Value = $TRAN_REASON ;
//$editor->Config = array('model_type' => '14') ;
$editor->Create() ;
?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
        <input type="submit" value="<?=_("����")?>" class="BigButton">
      </td>
    </tr>
  </table>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $(".xinxiyinchneg").remove();
});
</script>
</body>
</html>