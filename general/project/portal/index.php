<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("项目管理");
include_once("inc/header.inc.php");
/**
 *
 * 项目管理看板首页 项目查询看板首页
 * 采用jquery.tmpl + $.getJSON 实现
 * 调用可选参数 PROJ_STATUS
 * @name index.php
 * @version 1.0 2013-10-22
 * @author zfc
 *
 */


include_once("./new_page/selectProjList.php");
$projList = $a_new_array;

?>

<?php
	$query = "SELECT CODE_NO,CODE_NAME FROM SYS_CODE WHERE PARENT_NO='PROJ_TYPE'";
	$cursor = exequery(TD::conn(), $query);
	$typeMap = array();
	$typeSqlArray = array();
	$i = 0;
	while($ROW = mysql_fetch_array($cursor)){
		$typeMap[$ROW['CODE_NO']] = $ROW['CODE_NAME'];
		$typeSqlArray[$i] = $ROW;
		$i++;
	}
?>

<!DOCTYPE html>
<html lang="cn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>项目中心</title>
  <link rel="stylesheet" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/bootstrap.css">
  
	<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
	<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
	<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/template/jquery.tmpl.min.js"></script>
	<script src="/module/DatePicker/WdatePicker.js">/*时间控件*/</script>
	<link href="/module/DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">

	<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/js/backtop/jquery.backTop.css" />
	<script src="<?=MYOA_STATIC_SERVER?>/static/modules/project/js/backtop/jquery.backTop.js"></script>
  
	<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
  
  
<style>
	.progress{
	    margin-bottom: 0;
	    border-radius: 10px;
	}
	#loading{
	    height:30px;
	    line-height:30px;
	    width:100%;
	    display:none;
	    text-align:center;
		
	}
	.countProj a{
		font-weight: bold;
		font-size:20px;
		color:#1E90FF;
		cursor:pointer;
	}
	
</style>
</head>

<body>


    <?php include_once("./new_page/proj_menu.php"); ?>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span10">
        <form class="form-inline" id="proj_form">
          <label class="control-label" for="department">部门：</label>
	          	<input type="hidden" name="projDept" id="projDept" value="<?=$deptId?>" />
		        <input  name="projDeptName" id="projDeptName"  type="text" value="<?=$projDeptName?>" style="width:120px"/>
		        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','projDept','projDeptName')"><?=_("选择")?></a>&nbsp;
          
          <label class="control-label" for="person">负责人：</label>
          
	          	<input type="text" name="leaderName" id="leaderName" style="width:100px"  value="<?=$leaderName?>" >
		        <a href="javascript:;" class="orgAdd" onClick="SelectUser('65','','leaderId', 'leaderName')"><?=_("选择")?></a>
		        <input type="hidden" name="leaderId" id="leaderId" value="<?=$leaderId?>">&nbsp;
          
          <label class="control-label" for="name">项目名称：</label>
          <input type="text" id="projName" name="projName" style="width:120px" value="<?=$projName?>">&nbsp;
          <label class="control-label" for="state">状态：</label>
          <select id="PROJ_STATUS" name="PROJ_STATUS" style="width:120px" >
          	<option value=""></option>
            <option value="0">立项中</option>
            <option value="1">审批中</option>
            <option value="2">办理中</option>
            <option value="3">已办结</option>
            <option value="4">挂起中</option>
          </select><br><br>
          
          <label class="control-label" for="type">项目类别：</label>
          
          <select  name="projType" id="projType" style="width:120px">
          		<option></option>
          		<?php
//                 				$query = "SELECT CODE_NO,CODE_NAME FROM SYS_CODE WHERE PARENT_NO='PROJ_TYPE'";
//                                 $cursor = exequery(TD::conn(), $query);
                                    foreach ( $typeSqlArray as $ROW)
                                    { 
								        if($ROW['CODE_NO'] != $i_type_id){
											$CODE_NO = $ROW['CODE_NO'];
											$CODE_NAME = $ROW['CODE_NAME'];
											echo "<option value= '$CODE_NO' > $CODE_NAME </option>";
										}                                                                                                                              
                                    }
									
                ?>
          </select> 
        
          <label class="control-label" >立项时间：</label>
          <input type="text" style="width:80px" id="startTime" name="startTime" value="<?=$startTime ?>" onclick="WdatePicker()">
          <label class="control-label" >计划结束时间：</label>
          <input type="text" style="width:80px" id="endTime"  name="endTime" value="<?=$endTime ?>" onclick="WdatePicker()">
          <input type="hidden" name="pageIndex" value="<?=$pageIndex?>">
          <button type="submit" class="btn">查询</button>
        </form>
      </div>
      <div class="span2">
        <button type="button" class="btn btn-success"><a href="new/index.php" style="color:#fff;">新建项目</a></button>
      </div>
    </div>
    <p class="countProj">项目总数<a onclick="selectStatus('')"><?=$statusArray[8] ?></a>个(其中<a onclick="selectStatus(20)" style="color:#FF8000"><?=$statusArray[7] ?></a></a>个已延期)，立项中<a onclick="selectStatus(0)"><?=$statusArray[0] ?></a>个，审批中<a style="color:#FF8000" onclick="selectStatus(1)"><?=$statusArray[1] ?></a></a>个，办理中<a onclick="selectStatus(2)"><?=$statusArray[2] ?></a>个，挂起中<a onclick="selectStatus(4)"><?=$statusArray[4] ?></a>个，已办结<a onclick="selectStatus(3)"><?=$statusArray[3] ?></a>个</p>
   	<p id="loading"></p>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>序号</th>
          <th>部门名称</th>
          <th>负责人</th>
          <th>项目编号</th>
          <th>项目名称</th>
          <th>项目类别</th>
          <th>状态</th>
          <th>级别</th>
          <th>立项时间</th>
          <th>计划结束时间</th>
          <th width="15%">项目进度</th>
        </tr>
      </thead>
      <tbody id="projectList">
        
        <?php 
   			$i = 0;
	   		foreach($projList as $p){
		   		$i++;
		?>
	   		
		   		<tr >
			          <td><?=$i?><?php echo $typeMap[i] ?></td>
			          <td><?=$p['DEPT_NAME']?></td>
			          <td><?=$p['PROJ_LEADER']?></td>
			          <td><?=$p['PROJ_NUM']?></td>
			          <td style="cursor:pointer;color:#1E90FF;">
			          			<a href="details/?PROJ_ID=<?=$p['PROJ_ID']?>" target="_blank"><?=$p['PROJ_NAME']?></a>
		          			</td>
			          <td><?=$typeMap[$p['PROJ_TYPE']]?></td>
					  <td>
					 	<?php 
					 		if ($p['PROJ_STATUS'] == '0'){
					 			echo "立项中";
					 		}else if($p['PROJ_STATUS'] == '1'){
					 			echo "审批中";
					 		}else if($p['PROJ_STATUS'] == '2'){
					 			echo "办理中";
					 		}else if($p['PROJ_STATUS'] == '3'){
					 			echo "已办结";
					 		}else if($p['PROJ_STATUS'] == '4'){
					 			echo "挂起中";
					 		}
			   			?>   
						
					  </td>
					  <td><?=$p['PROJ_LEVEL']?>级</td>
			          <td><?=$p['PROJ_START_TIME']?></td>
			          <td><?=$p['PROJ_END_TIME']?></td>
			          <td>
			            <div class="progress">
			              <div class="bar bar-success" style="width: <?=$p['PROJ_PERCENT_COMPLETE']?>%;"></div>
			            </div>
			          </td>
				</tr>
	   		
	   	<?php 
	   		}
   		?>   
       
      </tbody>
    </table>
  </div>
</body>
</html>


<script type="text/javascript">

	$("#PROJ_STATUS").val(<?=$PROJ_STATUS ?>);
	$("#projType").val(<?=$projType ?>);


	function selectStatus(projStatus){
		$("#PROJ_STATUS").val(projStatus);
		$("#proj_form").submit();
	}
	
</script>



