<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/td_core.php");

if($PROJ_ID != "")
{
    $query = "select PROJ_NAME FROM PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW = mysql_fetch_array($cursor))
        $PROJ_NAME = $ROW["PROJ_NAME"];     
}

$query = "select PRIV_USER FROM PROJ_PRIV WHERE PRIV_CODE='NEW'";
$cursor = exequery(TD::conn(), $query);
if($ROW = mysql_fetch_array($cursor))
   $NEW_PRIV = $ROW["PRIV_USER"];

$NEW_PRIV = explode("|",$NEW_PRIV);
if(find_id($NEW_PRIV[0],$_SESSION["LOGIN_DEPT_ID"]) || $NEW_PRIV[0]=="ALL_DEPT"  || find_id($NEW_PRIV[1],$_SESSION["LOGIN_USER_PRIV"]) || find_id($NEW_PRIV[2],$_SESSION["LOGIN_USER_ID"]))
{
	$NewPriv=1;
}
else{
  $NewPriv=0;//"alert('"._("您没有立项权限，如需项目立项权限请与管理员联系开通！")."')";
}
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script>
     var index = 0;
     var NewPriv = <?=$NewPriv?>;

</script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="gridcolumn.php"></script>
<style>
   *{font-size:12px;}

</style>
<style type="text/css">
#loading {
position:absolute;
left:45%;
top:40%;
padding:2px;
z-index:20001;
height:auto;
}
#loading .loading-indicator {
background:white;
color:#444;
font:bold 20px tahoma, arial, helvetica;
padding:10px;
margin:0;
height:auto;
}
#loading-msg {
font: normal 18px arial, tahoma, sans-serif;
}
table.TableList td, table.TableList th {
    vertical-align: top;
}
table.TableList td .attach_link {
  display: block;
}
</style>
<div id="loading"  onclick="this.style.display='none';" >
<div class="loading-indicator"> <img src="<?=MYOA_JS_SERVER?>/static/js/ext/resources/themes/images/default/shared/large-loading.gif" width="32" height="32" style="margin-right:8px;float:left;vertical-align:top;"/> <span id="loading-msg">Loading ... </div>
</div>

<?
include_once("app.html");
?>
<div style="display:none">
<form name="form1"  id="searchTable">
<table class="TableList" width="100%" >

	<tr class="TableContent">
		<td >
        	<input name="RANGE" id="RANGE" type=hidden value=''>
        	
        	<select name="STATUS" id="STATUS" class="SmallSelect" title='<?=_("状态")?>'>
        		<option value="ALL"><?=_("所有状态")?></option>
        		<option value="0"><?=_("立项中")?></option>
        		<option value="1"><?=_("审批中")?></option>
        		<option value="2"><?=_("进行中")?></option>
        		<option value="3"><?=_("已结束")?></option>
                <option value="4"><?=_("挂起中")?></option>
                <option value="5"><?=_("已超时")?></option>
        	</select>
        	&nbsp;<?=_("项目计划周期：")?>
        	<INPUT type="text"  name="PROJ_START_TIME" id="start_time" size="10" value="<?=$PROJ_START_TIME?>" class="BigInput" onClick="WdatePicker()">
         <?=_("至")?>
          <INPUT type="text" class="BigInput" name="PROJ_END_TIME" id="end_time" size="10" value="<?=$PROJ_END_TIME?>" onClick="WdatePicker()">
       
		<!--</td>
	</tr>
	<tr class="TableContent">
		<td>-->
    <select name="PROJ_TYPE" id="PROJ_TYPE" class="SmallSelect" style="width:80px;">
    	<option value="ALL"><?=_("全部类型")?></option>
      <?=code_list("PROJ_TYPE","");?>
    </select>
    <?=_("项目编号：")?>
    <INPUT type="text"  name="PROJ_NUM" id="proj_num" size="10" value="<?=$PROJ_NUM?>" class="BigInput">
    &nbsp;<?=_("项目名称：")?>
    <INPUT type="text"  name="PROJ_NAME" id="PROJ_NAME" size="10" value="<?=$PROJ_NAME?>" class="BigInput">
    &nbsp;<?=_("项目创建人：")?>
    <input type="text" name="TO_NAME" size="10" class="SmallInput" readonly>
    <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('65','','TO_ID', 'TO_NAME')"><?=_("选择")?></a>
    <input type="hidden" name="TO_ID" id="to_id" value="">
    
    <!--  当前正在操作的任务记录ID -->
    <input type="hidden" name="CURR_PROJ_ID" id="CURR_PROJ_ID" value="">
    <input type="hidden" name="HIDE_FINISHED" id="hide_finished" value="true">
		</td>
	</tr>
</table>
</form>

</div>

<script type="text/javascript"> 
   Ext.onReady(function(){
     Ext.get('loading').dom.style.display = 'none'; 
<?
// if($PROJ_ID!="") echo "detail_proj('".$PROJ_ID."','".$PROJ_NAME."');";
?>
   });
   

function refresh_tree()
{
   
   refresh_proj_grid();
   refresh_proj_tree();
}
function refresh_proj_tree()
{
   var delNode;  
   while (delNode = Ext.getCmp("treepanel").getRootNode().firstChild) {  //childNodes[0]
       Ext.getCmp("treepanel").getRootNode().removeChild(delNode);  
   }
   var filtercfg = [
      {'property':"PROJ_TYPE",'value':$("PROJ_TYPE").value},
      {'property':"STATUS",'value':$("STATUS").value},
      {'property':"PROJ_START_TIME",'value':$("TREE_DATE1").value},
      {'property':"PROJ_END_TIME",'value':$("TREE_DATE2").value}
   ];
   var param = {
      filters:filtercfg
   };

   Ext.getCmp("treepanel").store.load(param);
}

function refresh_proj_grid(newValue)
{
	var NO_HIDE_FINISHED = true;
   if(newValue){
      var prop = newValue.property;
      var val =  newValue.value;
	  if(prop == "HIDE_FINISHED"){
		//document.getElementsByName(prop).value = val;
		var NO_HIDE_FINISHED = val;
	  }else{
		$(prop).value = val;
	  }
   }
   //var NO_HIDE_FINISHED = $("hide_finished").value;
   //alert(NO_HIDE_FINISHED);
   //var PROJ_TYPE = $("PROJ_TYPE").value;
   var STATUS = $("STATUS").value ;
   var PROJ_OWNER = $("to_id").value;
   var PROJ_NUM = $("proj_num").value;
   var PROJ_NAME = $("PROJ_NAME").value;
   var PROJ_START_TIME = $("start_time").value;
   var PROJ_END_TIME = $("end_time").value;
   var RANGE = $("RANGE").value;


   var pagebarstore = Ext.getCmp("gridpanel").store;
   pagebarstore.pageSize = $("pagesize").value;
   
   var filtercfg = {
      "limit": Ext.getCmp("gridpagebar").pageSize,
      "page":Ext.getCmp("gridpagebar").page,
      "start":Ext.getCmp("gridpagebar").start,
      
      "NO_HIDE_FINISHED":NO_HIDE_FINISHED,
      "STATUS":STATUS,
      "PROJ_OWNER":PROJ_OWNER,
      "PROJ_NUM":PROJ_NUM,
      "PROJ_NAME":PROJ_NAME,
      "PROJ_START_TIME":PROJ_START_TIME,
      "PROJ_END_TIME":PROJ_END_TIME,
      "RANGE":RANGE
   };
   Ext.apply(pagebarstore.proxy.extraParams,filtercfg);
   pagebarstore.load();
}

function proj_win_close(PROJ_ID)
{
   refresh_proj_grid();
   Ext.getCmp("center").getActiveTab().close();   
}
function detail_proj(PROJ_ID,PROJ_NAME)
{
   Ext.getCmp("center").ShowDetail(PROJ_ID,PROJ_NAME); 
}

function edit_proj(PROJ_ID,EDIT_FLAG,PROJ_NAME)
{
   var tabs = Ext.getCmp("center");
   var newtab = tabs.add({
      id :'Tab '+ (tabs.items.length + 1),
      closable:true,
      html:'<iframe frameborder=0 width=100% height=100% src="./new/?PROJ_ID='+PROJ_ID+'&EDIT_FLAG='+EDIT_FLAG+'"></iframe>',
      title:  PROJ_NAME?PROJ_NAME:'<?=_("修改项目")?>'

   });
   tabs.setActiveTab(newtab);

}

function show_proj(PROJ_ID,PROJ_NAME)
{
   Ext.getCmp("center").ShowDetail(PROJ_ID,PROJ_NAME);

   $("CURR_PROJ_ID").value = PROJ_ID;

}

function end_proj(PROJ_ID)
{
   _get("over.php", "PROJ_ID="+PROJ_ID,function(req){refresh_tree();});
}

function delete_proj(proj_id)
{
  	var msg=td_lang.general.project.msg.msg_3;//"确认要删除所选项目吗？"
  	if(window.confirm(msg))
  	{
  		if(typeof proj_id == "undefined")
  		{
         var proj_str=get_proj_str();
         if(proj_str=="")
         {
         	alert(td_lang.general.project.msg.msg_4);//"要删除项目，请至少选择其中一项。"
         	return;
         }
    	}
    	else
    	{
    	 	var proj_str=proj_id;
    	}
    	_get("delete.php?PROJ_ID_STR="+proj_str,'',function(req) {
     		alert(td_lang.general.project.msg.msg_5);//'所选项目已全部删除'
    		if(req.responseText.indexOf(",")>0)
    		{
    			var tmp=proj_str.split(",");
     	   }
  	   
      refresh_tree();
      });
  	}
}  

function check_proj_finished(PROJ_ID) {
   $("CURR_PROJ_ID").value = PROJ_ID;
	_get("check_task_finished.php", "PROJ_ID=" + PROJ_ID, check_proj_ret);
}

function check_proj_ret(req)
{
   if(req.status==200)
   {
      var PROJ = $("CURR_PROJ_ID").value;
      if(req.responseText.indexOf('OK') > 0) 
      {
         var msg=td_lang.general.project.msg.msg_6;//'<?=_("确认要结束此项目吗？")?>'
         if(window.confirm(msg))
         {
            end_proj(PROJ);
         }
      }
      else 
      {
         var msg=td_lang.general.project.msg.msg_7;//'<?=_("该项目还有尚未结束的任务，是否强制结束？")?>'
         if(window.confirm(msg))
         {
            end_proj(PROJ);
         }
      }
   }
}

function resume_proj(PROJ_ID)
{
	var msg=td_lang.general.project.msg.msg_8;//"确认要恢复执行此项目吗？"
   if(window.confirm(msg))
   {
      _get("resume.php", "PROJ_ID=" + PROJ_ID, function(req){refresh_tree();});
   }
}
<?

$STATUS_ARR=array("0"=>_("立项中"),"1"=>_("审批中"),"2"=>_("进行中"),"3"=>_("已结束"),"4"=>_("挂起中"));
$STATUS_COLOR=array("0"=>"#947BD1","1"=>"blue","2"=>"green","3"=>"red","4"=>"#000000");
?>
var STATUS_ARR = <?=array_to_json($STATUS_ARR);?>;
var STATUS_COLOR = <?=array_to_json($STATUS_COLOR);?>;
</script>

