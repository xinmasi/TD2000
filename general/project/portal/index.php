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
	          	<input type="hidden" name="projDept" id="projDept" value="<?=$s_dept_id?>" />
		        <input  name="projDeptName" id="projDeptName"  type="text" value="<?=$s_dept?>" style="width:120px"/>
		        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','projDept','projDeptName')"><?=_("选择")?></a>&nbsp;
          
          <label class="control-label" for="person">负责人：</label>
          
	          	<input type="text" name="leaderName" id="leaderName" style="width:100px"  value="<?=$s_leader?>" >
		        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('65','','leaderId', 'leaderName')"><?=_("选择")?></a>
		        <input type="hidden" name="leaderId" id="leaderId" value="<?=$s_leader_id?>">&nbsp;
          
          <label class="control-label" for="name">项目名称：</label>
          <input type="text" id="projName" name="projName" style="width:120px">&nbsp;
          <label class="control-label" for="state">状态：</label>
          <select id="projStatus" name="projStatus" style="width:120px">
          	<option value=""></option>
            <option value="0">立项中</option>
            <option value="1">审批中</option>
            <option value="2">办理中</option>
            <option value="3">已办结</option>
            <option value="4">挂起中</option>
          </select><br><br>
          
          <label class="control-label" for="type">项目类别：</label>
          
          <select id="type" name="PROJ_TYPE" style="width:120px">
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
          <input type="text" style="width:80px" value="2018-08-23">
          <label class="control-label" >结束时间：</label>
          <input type="text" style="width:80px">
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
        <tr>
          <td>1</td>
          <td>部门名称</td>
          <td>负责人</td>
          <td>项目编号</td>
          <td>项目名称</td>
          <td>项目类别</td>
          <td>立项时间</td>
          <td>开始时间</td>
          <td>结束时间</td>
          <td>
            <div class="progress">
              <div class="bar bar-success" style="width: 60%;"></div>
            </div>
          </td>
        </tr>
        <tr>
          <td>2</td>
          <td>部门名称</td>
          <td>负责人</td>
          <td>项目编号</td>
          <td>项目名称</td>
          <td>项目类别</td>
          <td>状态</td>
          <td>立项时间</td>
          <td>开始时间</td>
          <td>结束时间</td>
          <td>
            <div class="progress">
              <div class="bar" style="width: 50%;"></div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</body>
</html>
<script id="myTemplate" type="text/x-jquery-tmpl">
	<tr >
          <td>1</td>
          <td>部门名称</td>
          <td>${PROJ_LEADER}</td>
          <td>${PROJ_NUM}</td>
          <td onclick="javascript:open_project('${PROJ_ID}',1)" style="cursor:pointer;">${PROJ_NAME}</td>
          <td>${PROJ_TYPE}</td>
		  <td>
			{{if PROJ_STATUS == '0'}}
            	立项中
            {{else PROJ_STATUS == '1'}}
            	审批中
            {{else PROJ_STATUS == '2'}}
            	办理中
            {{else PROJ_STATUS == '3'}}
            	已办结
            {{else PROJ_STATUS == '4'}}
            	挂起中	
            {{else}}
            {{/if}}
		  </td>
          <td>立项时间</td>
          <td>${PROJ_START_TIME}</td>
          <td>${PROJ_END_TIME}</td>
          <td>
            <div class="progress">
              <div class="bar bar-success" style="width: ${PROJ_PERCENT_COMPLETE}%;"></div>
            </div>
          </td>
        </tr>
</script>


<script type="text/javascript">

	var now_proj_status = <?=isset($PROJ_STATUS) ? intval($PROJ_STATUS) : 2;?>;
	var url = "";
	var page = 1;

	$.getJSON("proj_list.php?PROJ_STATUS=" + now_proj_status + url )
	.success(function(data){
	    if(data.count > 0){
	    	$("#projectList").empty();
	        $('#myTemplate').tmpl(data.data).appendTo('#projectList');
	    }else{
	
	        $("#loading").text("没有更多的数据").show();
	    }
	})
	.fail(function( jqxhr, textStatus, error ) {
	    //加载失败点击重新加载
	    $("#loading").html("加载失败... <a href='#' onclick='load_tmpl()'>点击这里重新载入</a> [" + error + "]").show(speed);
	});

</script>
<script type="text/javascript">
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



