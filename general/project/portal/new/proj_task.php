<div>
    <fieldset style="padding-top:15px;">
    <div class="control-group">
        <label class="control-label" for="TASK_NO"><span style="color:red"> * </span><?=_("�������")?></label>
        <div class="controls">
            <input type="text" name="TASK_NO" id="TASK_NO" placeholder="<?=_("�������")?>" value="01">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="TASK_NAME"><span style="color:red"> * </span><?=_("��������")?></label>
        <div class="controls">
            <input type="text" name="TASK_NAME" id="TASK_NAME" placeholder="<?=_("��������")?>" value="<?=_("��һ����Ŀ����")?>">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" ><span style="color:red"> * </span><?=_("ִ&nbsp;��&nbsp;��")?></label>
        <div class="controls">
            <input type="text" name="TASK_USER_TO_NAME" id="TASK_USER_TO_NAME" style="height:26px;" readonly value="<?=$TASK_USER_TO_NAME!="" ? $TASK_USER_TO_NAME : $_SESSION['LOGIN_USER_NAME'];?>">
            <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('65','','TASK_USER', 'TASK_USER_TO_NAME')"><?=_("ѡ��")?></a>
            <input type="hidden" name="TASK_USER" id="TASK_USER" value="<?=$TASK_USER!="" ? $TASK_USER : $_SESSION['LOGIN_USER_ID'];?>">
            <a href="javascript:;" class="orgClear" onclick="ClearUser('TASK_USER', 'TASK_USER_TO_NAME')"><?=_("���")?></a>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="TASK_LEVEL"><span style="color:red"> * </span><?=_("���񼶱�")?></label>
        <div class="controls">
            <select name="TASK_LEVEL" class="SmallSelect" id="TASK_LEVEL" style="height:28px">
                <option value="0"><?=_("��Ҫ")?></option>
                <option value="1" selected><?=_("һ��")?></option>
                <option value="2"><?=_("��Ҫ")?></option>
                <option value="3"><?=_("�ǳ���Ҫ")?></option>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><span style="color:red"> * </span><?=_("����ƻ�����")?></label>
        <div class="controls">
            <input type="text" class="SmallInput" style="width:85px" onClick="WdatePicker()" name="TASK_START_TIME" id="TASK_START_TIME" value="<? echo date("Y-m-d",time())?>"/> 
                <?=_("��")?>
            <input type="text" class="SmallInput" style="width:85px" onClick="WdatePicker()" name="TASK_END_TIME" id="TASK_END_TIME" value="<? echo date("Y-m-d",time())?>"/> 
        </div>
    </div>   
    <div class="control-group">
        <label class="control-label" for="TASK_DESCRIPTION"><?=_("��������")?></label>
        <div class="controls">
            <textarea cols="50" name="TASK_DESCRIPTION" id="TASK_DESCRIPTION" rows="3" style="width:219px;border: 1px solid #ccc;"></textarea>
        </div>
    </div>
    </fieldset>	
</div> 