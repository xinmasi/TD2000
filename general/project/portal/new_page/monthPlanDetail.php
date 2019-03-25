<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�¼ƻ�");
include_once("inc/header.inc.php");
/**
 *
 * ��Ŀ����������ҳ ��Ŀ��ѯ������ҳ
 * ����jquery.tmpl + $.getJSON ʵ��
 * ���ÿ�ѡ���� PROJ_STATUS
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
  <title>�¼ƻ�</title>
  <link rel="stylesheet" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/bootstrap.css">
  
	<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
	<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
	<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/template/jquery.tmpl.min.js"></script>
	<script src="/module/DatePicker/WdatePicker.js">/*ʱ��ؼ�*/</script>
	
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
  /*��ť*/  
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

  /*end--��ť*/

  /*ztree����*/
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
      width: 16%;
  }
  .ztree div.diy:nth-child(2) {
      width: 20%;
  }
  .ztree div.diy:nth-child(3) {
      width: 6%;
  }
  .ztree div.diy:nth-child(4) {
      width: 6%;
  }
  .ztree div.diy:nth-child(5) {
      width: 6%;
  }
  .ztree div.diy:nth-child(6) {
      width: 6%;
  }
  .ztree div.diy:nth-child(7) {
      width: 8%;
  }
  .ztree div.diy:nth-child(8) {
      width: 8%;
  }
  .ztree div.diy:nth-child(9) {
      width: 8%;
  }
  .ztree div.diy:nth-child(10) {
      width: 8%;
  }
  .ztree div.diy:nth-child(11) {
      width: 8%;
  }
  .ztree div.diy:nth-child(12) {
      width: 8%;
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

  /*end--ztree����*/
  </style>
</head>
<body>
    <?php include_once("./proj_menu.php"); ?>
  <div class="container-fluid">
    <form class="form-inline">
      <label class="control-label" for="month">�·ݣ�</label>
      <input type="text" id="month" name="month" style="width:100px;" onfocus="selectMonth()" value="<?=$month?>"/>
      
     	  <label class="control-label" for="department">���ţ�</label>
	          	<input type="hidden" name="projDept" id="projDept" value="<?=$deptId?>" />
		        <input  name="projDeptName" id="projDeptName"  type="text" value="<?=$projDeptName?>" style="width:120px"/>
		        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','projDept','projDeptName')"><?=_("ѡ��")?></a>&nbsp;
          
          <label class="control-label" for="person">�����ˣ�</label>
          
	          	<input type="text" name="leaderName" id="leaderName" style="width:100px"  value="<?=$leaderName?>" >
		        <a href="javascript:;" class="orgAdd" onClick="SelectUser('65','','leaderId', 'leaderName')"><?=_("ѡ��")?></a>
		        <input type="hidden" name="leaderId" id="leaderId" value="<?=$leaderId?>">&nbsp;
      			<input type="hidden" name="pageIndex" value="<?=$pageIndex?>">
      			<input type="hidden" name="dateType" value="month">
      			
      <button type="submit" class="btn">��ѯ</button>
    </form>
    <h4>��Ŀ�ƻ� <a href="javascript:history.go(-1);" class="pull-right"> �������� </a></h4>
    <div id="tableMain">
      <ul id="dataTree" class="ztree"></ul>
    </div>
    
    <?php 
    	
//     	echo $query;
    ?>
    
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
       * �Զ���DOM�ڵ�
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
          editStr += '<div class="diy">' + (treeNode.USER_NAME == null ? '&nbsp;' : treeNode.USER_NAME) + '</div>';
          editStr += '<div class="diy">' + (treeNode.DEPT_NAME == null ? '&nbsp;' : treeNode.DEPT_NAME) + '</div>';
          var corpCat = '<div title="' + treeNode.CORP_CAT + '">' + treeNode.CORP_CAT + '</div>';
          editStr += '<div class="diy">'+ treeNode.TASK_START_TIME +'</div>';
          editStr += '<div class="diy">'+ treeNode.TASK_END_TIME +'</div>';
          editStr += '<div class="diy">' + treeNode.TASK_PERCENT_COMPLETE + '%</div>';
          editStr += '<div class="diy"></div>';
          editStr += '<div class="diy"></div>';
          editStr += '<div class="diy"></div>';
          editStr += '<div class="diy"></div>';
          editStr += '<div class="diy"></div>';
          aObj.append(editStr);
      }
      /**
       * ��ѯ����
       */
      function query() {
          var data = <?=$jsonDate?>;
//               [
//           {"CONTACT_USER":"xxx��Ŀ","SECTOR_NAME":"ĳĳ","CONTACT_PHONE":"18888888888","addFlag":true,"ORG_ID":1,"id":"o1","pId":"onull","open":true,"name":"3.�������","modFlag":true,"CORP_CAT":"�ۿ�-��ȻҺ����,�ۿ�-Һ��ʯ����","TYPE":"org","delFlag":true},
//           {"CONTACT_USER":"xxx��Ŀ","SECTOR_NAME":"ĳĳ","addFlag":true,"CONTACT_PHONE":null,"SECTOR_ID":1,"ORG_ID":1,"id":"s1","pId":"o1","name":"3.1ǰ�˿���","modFlag":true,"PARENT_ID":null,"CORP_CAT":"�ۿ�-��ȻҺ����","TYPE":"sector","delFlag":true},
          //��ʼ���б�
          zTreeNodes = data;
          //��ʼ����
          $.fn.zTree.init($("#dataTree"), setting, zTreeNodes);
          //���ӱ�ͷ
          var li_head = ' <li class="head"><a><div class="diy">������Ŀ</div><div class="diy">����ƻ�����</div><div class="diy">������</div>' +
              '<div class="diy">����</div><div class="diy">��ʼʱ��</div><div class="diy">����ʱ��</div><div class="diy" >�������</div>'+
              '<div class="diy">�ƻ���ֵ</div><div class="diy">��ɻ���</div><div class="diy">���˼���˵��</div><div class="diy">�쵼���ӻ�Ӧ</div><div class="diy">ȷ����</div>  </a></li>';
          var rows = $("#dataTree").find('li');
          if (rows.length > 0) {
              rows.eq(0).before(li_head)
          } else {
              $("#dataTree").append(li_head);
              $("#dataTree").append('<li ><div style="text-align: center;line-height: 30px;" >�޷�����������</div></li>')
          }
      }
      /**
       * ����Ȩ��չʾ���ܰ�ť
       * @param treeNode
       * @returns {string}
       */
      function formatHandle(treeNode) {
          var date01 = "<?=$month?>"+"-01";
//           var startNum = treeNode.TASK_START_TIME.substr(-2)*1;
          var startNum = datedifference(date01,treeNode.TASK_START_TIME);
          var diffDay = datedifference(treeNode.TASK_START_TIME,treeNode.TASK_END_TIME);
		  var divWidth = (diffDay+1)*16+"px"; 
		  
          
          var htmlStr = "<div class='progress' style='margin-left:"+(startNum)*16 +"px;width:"+divWidth+";'><div class='bar bar-success' style='width: "+treeNode.TASK_PERCENT_COMPLETE +"%;'></div></div>";
          return htmlStr;
      }
  
  
      $(function () {
          //��ʼ������
          query();
      })
      
      function datedifference(sDate1, sDate2) {    //sDate1��sDate2��2006-12-18��ʽ  
        var dateSpan,
            tempDate,
            iDays;
        sDate1 = Date.parse(sDate1);
        sDate2 = Date.parse(sDate2);
        dateSpan = sDate2 - sDate1;
//         dateSpan = Math.abs(dateSpan);
        iDays = Math.floor(dateSpan / (24 * 3600 * 1000));
        return iDays
    };

    function selectMonth() {  
        WdatePicker({ dateFmt: 'yyyy-MM', isShowToday: false, isShowClear: false });  
    }  
    
  </script>
</body>
</html>