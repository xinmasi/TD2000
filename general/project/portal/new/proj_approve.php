<?php
include_once("inc/auth.inc.php");
?>
<div>
<fieldset style="padding-top:15px;">
<div class="control-group">  
    <div class="controls" style="margin:20px">
<?php
if($priv == "2")
{
    $show_noapp = _("免审核");
    Message("",_("您是免审批人员"));
    echo '<input type="hidden" name="PROJ_MANAGER" value="'.$_SESSION['LOGIN_USER_ID'].'">';
    //审批人状态
    echo "<input type='hidden' value='2' name='PROJ_STATUS'>";
}else if($priv == "1")
{
    Message("",_("您没有设置审批人，请确认后联系管理员。<br> 您是系统管理员请去菜单->项目管理->基础数据设置->项目权限设置->审批权限去设置。"));
}
else
{
    $username = GetUserNameById($priv);
    $user_array = explode(",", td_trim($priv));
    $user_name_array = explode(",", td_trim($username));

    echo "<input type='hidden' value='1' name='PROJ_STATUS'>";
?>          
<label class="control-label" for="PROJ_MANAGER"><?=_("选择审批人&nbsp;")?></label>
<div class="controls">
    <select name="PROJ_MANAGER" class="Select" id="PROJ_MANAGER" >
        <option selected="selected" value="choose"><?=_(请选择)?></option>
<?php
    foreach($user_array as $k => $v)
    {
?>
        <option value="<?=$v?>"><?=$user_name_array[$k]?></option>
<?php

    }
?>
    </select>
</div>
<?php
}
?>
</div>
    
</div>	
</fieldset>       
</div>
