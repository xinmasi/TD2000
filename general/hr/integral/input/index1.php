<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
$CUR_TIME=date("Y-m-d H:i:s",time());

$HTML_PAGE_TITLE = _("积分录入");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script Language="JavaScript">
var vals_tem;
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
jQuery(document).ready(function(){
	choose_item();
	
});
function CheckForm()
{
   if(document.form1.USER_IDS.value=="")
   { 
      alert("<?=_("请选择获得积分人！")?>");
      return (false);
   }
   if(document.form1.INTEGRAL_TYPE.value=="3" && document.form1.ITEM_ID.value=="")
   { 
      alert("<?=_("请选择自定义积分项！")?>");
      return (false);
   }
   
    if(isNaN(document.form1.INTEGRAL_DATA.value))
   { 
       alert("<?=_("分值项请输入数字！！！")?>");
       document.form1.INTEGRAL_DATA.focus();
       return (false);
   }
   
   if(document.form1.INTEGRAL_TYPE.value=="3" && document.form1.ITEM_ID.value=="")
   { 
      alert("<?=_("请选择自定义积分项！")?>");
      return (false);
   }
   
   if (getEditorText('INTEGRAL_REASON').length == 0 && getEditorHtml('INTEGRAL_REASON') == "" && document.form1.ATTACHMENT_ID_OLD.value == "")
   { alert("<?=_("积分理由不能为空！")?>");
     return (false);
   }
   return (true);
}

function InsertImage(src)
{
   AddImage2Editor('CARE_CONTENT', src);
}
function sendForm()
{
  if(CheckForm())
     document.form1.submit();
}

function get_item(type_id)
{
	jQuery.getJSON("get_item.php",{TYPE_ID:type_id},function(data){
		tem="";
		if(data.count>0)
		{
			vals_tem=new Array();
			tem='<option value=""><?=_("请选择自定义项")?></option>';
			for(i=1;i<=data.count;i++)
			{
				ID=data['items']['key_'+i].ID;
				NAME=data['items']['key_'+i].NAME;
				ITEM_VALUE=data['items']['key_'+i].ITEM_VALUE;
				vals_tem.push({ID:ID,VAL:ITEM_VALUE});
				tem+="<option value=\""+ID+"\">"+NAME+"</option>";
			}
		}
		else
			tem='<option value=""><?=_("此分类暂无可用积分项")?></option>';
		jQuery("#ITEM_ID").html(tem);
	});
}
function choose_item()
{
	var a=jQuery("#INTEGRAL_TYPE").val();
	if(a==3)
		jQuery("#ITEM_CHOOSE").show();
	else
		jQuery("#ITEM_CHOOSE").hide();
		
}
function check_itemval()
{
	ID_CHOOSE=jQuery("#ITEM_ID").val();
	for(i=0;i<vals_tem.length;i++)
	{
		if(vals_tem[i].ID==ID_CHOOSE)
		{
			jQuery("#INTEGRAL_DATA").val(vals_tem[i].VAL);
		}
	}
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("录入积分")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>

<form enctype="multipart/form-data" action="add.php"  method="post" name="form1" onSubmit="return CheckForm();">
<table class="TableBlock" width="80%" align="center">
	
	 <tr>
      <td nowrap class="TableData" ><?=_("积分获得人：")?></td>
      <td class="TableData" colspan=3>
        <input type="hidden" name="USER_IDS" value="">
        <textarea cols=60 name="USER_NAMES" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','USER_IDS', 'USER_NAMES','0')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('USER_IDS', 'USER_NAMES')"><?=_("清空")?></a>
      </td>
    </tr>   
   <tr>
      <td nowrap class="TableData" width="15%"><?=_("积分类型：")?></td>
      <td class="TableData" width="35%">
        <select name="INTEGRAL_TYPE" id="INTEGRAL_TYPE" style="background: white;" onChange="choose_item()">
        <option value="3" ><?=_("自定义项积分录入")?></option>
        <option value="0" selected><?=_("未定义项积分录入")?></option>
        </select>
      </td>
      <td nowrap class="TableData" width="15%"><?=_("分值：")?></td>
      <td class="TableData" width="35%">
        <input type="text" name="INTEGRAL_DATA" id="INTEGRAL_DATA" class=BigInput size="12">&nbsp;
      </td>    
    </tr>
    <tr id="ITEM_CHOOSE">
    	<td nowrap class="TableData"><?=_("自定义项分类类型：")?></td>
    	<td nowrap class="TableData">
    		<select name="ITEM_TYPE" id="ITEM_TYPE" onChange="get_item(this.value)">
    			<option value=""><?=_("请选择自定义项分类")?></option>
<?
$query="select * from HR_INTEGRAL_ITEM_TYPE where TYPE_FROM=3";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$TYPE_ID=$ROW["TYPE_ID"];
	$TYPE_NO=$ROW["TYPE_NO"];
	$TYPE_NAME=$ROW["TYPE_NAME"];
?>    						
		     <option value="<?=$TYPE_ID?>"><?=$TYPE_NAME?></option>
<?
}
?>
    		</select>
    	</td>
    	<td nowrap class="TableData"><?=_("自定义项：")?></td>
    	<td nowrap class="TableData">
    		<select name="ITEM_ID" id="ITEM_ID" onChange="check_itemval()">
    			<option value=""><?=_("请选择自定义项")?></option>
    	    </select>
    	 </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("打分人员：")?></td>
      <td class="TableData">
        <input type="text" name="CREATE_PERSON_NAME" size="14" class="BigStatic" readonly value="<?=$_SESSION["LOGIN_USER_NAME"]?>">&nbsp;
        <input type="hidden" name="CREATE_PERSON" value="<?=$_SESSION["LOGIN_USER_ID"]?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','CREATE_PERSON', 'CREATE_PERSON_NAME','1')"><?=_("选择")?></a>
      </td>
      <td nowrap class="TableData"> <?=_("积分获得日期：")?></td>
      <td class="TableData">
        <input type="text" name="INTEGRAL_TIME" size="16" maxlength="19" class="BigInput" value="<?=$CUR_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
      </td>
    </tr>
   <tr>
      <td nowrap class="TableData"> <?=_("提醒：")?></td>
      <td class="TableData" colspan=3>
<?=sms_remind(78);?>
      </td>
   </tr>
    <tr id="EDITOR">
      <td class="TableData" colspan="4"> <?=_("积分理由：")?>
<?
$editor = new Editor('INTEGRAL_REASON') ;
$editor->Height = '300';
$editor->ToolbarSet='Basic';
$editor->Value = $CARE_CONTENT ;
$editor->Create() ;
?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
        <input type="submit" value="<?=_("保存")?>" class="BigButton">
      </td>
    </tr>
  </table>
</form>
</body>
</html>