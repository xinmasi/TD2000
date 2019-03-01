<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("月计划");
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
include_once("./monthPlanSelect.php");
?>
<!DOCTYPE html>
<html lang="cn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>月计划</title>
  <link rel="stylesheet" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/bootstrap.css">
  
	<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
	<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
	<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/template/jquery.tmpl.min.js"></script>
	<script src="/module/DatePicker/WdatePicker.js">/*时间控件*/</script>
	
	<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/js/backtop/jquery.backTop.css" />
	<script src="<?=MYOA_STATIC_SERVER?>/static/modules/project/js/backtop/jquery.backTop.js"></script>
	  
	<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
  	<link rel="stylesheet" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/zTreeStyle.min.css">
  
  	
  
  
  <style>
  .progress{
    margin-top: 6px;
    margin-bottom: 0;
    border-radius: 10px;
  }
  /*按钮*/  
  .icon_div {
      display: inline-block;
      height: 25px;
      width: 35px;
  }

  .icon_div a {
      display: inline-block;
      width: 27px;
      height: 20px;
      cursor: pointer;
  }

  /*end--按钮*/

  /*ztree表格*/
  .ztree {
      padding: 0;
      border: 1px solid #CDD6D5;
  }

  .ztree li a {
      vertical-align: middle;
      height: 34px;
  }

  .ztree li > a {
      width: 100%;
  }

  .ztree li > a,
  .ztree li a.curSelectedNode {
      padding-top: 0px;
      background: none;
      height: 34px;
      border: none;
      cursor: default;
      opacity: 1;
  }

  .ztree li ul {
      padding-left: 0px
  }

  .ztree div.diy span {
      /* line-height: 34px; */
      vertical-align: middle;
  }

  .ztree div.diy {
      height: 100%;
      line-height: 34px;
      border-top: 1px solid #eeeeee;
      border-left: 1px solid #eeeeee;
      text-align: center;
      display: inline-block;
      box-sizing: border-box;
      color: #6c6c6c;
      font-family: "SimSun";
      font-size: 12px;
      overflow: hidden;
  }
  .ztree div.diy:nth-child(1) {
      width: 10%;
  }
  .ztree div.diy:nth-child(2) {
      width: 20%;
  }
  .ztree div.diy:nth-child(3) {
      width: 5%;
  }
  .ztree div.diy:nth-child(4) {
      width: 5%;
  }
  .ztree div.diy:nth-child(5) {
      width: 5%;
  }
  .ztree div.diy:nth-child(6) {
      width: 5%;
  }
  .ztree div.diy:nth-child(7) {
      width: 5%;
  }
  .ztree div.diy:nth-child(8) {
      width: 45%;
  }

  .ztree div.diy:first-child {
      text-align: left;
      border-left: none;
  }
  .ztree div.diy:nth-child(2) {
      text-align: left;
      text-indent: 10px;
  }

  .ztree .head {
      background: #5787EB;
  }

  .ztree .head div.diy {
      border-top: none;
      border-right: 1px solid #CDD2D4;
      color: #fff;
      font-family: "Microsoft YaHei";
      font-size: 14px;
  }

  /*end--ztree表格*/
  </style>
</head>
<body>
    <?php include_once("./proj_menu.php"); ?>
  <div class="container-fluid">
    <form class="form-inline">
      <label class="control-label" for="month">月份：</label>
      <select id="month" style="width:100px">
        <option>2019-01</option>
        <option>2018-12</option>
        <option>2018-11</option>
      </select>
      
      <label class="control-label" for="department">部门：</label>
	          	<input type="hidden" name="PROJ_DEPT" id="PROJ_DEPT" value="<?=$s_dept_id?>" />
		        <input  name="PROJ_DEPT_NAME"   type="text" value="<?=$s_dept?>" style="width:120px"/>
		        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','PROJ_DEPT','PROJ_DEPT_NAME')"><?=_("选择")?></a>&nbsp;
          
          <label class="control-label" for="person">负责人：</label>
          
	          	<input type="text" name="PROJ_LEADER_TO_NAME" style="width:100px"  value="<?=$s_leader?>" >
		        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('65','','PROJ_LEADER', 'PROJ_LEADER_TO_NAME')"><?=_("选择")?></a>
		        <input type="hidden" name="PROJ_LEADER" id="PROJ_LEADER" value="<?=$s_leader_id?>">&nbsp;
      			<input type="hidden" name="pageIndex" value="<?=$pageIndex?>">
      			
      <button type="submit" class="btn">查询</button>
    </form>
    <h4>项目计划 <a href="" class="pull-right">计划详情查看 >></a></h4>
    <div id="tableMain">
      <ul id="dataTree" class="ztree"></ul>
    </div>
    
    <?php 
    	foreach ($date as $obj){
    		echo $obj["PROJ_NAME"];
    	}
    	
    	echo PHP_VERSION;
    ?>
    
    <h4>日常事务安排 </h4>
    <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>事务内容</th>
            <th>参与人</th>
            <th>所属人</th>
            <th>部门</th>
            <th>事务类别</th>
            <th>开始时间</th>
            <th>结束时间</th>
            <th>当前进度</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>事务内容</td>
            <td>参与人</td>
            <td>所属人</td>
            <td>部门</td>
            <td>事务类别</td>
            <td>开始时间</td>
            <td>结束时间</td>
            <td>
              <div class="progress">
                <div class="bar bar-success" style="width: 60%;"></div>
              </div>
            </td>
          </tr>
          <tr>
            <td>事务内容</td>
            <td>参与人</td>
            <td>所属人</td>
            <td>部门</td>
            <td>事务类别</td>
            <td>开始时间</td>
            <td>结束时间<?=$typeSqlArray?></td>
            <td>
              <div class="progress">
                <div class="bar" style="width: 50%;"></div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
  </div>
  <script src="/static/modules/project/js/jquery.min.js"></script>
  <script src="/static/modules/project/js/jquery.ztree.all.min.js"></script>
  <script>
      var zTreeNodes;
      var setting = {
          view: {
              showLine: false,
              showIcon: false,
              addDiyDom: addDiyDom
          },
          data: {
              simpleData: {
                  enable: true
              }
          }
      };
      /**
       * 自定义DOM节点
       */
      function addDiyDom(treeId, treeNode) {
          var spaceWidth = 15;
          var liObj = $("#" + treeNode.tId);
          var aObj = $("#" + treeNode.tId + "_a");
          var switchObj = $("#" + treeNode.tId + "_switch");
          var icoObj = $("#" + treeNode.tId + "_ico");
          var spanObj = $("#" + treeNode.tId + "_span");
          aObj.attr('title', '');
          var objname = '<div class="diy">' + (treeNode.PROJ_NAME == null ? '&nbsp;' : treeNode.PROJ_NAME) + '</div>';
          aObj.append(objname);
          aObj.append('<div class="diy swich"></div>');
          var div = $(liObj).find('div').eq(1);
          switchObj.remove();
          spanObj.remove();
          icoObj.remove();
          div.append(switchObj);
          div.append(spanObj);
          var spaceStr = "<span style='height:1px;display: inline-block;width:" + (spaceWidth * treeNode.level) + "px'></span>";
          switchObj.before(spaceStr);
          var editStr = '';
          editStr += '<div class="diy">' + (treeNode.SECTOR_NAME == null ? '&nbsp;' : treeNode.SECTOR_NAME) + '</div>';
          editStr += '<div class="diy">' + (treeNode.CONTACT_USER == null ? '&nbsp;' : treeNode.CONTACT_USER) + '</div>';
          var corpCat = '<div title="' + treeNode.CORP_CAT + '">' + treeNode.CORP_CAT + '</div>';
          editStr += '<div class="diy">业务项目</div>';
          editStr += '<div class="diy">2019-01-01</div>';
          editStr += '<div class="diy">2019-01-20</div>';
          editStr += '<div class="diy">' + formatHandle(treeNode) + '</div>';
          aObj.append(editStr);
      }
      /**
       * 查询数据
       */
      function query() {
          var data = <?=$jsonDate?>;
              [
          {"CONTACT_USER":"xxx项目","SECTOR_NAME":"某某","CONTACT_PHONE":"18888888888","addFlag":true,"ORG_ID":1,"id":"o1","pId":"onull","open":true,"name":"3.编码落地","modFlag":true,"CORP_CAT":"港口-天然液化气,港口-液化石油气","TYPE":"org","delFlag":true},
          {"CONTACT_USER":"xxx项目","SECTOR_NAME":"某某","addFlag":true,"CONTACT_PHONE":null,"SECTOR_ID":1,"ORG_ID":1,"id":"s1","pId":"o1","name":"3.1前端开发","modFlag":true,"PARENT_ID":null,"CORP_CAT":"港口-天然液化气","TYPE":"sector","delFlag":true},
          {"CONTACT_USER":"xxx项目","SECTOR_NAME":"某某","addFlag":true,"CONTACT_PHONE":"0","SECTOR_ID":2,"ORG_ID":1,"id":"s2","pId":"s1","name":"3.1.1应用模块分析","modFlag":true,"PARENT_ID":1,"CORP_CAT":"港口-集装箱","TYPE":"sector","delFlag":true},
          {"CONTACT_USER":"xxx项目","SECTOR_NAME":"某某","addFlag":true,"CONTACT_PHONE":"0","SECTOR_ID":3,"ORG_ID":1,"id":"s3","pId":"s1","name":"3.1.2其他模块分析","modFlag":true,"PARENT_ID":1,"CORP_CAT":"港口-集装箱","TYPE":"sector","delFlag":true},
          {"CONTACT_USER":"xxx项目","SECTOR_NAME":"某某","addFlag":true,"CONTACT_PHONE":"0","SECTOR_ID":4,"ORG_ID":1,"id":"s4","pId":"s2","name":"3.1.1.1能耗界面开发","modFlag":true,"PARENT_ID":2,"CORP_CAT":"港口-集装箱","TYPE":"sector","delFlag":true},
          {"CONTACT_USER":"xxx项目","SECTOR_NAME":"某某","addFlag":true,"CONTACT_PHONE":"0","SECTOR_ID":5,"ORG_ID":1,"id":"s5","pId":"s3","name":"3.1.2.1设备监控界面开发","modFlag":true,"PARENT_ID":3,"CORP_CAT":"港口-集装箱","TYPE":"sector","delFlag":true},
          {"CONTACT_USER":"xxx项目","SECTOR_NAME":"某某","addFlag":true,"CONTACT_PHONE":"0","SECTOR_ID":8,"ORG_ID":1,"id":"s8","pId":"s2","name":"3.1.1.2个人中心界面开发","modFlag":true,"PARENT_ID":2,"CORP_CAT":"-","TYPE":"sector","delFlag":true},
          {"CONTACT_USER":"xxx项目","SECTOR_NAME":"某某","addFlag":true,"CONTACT_PHONE":"0","SECTOR_ID":9,"ORG_ID":1,"id":"s9","pId":"s2","name":"3.1.1.3空调监控界面开发","modFlag":true,"PARENT_ID":2,"CORP_CAT":"-","TYPE":"sector","delFlag":true},
          {"CONTACT_USER":"xxx项目","SECTOR_NAME":"某某","addFlag":true,"CONTACT_PHONE":null,"SECTOR_ID":38,"ORG_ID":1,"id":"s38","pId":"o1","name":"3.1后端开发","modFlag":true,"PARENT_ID":null,"CORP_CAT":"-","TYPE":"sector","delFlag":true},
          {"CONTACT_USER":"xxx项目","SECTOR_NAME":"某某","addFlag":true,"CONTACT_PHONE":null,"SECTOR_ID":61,"ORG_ID":1,"id":"s61","pId":"o1","name":"3.1调试","modFlag":true,"PARENT_ID":null,"CORP_CAT":"港口-天然液化气","TYPE":"sector","delFlag":true}]
          //初始化列表
          zTreeNodes = data;
          //初始化树
          $.fn.zTree.init($("#dataTree"), setting, zTreeNodes);
          //添加表头
          var li_head = ' <li class="head"><a><div class="diy">所属项目</div><div class="diy">任务计划名称</div><div class="diy">负责人</div>' +
              '<div class="diy">部门</div><div class="diy">类型</div><div class="diy">开始时间</div><div class="diy">结束时间</div><div class="diy">任务进度</div></a></li>';
          var rows = $("#dataTree").find('li');
          if (rows.length > 0) {
              rows.eq(0).before(li_head)
          } else {
              $("#dataTree").append(li_head);
              $("#dataTree").append('<li ><div style="text-align: center;line-height: 30px;" >无符合条件数据</div></li>')
          }
      }
      /**
       * 根据权限展示功能按钮
       * @param treeNode
       * @returns {string}
       */
      function formatHandle(treeNode) {
          var htmlStr = "<div class='progress' style='margin-left:"+(treeNode.level+treeNode.getIndex())*20 +"px'><div class='bar bar-success' style='width: 60%;'></div></div>";
          return htmlStr;
      }
  
  
      $(function () {
          //初始化数据
          query();
      })
  </script>
</body>
</html>