<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
$HTML_PAGE_TITLE = _("新增培训记录");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>

<script Language="JavaScript">
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
var pattern = new RegExp(/^\s+$/);

function LoadWindow()
{
    URL = "record_select/?T_PLAN_NO=<?=$T_PLAN_NO?>";
    
    var loc_y = (window.screen.height-30-260)/2; //获得窗口的垂直位置;
    var loc_x = (window.screen.width-10-350)/2; //获得窗口的水平位置;

    // window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:400px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
	window.open(URL,"detail","height=500,width=450,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes");

}
</script>
<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新增培训记录")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="add.php"  method="post" id="form1" name="form1" >
<table class="TableBlock" width="60%" align="center">
	<tr>
    	   <td nowrap class="TableData"><?=_("培训计划名称：")?></td>
       <td class="TableData" colspan=3>
      	<input type="hidden" name="T_PLAN_NO" value="">
         <input type="text"name="T_PLAN_NAME" class="BigStatic validate[required]" data-prompt-position="topRight:0,-8" size="46" readonly value="">
         <a href="javascript:;" class="orgAdd" onClick="LoadWindow()"><?=_("选择")?></a>
       </td>
	</tr>
    <tr>
      <td nowrap class="TableData"><?=_("受训人：")?></td>
      <td class="TableData" colspan=3>
      	<!--<input type="hidden" name="T_JOIN_PERSON">
      	<textarea cols=45 name="T_JOIN_PERSON_NAME" rows=2 class="BigInput" wrap="yes" readonly></textarea>-->
        <input type="hidden" name="T_JOIN_PERSON">
        <textarea cols=45 name="T_JOIN_PERSON_NAME" rows=2 class="BigStatic validate[required]" data-prompt-position="topRight:0,-8"  wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','5','T_JOIN_PERSON','T_JOIN_PERSON_NAME','1')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('T_JOIN_PERSON','T_JOIN_PERSON_NAME')"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("培训机构：")?></td>
      <td class="TableData">
       <input type="text" maxlength="50" name="T_INSTITUTION_NAME" size="20" class="BigInput" >
      </td>
      <td nowrap class="TableData"><?=_("培训费用：")?></td>
      <td class="TableData">
       <input type="text" name="TRAINNING_COST" size="10" class="BigInput validate[custom[money]]"  data-prompt-position="centerRight:0,-7">
      </td>
    </tr>    
     <tr height="25">
    <td nowrap class="TableData"><?=_("附件选择：")?></td>
    <td class="TableData" colspan="6">
       <script>ShowAddFile();</script>
       <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
       <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
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