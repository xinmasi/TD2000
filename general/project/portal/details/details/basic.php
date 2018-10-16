<html>
<head>
    <script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
</head>
<body>

<style>
    .tdspan span{
        display:block;
    }
</style>

</script>
<div style="overflow-y:auto;height:90%">
    <table class="table table-bordered table-striped" width="100%" align="center" border='1'>
    <tr class="info">
    <td nowrap align="center" colspan='4'>
    <strong><?=_("项目详情基本信息")?></strong>
    </td>
    </tr>
    <tr>
    <td nowrap align="left" width="15%">
    <strong><?=_("项目编号")?></strong>
    </td>
    <td nowrap align="center"><?=$s_num?></td>
    <td nowrap align="left" width="15%">
    <strong><?=_("项目名称")?></strong>
    </td>
    <td nowrap align="center"><?=$s_name?></td>
    </tr>
    <tr>
    <td nowrap align="left" width="15%">
    <strong><?=_("项目类别")?></strong>
    </td>
    <td nowrap align="center"><?=$s_type?></td>
    <td nowrap align="left" width="15%">
    <strong><?=_("参与部门")?></strong>
    </td>
    <td align="center"><?=td_trim($s_dept)?></td>
    </tr>
    <tr>
    <td nowrap align="left" width="15%">
    <strong><?=_("创建者")?></strong>
    </td>
    <td nowrap align="center"><?=$s_owner?></td>
    <td nowrap align="left" width="15%">
    <strong><?=_("负责人")?></strong>
    </td>
    <td nowrap align="center"><?=$s_leader?></td>
    </tr>
    <tr>
    <td nowrap align="left" width="15%">
    <strong><?=_("项目查看者")?></strong>
    </td>
    <td align="center"><?=td_trim($s_viewer);?></td>
    <td nowrap align="left" width="15%">
    <strong><?=_("项目级别")?></strong>
    </td>
    <td nowrap align="center"><?=$s_level_name?></td>
    </tr>
    <tr>
    <td nowrap align="left" width="15%" >
    <strong><?=_("项目计划周期")?></strong>
    </td>
    <td nowrap align="center"><? echo $s_start_time._(" 至 ").$s_end_time;?></td>
    <td nowrap align="left" width="15%" >
    <strong><?=_("总预算资金")?></strong>
    </td>
    <?
    if(!isset($f_cost_money) || $f_cost_money =="" || $f_cost_money == 0)
    {
        $f_cost_money = 0;
    }
    else
    {
        $f_cost_money = number_format($f_cost_money,2);
    }
    ?>
    <td nowrap align="center"><?=$f_cost_money._("元")?></td>
    </tr>
    <?php
    //全局字段
    $quanju_type_id = 'G'.$i_type_id;
    $query = "select * from PROJ_FIELDSETTING where TYPE_CODE_NO = 'G' order by ORDERNO";

    $cursor=exequery(TD::conn(),$query);

    while($ROW=mysql_fetch_array($cursor)){

    unset($FIELDNAME);
    unset($ITEM_DATE);
    unset($index);
    $FIELDNAME = $ROW["FIELDNAME"]; //字段名称
    $FIELDNO = $ROW["FIELDNO"];   //扩展字段编号

    $STYPE=$ROW["STYPE"];  //  字段类型
    $TYPENAME=$ROW["TYPENAME"];// 选项名称
    $TYPEVALUE=$ROW["TYPEVALUE"];//  选项值
    $TYPECODE=$ROW["TYPECODE"];//  系统代码

    $query1 = "select ITEM_DATE from PROJ_FIELD_DATE where TYPE_CODE_NO='$quanju_type_id' and FIELDNO='$FIELDNO' and IDENTY_ID='$i_proj_id'";
    //echo $query;
    $cursor1=exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1)){
        $ITEM_DATE = $index = $ROW1["ITEM_DATE"]; //文本值的话 是直接获取值 非文本的话是先获取key值

    }

    //非文本类型 系统代码的获值
    if(($STYPE=="D" || $STYPE=="C" || $STYPE=="R")&& $TYPECODE!==""){
        $ITEM_DATE = "";
        $query2="select CODE_NO,CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='$TYPECODE' order by CODE_ORDER";
        $cursor2= exequery(TD::conn(),$query2);

        if(strstr($index,",")){     //关于复选的处理
            $index=explode(',',$index);
            array_pop($index);  //去除最后一个值为空的记录
        }
        while($ROW2=mysql_fetch_array($cursor2)){
            $CODE_NO=$ROW2["CODE_NO"];
            $CODE_NAME=$ROW2["CODE_NAME"];

            if(is_array($index)){           //复选  需要拼接值
                foreach($index as $key){
                    if($CODE_NO==$key){
                        $ITEM_DATE .=$CODE_NAME."&nbsp;";
                    }
                }
            }else{

                if($CODE_NO==$index){
                    $ITEM_DATE = $CODE_NAME;
                }
            }
        }
    }

    //下面的是进行自定义代码字段的 获值
    if(($STYPE=="D" || $STYPE=="C" || $STYPE=="R")&&$TYPECODE==""){
        $ITEM_DATE = "";
        $TYPEVALUE=str_replace("，",",",$TYPEVALUE);
        $TYPEVALUE_ARRAY=explode(",",$TYPEVALUE);
        $TYPENAME=str_replace("，",",",$TYPENAME);
        $TYPENAME_ARRAY=explode(",",$TYPENAME);

        if(strstr($index,",")){     //关于复选的处理
            $index=explode(',',$index);
            array_pop($index);  //去除最后一个值为空的记录
        }
        $array_all = array_combine($TYPEVALUE_ARRAY,$TYPENAME_ARRAY);  //合并数组
        if(is_array($index)){           //如果是复选  需要拼接值
            foreach($index as $key){
                $ITEM_DATE .= $array_all[$key]."&nbsp;";
            }
        }else{
            $ITEM_DATE = $array_all[$index];
        }
    }


    ?>
    <tr>
    <td nowrap align="left" width="15%" >
    <strong><?=$FIELDNAME?></strong>
    </td>
    <td nowrap align="center"  colspan='3'><?=$ITEM_DATE?></td>

    </tr>
    <?}?>
    <?php
    //自定义字段
    $query = "select * from PROJ_FIELDSETTING where TYPE_CODE_NO = '".$i_type_id."' order by ORDERNO";
    $cursor=exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor)){
    unset($FIELDNAME);
    unset($ITEM_DATE);
    $FIELDNAME = $ROW["FIELDNAME"]; //字段名称
    $FIELDNO = $ROW["FIELDNO"];   //扩展字段编号

    $STYPE=$ROW["STYPE"];  //  字段类型
    $TYPENAME=$ROW["TYPENAME"];// 选项名称
    $TYPEVALUE=$ROW["TYPEVALUE"];//  选项值
    $TYPECODE=$ROW["TYPECODE"];//  系统代码


    $query = "select ITEM_DATE from PROJ_FIELD_DATE where TYPE_CODE_NO='$i_type_id' and FIELDNO='$FIELDNO'and IDENTY_ID='$i_proj_id'";
    $cursor1=exequery(TD::conn(),$query);
    if($ROW1=mysql_fetch_array($cursor1)){
        $ITEM_DATE = $index = $ROW1["ITEM_DATE"]; //文本值的话 是直接获取值 非文本的话是先获取key值
    }

    //非文本类型 系统代码的获值
    if(($STYPE=="D" || $STYPE=="C" || $STYPE=="R")&& $TYPECODE!==""){
        $ITEM_DATE = "";
        $query2="select CODE_NO,CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='$TYPECODE' order by CODE_ORDER";
        $cursor2= exequery(TD::conn(),$query2);

        if(strstr($index,",")){     //关于复选的处理
            $index=explode(',',$index);
            array_pop($index);  //去除最后一个值为空的记录
        }
        while($ROW2=mysql_fetch_array($cursor2)){
            $CODE_NO=$ROW2["CODE_NO"];
            $CODE_NAME=$ROW2["CODE_NAME"];

            if(is_array($index)){           //复选  需要拼接值
                foreach($index as $key){
                    if($CODE_NO==$key){
                        $ITEM_DATE .=$CODE_NAME."&nbsp;";
                    }
                }
            }else{

                if($CODE_NO==$index){
                    $ITEM_DATE = $CODE_NAME;
                }
            }
        }
    }

    //下面的是进行自定义代码字段的 获值
    if(($STYPE=="D" || $STYPE=="C" || $STYPE=="R")&&$TYPECODE==""){
        $ITEM_DATE = "";
        $TYPEVALUE=str_replace("，",",",$TYPEVALUE);
        $TYPEVALUE_ARRAY=explode(",",$TYPEVALUE);
        $TYPENAME=str_replace("，",",",$TYPENAME);
        $TYPENAME_ARRAY=explode(",",$TYPENAME);

        if(strstr($index,",")){     //关于复选的处理
            $index=explode(',',$index);
            array_pop($index);  //去除最后一个值为空的记录
        }
        $array_all = array_combine($TYPEVALUE_ARRAY,$TYPENAME_ARRAY);  //合并数组
        if(is_array($index)){           //如果是复选  需要拼接值
            foreach($index as $key){
                $ITEM_DATE .= $array_all[$key]."&nbsp;";
            }
        }else{
            $ITEM_DATE = $array_all[$index];
        }
    }



    ?>
    <tr>
    <td nowrap align="left" width="15%" >
    <strong><?=$FIELDNAME?></strong>
    </td>
    <td nowrap align="center"  colspan='3'><?=$ITEM_DATE?></td>

    </tr>
    <?}?>
        <!--zfc-->
    <tr>
    <td nowrap align="left" width="15%" >
    <strong><?=_("审批人")?></strong>
    </td>
    <td nowrap align="center"  colspan='3'><?=$s_manager?></td>

    </tr>

    <tr>

    <?
    $query = "select ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
    }
    ?>

    <td nowrap align="left" width="15%" >
    <strong><?=_("附件")?></strong>
    </td>
    <td nowrap align="center" class="tdspan"  colspan='3'>
    <?
    if($ATTACHMENT_ID=="")
        echo _("无附件");
    else{
        echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,0,0,0,0,0,0);
    }
    ?>
    </td>
    <script type="text/javascript">
    jQuery(function(){
        var obj = "";
        jQuery(".attach_link").mouseenter(function(){
            obj = jQuery(this).attr("id");
        })

        jQuery(".attach_link").mousemove(function(e){
            var x = e.pageX;
            var y = e.pageY;
            jQuery("#"+obj+"_menu").css({"top":y+3,"left":x+3});
        })

    })
</script>
</tr>



<tr>
    <td nowrap align="left" width="15%">
        <strong><?=_("项目描述")?></strong>
    </td>
    <td nowrap align="center"  colspan='3'>
        <div style="word-break:break-all;width:650px;height:200px;overflow:auto;white-space:normal;" ><?php echo $s_description?></div>
    </td>
</tr>
</table>
</div>
</body>
</html>
