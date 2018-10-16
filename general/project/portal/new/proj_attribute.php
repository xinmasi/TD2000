<div>
<fieldset style="padding-top:15px;">
    <div class="control-group">
        <label class="control-label" for="PROJ_NUM"><span style="color:red"> * </span><?=_("��Ŀ���")?></label>
        <div class="controls">
            <input type="text" name="PROJ_NUM" id="PROJ_NUM" placeholder="<?=_("��Ŀ���")?>" value="<?=date("Ymdhim")?>" maxlength="40" >
            <span id="NUM" >Ĭ��Ϊ���������ַ���</span>
        </div>
    </div>
<div class="control-group">
    <label class="control-label" for="USER_CUST_DEFINE"><span style="color:red"> * </span><?=_("��Ŀ����")?></label>
    <div class="controls">
        <select name="PROJ_TYPE"  id="USER_CUST_DEFINE" onchange="get_define_type(this.options[this.selectedIndex].value)">
            <?=code_list("PROJ_TYPE",'');?>
        </select>
        <span id="CUST_DEFINE" style="color:red"></span>
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="PROJ_NAME"><span style="color:red"> * </span><?=_("��Ŀ����")?></label>
    <div class="controls">
        <input  type="text"  name="PROJ_NAME" id="PROJ_NAME" placeholder="<?=_("��Ŀ����")?>">
        <span id="NAME" style="color:red"></span>
    </div>
</div>
<div class="control-group">
    <label class="control-label"><span style="color:red"> * </span><?=_("��Ŀ����")?></label>
    <div class="controls">
       <input type="text" class="SmallInput" style="width:85px" onClick="WdatePicker()" name="PROJ_START_TIME" id="PROJ_START_TIME" value="<?=date("Y-m-d")?>"/> 
        <?=_("��")?>
       <input type="text" class="SmallInput" style="width:85px" onClick="WdatePicker()" name="PROJ_END_TIME" id="PROJ_END_TIME" value="<?=date("Y-m-d",strtotime("+1 month"))?>"/> 
       <span id="TIME" >Ĭ��Ϊһ����</span>
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="PROJ_LEVEL" ><span style="color:red"> * </span><?=_("��Ŀ����")?></label>
    <div class="controls">
        <input type="radio" name="PROJ_LEVEL"  value="a" ><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/a.png' title='<?=_("A����Ŀ")?>'>
        <input type="radio" name="PROJ_LEVEL"  value="b" ><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/b.png' title='<?=_("B����Ŀ")?>'>
        <input type="radio" name="PROJ_LEVEL" checked value="c" ><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/c.png' title='<?=_("C����Ŀ")?>'>
        <span id="LEVEL" style="color:red"></span>
    </div>
</div>
<div class="control-group">
    <label class="control-label" ><?=_("���벿��")?></label>
    <div class="controls">
        <input type="hidden" name="PROJ_DEPT" id="PROJ_DEPT">
        <textarea cols=40 name="PROJ_DEPT_NAME" rows=3 wrap="yes" readonly style="width:250px;"></textarea>
        <a href="javascript:;" onClick="SelectDept('','PROJ_DEPT','PROJ_DEPT_NAME')"><?=_("���")?></a>&nbsp;
        <a href="javascript:;" onClick="ClearUser('PROJ_DEPT','PROJ_DEPT_NAME')"><?=_("���")?></a> 
    </div>
</div>
<div class="control-group">
    <label class="control-label"><?=_("��Ŀ����")?></label>
    <div class="controls">
<?php
        $editor = new Editor("PROJ_DESCRIPTION");
        $editor->Height = '450';
        $editor->Width = '450';
        $editor->Value = $PROJ_DESCRIPTION;
        $editor->Config = array("contentsCss" => "body{font-size:".$FONT_SIZE."pt;}","model_type" =>"04");
        $editor->Create();
?>
    </div>
</div>
</fieldset>
<fieldset>
<legend><?=_("�ʽ�Ԥ����Ϣ")?></legend>
<div class="control-group">
    <label class="control-label" for="total"><?=_("��Ԥ���ʽ�")?></label>
        <div class="controls">
            <input type="text" class="span2" name="COST_MONEY" id="total" value="0.00" readonly />
                <?=_("Ԫ")?> <a href="#" id="budget_add" ><?=_("�����ϸ")?></a>
        </div>
</div>    
<div id="budget_hide_div" style="display:none">        
    <div class="accordion" id="accordion_cost">
<?php
    //��ѯTYPE_NO�ĳ���Ϊ3��
    $i_type_length = "select TYPE_NAME, TYPE_NO from proj_budget_type where CHAR_LENGTH(TYPE_NO) = 3";
    $s_type_length = exequery(TD::conn(), $i_type_length);
        $i = $j = 0;
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
                    //��ѯTYPE_NO�ĳ���Ϊ6��ͬʱǰ3ΪҪ��$TYPE_NOһ����
                    $i_type_next = "select * from proj_budget_type where TYPE_NO like '".$first_type_no."%' and CHAR_LENGTH(TYPE_NO) = 6";
                    $s_type_next = exequery(TD::conn(), $i_type_next);
                    while($a_next_row = mysql_fetch_array($s_type_next))
                    {   
                        $type_no = $a_next_row['type_no'];
                        $type_name = $a_next_row['type_name'];
                    ?>
                    <div class="control-group">
                        <label class="control-label" for="BUDGET_AMOUNT_<?=$i;?>"><?=$type_name?></label>
                        <div class="controls">
                            <input type="hidden" name="type_name<?=$i?>" value="<?=$type_name?>" >
                            <input type="text" class="SmallInput span2 budget-amount" name="BUDGET_AMOUNT_<?=$i;?>" id="BUDGET_AMOUNT_<?=$i;?>" value="0"  style="count" onkeypress="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onkeyup="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value"/><?=_("Ԫ")?>
                        </div>
                    </div>
<?php
                        $i++;
                    }
                    ?>
                </div>
            </div>
        </div>
        <?
            $j++;
        }
        if($j == 0)
        {
            echo "<div style='margin-left:175px;'>"._("��û�����÷��ÿ�Ŀ������ϵϵͳ����Ա")."</div>";
        }
?>
    </div>  
    <div align="center">
        <input class="btn" type="button" id="budget_save" value="<?=_("����")?>">
        &nbsp;&nbsp;&nbsp;
        <input class="btn" type="button" id="budget_delete" value="<?=_("ȡ��")?>">
    </div>
</div>
</fieldset>
</div>
<script type="text/javascript">
    UEDITOR_CONFIG.AutoFloatEnabled = false;
</script>

   