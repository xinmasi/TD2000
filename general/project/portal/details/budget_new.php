<?
include_once("details.inc.php");
//Ȩ�޼��  (��Ŀ������/��Ŀ��Ա)
// $QUERY = "SELECT * FROM PROJ_PROJECT WHERE PROJ_ID = '$PROJ_ID' AND PROJ_LEADER = '$LOGIN_USER_ID' ";
// $QUERY .= " OR find_in_set('$LOGIN_USER_ID',PROJ_USER) > 0 AND PROJ_ID = '$PROJ_ID'";

// if(!project_apply_priv($PROJ_ID)){
    // Message(_("����"),_("��Ȩ�����ʽ�!"));
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
//------��ʼ������Ԫ��------
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
<!--�����-->
<div class="proj_sidebar" style="overflow: visible; padding: 0px; width: 182px;">
    <?php 
    //���������
    include_once("public_left.php"); 
    ?>
</div>
<!--������-->
<div class="proj_container">
    <div class="proj_navbar" style=" width:100%">
        <p class="proj_navbar_header">
        <strong>
            <?=_("��Ŀ����")?> >> <?=_("�ҵ���Ŀ")?> >> <?=$s_name?> >><?=_("�������")?>
        </strong>
        </p>
        <? help('009','skill/project');?>
    </div>

<!-- �Ҳ������������� -->
    <div class="proj_content" style="overflow:auto;">
        <div class="mancontent_d">
            <div class="mancontent"> 

  <form id="budget_f" class="form-horizontal" action="budget_save.php?PROJ_ID=<?=_($PROJ_ID)?>" method="POST">
    <fieldset>
      <div id="legend" class="">
        <legend class=""><?=_("��Ŀ��������")?></legend>
      </div>
     

    <div class="control-group">

          <!-- Select Basic -->
          <label class="control-label"><?=_("���ÿ�Ŀ")?></label>
          <div class="controls">
            <select class="input-xlarge" name="type_code">
<?
        	$select_budget_type = "select id,type_name from proj_budget_type where char_length(type_no) = 6";
        	$res_cursor_length = exequery(TD::conn(),$select_budget_type);
            while($a_budget_type = mysql_fetch_array($res_cursor_length))
            {
                $s_type_name = $a_budget_type["type_name"];     //��Ŀ����
                $i_type_code = $a_budget_type["id"];            //��ĿID
                echo "<option value='$i_type_code'>$s_type_name</option>";
            }
?>
      </select>
          </div>

        </div>

    

    <div class="control-group">

          <!-- Text input-->
          <label class="control-label" for="input01"><?=_("��� (Ԫ)")?></label>
          <div class="controls">
            <input type="text" id="input_money" name="budget_amount" t_value="����������" placeholder="<?=_("��������")?>" class="input-xlarge" onkeypress="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onkeyup="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" />
            <input type="hidden" name="PROJ_ID" value="<?=$PROJ_ID?>">
            <p class="help-block"></p>
          </div>
    </div>
    




    
 
    <div id="record" class="control-group" >

          <!-- Text input-->
          <label class="control-label" for="input01"><?=_("�ʽ�˵��")?></label>
          <div class="controls">
            <textarea rows="15" name="budget_record" placeholder="<?=_("�����뱸ע��Ϣ���ʽ���ϸ")?>" style="border:1px solid #ccc; width:60%; height:35%; padding:5px;"></textarea>
            
            <p class="help-block"></p>
          </div>
    </div> 

    <script type="text/javascript">
        function check(){
            if(parseFloat($("#input_money").val()) > 9999999999999998){
                alert("�ʽ����볬���޶�!");
                return ;
            }else if(parseFloat($("#input_money").val()) <= 0 || $("#input_money").val() ==""){
                alert("�ʽ����������0!");
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
            <button type="button" onclick="check();" class="btn btn-info"><?=_("�ύ")?></button>
           
            <button type="button" class="btn btn-info" onclick="javascript:history.back();"><?=_("����")?></button>
            
            <a href="budget_record.php?PROJ_ID=<?=_($PROJ_ID)?>&VALUE=4" class="btn-link"><?=_("�����¼")?></a>
          </div>
        </div>

    </fieldset>
  </form>

            </div>
        </div>
    </div>
<!-- �Ҳ������������� END -->
</div>

</body>
</html>