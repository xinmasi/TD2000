<div>
    <fieldset style="padding-top:15px;">
    <!--�˴����ȫ�ֱ���-->
     <div class="control-group">
        <label class="control-label" for="inputEmail">
        </label>
        <div class="controls" style="margin:20px">
            <td class="TableData" colspan="3" id="DEFINE_SYSCODE_CONTENT_G">
                <?=proj_get_field_table_g(proj_get_field_html('G'.$PROJ_TYPE,$PROJ_ID))!="" ? proj_get_field_table_g(proj_get_field_html('G'.$PROJ_TYPE,$PROJ_ID)) : Message("",_("����û�������Զ���ѡ������Ҫ���������ÿ�ͨ��<br> ��ӵ�·���ڲ˵���->��Ŀ����->������������->��Ŀ�Զ�������->ȫ���ֶι���ȥ��ӡ�"));?>
            </td>
        </div>
    </div>
    </fieldset>
</div>
