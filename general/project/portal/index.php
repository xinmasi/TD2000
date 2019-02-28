<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("��Ŀ����");
include_once("inc/header.inc.php");
/**
 *
 * ��Ŀ��������ҳ ��Ŀ��ѯ������ҳ
 * ����jquery.tmpl + $.getJSON ʵ��
 * ���ÿ�ѡ���� PROJ_STATUS
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
  <title>��Ŀ����</title>
  <link rel="stylesheet" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/bootstrap.css">
  
	<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
	<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
	<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/template/jquery.tmpl.min.js"></script>
	<script src="/module/DatePicker/WdatePicker.js">/*ʱ��ؼ�*/</script>

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
          <label class="control-label" for="department">���ţ�</label>
	          	<input type="hidden" name="projDept" id="projDept" value="<?=$deptId?>" />
		        <input  name="projDeptName" id="projDeptName"  type="text" value="<?=$projDeptName?>" style="width:120px"/>
		        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','projDept','projDeptName')"><?=_("ѡ��")?></a>&nbsp;
          
          <label class="control-label" for="person">�����ˣ�</label>
          
	          	<input type="text" name="leaderName" id="leaderName" style="width:100px"  value="<?=$leaderName?>" >
		        <a href="javascript:;" class="orgAdd" onClick="SelectUser('65','','leaderId', 'leaderName')"><?=_("ѡ��")?></a>
		        <input type="hidden" name="leaderId" id="leaderId" value="<?=$leaderId?>">&nbsp;
          
          <label class="control-label" for="name">��Ŀ���ƣ�</label>
          <input type="text" id="projName" name="projName" style="width:120px" value="<?=$projName?>">&nbsp;
          <label class="control-label" for="state">״̬��</label>
          <select id="PROJ_STATUS" name="PROJ_STATUS" style="width:120px" >
          	<option value=""></option>
            <option value="0">������</option>
            <option value="1">������</option>
            <option value="2">������</option>
            <option value="3">�Ѱ��</option>
            <option value="4">������</option>
          </select><br><br>
          
          <label class="control-label" for="type">��Ŀ���</label>
          
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
        
          <label class="control-label" >��ʼʱ�䣺</label>
          <input type="text" style="width:80px" id="startTime" name="startTime" value="<?=$startTime ?>">
          <label class="control-label" >����ʱ�䣺</label>
          <input type="text" style="width:80px" id="endTime"  name="endTime" value="<?=$endTime ?>">
          <input type="hidden" name="pageIndex" value="<?=$pageIndex?>">
          <button type="submit" class="btn">��ѯ</button>
        </form>
      </div>
      <div class="span2">
        <button type="submit" class="btn btn-success">�½���Ŀ</button>
      </div>
    </div>
    <p class="countProj">��Ŀ����<b>83</b>����������<b>10</b>����������<b style="color:#FF8000">3</b>����������<b>10</b>��(����<b style="color:#FF8000">2</b>��������)��������<b>2</b>�����Ѱ��<b>8</b>��</p>
   	<p id="loading"></p>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>���</th>
          <th>��������</th>
          <th>������</th>
          <th>��Ŀ���</th>
          <th>��Ŀ����</th>
          <th>��Ŀ���</th>
          <th>״̬</th>
          <th>����ʱ��</th>
          <th>��ʼʱ��</th>
          <th>����ʱ��</th>
          <th>��Ŀ����</th>
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
					 			echo "������";
					 		}else if($p['PROJ_STATUS'] == '1'){
					 			echo "������";
					 		}else if($p['PROJ_STATUS'] == '2'){
					 			echo "������";
					 		}else if($p['PROJ_STATUS'] == '3'){
					 			echo "�Ѱ��";
					 		}else if($p['PROJ_STATUS'] == '4'){
					 			echo "������";
					 		}
			   			?>   
						
					  </td>
			          <td>����ʱ��</td>
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



