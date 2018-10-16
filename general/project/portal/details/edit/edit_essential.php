<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_project.php");
include_once("inc/utility_org.php");
//include_once("portal/new/index.php");  //这个是有报错 自己去除了
include_once("inc/sys_code_field.php");  //spz 10.24
?>
<html>

<style type="text/css">
select.BigSelect  { COLOR: #000066;  border: 1px solid #C0BBB4; background: white url('../10/images/bg_input_text.png') top left repeat-x; BORDER-BOTTOM:1px double; FONT-SIZE: 10pt; FONT-STYLE: normal; FONT-VARIANT: normal; FONT-WEIGHT: normal; HEIGHT: 25px; LINE-HEIGHT: normal}
select.BigSelect:hover  { COLOR: #000066;  border: 1px solid #C0BBB4; background: white url('../10/images/bg_input_text_hover.png') top left repeat-x; BORDER-BOTTOM:1px double; FONT-SIZE: 10pt; FONT-STYLE: normal; FONT-VARIANT: normal; FONT-WEIGHT: normal; HEIGHT: 25px; LINE-HEIGHT: normal}

</style>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>


<script>


var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";

function InsertImage(src)
{
   AddImage2Editor('PROJ_DESCRIPTION', src);
}

function CheckForm()
{
	if(document.form1.PROJ_NUM.value=="")
		{
	    	alert("<?=_("项目编号不能为空！")?>");
	     	return (false);
		}
	if(document.form1.PROJ_NAME.value=="")
		{
	    	alert("<?=_("项目名称不能为空！")?>");
	     	return (false);
		}
	if(document.form1.PROJ_TYPE.value=="")
		{
	    	alert("<?=_("项目类别不能为空！")?>");
	     	return (false);
		}
	if(document.form1.PROJ_USER_TO_NAME.value=="")
		{
	    	alert("<?=_("项目创建者不能为空！")?>");
	     	return (false);
		}

	if(document.form1.PROJ_LEADER_TO_NAME.value=="")
		{
	    	alert("<?=_("项目负责人不能为空！")?>");
	     	return (false);
		}
	if(document.form1.PROJ_START_TIME.value=="")
		{
	    	alert("<?=_("开始时间不能为空！")?>");
	     	return (false);
		}
	if(document.form1.PROJ_END_TIME.value=="")
		{
	    	alert("<?=_("结束时间不能为空！")?>");
	     	return (false);
		}
	if(document.form1.PROJ_END_TIME.value<document.form1.PROJ_START_TIME.value)
		{
	    	alert("<?=_("结束时间不能小于开始时间！")?>");
	     	return (false);
		}
	return (true);
}



</script>

<script type="text/javascript">
var msg = "";

function ask(){

    <?
    if($s_manager_id == $_SESSION['LOGIN_USER_ID']){
    ?>
        document.getElementById("form1").submit();
        return ;
    <?
        }
    ?>

    if(msg !== ""){
        alert(msg);
        return ;
    }
    <? 
    if($i_status =="0"){
    ?>
    document.getElementById("form1").submit();
    <?
    }else if(check_project_priv() == 2){
    ?>
    document.getElementById("form1").submit();
    <?
    }else if(check_project_priv() == 1){
    ?>
    alert("没有审批人员请先添加项目审批人员!");
    <?
    }else  {
     ?>
    if(confirm("修改项目需要提交审核!确认提交?")){
        document.getElementById("form1").submit();
    }
    <?
    }
    ?>
}

function jisuan(){
    var objs = $(".mnum");
    var num = objs.length;
    var m = 0;
    msg = "";
    for(i=0;i<num;i++){
        if(isNaN(objs.eq(i).val().trim())){
            msg += objs.eq(i).attr("type_name") + "必须为数字 \n";
        }
        m += parseFloat(objs.eq(i).val().trim());
    }

    if(m > 999999999999999 + 1){
        msg += "金额超出范围 \n";
    }
    
    if(msg === ""){
        $("#total").val(m);
    }else{
        alert(msg);
    }
}

$(function(){
    $(".mnum").change(function(){
        jisuan();
    })
})

////spz 2016.10.24
//新修改的  关于项目的  全局字段和自定义字段
function aa(CODE_ID){
	var PROJ_ID = <?=$i_proj_id?>;
    _get('/inc/sys_code_field_get.php',"CODE_ID="+CODE_ID+"&PROJ_ID="+PROJ_ID,callback);
}

function callback(req){
	
    if(req.status==200)
    {  
        if(req.responseText)
        {
		    document.getElementById("DEFINE_SYSCODE_CONTENT").innerHTML = '';
			document.getElementById("DEFINE_SYSCODE_CONTENT").innerHTML = req.responseText;
        	document.getElementById("DEFINE_SYSCODE_CONTENT").style.display = '';
        }else{
            document.getElementById("DEFINE_SYSCODE_CONTENT").innerHTML = '';
        	document.getElementById("DEFINE_SYSCODE_CONTENT").style.display = 'none';   
        }
    }   
} 
//这个也是为了执行自定义字段的查询
window.onload=myfun; 
function myfun()
{
	var CODE_ID = <?=$i_type_id?>;
	var PROJ_ID = <?=$i_proj_id?>;
    _get('/inc/sys_code_field_get.php',"CODE_ID="+CODE_ID+"&PROJ_ID="+PROJ_ID,callback);
}	  
</script>

<body>

<style>
.tdspan span{
    display:block;
}
</style>

 <div style="overflow-y:auto;height:90%">
<form action="edit_details.php?PROJ_ID=<?=$i_proj_id?> && PROJ_STATUS=<?=$i_status?> " method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="return CheckForm();">
 <table class="table table-bordered table-striped" width="100%" align="center" border='1'>
  <tr class="info">
      <td nowrap align="center" colspan='4'><?=_("项目详情基本信息")?></td>
  </tr>
  <tbody>
  <tr>
	<td nowrap align="left" width="15%"><?=_("项目编号")?></td>
    <td nowrap align="center"><input type="text" name="PROJ_NUM"  style="height:26px; width:180px" value="<?=$s_num?>"/></td>
    <td nowrap align="left" width="15%"><?=_("项目名称")?></td>
    <td nowrap align="center"><input type="text" name="PROJ_NAME"  style="height:26px; width:180px" value="<?=$s_name?>"/></td>
  </tr>
  
    <tr>
    <td nowrap align="left" width="15%"><?=_("项目类别")?></td>
    <td nowrap align="center">
		<select name="PROJ_TYPE"  id="USER_CUST_DEFINE"  style="width:192px" onchange="aa(this.options[this.selectedIndex].value)">
                      <option selected="selected" value='<?=$i_type_id?>'><?=$s_type?></option>
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
	</td>
	<td nowrap align="left" width="15%"><?=_("参与部门")?></td>
    <td nowrap align="center">
		<input type="hidden" name="PROJ_DEPT" id="PROJ_DEPT" value="<?=$s_dept_id?>">
        <textarea cols=40  name="PROJ_DEPT_NAME" rows=2 wrap="yes" readonly  style="width:192px"><?=$s_dept?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','PROJ_DEPT','PROJ_DEPT_NAME')"><?=_("选择")?></a>&nbsp;
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PROJ_DEPT','PROJ_DEPT_NAME')"><?=_("清空")?></a> 
	</td>
  </tr>
  <tr>
    <td nowrap align="left" width="15%"><?=_("创建者")?></td>
    <td nowrap align="center">
		<input type="text" name="PROJ_USER_TO_NAME"  style="width:180px"  value="<?=$s_owner?>" readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('65','','PROJ_USER_TO_ID', 'PROJ_USER_TO_NAME')"><?=_("选择")?></a>
        <input type="hidden" name="PROJ_USER_TO_ID" id="PROJ_USER_TO_ID" value="<?=$s_owner_id?>">
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PROJ_USER_TO_ID', 'PROJ_USER_TO_NAME')"><?=_("清空")?></a>
	</td>
	
	<td nowrap align="left" width="15%"><?=_("负责人")?></td>
    <td nowrap align="center" class="TableData">
		<input type="text" name="PROJ_LEADER_TO_NAME" style="height:26px; width:180px"  value="<?=$s_leader?>" readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('65','','PROJ_LEADER', 'PROJ_LEADER_TO_NAME')"><?=_("选择")?></a>
        <input type="hidden" name="PROJ_LEADER" id="PROJ_LEADER" value="<?=$s_leader_id?>">
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PROJ_LEADER', 'PROJ_LEADER_TO_NAME')"><?=_("清空")?></a>
	</td>
  </tr>
  
    <!--此处存放全局变量--> 
	<tr> 
		<td class="TableData" colspan="4" id="DEFINE_SYSCODE_CONTENT_G"><?=proj_get_field_table_g(proj_get_field_html('G'.$i_type_id,$i_proj_id))?></td>
    </tr>
    <tr>  
        <td class="TableData" colspan="4" id="DEFINE_SYSCODE_CONTENT" style="display:none"> </td>
    </tr>
  
  
  
  
  <tr>
	<td nowrap align="left" width="15%"><?=_("项目查看者")?></td>
	<td nowrap align="center">
    <textarea cols=40   name="PROJ_VIEWER_NAME" rows=2 wrap="yes" style="width:192px" readonly ><?=$s_viewer?></textarea>
    <input type="hidden" name="PROJ_VIEWER" id="PROJ_VIEWER" value="<?=$s_viewer_id?>" />
    <a href="javascript:;" class="orgAdd" onClick="SelectUser('65','','PROJ_VIEWER', 'PROJ_VIEWER_NAME')"><?=_("选择")?></a>
    <a href="javascript:;" class="orgClear" onClick="ClearUser('PROJ_VIEWER', 'PROJ_VIEWER_NAME')"><?=_("清空")?></a>
	</td>
	<td nowrap align="left" width="15%"><?=_("项目级别")?></td>
    <td nowrap align="center">
         <?php if($s_level == "a"){?>
         <input type="radio" name="PROJ_LEVEL"  value="a" checked><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/a.png' title='<?=_("项目级别")?>'>
         <input type="radio" name="PROJ_LEVEL"  value="b" ><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/b.png' title='<?=_("项目级别")?>'>
         <input type="radio" name="PROJ_LEVEL"  value="c" ><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/c.png' title='<?=_("项目级别")?>'>
         <?php 
         }else if($s_level == "b"){?>
         <input type="radio" name="PROJ_LEVEL"  value="a" ><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/a.png' title='<?=_("项目级别")?>'>
         <input type="radio" name="PROJ_LEVEL"  value="b" checked><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/b.png' title='<?=_("项目级别")?>'>
         <input type="radio" name="PROJ_LEVEL"  value="c" ><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/c.png' title='<?=_("项目级别")?>'>
         <?php
         }else if($s_level == "c"){?>
         <input type="radio" name="PROJ_LEVEL"  value="a" ><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/a.png' title='<?=_("项目级别")?>'>
         <input type="radio" name="PROJ_LEVEL"  value="b" ><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/b.png' title='<?=_("项目级别")?>'>
         <input type="radio" name="PROJ_LEVEL"  value="c" checked><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/c.png' title='<?=_("项目级别")?>'>
         <?php 
         }
         ?>
	</td>
  </tr>
  <tr>
    <td nowrap align="left" width="15%" ><?=_("项目计划周期")?></td>
    <td nowrap align="center" >
		<input type="text" class="SmallInput" style="width:85px" onClick="WdatePicker()" name="PROJ_START_TIME" id="PROJ_START_TIME" value="<? echo $s_start_time?>"/> 
         <?=_("至")?>
         <input type="text" class="SmallInput" style="width:85px" onClick="WdatePicker()" name="PROJ_END_TIME" id="PROJ_END_TIME" value="<? echo $s_end_time?>"/> 
	</td>
    <!--zfc-->
    <td nowrap align="left" width="15%" ><?=_("项目审批人")?></td>
    <td nowrap align="center">
        <?php
            $priv = check_project_priv();
	    if($priv == 2)
	    {
	    echo "免审批";
	    }
	    else if($priv!=1)
	    {
            $username = GetUserNameById($priv);
            $user_array = explode(",", td_trim($priv));
            $user_name_array = explode(",", td_trim($username));
        ?>
		<select name="PROJ_MANAGER"  style="width:192px" >
            <option selected="selected" value='<?=$s_manager_id?>'><?=$s_manager?></option>
            <?php
            foreach($user_name_array as $key => $val){
            ?>
                <option value='<?=$user_array[$key]?>'><?=$val?></option>
            <?php
            }
            ?>
		</select>
		<?
		}
		else
		{
		echo "未设置审批人";
		}
		?>
        

        
	</td>
    
  </tr>
  <tr>
	<td nowrap align="left" width="15%"><?=_("项目描述")?></td>
    <td nowrap align="center"  colspan='3'>
  		<div class="controls" style="width: 930px;">
                        <?
                        include_once ("inc/editor.php");
                        $editor = new Editor('PROJ_DESCRIPTION') ;
                        $editor->ToolbarSet = 'Default';
                        $editor->Height = stristr($HTTP_USER_AGENT, 'iPad') ? '130' : '200' ;
                        $editor->Config = array("contentsCss" => "body{font-size:".$FONT_SIZE."pt;}","model_type" => "02");
                        $editor->Value = $s_description ;
                        $editor->Create() ;
                        ?>
        </div>
	</td>
  </tr>
  <!--zfc-->

    <?
	$query = "select ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID'";
	$cursor = exequery(TD::conn(), $query);
	if($ROW=mysql_fetch_array($cursor))
	{
		$ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
		$ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
	}
    ?>
    
    <tr>
        <td nowrap class="TableContent"><?=_("附件文档：")?></td>
        <td nowrap class="TableData tdspan" colspan="3">
            <?
            if($ATTACHMENT_ID=="")
                echo _("无附件");
            else
                echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,0,1);
            ?>
        </td>
        <script type="text/javascript">
            jQuery(function(){
                var obj = "";
                jQuery(".attach_link").mouseenter(function(){
                    obj = jQuery(this).attr("id");
                })
                
                jQuery(".attach_link").mousemove(function(e){
                     var x = e.pageX;
                     var y = e.pageY;
                     jQuery("#"+obj+"_menu").css({"top":y+3,"left":x+3});
                })
                
            })
        </script>
        
    </tr>
    <tr>
        <td nowrap class=""><?=_("附件选择：")?></td>
        <td class="TableData" colspan="3">
           <script>ShowAddFile();</script>
           <script>ShowAddImage();</script>
        </td>
    </tr> 
    
 <tr>
 <td><?=_("资金预算信息")?></td>
 <td nowrap align="center"  colspan='3'>

<div class="control-group">
    <label class="control-label" for="total"><?=_("总预算资金")?></label>
        <div class="controls">
            <input type="text" class="span2" name="COST_MONEY" id="total" value="<?=$f_cost_money?>" readonly />
                <?=_("元")?> <a href="#" id="budget_add" ><?=_("添加明细")?></a>
        </div>
</div>    
<div id="budget_hide_div" style="display:none">        
    <div class="accordion" id="accordion_cost">
    <?php
        //查询TYPE_NO的长度为3的
        $i_type_length = "select TYPE_NAME, TYPE_NO from proj_budget_type where CHAR_LENGTH(TYPE_NO) = 3";
        $s_type_length = exequery(TD::conn(), $i_type_length);
            $i = $j= 0;
            while($a_type_row = mysql_fetch_array($s_type_length))
            {	
                $first_type_name = $a_type_row['TYPE_NAME'];
                $first_type_no = $a_type_row['TYPE_NO'];
                
    ?>
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_cost" href="#collapse_<?=$first_type_no?>">
                    <?=$first_type_name?>
                </a>
            </div>
            <div id="collapse_<?=$first_type_no?>" class="accordion-body collapse in">
                <div class="accordion-inner">
                    <?
                    //查询TYPE_NO的长度为6的同时前3为要跟$TYPE_NO一样的
                    $i_type_next = "select * from proj_budget_type where TYPE_NO like '".$first_type_no."%' and CHAR_LENGTH(TYPE_NO) = 6";
                    $s_type_next = exequery(TD::conn(), $i_type_next);
                    while($a_next_row = mysql_fetch_array($s_type_next))
                    {   
                        $type_no = $a_next_row['type_no'];
                        $type_name = $a_next_row['type_name'];
						$type_code = $a_next_row['id'];
						$s_budget_query = "select id,BUDGET_AMOUNT from proj_budget where type_code ='$type_code' and PROJ_ID ='$i_proj_id'";
                   	    $s_budget_amount = exequery(TD::conn(), $s_budget_query);
						$a_budget_row = mysql_fetch_array($s_budget_amount);
						$f_budget_amount = $a_budget_row["BUDGET_AMOUNT"];
                        $id = $a_budget_row["id"];
                        
						if($f_budget_amount==""){
							$f_budget_amount =0.00;
						}
                    ?>
                    <div class="control-group">
                        <label class="control-label" for="BUDGET_AMOUNT_<?=$i;?>"><?=$type_name?></label>
                        <div class="controls">
                            <input type="hidden" name="z_budget_id<?=$i?>" value="<?=$id?>" >
                            <input type="hidden" name="type_id<?=$i?>" value="<?=$type_code?>" >
                            <input type="text" type_name="<?=$type_name?>" class="mnum SmallInput span2 budget-amount" name="BUDGET_AMOUNT_<?=$i;?>" id="BUDGET_AMOUNT_<?=$i;?>"  onKeyPress="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onKeyUp="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" value=<?php echo $f_budget_amount;?>   style="count"/><?=_(元)?>
                        </div>
                    </div>
                    <?php
                      $i++;
                      $num = $i;                      
                    }
                    ?>
                    <input type="hidden" name="type_num" value="<?=$num?>" />
                </div>
            </div>
        </div>
        <? 
        $j++;        
        }
        if($j == 0)
            {
               echo _("您没有设置费用科目，请联系系统管理员");
            }
        ?>
    </div>  
    <div align="center">
        <input class="btn" type="button" id="budget_save" value="<?=_("计算")?>">
        &nbsp;&nbsp;&nbsp;
        <input class="btn" type="button" id="budget_delete" value="<?=_("取消")?>">
    </div>
</div>
</fieldset>
</td>
 </tr> 
  <tr>
    <td align="center" colspan="4">
      <span class="pull-right">  
	  <input class="btn btn-success" id="save_all" type="button" onclick="ask()"  name="sub"  value=" <?=_("保存")?> "/>&nbsp;
      <input class="btn btn-info" type="button" name="sub"  value=" <?=_("返回")?> " onClick="location.href='proj_detail.php?VALUE=<?=($_GET['VALUE'])?>&PROJ_ID=<?=($_GET['PROJ_ID'])?>'"/>
    </span>
    </td>
  </tr>
  </tbody>
 </table>
 
 
         <input type="hidden" name="ATTACHMENT_ID_OLD" id="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" id="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
        <input type="hidden" name="CHECK_NO_FLAG" id="CHECK_NO_FLAG">
 
</form>
</div>

</body>
</html>