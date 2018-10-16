<?
include_once("details.inc.php");
//权限检测  (项目负责人/项目成员)
// $QUERY = "SELECT * FROM PROJ_PROJECT WHERE PROJ_ID = '$PROJ_ID' AND PROJ_LEADER = '$LOGIN_USER_ID' ";
// $QUERY .= " OR find_in_set('$LOGIN_USER_ID',PROJ_USER) > 0 AND PROJ_ID = '$PROJ_ID'";

// if(!project_apply_priv($PROJ_ID)){
    // Message(_("错误"),_("无权申请资金!"));
    // Button_Back();
    // exit();    
// }
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/project/css/project.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="/inc/js_lang.php"></script>
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
        $('.proj_content').width(width-20);
        $('.mancontent_d').height(height-102);
        $('.mancontent').height(height-204);
        $('.scrollzone').height(height-234);
    }
    
    $(window).resize(function(){init();});
    $(document).ready(function(){init();});
});
</script>

<body style=" overflow:hidden;">
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
            <?=_("项目管理")?> >> <?=_("我的项目")?> >> <?=$s_name?> >><?=_("申请费用")?>
        </strong>
        </p>
        <? help('009','skill/project');?>
    </div>

<!-- 右侧主体内容区域 -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <div class="mancontent"> 

  <form id="budget_f" class="form-horizontal" action="budget_save.php?PROJ_ID=<?=_($PROJ_ID)?>" method="POST">
    <fieldset>
      <div id="legend" class="">
        <legend class=""><?=_("项目费用申请")?></legend>
      </div>
     

    <div class="control-group">

          <!-- Select Basic -->
          <label class="control-label"><?=_("费用科目")?></label>
          <div class="controls">
            <select class="input-xlarge" name="type_code">
<?
        	$select_budget_type = "select id,type_name from proj_budget_type where char_length(type_no) = 6";
        	$res_cursor_length = exequery(TD::conn(),$select_budget_type);
            while($a_budget_type = mysql_fetch_array($res_cursor_length))
            {
                $s_type_name = $a_budget_type["type_name"];     //科目名称
                $i_type_code = $a_budget_type["id"];            //科目ID
                echo "<option value='$i_type_code'>$s_type_name</option>";
            }
?>
      </select>
          </div>

        </div>

    

    <div class="control-group">

          <!-- Text input-->
          <label class="control-label" for="input01"><?=_("金额 (元)")?></label>
          <div class="controls">
            <input type="text" id="input_money" name="budget_amount" t_value="请输入数字" placeholder="<?=_("请输入金额")?>" class="input-xlarge" onkeypress="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onkeyup="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" />
            <input type="hidden" name="PROJ_ID" value="<?=$PROJ_ID?>">
            <p class="help-block"></p>
          </div>
    </div>
    




    
 
    <div id="record" class="control-group" >

          <!-- Text input-->
          <label class="control-label" for="input01"><?=_("资金说明")?></label>
          <div class="controls">
            <textarea rows="15" name="budget_record" placeholder="<?=_("请输入备注信息或资金明细")?>" style="border:1px solid #ccc; width:60%; height:35%; padding:5px;"></textarea>
            
            <p class="help-block"></p>
          </div>
    </div> 

    <script type="text/javascript">
        function check(){
            if(parseFloat($("#input_money").val()) > 9999999999999998){
                alert("资金申请超出限额!");
                return ;
            }else if(parseFloat($("#input_money").val()) <= 0 || $("#input_money").val() ==""){
                alert("资金申请金额大于0!");
                return ;
            }else{
                $("#budget_f").submit();
            }
        }
    </script>    
    
    <div class="control-group">
          <label class="control-label"></label>

          <!-- Button -->
          <div class="controls">
            <button type="button" onclick="check();" class="btn btn-info"><?=_("提交")?></button>
           
            <button type="button" class="btn btn-info" onclick="javascript:history.back();"><?=_("返回")?></button>
            
            <a href="budget_record.php?PROJ_ID=<?=_($PROJ_ID)?>&VALUE=4" class="btn-link"><?=_("申请记录")?></a>
          </div>
        </div>

    </fieldset>
  </form>

            </div>
        </div>
    </div>
<!-- 右侧主体内容区域 END -->
</div>

</body>
</html>