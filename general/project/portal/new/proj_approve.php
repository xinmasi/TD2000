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
    $show_noapp = _("�����");
    Message("",_("������������Ա"));
    echo '<input type="hidden" name="PROJ_MANAGER" value="'.$_SESSION['LOGIN_USER_ID'].'">';
    //������״̬
    echo "<input type='hidden' value='2' name='PROJ_STATUS'>";
}else if($priv == "1")
{
    Message("",_("��û�����������ˣ���ȷ�Ϻ���ϵ����Ա��<br> ����ϵͳ����Ա��ȥ�˵�->��Ŀ����->������������->��ĿȨ������->����Ȩ��ȥ���á�"));
}
else
{
    $username = GetUserNameById($priv);
    $user_array = explode(",", td_trim($priv));
    $user_name_array = explode(",", td_trim($username));

    echo "<input type='hidden' value='1' name='PROJ_STATUS'>";
?>          
<label class="control-label" for="PROJ_MANAGER"><?=_("ѡ��������&nbsp;")?></label>
<div class="controls">
    <select name="PROJ_MANAGER" class="Select" id="PROJ_MANAGER" >
        <option selected="selected" value="choose"><?=_(��ѡ��)?></option>
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
