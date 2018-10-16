<div style="width:610px; height:380px; margin:20px auto; background:#fff;">
    <div class="welcome" style="width:100%;">
        <h3 >项目查询</h3>
        <h5>项目条件综合查询</h5>
    </div>
    <div style="height:auto; width:100%; padding-top:15px; border-bottom:1px solid #ccc;">

        <div class="control-group clearfix">
            <label class="control-label" for="PROJ_NUM" style="float:left; text-align:right; width:220px; margin-top:6px;"><?=_("项目编号&nbsp;&nbsp;")?></label>
            <input type="text" id="PROJ_NUM" style="float:left;" placeholder="项目编号 如:<?=date("Ymd");?>" name="PROJ_NUM">
            <input type="checkbox" style="margin-left:20px; float:left; margin-top:9px; margin-right:5px;" name="PROJ_NUM_M" id="PROJ_NUM_M"/>
            <label class="control-label" for="PROJ_NUM_M" style="float:left; text-align:right; margin-top:6px; color:red;"><?=_("模糊查询&nbsp;&nbsp;")?></label>
        </div>

        <div class="control-group clearfix">
            <label class="control-label" for="PROJ_TYPE" style="float:left; text-align:right; width:220px; margin-top:6px;"><?=_("项目类型&nbsp;&nbsp;")?></label>
            <select id="PROJ_TYPE" name="PROJ_TYPE">
                <option value="">不筛选项目类型</option>
                <?=code_list("PROJ_TYPE",'');?>
            </select>
        </div>

        <div class="control-group clearfix">
            <label class="control-label" for="PROJ_NUM" style="float:left; text-align:right; width:220px; margin-top:6px;"><?=_("项目名称&nbsp;&nbsp;")?></label>
            <input type="text" id="PROJ_NAME" style="float:left;" placeholder="项目名称" name="PROJ_NAME">
            <input type="checkbox" style="margin-left:20px; float:left; margin-top:9px; margin-right:5px;" name="PROJ_NAME_M" id="PROJ_NAME_M"/>
            <label class="control-label" for="PROJ_NAME_M" style="float:left; text-align:right; margin-top:6px; color:red;"><?=_("模糊查询&nbsp;&nbsp;")?></label>
        </div>

        <div class="control-group clearfix">
            <label class="control-label" for="PROJ_NUM" style="float:left; text-align:right; width:220px; margin-top:6px;"><?=_("项目周期&nbsp;&nbsp;")?></label>
            <div class="controls">
                <input type="text" class="SmallInput" style="width:85px" onClick="WdatePicker()" name="PROJ_START_TIME" id="PROJ_START_TIME" placeholder=""/>
                <?=_("至")?>
                <input type="text" class="SmallInput" style="width:85px" onClick="WdatePicker()" name="PROJ_END_TIME" id="PROJ_END_TIME" placeholder=""/>
            </div>
        </div>

        <div class="control-group clearfix">
            <label class="control-label" style="float:left; text-align:right; width:220px; margin-top:6px;"><?=_("项目级别&nbsp;&nbsp;")?></label>
            <div class="controls level">
                <input type="checkbox" class=''  value="a" ><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/a.png' title='<?=_("A级项目")?>'>
                &nbsp;
                <input type="checkbox" class=''  value="b" ><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/b.png' title='<?=_("B级项目")?>'>
                &nbsp;
                <input type="checkbox" class=''  value="c" ><img src='<?=MYOA_STATIC_SERVER?>/static/modules/project/img/c.png' title='<?=_("C级项目")?>'>
            </div>
        </div>


    </div>
    <div class="new-bottom">
        <div class="pull-right ">
            <button class="btn btn-primary btn-large" type="button" onclick="find_find()">查询</button>
            &nbsp;
            <button class="btn btn-info btn-large" type="button" onclick="quxiao_find()">取消</button>
        </div>
    </div>

</div>