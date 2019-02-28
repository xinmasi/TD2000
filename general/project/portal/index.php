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
	.countProj b{
		font-weight: bold;
		font-size:20px;
		color:#1E90FF;
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
                				$query = "SELECT CODE_NO,CODE_NAME FROM SYS_CODE WHERE PARENT_NO='PROJ_TYPE'";
                                $cursor = exequery(TD::conn(), $query);
                                    while($ROW = mysql_fetch_array($cursor))
                                    { 
								        if($ROW['CODE_NO'] != $i_type_id){
											$CODE_NO = $ROW['CODE_NO'];
											$CODE_NAME = $ROW['CODE_NAME'];
											echo "<option value= '$CODE_NO' > $CODE_NAME </option>";
										}                                                                                                                              
                                    }
									
                ?>
          </select> 
        
          <label class="control-label" >开始时间：</label>
          <input type="text" style="width:80px" id="startTime" name="startTime" value="<?=$startTime ?>">
          <label class="control-label" >结束时间：</label>
          <input type="text" style="width:80px" id="endTime"  name="endTime" value="<?=$endTime ?>">
          <input type="hidden" name="pageIndex" value="<?=$pageIndex?>">
          <button type="submit" class="btn">查询</button>
        </form>
      </div>
      <div class="span2">
        <button type="submit" class="btn btn-success">新建项目</button>
      </div>
    </div>
    <p class="countProj">项目总数<b>83</b>个，立项中<b>10</b>个，审批中<b style="color:#FF8000">3</b>个，办理中<b>10</b>个(其中<b style="color:#FF8000">2</b>个已延期)，挂起中<b>2</b>个，已办结<b>8</b>个</p>
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
          <th>立项时间</th>
          <th>开始时间</th>
          <th>结束时间</th>
          <th>项目进度</th>
        </tr>
      </thead>
      <tbody id="projectList">
        
        <?php 
   			$i = 0;
	   		foreach($projList as $p){
		   		$i++;
		?>
	   		
		   		<tr >
			          <td><?=$i?></td>
			          <td><?=$p['DEPT_NAME']?></td>
			          <td><?=$p['PROJ_LEADER']?></td>
			          <td><?=$p['PROJ_NUM']?></td>
			          <td onclick="javascript:open_project('<?=$p['PROJ_ID']?>',1)" style="cursor:pointer;"><?=$p['PROJ_NAME']?></td>
			          <td><?=$p['PROJ_TYPE']?></td>
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
			          <td>立项时间</td>
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

	var obj_op = false;
	var now_proj_status = <?=isset($PROJ_STATUS) ? intval($PROJ_STATUS) : 2;?>;
	var url = "";
	var page = 1;
	
	function open_project(PROJ_ID,FORMAT)
	{
	    if(obj_op)
	        obj_op.close();
	    URL="details/?PROJ_ID="+PROJ_ID;
	    myleft=(screen.availWidth-780)/2;
	    mytop=100;
	    mywidth=780;
	    myheight=500;
	    if(FORMAT == "1")
	    {
	        myleft=0;
	        mytop=0;
	        mywidth=screen.availWidth-25;
	        myheight=screen.availHeight-70;
	    }
	    obj_op = window.open(URL,"project_detail_"+PROJ_ID,"height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
	}
	
	
</script>



