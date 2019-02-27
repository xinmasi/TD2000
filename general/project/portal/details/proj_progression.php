<?php 
//$HTML_PAGE_TITLE = _("项目管理");
include_once("details.inc.php");
include_once ("inc/utility_project.php");

$priv = check_project_priv();
//$run_id = is_run_hook("PROJ_ID",$PROJ_ID);
//暂取最大ID
// $query = "select max(run_id) as run_id from flow_run_hook where field = 'PROJ_ID' and key_id = '$i_proj_id'";
// $cur = exequery(TD::conn(), $query);
// $run_id = mysql_fetch_array($cur);
// $run_id = $run_id['run_id'];
$run_id = get_run_id("PROJ_ID",$i_proj_id);
if(is_array($run_id) && count($run_id) >= 1){
	$run_id = $run_id[count($run_id) - 1];
}else{
	$run_id = 0;
}
$proj_hook = project_hook("project_apply_x1");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/project.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_STATIC_SERVER?>/static/modules/project/js/jquery.stickytableheaders.js"></script>

<script>
$(function()
{
//------初始化布局元素------
	function init(){
        var height = $(window).height(); 
        var width = $(window).width()-182;
        $(".proj_sidebar, .content").height(height); 
        $(".content").height(height-$(".navbar").height());
        $(".proj_content").height(height-80);
        $('.proj_container').width(width);
        $('.proj_navbar').width(width-20);
        $('.proj_content').width(width);
        $('.mancontent_d').height(height-102);
        $('.mancontent').height(height-210);
    }
    $(window).resize(function(){init();});
    $(document).ready(function(){
        init();
        $("table").stickyTableHeaders();
    });
});
</script>

<script type="text/javascript">

function check(PROJ_ID)
{
	 if(window.confirm("<?=_('确认要结束项目吗？');?>"))
     {   
         URL="project_over.php?PROJ_ID="+PROJ_ID;
         window.location.href = URL;
     }
}

function check_hang(PROJ_ID,HANG){

    var str = "<?=_('确认挂起项目吗?');?>";
    
    if(HANG == 0)
        str = "<?=_('确认恢复挂起项目吗?');?>";

	 if(window.confirm(str))
     {   
         URL="project_hang.php?PROJ_ID="+PROJ_ID+"&HANG="+HANG;
         window.location.href = URL;
     }
}

function manager(PROJ_ID)
{
    var manager = $("#PROJ_MANAGER").val();
    var proj_name =$("#proj_name").val();
    var proj_num =$("#proj_num").val();
    var flag=window.confirm("<?=_('确认要提交项目申请吗？');?>");
    if(flag)
    {
        if(flag==true && manager!="choose")
        {
            URL = "set_manager.php?PROJ_ID="+PROJ_ID+"&PROJ_MANAGER="+manager+"&PROJ_NAME=" + proj_name + "&PROJ_NUM=" + proj_num;
            window.location.href = URL;
        }
        else
        {
            alert("审批人不能为空！");
        }
    }
    
}

function approve_again(PROJ_ID){
	var flag=window.confirm("<?=_('确认要提交项目申请吗？');?>");
    var PROJ_MANAGER = $("#PROJ_MANAGER").val();
	if(flag==true){
	<?
	if($proj_hook == 1 && $i_status == 0){
	?>
	window.open("/general/project/portal/new/project_flow_run.php?PROJ_ID="+PROJ_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
	<?
	}else{
	?>
		window.location.href = "/general/project/portal/new/project_apply.php?PROJ_ID="+PROJ_ID+"&PROJ_MANAGER="+PROJ_MANAGER;
	<?
	}
	?>
	}
}

function look_flow(RUN_ID){
	window.open("/general/workflow/list/print/index.php?actionType=view&RUN_ID="+RUN_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}
</script>
<body style="overflow:hidden;">
<!--侧边栏-->
<div class="proj_sidebar" style="overflow: visible; padding: 0px; width: 182px;">
       <?php 
    //引入左边栏
    include_once("public_left.php"); 
    ?>
</div>
<!--导航栏-->
<div class="proj_container">
    <div class="proj_navbar" style=" width:100%">
        <p class="proj_navbar_header">
             <strong>
            <?=_("项目管理")?> >> <?=_("我的项目")?> >> <?=$s_name?> >> <?=_("项目进度")?>
        </strong>
        </p>
        <input hidden style="display:none;" value="<?=$s_name?>" name="proj_name" id="proj_name"/>
        <input hidden style="display:none;" value="<?=$s_num?>" name="proj_num" id="proj_num"/>
        <? help('010','skill/project');?>
        <p class="xjxm_xjxm_xg">
         <?php  
            if($i_status!=3 && project_update_priv($i_proj_id))
			{
            if($i_status!=4 && $i_status!=0){
			?>

            <a class="btn btn-success" href="#" onClick='window.location.href = "/general/project/proj/new/task/index.php?PROJ_ID=<?=$i_proj_id?>"' ><span class="assign_job"><?=_("分配任务")?></span></a> 

			<?php
            }
			if ($i_status==0) 
			{
			if($s_manager_id == "choose"){
			?>
            &nbsp;
			<a style="width:100px; margin:5px auto; " class="btn btn-warning" href="#zhezhaoceng" role="button" class="btn" data-toggle="modal">
			<span class="choose_person"><?=_("选择审批人")?></span>
			</a>
            <?php
			}else{
				if($run_id > 0){
				?>
					<a class="btn btn-warning" href="#" onClick="javascript:look_flow('<?=$run_id?>')" ><span class="proj_flow"><?=_("查看流程")?></span></a>
				<?}?>
					<a class="btn btn-warning" href="#" onClick="javascript:approve_again('<?=$i_proj_id?>')" ><span><?=_("提交审批")?></span></a>
				<?
			}
			}
			
			//站位状态9
			if($i_status == 9){
			?>
				<a href="#myModal" role="button" class="btn  btn-info" data-toggle="modal"><span><?=_("原因查询")?></span></a>
				<a class="btn btn-warning" href="#" onClick="javascript:approve_again('<?=$i_proj_id?>')" ><span><?=_("再次审批")?></span></a>			
			<?
			}
			
			//走流程并流程没有结束 -> 显示
			if($i_status==1){
				if(!project_build($run_id) && $run_id != 0){
				?>
					<a class="btn btn-warning" href="#" onClick="javascript:look_flow('<?=$run_id?>')" ><span class="proj_flow"><?=_("查看流程")?></span></a>				
				<?
				}else{
				?>
					<a href="#myModal" role="button" class="btn  btn-info" data-toggle="modal"><span><?=_("原因查询")?></span></a>
				<?
				}
			}
			
        	?>
            &nbsp;
			<?php
			if($i_status==2){
			?>
            <a style="width:100px; margin:5px auto; " class="btn btn-info" href="#" onClick="check('<?=$i_proj_id?>')" ><span class="finish_job"><?=_("结束项目")?></span></a>
            &nbsp;
        	<?php 
			}
			}
            
            if($i_status == 4 &&  project_update_priv($i_proj_id)){
               ?>
                <a style="width:100px; margin:5px auto; " class="btn btn-warning" href="#" onClick="check_hang('<?=$i_proj_id?>',0)" ><span class="hang"><?=_("恢复挂起")?></span></a>
                </p>
               <?
            }else if($i_status == 2 &&  project_update_priv($i_proj_id)){
               ?>
                <a style="width:100px; margin:5px auto; " class="btn btn-warning" href="#" onClick="check_hang('<?=$i_proj_id?>',1)" ><span class="hang"><?=_("挂起项目")?></span></a>
                </p>
               <?                
            }
        	?>
   
    </div>

<!-- 右侧主体内容区域 -->
    <?php
		//逻辑切换后台需要转换回来
		$by = isset($BY) ? $BY : "ASC";
		if($by == "DESC"){
			$by = "ASC";
			$forward = "down";
		}else{
			$by = "DESC";
			$forward = "up";
		}
		
		$order = isset($ORDER) ? $ORDER : "TASK_NO";
		
		
		
	?>    
    <div class="proj_content" style="overflow:auto">
        <div class="mancontent_d" >
            <div class="mancontent" id="progression"> 			
               <?php //include_once("progression/progression.php"); ?>
				<div style="overflow-y:auto;height:99%;" >
					<table class="table table-bordered " width="100%" style="text-align:center; padding:0px;  margin-bottom:0px;" >
						<thead>
							<tr class="time_table_top">
								<td nowrap  align="center">
									<i class="icon-hand-<?= $forward?> icon-white1 <? if($ORDER != "TASK_NO") echo "hide"?>"></i>
									<strong>
										<a href="index.php?PROJ_ID=<?= $PROJ_ID?>&BY=<?= $by?>&ORDER=TASK_NO"><?= _("编号") ?></a> 
									</strong>
								
									<strong>
										<?= _("任务名称") ?>
									</strong>
								</td>
								<td nowrap ><strong >
										<?= _("负责人") ?>
									</strong></td>
								<td nowrap width="12%">
									<i class="icon-hand-<?= $forward?> icon-white1 <? if($ORDER != "TASK_START_TIME") echo "hide"?>"></i>
									<strong>
										<a href="index.php?PROJ_ID=<?= $PROJ_ID?>&BY=<?= $by?>&ORDER=TASK_START_TIME"><?= _("开始日期") ?></a>
									</strong>
								</td>
								<td nowrap  width="5%">
									<i class="icon-hand-<?= $forward?> icon-white1 <? if($ORDER != "TASK_TIME") echo "hide"?>"></i>
									<strong>
										<a href="index.php?PROJ_ID=<?= $PROJ_ID?>&BY=<?= $by?>&ORDER=TASK_TIME"><?= _("工期") ?></a>
									</strong>
								</td>
								<td nowrap  width="12%">
									<i class="icon-hand-<?= $forward?> icon-white1 <? if($ORDER != "TASK_END_TIME") echo "hide"?>"></i>
									<strong>
										<a href="index.php?PROJ_ID=<?= $PROJ_ID?>&BY=<?= $by?>&ORDER=TASK_END_TIME"><?= _("结束日期") ?></a>
									</strong>
								</td>
								<td nowrap  width="30%">
									<i class="icon-hand-<?= $forward?> icon-white1 <? if($ORDER != "TASK_PERCENT_COMPLETE") echo "hide"?>"></i>
									<strong>
										<a href="index.php?PROJ_ID=<?= $PROJ_ID?>&BY=<?= $by?>&ORDER=TASK_PERCENT_COMPLETE"><?= _("任务完成度") ?></a>
									</strong>
								</td>    
							</tr>
						</thead>
						<tbody id="tree">
							
						</tbody>
					</table>
				</div>
		   </div>
			<div class="hide" id="loading" style="position:fixed; margin-top:-48px; margin-left:10px;">
				<div class="alert alert-success fade in">正在接收...</div>
			</div>		   
            <div class="rightbar_bottom">
                <?php include_once("column.php"); ?>
            </div>
        </div>
    </div>
    <!-- 右侧主体内容区域 END -->
</div>
<!--遮罩层-->
			<div id="zhezhaoceng" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
					 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="myModalLabel">
						<?=_("选择审批人");?>
					</h3>
				</div>
				<div class="modal-body" align="center">
<?php
    if($priv == 1)
    {
        Message("",_("您没有设置审批人，请联系管理员。"));
    }
    else if($priv == 2)
    {
        $show_noapp = _("免审批");
        echo $_SESSION['LOGIN_USER_NAME']."(".$show_noapp.")".'<input type="hidden" name="PROJ_MANAGER" value="'.$LOGIN_USER_ID.'">';
    }
    else
    {
        $username = GetUserNameById($priv);
        $user_array = explode(",", td_trim($priv));
        $user_name_array = explode(",", td_trim($username));
		
		$PROJ_MANAGER_THIS="";
		$sql = "SELECT PROJ_MANAGER FROM proj_project WHERE PROJ_ID = '$PROJ_ID'";
		$cur = exequery(TD::conn(),$sql);
		if($row = mysql_fetch_array($cur))
		{
		    $PROJ_MANAGER_THIS = $row['PROJ_MANAGER'];
        }
        ?>
        <select name="PROJ_MANAGER" class="Select" id="PROJ_MANAGER">
        <!--<option selected="selected" value="choose"><?=_("请选择")?></option>-->
<?php
    foreach($user_array as $k => $v)
    {
        $type_selected = "";
        if($PROJ_MANAGER_THIS == $v)
        {
            $type_selected = " selected ";
        }
?>
        <option value="<?=$v?>" <?=$type_selected?>><?=$user_name_array[$k]?></option>
<?php

    }
?>
        </select>
        </div>
<?
    }
?>
<div class="modal-footer">
     <button class="btn" data-dismiss="modal" aria-hidden="true"><?=_("关闭")?></button> 
	 <button class="btn btn-primary" onclick ="approve_again(<?=$i_proj_id?>)"><?=$priv == 2 ? _("免审批立项") : _("提交审批")?></button>
</div>
</div>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">项目审批记录原因</h3>
  </div>
  <div class="modal-body">
    <p>
		<?php
			$data = rtrim($s_approve_log,"|*|");
			$data = explode("|*|",$data);
			for($i = count($data) - 1;$i>=0;$i--){
				 $str = empty($data[0])?"正在审批...":$data[$i];
				echo "第" . ($i+1) . "次审批<br/>" . $str . "<br/><br/>";
			}
			
			// $data = array_reverse($data);
			// foreach($data as $key => $datas){
				// echo "第" . $key . "次审批<br/>" . $datas."<br/><br/>";
			// }
		?>
	</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
  </div>
</div>


<script>

	// $(function(){
		//var data = "";
		$("#loading div").html("<img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/loading_16.gif'/> 数据获取中...").show();
		$("#loading").show();
		$.getJSON("progression/progression.php",{i_proj_id:<?= $i_proj_id;?>,by:"<?= $by?>",order:"<?= $order?>"}).success(function(data){
			//var num = data.length;
			// var data1 ="";
			//data = data;
			//for(i=0;i<num;i++){
			//	var datas = eval('('+data[i]+')');
				//$("#myTemplate").tmpl(datas).appendTo("#tree");
				//使用模板速度较慢 1492条数据使用模板7.2s  不适用 5.6s
				
				// if(i%10 == 0){
					// if(datas.TASK_ID == 'no'){
						// data1 += '<tr ><td style="background:#efefef;" colspan="8"></td></tr>';
					// }else{
						// data1 += "<tr><td>"+datas.TASK_NO+"</td><td><a href='proj_task.php?VALUE=2&TASK_ID="+datas.TASK_ID+"&PROJ_ID="+datas.PROJ_ID+"'>"+datas.TASK_NAME+"</a></td><td>"+datas.TASK_USER+"</td><td>"+datas.TASK_START_TIME+"</td><td>"+datas.TASK_TIME+"<?= _("天") ?></td><td>"+datas.TASK_END_TIME+"</td><td><div class='progress progress-striped progress-info active' style='margin:0px'><div class='bar' style='width:"+datas.TASK_PERCENT_COMPLETE+"%;'><span style='color:black'>"+datas.TASK_PERCENT_COMPLETE+"%</span></div></div></td></tr>";
					// }
					// $("#tree").append(data1);
					// data1 = "";
				// }
				
				// if(datas.TASK_ID == 'no')
					// $("#tree").append('	<tr ><td style="background:#efefef;" colspan="8"></td></tr>');
				// else
					// $("#tree").append("<tr><td>"+datas.TASK_NO+"</td><td><a href='proj_task.php?VALUE=2&TASK_ID="+datas.TASK_ID+"&PROJ_ID="+datas.PROJ_ID+"'>"+datas.TASK_NAME+"</a></td><td>"+datas.TASK_USER+"</td><td>"+datas.TASK_START_TIME+"</td><td>"+datas.TASK_TIME+"<?= _("天") ?></td><td>"+datas.TASK_END_TIME+"</td><td><div class='progress progress-striped progress-info active' style='margin:0px'><div class='bar' style='width:"+datas.TASK_PERCENT_COMPLETE+"%;'><span style='color:black'>"+datas.TASK_PERCENT_COMPLETE+"%</span></div></div></td></tr>");
				
			//}
			$("#loading div").html("<img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/loading_16.gif'/> 数据玩命载入中...");
			size = data.length;
        
			obj = {"data":data,"size":size};
			if(size > 0)
				temp(obj);
			else{
				$("#loading div").text("未发现任务");
			}
		}).fail(function(){
			$("#loading div").text("数据载入失败请刷新重试多次无效请联系管理员");
			$("#loading div").removeClass("alert-success").addClass("alert-error");
		})		
	// })
	
	var i= 0;
	var time = "";
	function temp(data){
		var size = data.size;
		var datas = eval('('+data.data[i++]+')');
		if(datas.TASK_ID == 'no')
			$("#tree").append('	<tr ><td style="background:#efefef;" colspan="8"></td></tr>');
		else
		{

			var tempArray = datas.TASK_NO.split(".");;
			var num = tempArray.length * 20;
			var styleTd = "text-align: left; padding-left:" + num + "px;";
			
			$("#tree").append("<tr><td style='"+styleTd+"'>"+datas.TASK_NO+" <a href='proj_task.php?VALUE=2&TASK_ID="+datas.TASK_ID+"&PROJ_ID="+datas.PROJ_ID+"'>"+datas.TASK_NAME+"</a></td><td>"+datas.TASK_USER+"</td><td>"+datas.TASK_START_TIME+"</td><td>"+datas.TASK_TIME+"<?= _("天") ?></td><td>"+datas.TASK_END_TIME+"</td><td><a href='/general/project/proj/task/task_detail.php?PROJ_ID="+datas.PROJ_ID+"&TASK_ID="+datas.TASK_ID+"'  title='"+datas.TASK_LOG+"'><div class='progress progress-striped progress-info active' style='margin:0px' ><div class='bar' style='width:"+datas.TASK_PERCENT_COMPLETE+"%;'><span style='color:black'>"+datas.TASK_PERCENT_COMPLETE+"%</span></div></div></a></td></tr>");
			if(datas.hasOwnProperty("SON"))
			{
				son(datas.SON);
			}
		}
		if(i<size){
			time = setTimeout(function(){temp(data)},10);//时间越快越卡
		}else{
			$("#loading div").text("数据装载完成...");
			$("#loading").hide(500);
			clearTimeout(time);
		}
	}
	function son(date)
	{
		for(var i=0;i<date.length;i++)
		{
			var tempArray = date[i].TASK_NO.split(".");;
			var num = tempArray.length * 20;
			var styleTd = "text-align: left; padding-left:" + num + "px;";
			
			$("#tree").append("<tr><td style='"+styleTd+"'>"+date[i].TASK_NO+" <a href='proj_task.php?VALUE=2&TASK_ID="+date[i].TASK_ID+"&PROJ_ID="+date[i].PROJ_ID+"'>"+date[i].TASK_NAME+"</a></td><td>"+date[i].TASK_USER+"</td><td>"+date[i].TASK_START_TIME+"</td><td>"+date[i].TASK_TIME+"<?= _("天") ?></td><td>"+date[i].TASK_END_TIME+"</td><td><a href='/general/project/proj/task/task_detail.php?PROJ_ID="+date[i].PROJ_ID+"&TASK_ID="+date[i].TASK_ID+"'  title='"+date[i].TASK_LOG+"'><div class='progress progress-striped progress-info active' style='margin:0px' ><div class='bar' style='width:"+date[i].TASK_PERCENT_COMPLETE+"%;'><span style='color:black'>"+date[i].TASK_PERCENT_COMPLETE+"%</span></div></div></a></td></tr>");
			if(date[i].hasOwnProperty("SON"))
			{
				son(date[i].SON);
			}
		}
		
	}

</script>
</body>
</html>