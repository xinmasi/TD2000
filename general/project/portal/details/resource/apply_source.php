 <?php
    include_once("inc/auth.inc.php");
    include_once("inc/utility_all.php");
 ?>
 <html>
    <head>
        <script src="/module/DatePicker/WdatePicker.js">/*时间控件*/</script>
    </head>
 <body>
     <div style="overflow-y:auto;height:90%">
        <form  method="post" name="form1" id="form1">
            <table class="table table-bordered table-striped" width="100%" align="center" border='1'>
                <tr>    
                    <td nowrap align="left" width="15%"><strong>
                    <?=_("资源名称")?>
                    </strong></td>
                    <td nowrap align="center"><input type="text" name="SOURCE_NAME"  style="height:26px; width:180px" value=""/></td>
                    <td nowrap align="left" width="15%"><strong>
                    <?=_("申请时间")?>
                    </strong></td>
                    <td>
                        <input type="text" class="SmallInput" style="width:85px" onClick="WdatePicker()" name="SOURCE_START_TIME" id="SOURCE_START_TIME" value="<? echo date("Y-m-d",time())?>"/>                    </td>
                </tr>
                <tr>
                    <td nowrap align="left" width="15%"><strong>
                    <?=_("资源类别")?>
                    </strong></td>
                    <td nowrap align="center"><input type="text" name="SOURCE_TYPE"  style="height:26px; width:180px" value=""/></td>
                    <td nowrap align="left" width="15%"><strong>
                    <?=_("资源类别编号")?>
                    </strong></td>
                    <td nowrap align="center"><input type="text" name="SOURCE_TYPE"  style="height:26px; width:180px" value=""/></td>
                </tr>
                <tr>      
                    <td nowrap align="left" width="15%"><strong>
                    <?=_("资源单价")?>
                    </strong></td>
                    <td nowrap align="center"><input type="text" name="SOURCE_PRICE"  style="height:26px; width:180px" value=""/></td>
                    <td nowrap align="left" width="15%"><strong>
                    <?=_("资源数量")?>
                    </strong></td>
                    <td nowrap align="center"><input type="text" name="SOURCE_COUNT"  style="height:26px; width:180px" value=""/></td>
                </tr>
                <tr>
                    <td align="center" colspan="4">
                    <span class="pull-right"><strong>
                    <input class="btn btn-success" type="button" name="sub" onClick="check(<?=$i_proj_id?>)" value=" <?=_("确认申请")?> "/>
                    &nbsp;
                    <input class="btn btn-info" type="button" name="sub"  value=" <?=_("返回")?> " onClick="history.back(-1);"/>
                    </strong></span>                    </td>
                </tr>
            </table>
        </form>
     </div>
 </body>
 </html>