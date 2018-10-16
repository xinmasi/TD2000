<div>
    <fieldset style="padding-top:15px;">
    <div class="control-group">
        <label class="control-label" for="TASK_NO"><span style="color:red"> * </span><?=_("任务序号")?></label>
        <div class="controls">
            <input type="text" name="TASK_NO" id="TASK_NO" placeholder="<?=_("任务序号")?>" value="01">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="TASK_NAME"><span style="color:red"> * </span><?=_("任务名称")?></label>
        <div class="controls">
            <input type="text" name="TASK_NAME" id="TASK_NAME" placeholder="<?=_("任务名称")?>" value="<?=_("第一个项目任务")?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" ><span style="color:red"> * </span><?=_("执&nbsp;行&nbsp;人")?></label>
        <div class="controls">
            <input type="text" name="TASK_USER_TO_NAME" id="TASK_USER_TO_NAME" style="height:26px;" readonly value="<?=$TASK_USER_TO_NAME!="" ? $TASK_USER_TO_NAME : $_SESSION['LOGIN_USER_NAME'];?>">
            <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('65','','TASK_USER', 'TASK_USER_TO_NAME')"><?=_("选择")?></a>
            <input type="hidden" name="TASK_USER" id="TASK_USER" value="<?=$TASK_USER!="" ? $TASK_USER : $_SESSION['LOGIN_USER_ID'];?>">
            <a href="javascript:;" class="orgClear" onclick="ClearUser('TASK_USER', 'TASK_USER_TO_NAME')"><?=_("清空")?></a>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="TASK_LEVEL"><span style="color:red"> * </span><?=_("任务级别")?></label>
        <div class="controls">
            <select name="TASK_LEVEL" class="SmallSelect" id="TASK_LEVEL" style="height:28px">
                <option value="0"><?=_("次要")?></option>
                <option value="1" selected><?=_("一般")?></option>
                <option value="2"><?=_("重要")?></option>
                <option value="3"><?=_("非常重要")?></option>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><span style="color:red"> * </span><?=_("任务计划周期")?></label>
        <div class="controls">
            <input type="text" class="SmallInput" style="width:85px" onClick="WdatePicker()" name="TASK_START_TIME" id="TASK_START_TIME" value="<? echo date("Y-m-d",time())?>"/> 
                <?=_("至")?>
            <input type="text" class="SmallInput" style="width:85px" onClick="WdatePicker()" name="TASK_END_TIME" id="TASK_END_TIME" value="<? echo date("Y-m-d",time())?>"/> 
        </div>
    </div>   
    <div class="control-group">
        <label class="control-label" for="TASK_DESCRIPTION"><?=_("任务描述")?></label>
        <div class="controls">
            <textarea cols="50" name="TASK_DESCRIPTION" id="TASK_DESCRIPTION" rows="3" style="width:219px;border: 1px solid #ccc;"></textarea>
        </div>
    </div>
    </fieldset>	
</div> 