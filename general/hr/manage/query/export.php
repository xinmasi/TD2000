<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("导出内容");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script Language="JavaScript">
function move_option(e1,e2)
{
    for (var i=0;i<e1.options.length; i++)
    {
        if(e1.options[i].selected)
        {
            option_text=e1.options[i].text;
            option_value=e1.options[i].value;
            option_style_color=e1.options[i].style.color;

            var my_option = document.createElement("OPTION");
            my_option.text=option_text;
            my_option.value=option_value;
            my_option.style.color=option_style_color;

            e2.options.add(my_option);
            e1.remove(i);
            i--;
        }
    }//for
}
function func_select_all1()
{
    for (i=document.form1.select1.options.length-1; i>=0; i--)
        document.form1.select1.options[i].selected=true;
}
function func_select_all2()
{
    for (i=document.form1.select2.options.length-1; i>=0; i--)
        document.form1.select2.options[i].selected=true;
}
function func_delete()
{
    for (i=document.form1.select1.options.length-1; i>=0; i--)
    {
        if(document.form1.select1.options[i].selected)
        {
            option_text=document.form1.select1.options[i].text;
            option_value=document.form1.select1.options[i].value;

            var my_option = document.createElement("OPTION");
            my_option.text=option_text;
            my_option.value=option_value;

            //pos=func_find(select2,option_text);
            document.form1.select2.add(my_option);
            document.form1.select1.remove(i);
        }
    }//for
}
function exreport()
{
    fld_str="";
    fld_str1="";
    if(document.form1.select1.options.length!=0)
    {
        for (i=0; i< document.form1.select1.options.length; i++)
        {
            options_value=document.form1.select1.options[i].value;
            options_text=document.form1.select1.options[i].text;
            fld_str+=options_value+",";
            fld_str1+=options_text+",";
        }
    }
    else
    {
        for (i=0; i< document.form1.select2.options.length; i++)
        {
            options_value=document.form1.select2.options[i].value;
            options_text=document.form1.select2.options[i].text;
            fld_str+=options_value+",";
            fld_str1+=options_text+",";
        }
    }
    document.form1.FIELDMSG.value=fld_str;
    document.form1.FIELDMSGNAME.value=fld_str1;
    document.form1.submit();
}

function chk(input,KC,KT,count)
{
    var lstr="";
    if(count==1)
    {
        if(document.all(KC).checked)
        {
            document.all(KT).value=document.all(KC).value;
        }
        else
        {
            document.all(KT).value="";
        }
    }
    else
    {
        for(i=0;i<document.all(KC).length;i++)
        {
            el=document.all(KC).item(i);
            if(el.checked)
            {
                val=el.value;
                lstr+=val+",";
            }
        }
        document.all(KT).value=lstr;
    }
}
function func_up()
{
    sel_count=0;
    for (i=document.form1.select1.options.length-1; i>=0; i--)
    {
        if(document.form1.select1.options[i].selected)
            sel_count++;
    }

    if(sel_count==0)
    {
        alert("<?=_("调整顺序时，请选择其中一项！")?>");
        return;
    }
    else if(sel_count>1)
    {
        alert("<?=_("调整顺序时，只能选择其中一项！")?>");
        return;
    }

    i=document.form1.select1.selectedIndex;

    if(i!=0)
    {
        var my_option = document.createElement("OPTION");
        my_option.text=document.form1.select1.options[i].text;
        my_option.value=document.form1.select1.options[i].value;

        document.form1.select1.options.add(my_option,i-1);
        document.form1.select1.remove(i+1);
        document.form1.select1.options[i-1].selected=true;
    }
}
function func_down()
{
    sel_count=0;
    for (i=document.form1.select1.options.length-1; i>=0; i--)
    {
        if(document.form1.select1.options[i].selected)
            sel_count++;
    }

    if(sel_count==0)
    {
        alert("<?=_("调整顺序时，请选择其中一项！")?>");
        return;
    }
    else if(sel_count>1)
    {
        alert("<?=_("调整顺序时，只能选择其中一项！")?>");
        return;
    }

    i=document.form1.select1.selectedIndex;

    if(i!=document.form1.select1.options.length-1)
    {
        var my_option = document.createElement("OPTION");
        my_option.text=document.form1.select1.options[i].text;
        my_option.value=document.form1.select1.options[i].value;

        document.form1.select1.options.add(my_option,i+2);
        document.form1.select1.remove(i);
        document.form1.select1.options[i+1].selected=true;
    }
}
</script>

<body class="bodycolor"">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/send.gif" width="18" HEIGHT="18"><span class="big3"> <?=_("导出")?>EXCEL<?=_("人员档案")?></span>
        </td>
    </tr>
</table>
<form action="excel_report.php?ispirit_export=1" method="POST" name="form1">
    <?
    foreach ($_POST as $key=> $value)
    {
        ?>
        <input type="hidden" name="<?=$key?>" value="<?=$value?>">
        <?
    }
    ?>
    <table align="center" width="95%" class="TableBlock">
        <tr>
            <td nowrap class="TableHeader" colspan="4">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif"> &nbsp;<?=_("导出内容")?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" colspan="4" align="center"><br>
                <table width="150" class="TableBlock">
                    <tr bgcolor="#CCCCCC">
                        <td align="center"><?=_("排序")?></td>
                        <td align="center"><b><?=_("显示字段")?></b></td>
                        <td align="center">&nbsp;</td>
                        <td align="center" valign="top"><b><?=_("可选字段")?></b></td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#999999">
                            <input type="button" class="SmallInput" value="<?=_(" ↑ ")?>" onClick="func_up();">
                            <br><br>
                            <input type="button" class="SmallInput" value="<?=_(" ↓ ")?>" onClick="func_down();">
                        </td>
                        <td valign="top" align="center" bgcolor="#CCCCCC">
                            <select  name="select1" ondblclick="func_delete();" MULTIPLE style="width:200px;height:280px">
                            </select>
                            <input type="button" value=" <?=_("全选")?> " onClick="func_select_all1();" class="SmallInput">
                        </td>
                        <td align="center" bgcolor="#999999">
                            <input type="button" class="SmallInput" value="<?=_(" ← ")?>" onClick="move_option(select2,select1);">
                            <br><br>
                            <input type="button" class="SmallInput" value="<?=_(" → ")?>" onClick="move_option(select1,select2);">
                        </td>
                        <td align="center" valign="top" bgcolor="#CCCCCC">
                            <select  name="select2" ondblclick="move_option(select2,select1);" MULTIPLE style="width:200px;height:280px">
                                <option value="STAFF_NAME"><?=_("姓名")?></option>
                                <option value="BEFORE_NAME"><?=_("曾用名")?></option>
                                <option value="STAFF_E_NAME"><?=_("英文名")?></option>
                                <option value="DEPT_ID"><?=_("部门")?></option>
                                <option value="USER_PRIV"><?=_("角色")?></option>
                                <option value="STAFF_SEX"><?=_("性别")?></option>
                                <option value="STAFF_NO"><?=_("编号")?></option>
                                <option value="WORK_NO"><?=_("工号")?></option>
                                <option value="WORK_JOB"><?=_("岗位")?></option>
                                <option value="STAFF_CARD_NO"><?=_("身份证号码")?></option>
                                <option value="STAFF_BIRTH"><?=_("出生日期")?></option>
                                <option value="STAFF_AGE"><?=_("年龄")?></option>
                                <option value="STAFF_NATIVE_PLACE"><?=_("籍贯（省份）")?></option>
                                <option value="STAFF_NATIVE_PLACE2"><?=_("籍贯（城市）")?></option>
                                <option value="BLOOD_TYPE"><?=_("血型")?></option>
                                <option value="STAFF_NATIONALITY"><?=_("民族")?></option>
                                <option value="STAFF_MARITAL_STATUS"><?=_("婚姻状况")?></option>
                                <option value="STAFF_POLITICAL_STATUS"><?=_("政治面貌")?></option>
                                <option value="WORK_STATUS"><?=_("在职状态")?></option>
                                <option value="JOIN_PARTY_TIME"><?=_("入党时间")?></option>
                                <option value="STAFF_PHONE"><?=_("联系电话")?></option>
                                <option value="STAFF_MOBILE"><?=_("手机号码")?></option>
                                <!--<option value="STAFF_LITTLE_SMART"><?=_("小灵通")?></option>-->
                                <option value="STAFF_MSN">MSN</option>
                                <option value="STAFF_QQ">QQ</option>
                                <option value="STAFF_EMAIL"><?=_("电子邮件")?></option>
                                <option value="HOME_ADDRESS"><?=_("家庭地址")?></option>
                                <option value="JOB_BEGINNING"><?=_("参加工作时间")?></option>
                                <option value="OTHER_CONTACT"><?=_("其他联系方式")?></option>
                                <option value="WORK_AGE"><?=_("总工龄")?></option>
                                <option value="STAFF_HEALTH"><?=_("健康状况")?></option>
                                <option value="STAFF_DOMICILE_PLACE"><?=_("户口所在地")?></option>
                                <option value="STAFF_TYPE"><?=_("户口类别")?></option>
                                <option value="DATES_EMPLOYED"><?=_("入职时间")?></option>
                                <option value="STAFF_HIGHEST_SCHOOL"><?=_("学历")?></option>
                                <option value="STAFF_HIGHEST_DEGREE"><?=_("学位")?></option>
                                <option value="GRADUATION_DATE"><?=_("毕业时间")?></option>
                                <option value="STAFF_MAJOR"><?=_("专业")?></option>
                                <option value="GRADUATION_SCHOOL"><?=_("毕业院校")?></option>
                                <option value="COMPUTER_LEVEL"><?=_("计算机水平")?></option>
                                <option value="FOREIGN_LANGUAGE1"><?=_("外语语种")?>1</option>
                                <option value="FOREIGN_LANGUAGE2"><?=_("外语语种")?>2</option>
                                <option value="FOREIGN_LANGUAGE3"><?=_("外语语种")?>3</option>
                                <option value="FOREIGN_LEVEL1"><?=_("外语水平")?>1</option>
                                <option value="FOREIGN_LEVEL2"><?=_("外语水平")?>2</option>
                                <option value="FOREIGN_LEVEL3"><?=_("外语水平")?>3</option>
                                <option value="STAFF_SKILLS"><?=_("特长")?></option>
                                <option value="WORK_TYPE"><?=_("工种")?></option>
                                <option value="ADMINISTRATION_LEVEL"><?=_("行政级别")?></option>
                                <option value="STAFF_OCCUPATION"><?=_("员工类型")?></option>
                                <option value="JOB_POSITION"><?=_("职务")?></option>
                                <option value="PRESENT_POSITION"><?=_("职称")?></option>
                                <option value="WORK_LEVEL"><?=_("职称级别")?></option>
                                <option value="JOB_AGE"><?=_("本单位工龄")?></option>
                                <option value="BEGIN_SALSRY_TIME"><?=_("起薪时间")?></option>
                                <option value="LEAVE_TYPE"><?=_("年休假")?></option>
                                <option value="RESUME"><?=_("简历")?></option>
                                <option value="SURETY"><?=_("担保记录")?></option>
                                <option value="CERTIFICATE"><?=_("职务情况")?></option>
                                <option value="INSURE"><?=_("社保缴纳情况")?></option>
                                <option value="BODY_EXAMIM"><?=_("体检记录")?></option>
                                <option value="REMARK"><?=_("备注")?></option>
                                <?
                                $query = "select * from FIELDSETTING where TABLENAME='HR_STAFF_INFO' ORDER BY ORDERNO ASC ";
                                $cursor1=exequery(TD::conn(),$query);
                                while($ROW=mysql_fetch_array($cursor1))
                                {
                                    ?>
                                    <option value="<?=$ROW["FIELDNO"]?>"><?=$ROW["FIELDNAME"]?></option>
                                    <?
                                }
                                ?>
                            </select>
                            <input type="button" value=" <?=_("全选")?> " onClick="func_select_all2();" class="SmallInput">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tfoot align="center" class="TableFooter">
        <td nowrap colspan="4" align="center">
            <input type="reset" value="<?=_("导出")?>" class="BigButton" onClick="exreport()">
            <?
            while (list($key, $value) = each($_POST))
            {
                if($value!="")
                    echo "<input type=\"hidden\" name=\"".$key."\" value=\"".$value."\">\n";
            }
            ?>
            <input type="hidden" name="FIELDMSG">
            <input type="hidden" name="FIELDMSGNAME">
            <input type="hidden" name="is_leave" value="<?=$is_leave?>" />
        </td>
        </tfoot>
    </table>
</form>
</body>
</html>
