<div>
    <fieldset style="padding-top:15px;">
    <div class="control-group">
        <label class="control-label">
            <span style="color:red"> * </span><?=_("项目创建人")?>
        </label>
        <div class="controls">
            <input type="text" name="PROJ_OWNER_TO_NAME" value="<?=$PROJ_OWNER_NAME!="" ? $PROJ_OWNER_NAME : $_SESSION['LOGIN_USER_NAME'];?>" readonly>
            <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('65','', 'PROJ_OWNER', 'PROJ_OWNER_TO_NAME')"><?=_("选择")?></a>
            <input type="hidden" name="PROJ_OWNER" id="PROJ_OWNER" value="<?=$PROJ_OWNER!=""?$PROJ_OWNER:$_SESSION['LOGIN_USER_ID'];?>">
            <a href="javascript:;" class="orgClear" onclick="ClearUser('PROJ_OWNER', 'PROJ_OWNER_TO_NAME')"><?=_("清空")?></a>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">
            <span style="color:red"> * </span><?=_("项目负责人")?>
        </label>
        <div class="controls">
            <input type="text" name="PROJ_LEADER_TO_NAME" value="<?=$PROJ_LEADER_NAME!="" ? $PROJ_LEADER_NAME : $_SESSION['LOGIN_USER_NAME'];?>" readonly>
            <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('65','', 'PROJ_LEADER', 'PROJ_LEADER_TO_NAME')"><?=_("选择")?></a>
            <input type="hidden" name="PROJ_LEADER" id="PROJ_LEADER" value="<?=$PROJ_LEADER!="" ? $PROJ_LEADER : $_SESSION['LOGIN_USER_ID'];?>">
            <a href="javascript:;" class="orgClear" onclick="ClearUser('PROJ_LEADER', 'PROJ_LEADER_TO_NAME')"><?=_("清空")?></a>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">
        <?=_("项目查看人")?>
        </label>
        <div class="controls">
            <textarea cols=40 name="PROJ_VIEWER_NAME" rows=3 wrap="yes" readonly style="width:220px;"></textarea>
            <input type="hidden" name="PROJ_VIEWER" id="PROJ_VIEWER" >
            <a href="javascript:;" class="orgAdd" onClick="SelectUser('65','','PROJ_VIEWER', 'PROJ_VIEWER_NAME')"><?=_("选择")?></a>
            <a href="javascript:;" class="orgClear" onclick="ClearUser('PROJ_VIEWER', 'PROJ_VIEWER_NAME')"><?=_("清空")?></a>
        </div>
    </div>
<?php
    $query_code="SELECT CODE_NO,CODE_NAME FROM SYS_CODE WHERE PARENT_NO='PROJ_ROLE'";
    $cursor_code = exequery(TD::conn(), $query_code);
    $i = 1 ;
    while($ROW = mysql_fetch_array($cursor_code))
    {
        $CODE_NO=$ROW['CODE_NO'];
        $CODE_NAME=$ROW['CODE_NAME'];
        $s_user_name = $s_user
?>
        <div class="control-group">
            <label class="control-label">
                <?=$CODE_NAME?>
            </label>
            <div class="controls">
                <textarea cols=40 name="PROJ_NAME_<?=$CODE_NAME?>" rows=3 wrap="yes" readonly style="width:220px;"></textarea>

                <input type="hidden" name="PROJ_USER_<?=$CODE_NO?>" id="PROJ_USER_<?=$CODE_NO?>" value="">
 
        
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('65','','PROJ_USER_<?=$CODE_NO?>', 'PROJ_NAME_<?=$CODE_NAME?>')"><?=_("选择")?></a>
                <a href="javascript:;" class="orgClear" onclick="ClearUser('PROJ_USER_<?=$CODE_NO?>', 'PROJ_NAME_<?=$CODE_NAME?>')"><?=_("清空")?></a>
            </div>
        </div>
<?php
		$PROJ_USER_CODE_NO="";
        $i++;
    }
?>

    </fieldset> 
</div>