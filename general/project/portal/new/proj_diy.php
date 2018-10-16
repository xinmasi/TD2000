<div>
    <fieldset style="padding-top:15px;">
    <!--此处存放全局变量-->
     <div class="control-group">
        <label class="control-label" for="inputEmail">
        </label>
        <div class="controls" style="margin:20px">
            <td class="TableData" colspan="3" id="DEFINE_SYSCODE_CONTENT_G">
                <?=proj_get_field_table_g(proj_get_field_html('G'.$PROJ_TYPE,$PROJ_ID))!="" ? proj_get_field_table_g(proj_get_field_html('G'.$PROJ_TYPE,$PROJ_ID)) : Message("",_("您还没有设置自定义选项，如果需要请自行设置开通！<br> 添加的路径在菜单下->项目管理->基础数据设置->项目自定义设置->全局字段管理去添加。"));?>
            </td>
        </div>
    </div>
    </fieldset>
</div>
