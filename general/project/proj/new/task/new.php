<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("添加项目任务");
include_once("inc/header.inc.php");
include_once("general/workflow/prcs_role.php");
if($TASK_ID)
{
	$query = "select * from PROJ_TASK WHERE TASK_ID='$TASK_ID'";
	$cursor = exequery(TD::conn(), $query);
	if($ROW=mysql_fetch_array($cursor))
	{
		$TASK_NAME = $ROW["TASK_NAME"];
		$TASK_NO = $ROW["TASK_NO"];
		$PROJ_ID = $ROW["PROJ_ID"];
		$TASK_DESCRIPTION = $ROW["TASK_DESCRIPTION"];
		$TASK_USER  = $ROW["TASK_USER"];
		$TASK_TIME  = $ROW["TASK_TIME"];
		$PRE_TASK = $ROW["PRE_TASK"];
		$PARENT_TASK = $ROW["PARENT_TASK"];
		$TASK_START_TIME  = $ROW["TASK_START_TIME"];
		$TASK_END_TIME = $ROW["TASK_END_TIME"];
		$TASK_LEVEL  = $ROW["TASK_LEVEL"];
		$TASK_PERCENT_COMPLETE = $ROW["TASK_PERCENT_COMPLETE"];
		$TASK_MILESTONE  = $ROW["TASK_MILESTONE"];
		$FLOW_ID_STR = $ROW["FLOW_ID_STR"];
		$RUN_ID_STR = $ROW["RUN_ID_STR"];
		$REMARK = $ROW["REMARK"];
		$TAST_CONSTRAIN = $ROW["TAST_CONSTRAIN"];
        $CAL_ID = $ROW["CAL_ID"];
	}
} 
else
{
  $query = "SELECT 1 from PROJ_TASK where PROJ_ID='$PROJ_ID' AND PARENT_TASK = '0'";
  $cursor= exequery(TD::conn(),$query);
  $MAX_TASK_NO = mysql_num_rows($cursor) + 1;
}
$IMPORTANT_INFO='<span style="color:red">(*)</span>';

$query = "select PROJ_LEADER,PROJ_OWNER,PROJ_MANAGER,PROJ_USER,PROJ_END_TIME from PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(), $query);
if($ROW=mysql_fetch_array($cursor))
{
    $PROJ_USER = $ROW["PROJ_USER"];
    $PROJ_END_TIME = $ROW["PROJ_END_TIME"];
    $PROJ_LEADER = $ROW["PROJ_LEADER"];
    $PROJ_OWNER =$ROW["PROJ_OWNER"];
    $PROJ_MANAGER = $ROW["PROJ_MANAGER"];
}
$PROJ_USER = str_replace("|","",$PROJ_USER);

?>
<link rel="stylesheet" type="text/css" href="/general/workflow/assets/autocomplete.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION['LOGIN_THEME']?>/calendar.css">

<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/mouse_mon.js"></script>
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery-ui.custom.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.ui.autocomplete.min.js<?=$GZIP_POSTFIX?>"></script>
<script language="javascript" src="/general/workflow/assets/combobox.js"></script>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jQueryGantt/libs/date.js"></script>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />



<script> 
var g_proj_end_time = '<?=$PROJ_END_TIME?>';
jQuery.noConflict();
(function($){
    $(document).ready(
        function (){
         $( "#FLOW_ID" ).combobox();
         
        }
    );
})(jQuery);



function calc_time()
{
	if(document.form1.TASK_START_TIME.value=="" || document.form1.TASK_END_TIME.value=="")
	{
	  alert("<?=_("尚未定义项目周期！")?>");
	  return;
	}	
}
function cal_days()
{
    begin_day = Date.parseString(document.form1.TASK_START_TIME.value);
    end_day = Date.parseString(document.form1.TASK_END_TIME.value);
    
    document.getElementById("task_days").value = parseInt(Math.abs(end_day - begin_day) / 1000 / 60 / 60 /24) + 1;
}

function change_date(){
     document.form1.TASK_END_TIME.value = getNewDay(document.form1.TASK_START_TIME.value,parseInt(document.getElementById("task_days").value));
}

function getNewDay(dateTemp, days) {  
    var dateTemp = dateTemp.split("-");  
    var nDate = new Date(dateTemp[1] + '-' + dateTemp[2] + '-' + dateTemp[0]); //转换为MM-DD-YYYY格式    
    var millSeconds = Math.abs(nDate) + (days * 24 * 60 * 60 * 1000);  
    var rDate = new Date(millSeconds);  
    var year = rDate.getFullYear();  
    var month = rDate.getMonth() + 1;  
    if (month < 10) month = "0" + month;  
    var date = rDate.getDate() - 1; // -1 与系统日期选择相同 
    if (date < 10) date = "0" + date;  
    return (year + "-" + month + "-" + date);  
}  

function addFlow()
{
	var obj=document.form1.FLOW_ID;
	if(obj.value=="")
	{
		alert("<?=_("请选择流程！")?>");
		return;
	}
	var flow=document.createElement("span");
	flow.id=obj.value;	
	flow.innerHTML=obj.options[obj.selectedIndex].text+'<img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absmiddle" onclick=delFlow(this) />';
	document.getElementById('FLOW_STR').appendChild(flow);
	document.form1.FLOW_ID_STR.value+=obj.value+",";	
}
function delFlow(obj)
{	
	var flow_id=obj.parentNode.id;
	jQuery(obj).parent().remove();
	var val=document.form1.FLOW_ID_STR.value;
	if(val.indexOf(flow_id+",")==0){
	   val = val.replace(flow_id+",","");
	}else if(val.indexOf(","+flow_id+",")>0){
	   val = val.replace(flow_id+",","");
	}
	document.form1.FLOW_ID_STR.value=val;
}
function check_form()
{
	 if(document.form1.TASK_NAME.value=="" || document.form1.TASK_USER.value=="" || document.form1.TASK_START_TIME.value=="" || document.form1.TASK_END_TIME.value=="")
   { 
   	 alert("<?=_("请填写必填字段！")?>");
     return false;
   }
   var starttime=new Date((document.form1.TASK_START_TIME.value).replace(/-/g,"/"));
   var endtime=new Date((document.form1.TASK_END_TIME.value).replace(/-/g,"/"));
   if(endtime<starttime)
   {
      alert("<?=_("任务计划周期的结束时间不能小于开始时间！")?>");
      return false;
   }
   var proj_end_time = new Date((g_proj_end_time).replace(/-/g,"/"));
      if(endtime>proj_end_time)
   {
      if(!window.confirm("<?=_("任务计划周期的结束时间似乎超过了整个项目的计划周期，是否继续创建？\\n取消以修改任务结束时间。")?>"))
      {
          document.form1.TASK_END_TIME.focus();
          return false;
      }
      
   }
   
   document.form1.submit();
   document.getElementById('butn').disabled='disabled';
   document.form1.action="";
}

function showIt(str) {
	if(str!="") {
		document.getElementById("SHOW_CONSTRAIN").style.display="";
	}else{
		document.getElementById("SHOW_CONSTRAIN").style.display="none";
		document.form1.CONSTRAIN.checked=false;
	}
}
function set_option(option, id, className)
{
    hideMenu();
    option = typeof(option)=="undefined" ? "" : option;
    document.getElementById("TASK_LEVEL").value=option;

    document.getElementById(id).innerHTML=$(id+'_'+option).innerHTML;
    document.getElementById(id).className=className;
}

</script>

<body style="padding-bottom:0px;">

   
<form name="form1" method="post" action="<?if($TASK_ID) echo "update.php";else echo "submit.php";?>">
	<div style="padding:10px;">
		<table class="table table-bordered " >
			<tr class="info">
				<td colspan='4'><strong><?= _("添加新任务");?></strong></td>
			</tr>
			
			<tr>
				<td><?=_("任务序号：")?></td>
				<td>
					<input type="text" class="input-medium" style="margin-bottom:0px;" name="TASK_NO" id="z_t_n" value="<?=$TASK_NO ? $TASK_NO: $MAX_TASK_NO?>" size=20>
				</td>
				<td><?=_("任务级别：")?></td>
				<td>
                	<?
					if($TASK_ID!="")
					{
						if($TASK_LEVEL==0)
						{
							echo '<a id="level" href="javascript:;" class="CalLevel4" onClick="showMenu(this.id,0);" hidefocus="true">次要</a>';
						}
						if($TASK_LEVEL==1)
						{
							echo '<a id="level" href="javascript:;" class="CalLevel3" onClick="showMenu(this.id,1);" hidefocus="true">一般</a>';
						}
						if($TASK_LEVEL==2)
						{
							echo '<a id="level" href="javascript:;" class="CalLevel2" onClick="showMenu(this.id,2);" hidefocus="true">重要</a>';
						}
						if($TASK_LEVEL==3)
						{
							echo '<a id="level" href="javascript:;" class="CalLevel1" onClick="showMenu(this.id,3);" hidefocus="true">非常重要</a>';
						}
					}
					else
					{
						echo '<a id="level" href="javascript:;" class="CalLevel3" onClick="showMenu(this.id,1);" hidefocus="true">一般</a>';
					}
					
                    ?>
					<div id="level_menu" class="attach_div" style="width:110px;">
					   <a id="level_0" href="javascript:set_option('0','level','CalLevel4');" class="CalLevel4"><?=_("次要")?></a>
					   <a id="level_1" href="javascript:set_option('1','level','CalLevel3');" class="CalLevel3"><?=_("一般")?></a>
					   <a id="level_2" href="javascript:set_option('2','level','CalLevel2');" class="CalLevel2"><?=_("重要")?></a>
					   <a id="level_3" href="javascript:set_option('3','level','CalLevel1');" class="CalLevel1"><?=_("非常重要")?></a>
					</div>
					<input type="hidden" name="TASK_LEVEL" id="TASK_LEVEL" value="<?=$TASK_LEVEL!='' ? $TASK_LEVEL : 1?>">				
				</td>
			</tr>
			
			<tr>
				<td><?=_("任务名称：")?><?=$IMPORTANT_INFO?></td>
				<td>
					<input type="text" style="margin-bottom:0px;" class="input-medium" name="TASK_NAME" value="<?=$TASK_NAME?>" size=20>
				</td>
				<td><?=_("里程碑：")?></td>
				<td>
					 <label for="TASK_MILESTONE"><input type="checkbox" id="TASK_MILESTONE" name="TASK_MILESTONE" <? if($TASK_MILESTONE=="1")echo "checked" ?>> <?=_(" 标记为里程碑")?></label>
				</td>
			</tr>

			<tr>
				<td><?=_("执行人：")?><?=$IMPORTANT_INFO?></td>
				<td >
                    <select name="TASK_USER" style="margin-bottom:0px;" class="">
						<?
						$USER_COUNT=0;
						$query = "select USER_ID,USER_NAME from USER WHERE FIND_IN_SET(USER_ID,'$PROJ_USER')";
						$cursor = exequery(TD::conn(), $query);
						while($ROW=mysql_fetch_array($cursor))
						{
							 $USER_COUNT++;
						?>
								<option value="<?=$ROW["USER_ID"]?>" <?if($TASK_USER==$ROW["USER_ID"]) echo "selected";?>><?=$ROW["USER_NAME"]?></option>
                             
						<?
						}
						?>
					</select>   
                                      
                       <input type="checkbox"  name="add_executor" value=9  > <span> <?=_("加入执行人日程安排")?> </span>                 
				</td>
				<td><?=_("项目流程：")?></td>
				<td>
					<select name="FLOW_ID" id="FLOW_ID" style="margin-bottom:0px;"  style="width:200px">
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
								$cursor= exequery(TD::conn(),$query);
								if($ROW=mysql_fetch_array($cursor))
								  $FLOW_NAME=$ROW["FLOW_NAME"];       	 
								echo '<span id="'.$v.'">'.$FLOW_NAME.'<img src="'.MYOA_STATIC_SERVER.'/static/images/delete.gif" align="absmiddle" onclick="delFlow(this)"></span>';
							 }
						 }
					}
					?>       
					</div>				
				</td>
			</tr>
			
			<tr>
				<td><?=_("上级任务：")?></td>
				<td>
					<select id="z_s" name="PARENT_TASK" style="margin-bottom:0px;" class="" >
						<option value=""><?=_("无")?></option>
						<?
							$query = "select TASK_NO,TASK_ID,TASK_NAME from PROJ_TASK WHERE PROJ_ID='$PROJ_ID' AND TASK_ID<>'$TASK_ID'";
							$cursor = exequery(TD::conn(), $query);
						   while($ROW=mysql_fetch_array($cursor))
						   {	
						?>
						<option TASK_NO="<?= $ROW["TASK_NO"]?>" value="<?=$ROW["TASK_ID"]?>" <? if($ROW["TASK_ID"]==$PARENT_TASK) echo "selected";?>><?=$ROW["TASK_NAME"]?></option>
						<?
						}
						?>
					</select>				
				</td>
				<td><?=_("前置任务：")?></td>
				<td>
					<select id="z_s1" name="PRE_TASK"  style="margin-bottom:0px;" class="" onChange="showIt(this.options[this.selectedIndex].value);">
						<option value=""><?=_("无")?></option>
						<?
							$query = "select TASK_ID,TASK_NAME from PROJ_TASK WHERE PROJ_ID='$PROJ_ID' AND TASK_ID<>'$TASK_ID'";
							$cursor = exequery(TD::conn(), $query);
						   while($ROW=mysql_fetch_array($cursor))
						   {	
						?>
						<option value="<?=$ROW["TASK_ID"]?>" <? if($ROW["TASK_ID"]==$PRE_TASK) echo "selected";?>><?=$ROW["TASK_NAME"]?></option>
						<?
						}
						?>
					</select>				
				</td>
			</tr>
			
			<tr class="info" id="SHOW_CONSTRAIN" style="<? if($TAST_CONSTRAIN!=1) {echo "display:none;";}?>">
				<td><?=_("依赖性：")?></td>
				<td colspan='3'>
					<label for="CONSTRAIN"><input type="checkbox" name="CONSTRAIN" id="CONSTRAIN" <? if($TAST_CONSTRAIN==1) {echo "checked='checked'";}?> > <?=_(" 通过前置任务设定任务开始时间")?></label>
				</td>
			</tr>
			
			<tr>
				<td><?=_("任务计划周期：")?><?=$IMPORTANT_INFO?></td>
				<td colspan="3">
					<INPUT type="text" style="margin-bottom:0px;" id="start_time"  name="TASK_START_TIME" class="input-medium" size="10" value="<?=$TASK_START_TIME ? $TASK_START_TIME : date("Y-m-d") ?>"  onClick="WdatePicker()">
					<span class="help-inline"><?=_("至")?></span>
					<INPUT type="text" style="margin-bottom:0px;" id="end_time"  name="TASK_END_TIME" class="input-medium" size="10" onChange="cal_days()" value="<?=$TASK_END_TIME ? $TASK_END_TIME: date("Y-m-d",strtotime("+1 week")) ?>" onClick="WdatePicker()">
					<span class="help-inline"><?=_("共")?></span>
					<input type="text" id="task_days" class="input-mini" style=" margin-bottom:0px; text-align:center; " onChange="change_date()" value="<?=$TASK_TIME?$TASK_TIME:7?>"/>
					<span class="help-inline"> <?=_("个工作日");?></span>			
				</td>
			</tr>
			
			<tr>
				<td><?=_("任务描述：")?></td>
				<td colspan="3">
					<textarea style="width:50%; height:80px; margin-bottom:0px; overflow-y:auto;" cols="50" name="TASK_DESCRIPTION" wrap="yes"><?=$TASK_DESCRIPTION?></textarea>
				</td>
			</tr>
			
			<tr>
				<td><?=_("备注：")?></td>
				<td colspan="3">
					<textarea cols="50" name="REMARK"  style="width:50%; height:80px; margin-bottom:0px; overflow-y:auto;" wrap="yes"><?=$REMARK?></textarea>
				</td>
			</tr>
		</table>
		
	<div align="center" style="width:100%; height:50px; background:#fff; border-top:#3f9bca 3px solid; line-height:50px; position:fixed; top:100%; margin-top:-50px;">
	  <input type="hidden" name="PROJ_ID" value="<?=$PROJ_ID?>">
      <input type="hidden" name="CAL_ID"  value="<?=$CAL_ID?>">
      <input type="hidden" name="TASK_ID" value="<?=$TASK_ID?>">
      <input type="hidden" name="TASK_TIME" value="<?=$TASK_TIME?>">
      <input type="hidden" name="FLOW_ID_STR" value="<?=$FLOW_ID_STR?>">
	  <input type="hidden" name="RUN_ID_STR" value="<?=$RUN_ID_STR?>">
	  <input type="button"  id="butn" value="<?=_("保存")?>" class="btn btn-success" onClick='check_form();'>
	  <input type="button" value="<?=_("返回")?>"  class="btn" onClick="location.href='index.php?PROJ_ID=<?=$PROJ_ID?>'">
	</div>
		
</form>
</div>
<?
$USER_COUNT=0;
$query = "select USER_ID,USER_NAME from USER WHERE FIND_IN_SET(USER_ID,'$PROJ_USER')";
$cursor = exequery(TD::conn(), $query);
while($ROW=mysql_fetch_array($cursor))
   {
 $USER_COUNT++;
 }
if($USER_COUNT==0){
  $USER = $_SESSION['LOGIN_USER_ID'];                                                                          			
  if($USER == $PROJ_LEADER || $USER == $PROJ_OWNER || $USER == $PROJ_MANAGER ){								
    echo "<script>alert('请您首先添加项目成员！');location.href='../../new/user/index.php?PROJ_ID=$PROJ_ID&EDIT_FLAG=1&FROM=NEWTASK';</script>";
        }
        }
   ?>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>      
<script type="text/javascript">
jQuery.noConflict();
(function($){
	
	var selected = $("#z_s option:selected").index();
	var s = $("#z_t_n").val();
    jQuery("#z_s").change(function(){
		var str = $(this).children("option:selected").attr('value');
			
			if($(this).children("option:selected").index() == selected){
				$("#z_t_n").val(s);
			}else{
				
				$.getJSON("get_no.php?PROJ_ID=<?= $PROJ_ID?>&PARENT_ID=" + str).success(function(data){
					$("#z_t_n").val(data.no);
				}).fail(function(){
					alert("<?=_('自动生成编号失败请自行输入!')?>");
				})
				
			}
    })
})(jQuery);
</script>
</body>
</html>



















