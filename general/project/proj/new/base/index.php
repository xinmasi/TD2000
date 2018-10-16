<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/td_core.php");
include_once("inc/editor.php");
include_once("inc/utility_file.php");
include_once("inc/sys_code_field.php");

include_once("inc/utility_project.php");

$priv = check_project_priv();

if($priv == 2)
{
	$show_noapp=_("�����");
}

if($PROJ_ID)
{
	$query = "select * from PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID'";
	$cursor = exequery(TD::conn(), $query);
	if($ROW=mysql_fetch_array($cursor))
	{
		$PROJ_NUM = $ROW["PROJ_NUM"];
		$PROJ_NAME = $ROW["PROJ_NAME"];
		$PROJ_TYPE = $ROW["PROJ_TYPE"];
		$PROJ_DEPT = $ROW["PROJ_DEPT"];
		$PROJ_DESCRIPTION = $ROW["PROJ_DESCRIPTION"];
		$PROJ_OWNER = $ROW["PROJ_OWNER"];
		$PROJ_LEADER = $ROW["PROJ_LEADER"];
		$PROJ_VIEWER = $ROW["PROJ_VIEWER"];
		$PROJ_MANAGER = $ROW["PROJ_MANAGER"];
		$PROJ_START_TIME = $ROW["PROJ_START_TIME"];
		$PROJ_END_TIME = $ROW["PROJ_END_TIME"];
		$PROJ_USER = $ROW["PROJ_USER"];
		$PROJ_STATUS = $ROW["PROJ_STATUS"];
		$ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
		$ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
	}

  //-- ��Ŀ���������� --
  $query = "select USER_NAME from USER WHERE USER_ID='$PROJ_OWNER'";
  $cursor = exequery(TD::conn(), $query);
  while($ROW=mysql_fetch_array($cursor))
    $PROJ_OWNER_NAME .= $ROW["USER_NAME"];
  
  //-- ��Ŀ�鿴������ --
  if($PROJ_VIEWER)
  {
    $query = "select USER_NAME from USER WHERE FIND_IN_SET(USER_ID,'$PROJ_VIEWER')";
    $cursor = exequery(TD::conn(), $query);
    while($ROW=mysql_fetch_array($cursor))
      $PROJ_VIEWER_NAME .= $ROW["USER_NAME"].",";
  }
  //-- ��Ŀ�鸺�������� --
  if($PROJ_LEADER)
  {
    $query = "select USER_NAME from USER WHERE USER_ID='$PROJ_LEADER'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW=mysql_fetch_array($cursor))
      $PROJ_LEADER_NAME = $ROW["USER_NAME"];
  }
  
  //-- ��Ŀ���������� --
  if($PROJ_MANAGER)
  {
    $query = "select USER_NAME from USER WHERE USER_ID='$PROJ_MANAGER'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW=mysql_fetch_array($cursor))
      $PROJ_MANAGER_NAME = $ROW["USER_NAME"];
  }
  
  $query = "select COUNT(*) from PROJ_TASK WHERE PROJ_ID='$PROJ_ID'";
  $cursor = exequery(TD::conn(), $query);
  if($ROW=mysql_fetch_array($cursor))
     $TASK_COUNT=$ROW[0];
  
  if($PROJ_DEPT)
  {
  	if($PROJ_DEPT=="ALL_DEPT")
  	   $PROJ_DEPT_NAME=_("ȫ�岿��");
  	else
  	{
    	$query = "select DEPT_NAME from DEPARTMENT WHERE FIND_IN_SET(DEPT_ID,'$PROJ_DEPT')";
      $cursor = exequery(TD::conn(), $query);
      while($ROW = mysql_fetch_array($cursor))
         $PROJ_DEPT_NAME .= $ROW["DEPT_NAME"].",";
    }
  } 
}	
//����Ƿ�����ύ����
$IS_OK=0;
if(!$PROJ_ID)
   $MSG = _("��δ������Ŀ��Ϣ�������ύ��������");
elseif(!$PROJ_USER)
   $MSG = _("��δ������Ŀ��Ա�������ύ��������");
elseif(!$PROJ_MANAGER)
   $MSG = _("��δ����������Ա�������ύ��������");
elseif($TASK_COUNT==0)
   $MSG = _("��δ������Ŀ���񣬲����ύ��������");
else
   $IS_OK=1;

$IMPORTANT_INFO='<span style="color:red">(*)</span>';

$HTML_PAGE_TITLE = _("��Ŀ������Ϣ");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";

function get_define_type(CODE_ID,PROJ_ID)
{
  _get('/inc/sys_code_field_get.php',"CODE_ID="+CODE_ID+"&PROJ_ID="+PROJ_ID,callback);
}

function callback(req){
   if(req.status==200)
   {
      if(req.responseText)
      {
        	document.getElementById("DEFINE_SYSCODE_CONTENT").innerHTML = req.responseText;
        	document.getElementById("DEFINE_SYSCODE_CONTENT").style.display = '';
      }else{
         document.getElementById("DEFINE_SYSCODE_CONTENT").innerHTML = '';
        	document.getElementById("DEFINE_SYSCODE_CONTENT").style.display = 'none';   
      }
   }   
}
function InsertImage(src)
{
   AddImage2Editor('PROJ_DESCRIPTION', src);
}
function check()
{
<?
  if($IS_OK==0)
  {
?>
    alert("<?=$MSG?>");
    return(false);
<?
  }
  else
  {
?>
<?if($show_noapp):?>
   msg='<?=_("������������Χ�ڣ�ȷ��Ҫֱ��������")?>';
<?else:?>
   msg='<?=_("ȷ��Ҫ�ύ��Ŀ������")?>';
<?endif;?>
   if(window.confirm(msg))
   {
  	document.form1.action="approve.php";
  	document.form1.submit();
   }
<?
  }
?> 
}
function check_form()
{
	if(document.form1.PROJ_NUM.value=="" || document.form1.PROJ_NAME.value=="" || document.form1.PROJ_START_TIME.value=="" || document.form1.PROJ_END_TIME.value=="")
	{
	   alert("<?=_("������Ŀ����Ϊ�գ�")?>");
	   return(false);
	}
   var starttime=new Date((document.form1.PROJ_START_TIME.value).replace(/-/g,"/"));
   var endtime=new Date((document.form1.PROJ_END_TIME.value).replace(/-/g,"/"));
   if(endtime<starttime)
   {
      alert("<?=_("��Ŀ�ƻ����ڵĽ���ʱ�䲻��С�ڿ�ʼʱ�䣡")?>");
      return(false);
   }
	_get("check_proj_no.php","PROJ_NUM="+document.form1.PROJ_NUM.value+"&PROJ_ID="+document.form1.PROJ_ID.value, check_ret);
}

function check_ret(req)
{
   if(req.status==200)
   {
      if(req.responseText!="OK")
      {
        	document.getElementById("check_msg").innerHTML="<font color='red'>"+req.responseText+"</font>";
        	document.form1.PROJ_NUM.focus();
      }
      else
      {
        	document.getElementById("check_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/rule1.gif' align='absmiddle' title='<?=_("��Ŀ��ſ���")?>' />";
        	document.form1.submit();
      }
   }
}

function proj_export()
{
<?
  if(!$PROJ_ID)
  {
?>
    alert("<?=_("���ȱ�����Ŀ������Ϣ��")?>");
    return(false);
<?
  }
?>
	  location='export.php?PROJ_ID=<?=$PROJ_ID?>';
}

function delete_attach(ATTACHMENT_ID, ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
     //set_iframe_hidden_value();

     //��ɾ���ĸ�����Ϣ��ֵ��form2��hidden�� by dq 090616
     document.getElementById("DEL_ATTACHMENT_ID_FORM2").value = ATTACHMENT_ID;
     document.getElementById("DEL_ATTACHMENT_NAME_FORM2").value = URLSpecialChars(ATTACHMENT_NAME);

     document.form2.action = "delete_attach.php";
     //form2.target = "_blank";
     document.form2.submit();
  }
}
//------------zfc--------------
var p_r_i = false;
function proj_import()
{
    if(p_r_i)
        p_r_i.close();
  URL="import.php?PROJ_ID=<?=$PROJ_ID?>";
  myleft=(screen.availWidth-400)/2;
  mytop=100
  mywidth=400;
  myheight=550;
  p_r_i = window.open(URL,"","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

/**
 * �����ص�form2���hidden�ֵ�������ϵ���ʾ����� by dq 090605
 * �Զ������ɾ������ʱ�������������
 */
function set_iframe_hidden_value()
{
  //------- �����ϵ���Ŀ������Ϣ�ֶθ�form2���hidden�� -------
  document.getElementById("PROJ_NUM_FORM2").value = document.getElementById("PROJ_NUM").value;
  document.getElementById("PROJ_NAME_FORM2").value = document.getElementById("PROJ_NAME").value;
  document.getElementById("PROJ_START_TIME_FORM2").value = document.getElementById("PROJ_START_TIME").value;
  document.getElementById("PROJ_END_TIME_FORM2").value = document.getElementById("PROJ_END_TIME").value;
  document.getElementById("PROJ_TYPE_FORM2").value = document.getElementById("PROJ_TYPE").value;
  document.getElementById("PROJ_MANAGER_FORM2").value = document.getElementById("PROJ_MANAGER").value;
  document.getElementById("PROJ_DEPT_FORM2").value = document.getElementById("PROJ_DEPT").value;
  document.getElementById("PROJ_VIEWER_FORM2").value = document.getElementById("PROJ_VIEWER").value;
  document.getElementById("PROJ_DESCRIPTION_FORM2").value = getEditorHtml('PROJ_DESCRIPTION');

}

function reload_page()
{
   window.location='index.php?PROJ_ID=<?=$PROJ_ID?>';
}
function initPage() {
	<? if($PROJ_ID!="") { ?>
	var ddl =  document.getElementById("USER_CUST_DEFINE");
	var index = ddl.selectedIndex;
    var value = ddl.options[index].value;   
	get_define_type(value,<?=$PROJ_ID?>);
	<? }else{ ?>
		return;
	<? } ?>
}
</script>

<body class="bodycolor" onLoad="initPage();">
<form enctype="multipart/form-data" method="post" action="submit.php" name="form1" onSubmit="return check_form();">
 <table class="TableBlock" border="0" width="90%" align="center">
  	<tr>
  		<td nowrap class="TableContent" width="90"><?=_("��Ŀ��ţ�")?><?=$IMPORTANT_INFO?></td>
  	  <td class="TableData">
  	  	<input type="text" class="BigInput" name="PROJ_NUM" value="<?=$PROJ_NUM?>" size=20><span id="check_msg"></span>
  	  </td>  	  	
  		<td nowrap class="TableContent" width="90"><?=_("��Ŀ���ƣ�")?><?=$IMPORTANT_INFO?></td>
  	  <td class="TableData"><input type="text" class="BigInput" name="PROJ_NAME" value="<?=$PROJ_NAME?>" size=20></td>  	  	
  	</tr>
    <tr>
  		<td nowrap class="TableContent"><?=_("��Ŀ�����ˣ�")?></td>
  	  <td class="TableData">
		<input type="text" name="PROJ_LEADER_TO_NAME" size="20" class="SmallInput" value="<?=$PROJ_LEADER_NAME!=""?$PROJ_LEADER_NAME:$_SESSION["LOGIN_USER_NAME"]?>" readonly>
		<a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('65','','PROJ_LEADER', 'PROJ_LEADER_TO_NAME')"><?=_("ѡ��")?></a>
		<input type="hidden" name="PROJ_LEADER" id="PROJ_LEADER" value="<?=$PROJ_LEADER!=""?$PROJ_LEADER:$_SESSION["LOGIN_USER_ID"]?>">
		
  	  </td>
  		<td nowrap class="TableContent"><?=_("��Ŀ�ƻ����ڣ�")?><?=$IMPORTANT_INFO?></td>
  	  <td class="TableData"> 
  	   <?
  	      //2012/5/9 16:10:40 lp 
  	      if($PROJ_START_TIME == "")
  	         $PROJ_START_TIME = date("Y-m-d",time());
  	   ?>
  	    <INPUT type="text" readonly name="PROJ_START_TIME" class=BigInput size="10" value="<?=$PROJ_START_TIME?>" onClick="WdatePicker()">
       <?=_("��")?>
        <INPUT type="text" readonly name="PROJ_END_TIME" class=BigInput size="10" value="<?=$PROJ_END_TIME?>" onClick="WdatePicker()">
     
      </td>
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("��Ŀ���")?></td>
  	  <td class="TableData">
		<?=GET_SUB_TYPE_SELECT('PROJ_TYPE','PROJ_TYPE',$PROJ_TYPE,$PROJ_ID)?> <?=$IMPORTANT_INFO?>
  	  </td>	  	
     <td nowrap class="TableContent"><?=_("��Ŀ�����ˣ�")?></td>
     <td nowrap class="TableData">
<?
if($show_noapp)
{
   echo $PROJ_MANAGER_NAME."(".$show_noapp.")".'<input type="hidden" name="PROJ_MANAGER" value="'.$_SESSION["LOGIN_USER_ID"].'">';
}else{
   if($EDIT_FLAG==1)
      echo $PROJ_MANAGER_NAME.'<input type="hidden" name="PROJ_MANAGER" value="'.$PROJ_MANAGER.'">';
   else
   {
?>
     	 <select name="PROJ_MANAGER" class="SmallSelect">
<?
     $query = "SELECT * from PROJ_PRIV WHERE PRIV_CODE='APPROVE'";
     $cursor= exequery(TD::conn(),$query);
     $MANAGER_COUNT=0;
     $HISTORY_STR="";
     while($ROW=mysql_fetch_array($cursor))
     {
         $PRIV_USER=$ROW["PRIV_USER"];
     
         list($APPROVE_USER,$MANAGE_DEPT)=explode("|",$PRIV_USER);
         if(find_id($MANAGE_DEPT,$_SESSION["LOGIN_DEPT_ID"]) || $MANAGE_DEPT=="ALL_DEPT")
         {
            $query1="select USER_ID,USER_NAME from USER where find_in_set(USER_ID,'$APPROVE_USER')";
             $cursor1= exequery(TD::conn(),$query1);
             while($ROW=mysql_fetch_array($cursor1))
             {
             	 if(!find_id($HISTORY_STR,$ROW["USER_ID"]))
             	 {
             	   $HISTORY_STR.=$ROW["USER_ID"].",";
?>
               <option value="<?=$ROW["USER_ID"]?>" <?if($ROW["USER_ID"]==$PROJ_MANAGER) echo "selected";?>><?=$ROW["USER_NAME"]?></option>
<?
                }
             }
         }
     
     }
?>
       </select>
<?
   }
}
?>
     </td>
  	</tr>
  	<tr>
  		<td nowrap class="TableContent"><?=_("���벿�ţ�")?></td>
  	  <td class="TableData">
  	  	<input type="hidden" name="PROJ_DEPT" value="<?=$PROJ_DEPT?>">
        <textarea cols="40" name="PROJ_DEPT_NAME" rows="2" style="overflow-y:auto;" class="BigStatic" wrap="yes" readonly><?=$PROJ_DEPT_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','PROJ_DEPT', 'PROJ_DEPT_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PROJ_DEPT', 'PROJ_DEPT_NAME')"><?=_("���")?></a>
     </td>
  		<td nowrap class="TableContent"><?=_("��Ŀ�����ˣ�")?></td>
  	  <td class="TableData">
		<input type="text" name="PROJ_USER_TO_NAME" size="20" class="SmallInput" value="<?=$PROJ_OWNER_NAME!=""?$PROJ_OWNER_NAME:$_SESSION["LOGIN_USER_NAME"]?>" readonly>
		<a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('65','','PROJ_USER_TO_ID', 'PROJ_USER_TO_NAME')"><?=_("ѡ��")?></a>
		<input type="hidden" name="PROJ_USER_TO_ID" id="to_id" value="<?=$PROJ_OWNER!=""?$PROJ_OWNER:$_SESSION["LOGIN_USER_ID"]?>">
		
  	  </td>
  	</tr>
	<!--�˴����ȫ�ֱ���-->
	<tr>
		<td class="TableData" colspan="4" id="DEFINE_SYSCODE_CONTENT_G"><?=proj_get_field_table_g(proj_get_field_html('G'.$PROJ_TYPE,$PROJ_ID))?></td>
	</tr>
	<!--�˴�����Զ������-->
	<tr>
		<td class="TableData" colspan="4" id="DEFINE_SYSCODE_CONTENT" style="display:none"></td>
	</tr>
  	<tr>
     <td nowrap class="TableContent"><?=_("��Ŀ�鿴�ߣ�")?></td>
     <td nowrap class="TableData" colspan="3">
     	<INPUT type="hidden" name="PROJ_VIEWER" value="<?=$PROJ_VIEWER?>">
     	<textarea name="PROJ_VIEWER_NAME" cols="40" rows="2" style="overflow-y:auto;" class="BigStatic" wrap="yes" readonly><?=$PROJ_VIEWER_NAME?></textarea>
     	<a href="javascript:;" class="orgAdd" onClick="SelectUser('186','','PROJ_VIEWER', 'PROJ_VIEWER_NAME')"><?=_("ѡ��")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('PROJ_VIEWER', 'PROJ_VIEWER_NAME')"><?=_("���")?></a>
     </td>
   </tr>
    <tr>
  		<td nowrap class="TableContent"><?=_("��Ŀ������")?></td>
  	  <td class="TableData" colspan="3">
<?
$editor = new Editor('PROJ_DESCRIPTION');
$editor->ToolbarSet = 'Basic';
$editor->Config = array('model_type' => '10');
$editor->Height = '220' ;
$editor->Value = $PROJ_DESCRIPTION ;
$editor->Create() ;
?>
  	  </td>
  	</tr>
    <tr>
      <td nowrap class="TableContent"><?=_("�����ĵ���")?></td>
      <td nowrap class="TableData" colspan="3">
<?
      if($ATTACHMENT_ID=="")
         echo _("�޸���");
      else
         echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,0,1);
?>
      </td>
    </tr>
    <tr height="25">
      <td nowrap class="TableContent"><?=_("����ѡ��")?></td>
      <td class="TableData" colspan="3">
         <script>ShowAddFile();</script>
        <script>ShowAddImage();</script>
      </td>
    </tr>
    <tr align="center" class="TableControl">
    	<td colspan="4" nowrap>
    	<input type="hidden" name="PROJ_ID" id="PROJ_ID" value="<?=$PROJ_ID?>">
    	<input type="hidden" name="EDIT_FLAG" value="<?=$EDIT_FLAG?>">
    	<input type="button" value="<?=_("����")?>" class="BigButton" onClick="check_form();">&nbsp;
      <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="parent.parent.proj_win_close('<?=$PROJ_ID?>')">&nbsp;
<?
if($PROJ_STATUS==0)
{
?>
	    <input type="button" value="<?if($show_noapp){echo _("��������");}else{echo _("�ύ����");}?>" id='SubmitApprove' class="BigButton" onClick="check();">&nbsp;
<?
}
?>
      <input type="button" value="<?=_("��ģ�嵼��")?>" class="BigButton" onClick="proj_import();">
      <input type="button" value="<?=_("���Ϊģ��")?>" class="BigButton" onClick="proj_export();">
	    </td>
  </tr>
 </table>
        <input type="hidden" name="ATTACHMENT_ID_OLD" id="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" id="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
        <input type="hidden" name="CHECK_NO_FLAG" id="CHECK_NO_FLAG">
</form>

<form name="form2" method="post" target="hiddenFrame">
   <input type="hidden" name="PROJ_NUM_FORM2" id="PROJ_NUM_FORM2" value="<?=$PROJ_NUM?>">
   <input type="hidden" name="PROJ_NAME_FORM2" id="PROJ_NAME_FORM2" value="<?=$PROJ_NAME?>">
   <input type="hidden" name="PROJ_START_TIME_FORM2" id="PROJ_START_TIME_FORM2" value="<?=$PROJ_START_TIME?>">
   <input type="hidden" name="PROJ_END_TIME_FORM2" id="PROJ_END_TIME_FORM2" value="<?=$PROJ_END_TIME?>">
   <input type="hidden" name="PROJ_TYPE_FORM2" id="PROJ_TYPE_FORM2" value="<?=$PROJ_TYPE?>">
   <input type="hidden" name="PROJ_MANAGER_FORM2" id="PROJ_MANAGER_FORM2" value="<?=$PROJ_MANAGER?>">
   <input type="hidden" name="PROJ_DEPT_FORM2" id="PROJ_DEPT_FORM2" value="<?=$PROJ_DEPT?>">
   <input type="hidden" name="PROJ_VIEWER_FORM2" id="PROJ_VIEWER_FORM2" value="<?=$PROJ_VIEWER?>">
   <input type="hidden" name="PROJ_DESCRIPTION_FORM2" id="PROJ_DESCRIPTION_FORM2" value="<?=$PROJ_DESCRIPTION?>">
   <input type="hidden" name="DEL_ATTACHMENT_ID_FORM2" id="DEL_ATTACHMENT_ID_FORM2" value="">
   <input type="hidden" name="DEL_ATTACHMENT_NAME_FORM2" id="DEL_ATTACHMENT_NAME_FORM2" value="">
   <input type="hidden" name="PROJ_ID_FORM2" id="PROJ_ID_FORM2" value="<?=$PROJ_ID?>">
</form>

<!-- ����֡������ɾ������ʱ������Ŀ������Ϣ -->
<iframe name="hiddenFrame" id="hiddenFrame" width=0 height=0 frameborder=0 scrolling=no></iframe>

</body>
</html>