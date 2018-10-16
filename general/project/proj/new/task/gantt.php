<?php
//include_once("task_select.php");
include_once("general/workflow/prcs_role.php"); 
?>
<!DOCTYPE html>
<html>
<head>
  <link rel=stylesheet href="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/platform.css" type="text/css">
  <link rel=stylesheet href="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/libs/dateField/jquery.dateField.css" type="text/css">
  <link rel=stylesheet href="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/gantt.css" type="text/css">
    
  	<link href="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen"><!-- 基本样式 -->
    <link href="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/bootstrap/css/bootstrap-responsive.css" rel="stylesheet"><!-- 响应式布局样式 -->;
   <script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/bootstrap/js/jquery.js">//先加载jquery</script>
   <script type="text/javascript" src='<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/bootstrap/js/bootstrap.js'></script>
  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/libs/jquery.min.js"></script>
  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/libs/jquery-ui.min.js"></script>
  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/libs/jquery.livequery.min.js"></script>
  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/libs/jquery.timers.js"></script>
  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/libs/platform.js"></script>
  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/libs/date.js"></script>
  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/libs/i18nJs.js"></script>
  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/libs/dateField/jquery.dateField.js"></script>
  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/libs/JST/jquery.JST.js"></script>

  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/ganttUtilities.js"></script>
  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/ganttTask.js"></script>
  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/ganttDrawer.js"></script>
  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/ganttGridEditor.js"></script>
  <script src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/ganttMaster.js"></script>
 <script src="/module/DatePicker/WdatePicker.js">/*时间控件*/</script> 
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
  <style>
  input[type="text"]{
    height: 25px
  }
  </style>
</head>
<body> 
<div id="workSpace" style="padding:0px; overflow-y:auto; overflow-x:hidden;border:3px solid #e5e5e5;position:relative;margin:0 5px"></div>

<div id="taZone" style="display:none;">
  <textarea rows="8" cols="150" id="ta"> 
    {"tasks":[
    {"id":-1,"name":"Gantt editor","code":"","level":0,"status":"STATUS_ACTIVE","start":1346623200000,"duration":16,"end":1348523999999,"startIsMilestone":true,"endIsMilestone":false,"assigs":[]}
     ],"selectedRow":0,"deletedTaskIds":[],"canWrite":true,"canWriteOnParent":true }
  </textarea>
</div>

<style>
  .resEdit {
    padding: 15px;
  }

  .resLine {
    width: 95%;
    padding: 3px;
    margin: 5px;
    border: 1px solid #d0d0d0;
  }

  body {
    overflow: hidden;
  }
</style>

<script type="text/javascript">
var ge;  //this is the hugly but very friendly global var for the gantt editor
$(function() {

  //load templates
  $("#ganttemplates").loadTemplates();

  // here starts gantt initialization
  ge = new GanttMaster();
  var workSpace = $("#workSpace");
  workSpace.css({width:$(window).width() - 350,height:$(window).height() - 350});
  ge.init(workSpace);

  //inject some buttons (for this demo only)
  $(".ganttButtonBar div").append("<button onclick='clearGantt();' class='button'>清空</button>")
         // .append("&nbsp;")
          // .append("<button onclick='openResourceEditor();' class='button'>edit resources</button>")
          //.append("<button onclick='getFile();' class='button'>export</button>");
  $(".ganttButtonBar h1");
  $(".ganttButtonBar div").addClass('buttons');
  //overwrite with localized ones
  loadI18n();

  //simulate a data load from a server.
  loadGanttFromServer();


  //fill default Teamwork roles if any
  if (!ge.roles || ge.roles.length == 0) {
    setRoles();
  }

  //fill default Resources roles if any
  if (!ge.resources || ge.resources.length == 0) {
    setResource();
  }


  /*debug time scale
  $(".splitBox2").mousemove(function(e){
    var x=e.clientX-$(this).offset().left;
    var mill=Math.round(x/(ge.gantt.fx) + ge.gantt.startMillis)
    $("#ndo").html(x+" "+new Date(mill))
  });*/

});


function loadGanttFromServer(taskId, callback) {

  //this is a simulation: load data from the local storage if you have already played with the demo or a textarea with starting demo data
  loadFromLocalStorage();

  //this is the real implementation
  /*
  //var taskId = $("#taskSelector").val();
  var prof = new Profiler("loadServerSide");
  prof.reset();

  $.getJSON("ganttAjaxController.jsp", {CM:"LOADPROJECT",taskId:taskId}, function(response) {
    //console.debug(response);
    if (response.ok) {
      prof.stop();

      ge.loadProject(response.project);
      ge.checkpoint(); //empty the undo stack

      if (typeof(callback)=="function") {
        callback(response);
      }
    } else {
      jsonErrorHandling(response);
    }
  });
  */
}


function saveGanttOnServer() {

  //this is a simulation: save data to the local storage or to the textarea
  saveInLocalStorage();


  /*
  var prj = ge.saveProject();

  delete prj.resources;
  delete prj.roles;

  var prof = new Profiler("saveServerSide");
  prof.reset();

  if (ge.deletedTaskIds.length>0) {
    if (!confirm("TASK_THAT_WILL_BE_REMOVED\n"+ge.deletedTaskIds.length)) {
      return;
    }
  }

  $.ajax("ganttAjaxController.jsp", {
    dataType:"json",
    data: {CM:"SVPROJECT",prj:JSON.stringify(prj)},
    type:"POST",

    success: function(response) {
      if (response.ok) {
        prof.stop();
        if (response.project) {
          ge.loadProject(response.project); //must reload as "tmp_" ids are now the good ones
        } else {
          ge.reset();
        }
      } else {
        var errMsg="Errors saving project\n";
        if (response.message) {
          errMsg=errMsg+response.message+"\n";
        }

        if (response.errorMessages.length) {
          errMsg += response.errorMessages.join("\n");
        }

        alert(errMsg);
      }
    }

  });
  */
}


//-------------------------------------------  Create some demo data ------------------------------------------------------
function setRoles() {
  ge.roles = [
    {
      id:"tmp_1",
      name:"Project Manager"
    },
    {
      id:"tmp_2",
      name:"Worker"
    },
    {
      id:"tmp_3",
      name:"Stakeholder/Customer"
    }
  ];
}

function setResource() {
  var res = [];
  for (var i = 1; i <= 10; i++) {
    res.push({id:"tmp_" + i,name:"Resource " + i});
  }
  ge.resources = res;
}


function clearGantt() {
  ge.reset();
}

function loadI18n() {
  GanttMaster.messages = {
    "CHANGE_OUT_OF_SCOPE":"NO_RIGHTS_FOR_UPDATE_PARENTS_OUT_OF_EDITOR_SCOPE",
    "START_IS_MILESTONE":"START_IS_MILESTONE",
    "END_IS_MILESTONE":"END_IS_MILESTONE",
    "TASK_HAS_CONSTRAINTS":"TASK_HAS_CONSTRAINTS",
    "GANTT_ERROR_DEPENDS_ON_OPEN_TASK":"GANTT_ERROR_DEPENDS_ON_OPEN_TASK",
    "GANTT_ERROR_DESCENDANT_OF_CLOSED_TASK":"GANTT_ERROR_DESCENDANT_OF_CLOSED_TASK",
    "TASK_HAS_EXTERNAL_DEPS":"TASK_HAS_EXTERNAL_DEPS",
    "GANTT_ERROR_LOADING_DATA_TASK_REMOVED":"GANTT_ERROR_LOADING_DATA_TASK_REMOVED",
    "ERROR_SETTING_DATES":"ERROR_SETTING_DATES",
    "CIRCULAR_REFERENCE":"CIRCULAR_REFERENCE",
    "CANNOT_DEPENDS_ON_ANCESTORS":"CANNOT_DEPENDS_ON_ANCESTORS",
    "CANNOT_DEPENDS_ON_DESCENDANTS":"CANNOT_DEPENDS_ON_DESCENDANTS",
    "INVALID_DATE_FORMAT":"INVALID_DATE_FORMAT",
    "TASK_MOVE_INCONSISTENT_LEVEL":"TASK_MOVE_INCONSISTENT_LEVEL",

    "GANTT_QUARTER_SHORT":"trim.",
    "GANTT_SEMESTER_SHORT":"sem."
  };
}


//-------------------------------------------  Open a black popup for managing resources. This is only an axample of implementation (usually resources come from server) ------------------------------------------------------
function openResourceEditor() {
  var editor = $("<div>");
  editor.append("<h2>Resource editor</h2>");
  editor.addClass("resEdit");

  for (var i in ge.resources) {
    var res = ge.resources[i];
    var inp = $("<input type='text'>").attr("pos", i).addClass("resLine").val(res.name);
    editor.append(inp).append("<br>");
  }

  var sv = $("<div>save</div>").css("float", "right").addClass("button").click(function() {
    $(this).closest(".resEdit").find("input").each(function() {
      var el = $(this);
      var pos = el.attr("pos");
      ge.resources[pos].name = el.val();
    });
    ge.editor.redraw();
    closeBlackPopup();
  });
  editor.append(sv);

  var ndo = createBlackPage(800, 500).append(editor);
}

//-------------------------------------------  Get project file as JSON (used for migrate project from gantt to Teamwork) ------------------------------------------------------
function getFile() {
  $("#gimBaPrj").val(JSON.stringify(ge.saveProject()));
  $("#gimmeBack").submit();
  $("#gimBaPrj").val("");


  //alert(JSON.stringify(ge.saveProject()));

  /*  var uriContent = "data:text/html;charset=utf-8," + encodeURIComponent(JSON.stringify(prj));
   console.debug(uriContent);
   neww=window.open(uriContent,"dl");*/
}


//-------------------------------------------  LOCAL STORAGE MANAGEMENT (for this demo only) ------------------------------------------------------
Storage.prototype.setObject = function(key, value) {
  this.setItem(key, JSON.stringify(value));
};


Storage.prototype.getObject = function(key) {
  return this.getItem(key) && JSON.parse(this.getItem(key));
};


function loadFromLocalStorage() {
  var ret;
	//$("#taZone").show();

  //ret=JSON.parse('{"tasks":[{"id":"tmp_fk1372736117998","name":"123","code":"","level":0,"status":"STATUS_ACTIVE","start":1372694400000,"duration":31,"end":1376409599999,"startIsMilestone":false,"endIsMilestone":false,"collapsed":false,"assigs":[]},{"id":"tmp_fk1372736119835","name":"456","code":"","level":1,"status":"STATUS_ACTIVE","start":1372694400000,"duration":8,"end":1373558399999,"startIsMilestone":false,"endIsMilestone":false,"collapsed":false,"assigs":[]},{"id":"tmp_fk1372736123477","name":"789","code":"","level":1,"status":"STATUS_SUSPENDED","start":1373990400000,"duration":17,"end":1375977599999,"startIsMilestone":false,"endIsMilestone":false,"collapsed":false,"assigs":[],"depends":"2:3"}],"selectedRow":2,"deletedTaskIds":[],"resources":[{"id":"tmp_1","name":"Resource 1"},{"id":"tmp_2","name":"Resource 2"},{"id":"tmp_3","name":"Resource 3"},{"id":"tmp_4","name":"Resource 4"},{"id":"tmp_5","name":"Resource 5"},{"id":"tmp_6","name":"Resource 6"},{"id":"tmp_7","name":"Resource 7"},{"id":"tmp_8","name":"Resource 8"},{"id":"tmp_9","name":"Resource 9"},{"id":"tmp_10","name":"Resource 10"}],"roles":[{"id":"tmp_1","name":"Project Manager"},{"id":"tmp_2","name":"Worker"},{"id":"tmp_3","name":"Stakeholder/Customer"}],"canWrite":true,"canWriteOnParent":true}');


  //提取不到JSON时的处理机制
  if (!ret || !ret.tasks || ret.tasks.length == 0){

    //JSON显示的值
    ret = JSON.parse($("#ta").val());


    //actualiza data
    var offset=new Date().getTime()-ret.tasks[0].start;
    for (var i=0;i<ret.tasks.length;i++)
      ret.tasks[i].start=ret.tasks[i].start+offset;


  }

  ge.loadProject(ret);
  ge.checkpoint(); //empty the undo stack
}

//获取任务列表数据
function saveInLocalStorage() {
  var prj = ge.saveProject();
   //标准JSON化
  var str = JSON.stringify(prj);   
   //$("#ta").val(str); 
  var obj=eval("("+str+")");
	 for(index in obj.tasks){
	 var str1=obj.tasks[index];
 
	 if(str1.name!=""){
	 var id=-str1.id;
	 var name=str1.name;
	 var zxr=str1.zxr;
	 var sjrw=str1.sjrw;
	 var qzrw=str1.qzrw;
	 var rwjb=str1.rwjb;
	 var start=str1.start;
	 var end=str1.end;
	 var duration=str1.duration
	 var description=str1.description;
	 var bz=str1.bz;
	 var glgzl=str1.glgzl;	
	 var status=str1.status;
	  
	var arrTask= new Array();
	 arrTask[0]=id;//任务号
	 arrTask[1]=name;//任务名称
     arrTask[2]=zxr;//执行人
	 arrTask[3]=sjrw;//上级任务
	 arrTask[4]=qzrw;//前置任务
	 arrTask[5]=rwjb;//任务级别
	 arrTask[6]=start;//任务开始时间
	 arrTask[7]=end;//任务结束时间
	 arrTask[8]=description;//任务描述
	 arrTask[9]=bz;//备注
	 arrTask[10]=glgzl;//关联工作流
	 arrTask[11]=duration;//任务周期
	 arrTask[12]=status;//任务级别
	 
     $.ajax({
         url: "task_insert.php",  
         type: "POST",
         data:{trans_data:arrTask},
      //dataType: "json",
         error: function(){  
             alert('加载XML文档时出错');  
        },  
         success: function(data,status){//如果调用php成功    
             alert(unescape(data));//解码，显示汉字
         }
     });
	}
}

}
</script>


<div id="gantEditorTemplates" style="display:none;">
  <div class="__template__" type="GANTBUTTONS"><!--
  <div class="ganttButtonBar">
    <div class="buttons">
    <button onclick="$('#workSpace').trigger('undo.gantt');" class="button textual" title="后撤销"><span class="teamworkIcon">&#39;</span></button>
    <button onclick="$('#workSpace').trigger('redo.gantt');" class="button textual" title="前撤销"><span class="teamworkIcon">&middot;</span></button>
    <span class="ganttButtonSeparator"></span>
    <button onclick="$('#workSpace').trigger('addAboveCurrentTask.gantt');" class="button textual" title="在前插入任务"><span class="teamworkIcon">l</span></button>
    <button onclick="$('#workSpace').trigger('addBelowCurrentTask.gantt');" class="button textual" title="在后插入任务"><span class="teamworkIcon">X</span></button>
    <span class="ganttButtonSeparator"></span>
    <button onclick="$('#workSpace').trigger('moveUpCurrentTask.gantt');" class="button textual" title="上移任务"><span class="teamworkIcon">k</span></button>
    <button onclick="$('#workSpace').trigger('moveDownCurrentTask.gantt');" class="button textual" title="下移任务"><span class="teamworkIcon">j</span></button>
    <span class="ganttButtonSeparator"></span>
    <button onclick="$('#workSpace').trigger('zoomMinus.gantt');" class="button textual" title="放大观察"><span class="teamworkIcon">)</span></button>
    <button onclick="$('#workSpace').trigger('zoomPlus.gantt');" class="button textual" title="缩小观察"><span class="teamworkIcon">(</span></button>
    <span class="ganttButtonSeparator"></span>
    <button onclick="$('#workSpace').trigger('deleteCurrentTask.gantt');" class="button textual" title="删除当前任务"><span class="teamworkIcon">&cent;</span></button>
      &nbsp; &nbsp; &nbsp; &nbsp;
      <button onclick="document.body.id = 'msgBody';" class="button first big" title="save">新建任务</button>
      <button onclick="saveGanttOnServer();" class="button first big" title="save">保存</button>
    </div></div>
  --></div>

  <div class="__template__" type="TASKSEDITHEAD"><!--
  <table class="gdfTable" cellspacing="0" cellpadding="0" id="tb">
    <thead>
    <tr style="height:40px" >
      <th class="gdfColHeader" style="width:50px;">序号</th>
      <th class="gdfColHeader" style="width:42px;">级别</th>
      <th class="gdfColHeader gdfResizable" style="width:150px;">任务名称</th>
      <th class="gdfColHeader gdfResizable" style="width:150px;">开始时间</th>
      <th class="gdfColHeader gdfResizable" style="width:150px;">结束时间</th>
      <th class="gdfColHeader gdfResizable" style="width:60px;">工期</th>
      <th class="gdfColHeader gdfResizable" style="width:80px;">依存关系</th>
	  <th class="gdfColHeader gdfResizable" style="width:250px;">任务描述</th>
	  <th class="gdfColHeader gdfResizable" style="width:80px;">执行人</th>	  
    </tr>
    </thead>
  </table>
  --></div>

  <div class="__template__" type="TASKROW"><!--
  <tr taskId="(#=obj.id#)" class="taskEditRow" level="(#=level#)">
    <th class="gdfCell edit" align="right" style="cursor:pointer;"><span class="taskRowIndex">(#=obj.getRow()+1#)</span> <span class="teamworkIcon" style="font-size:12px;" >e</span></th>
    <td class="gdfCell" align="center"><div class="taskStatus cvcColorSquare" status="(#=obj.status#)"></div></td>
    <td class="gdfCell"><input type="text" name="name" value="(#=obj.name#)"></td>
    <td class="gdfCell"><input type="text" name="start"  value="" onClick="WdatePicker()"></td>
    <td class="gdfCell"><input type="text" name="end" value="" onClick="WdatePicker()"></td>
    <td class="gdfCell"><input type="text" name="duration" value="(#=obj.duration#)"></td>
    <td class="gdfCell"><input type="text" name="depends" value="(#=obj.depends#)" (#=obj.hasExternalDep?"readonly":""#)></td>
	<td class="gdfCell"><input type="text" name="description" value="(#=obj.description#)"></td>
	<td class="gdfCell" name="zxr" align="center"><input type="text" name="xxx" value="xxx"></td>
	
 
  </tr>
  --></div>

  <div class="__template__" type="TASKEMPTYROW"><!--
  <tr class="taskEditRow emptyRow" >
    <th class="gdfCell" align="right"></th>
    <td class="gdfCell" align="center"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
  </tr>
  --></div>

  <div class="__template__" type="TASKBAR"><!--
  <div class="taskBox" taskId="(#=obj.id#)" >
    <div class="layout (#=obj.hasExternalDep?'extDep':''#)">
      <div class="taskStatus" status="(#=obj.status#)"></div>
      <div class="taskProgress" style="width:(#=obj.progress>100?100:obj.progress#)%; background-color:(#=obj.progress>100?'red':'rgb(153,255,51);'#);"></div>
      <div class="milestone (#=obj.startIsMilestone?'active':''#)" ></div>

      <div class="taskLabel"></div>
      <div class="milestone end (#=obj.endIsMilestone?'active':''#)" ></div>
    </div>
  </div>
  --></div>


  <div class="__template__" type="CHANGE_STATUS"><!--
    <div class="taskStatusBox">
      <div class="taskStatus cvcColorSquare" status="STATUS_ACTIVE" title="active"></div>
      <div class="taskStatus cvcColorSquare" status="STATUS_DONE" title="completed"></div>
      <div class="taskStatus cvcColorSquare" status="STATUS_FAILED" title="failed"></div>
      <div class="taskStatus cvcColorSquare" status="STATUS_SUSPENDED" title="suspended"></div>
      <div class="taskStatus cvcColorSquare" status="STATUS_UNDEFINED" title="undefined"></div>
    </div>
  --></div>

 
<div class="__template__" type="TASK_EDITOR"><!--
<script>
function getChange1(value){
$("#qzrw").find('option[value="'+value+'"]').prop('disabled', true);
$("#qzrw").find('option[value!="'+value+'"]').prop('disabled', false);
}

function getChange2(value){
$("#sjrw").find('option[value="'+value+'"]').prop('disabled', true);
$("#sjrw").find('option[value!="'+value+'"]').prop('disabled', false);
}


$(document).ready(function(){
   var status=$("#status").attr("status");
  if(status=="STATUS_ACTIVE"){
    status="次要";
 }else if(status=="STATUS_DONE"){
    status="一般";
 }else if(status=="STATUS_FAILED"){
    status="重要";
 }else{
    status="非常重要";
}
   $("#jibie").html(status);
	
     var i=0;	
    $("#tb tr[taskid]:parent").each(function(){
    var text = $(this).children("td:eq(1)").find('input[name]="name"').val();
	if(text!=''){
	if(text!=$("#name").val()){
 
	  var obj = document.getElementById("sjrw"); 
       obj.add(new Option(text,i));
	  var obj = document.getElementById("qzrw"); 
       obj.add(new Option(text,i));
	    i++;
		}
	  }
    });	
 });
</script>

  <div class="ganttTaskEditor">
    
	<table width="100%" align="center" class="TableList" >
		<tr class="TableHeader">
			<td nowrap align="center" colspan="2"><h1>编 辑 任 务</h1></td>
		</tr>
	</table>
	
    <table width="600px" id="tab" align="center" class='table table-bordered table-hover'>
      <tbody>   
        <tr>
          <td nowrap align="center" class="TableLine1">任务名称</td>
          <td style="text-align:left;">
            <input type="text" name="name" id="name" class="SmallInput"  style="width:150px;height:30px"/>
          </td>
        </tr>
        <tr>
          <td nowrap align="center" class="TableLine1" width="150">执行人</td>
          <td width="555" class="TableData">
            <div align="left">
              <input type="text" style="width:150px;height:30px" name="zxr" id="zxr"  class="SmallInput" value="<?=$PROJ_OWNER_NAME!=""?$PROJ_OWNER_NAME:$LOGIN_USER_NAME?>" readonly> 
                        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('','PROJ_USER_TO_ID', 'PROJ_USER_TO_NAME')"><?=_("选择")?></a> 
                        <input type="hidden" name="PROJ_USER_TO_ID" id="PROJ_USER_TO_ID" value="<?=$PROJ_OWNER!=""?$PROJ_OWNER:$LOGIN_USER_ID?>"> 
                        <a href="javascript:;" class="orgClear" onclick="ClearUser('PROJ_USER_TO_ID', 'PROJ_USER_TO_NAME')">清空</a> 
            </div>
          </td>
       </tr>
       <tr>
          <td width="150" nowrap align="center" class="TableLine1">上级任务</td>
          <td width="555" class="TableData">
            <div align="left">
              <select style="width:150px" id="sjrw" name="name" onchange="getChange1(this.options[this.options.selectedIndex].value)">            
			    <option>-------请选择--------</option>
              </select>
            </div>
          </td>
       </tr>
       <tr>
          <td width="150" nowrap align="center" class="TableLine1">前置任务</td>
          <td width="555" class="TableData">
            <div align="left">
              <select style="width:150px" id="qzrw" name="name" onchange="getChange2(this.options[this.options.selectedIndex].value)">
                   <option>-------请选择--------</option>
              </select>
            </div>
          </td>
       </tr>
       <tr>
          <td width="150" nowrap align="center" class="TableLine1">任务级别</td>
          <td width="555" class="TableData">
            <div align="left"><div id="status" class="taskStatus" status=""></div><div id="jibie"></div></div>		
          </td>
       </tr>
	   <tr>
          <td width="150" nowrap align="center" class="TableLine1">设为里程碑</td>
          <td width="555" class="TableData">
            <div align="left">
               <input type="checkbox" id="TASK_MILESTONE">
            </div>
          </td>
       </tr>	   
        <tr>
          <td nowrap align="center" class="TableLine1">任务计划周期</td>
          <td class="TableData">
            <div align="left">
              <input type="text" name="start"onClick="WdatePicker()"  value="" id="start" class="SmallInput" style="width:88"/> 
              至
              <input type="text" name="end" onClick="WdatePicker()" value="" id="end" class="SmallInput" style="width:88" /> 
             </div>
           </td>
        </tr>
        <tr>
          <td nowrap align="center" class="TableLine1">描述</td>
          <td>
            <textarea name="description" id="description" style="width:400px;height:150px"></textarea>
            <script type="text/javascript">window.CKEDITOR_BASEPATH='/module/editor/';</script>
            <script type="text/javascript">window.HTML_MODEL_TYPE='10';</script>
            <script type="text/javascript" src="/module/editor/ckeditor.js.gz?t=B5GJ5GG"></script>
            <script type="text/javascript">CKEDITOR.replace('CONTENT', {"model_type":"10","width":"100%","height":"200","toolbar":"Default","language":"zh-CN"});</script>
          </td>
        </tr>
        
       <tr>
          <td nowrap align="center" class="TableLine1">备注</td>
          <td style="text-align:left;">
            <textarea id="bz" name="bz"></textarea>
          </td>
        </tr>
        <tr>
          <td nowrap align="center" class="TableLine1">关联工作流</td>
          <td style="text-align:left;" class="TableData">
            <select name="glgzl" id="glgzl" >
              <option value=""></option>
              <?
              $flow_array = get_flow_list(2);
              foreach($flow_array as $option)
              {
                  $option_str = '<option value="'.$option["value"].'" category="'.$option["category"].'" node="'.$option["node"].'"';
                  if($FLOW_ID == $option["value"])
                      $option_str .= ' selected';
                  $option_str .='>'.$option["txt"].'</option>';
                  echo $option_str;
              }
              ?>
            </select>
            <a href="javascript:;" class="orgAdd" onClick="addFlow()"><?=_("添加流程")?></a>
            <div id="FLOW_STR" style="margin-top:5px;">
            <?
            if($FLOW_ID_STR)
            {
               $FLOW_ID=explode(",",$FLOW_ID_STR);
               foreach($FLOW_ID AS $v)
               {
                 if($v!="")
                 {
                    $query = "SELECT FLOW_NAME from FLOW_TYPE WHERE FLOW_ID='$v'";
                    $cursor= exequery($connection,$query);
                    if($ROW=mysql_fetch_array($cursor))
                      $FLOW_NAME=$ROW["FLOW_NAME"];          
                    echo "<span id='".$v."'>".$FLOW_NAME."<img src='/images/delete.gif' align='absmiddle' onclick='delFlow(this)'></span>";
                 }
               }
            }
            ?>       
            </div>
          </td>
        </tr>
         
      </tbody>
    </table>

  <div style="text-align: right; padding-top: 20px"><button id="saveButton" class="button big">save</button></div>
  </div>
  --></div>


  <div class="__template__" type="ASSIGNMENT_ROW"><!--
  <tr taskId="(#=obj.task.id#)" assigId="(#=obj.assig.id#)" class="assigEditRow" >
    <td ><select name="resourceId"  class="formElements" (#=obj.assig.id.indexOf("tmp_")==0?"":"disabled"#) ></select></td>
    <td ><select type="select" name="roleId"  class="formElements"></select></td>
    <td ><input type="text" name="effort" value="(#=getMillisInHoursMinutes(obj.assig.effort)#)" size="5" class="formElements"></td>
    <td align="center"><span class="teamworkIcon delAssig" style="cursor: pointer">d</span></td>
  </tr>
  --></div>

</div>
<script type="text/javascript">


  $.JST.loadDecorator("ASSIGNMENT_ROW", function(assigTr, taskAssig) {

    var resEl = assigTr.find("[name=resourceId]");
    for (var i in taskAssig.task.master.resources) {
      var res = taskAssig.task.master.resources[i];
      var opt = $("<option>");
      opt.val(res.id).html(res.name);
      if (taskAssig.assig.resourceId == res.id)
        opt.attr("selected", "true");
      resEl.append(opt);
    }


    var roleEl = assigTr.find("[name=roleId]");
    for (var i in taskAssig.task.master.roles) {
      var role = taskAssig.task.master.roles[i];
      var optr = $("<option>");
      optr.val(role.id).html(role.name);
      if (taskAssig.assig.roleId == role.id)
        optr.attr("selected", "true");
      roleEl.append(optr);
    }

    if (taskAssig.task.master.canWrite) {
      assigTr.find(".delAssig").click(function() {
        var tr = $(this).closest("[assigId]").fadeOut(200, function() {
          $(this).remove();
        });
      });
    }


  });
</script>

 </body>
</html>
 