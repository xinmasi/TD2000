<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("��������");
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
        alert("<?=_("����˳��ʱ����ѡ������һ�")?>");
        return;
    }
    else if(sel_count>1)
    {
        alert("<?=_("����˳��ʱ��ֻ��ѡ������һ�")?>");
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
        alert("<?=_("����˳��ʱ����ѡ������һ�")?>");
        return;
    }
    else if(sel_count>1)
    {
        alert("<?=_("����˳��ʱ��ֻ��ѡ������һ�")?>");
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
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/send.gif" width="18" HEIGHT="18"><span class="big3"> <?=_("����")?>EXCEL<?=_("��Ա����")?></span>
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
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif"> &nbsp;<?=_("��������")?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" colspan="4" align="center"><br>
                <table width="150" class="TableBlock">
                    <tr bgcolor="#CCCCCC">
                        <td align="center"><?=_("����")?></td>
                        <td align="center"><b><?=_("��ʾ�ֶ�")?></b></td>
                        <td align="center">&nbsp;</td>
                        <td align="center" valign="top"><b><?=_("��ѡ�ֶ�")?></b></td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#999999">
                            <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_up();">
                            <br><br>
                            <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="func_down();">
                        </td>
                        <td valign="top" align="center" bgcolor="#CCCCCC">
                            <select  name="select1" ondblclick="func_delete();" MULTIPLE style="width:200px;height:280px">
                            </select>
                            <input type="button" value=" <?=_("ȫѡ")?> " onClick="func_select_all1();" class="SmallInput">
                        </td>
                        <td align="center" bgcolor="#999999">
                            <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="move_option(select2,select1);">
                            <br><br>
                            <input type="button" class="SmallInput" value="<?=_(" �� ")?>" onClick="move_option(select1,select2);">
                        </td>
                        <td align="center" valign="top" bgcolor="#CCCCCC">
                            <select  name="select2" ondblclick="move_option(select2,select1);" MULTIPLE style="width:200px;height:280px">
                                <option value="STAFF_NAME"><?=_("����")?></option>
                                <option value="BEFORE_NAME"><?=_("������")?></option>
                                <option value="STAFF_E_NAME"><?=_("Ӣ����")?></option>
                                <option value="DEPT_ID"><?=_("����")?></option>
                                <option value="USER_PRIV"><?=_("��ɫ")?></option>
                                <option value="STAFF_SEX"><?=_("�Ա�")?></option>
                                <option value="STAFF_NO"><?=_("���")?></option>
                                <option value="WORK_NO"><?=_("����")?></option>
                                <option value="WORK_JOB"><?=_("��λ")?></option>
                                <option value="STAFF_CARD_NO"><?=_("���֤����")?></option>
                                <option value="STAFF_BIRTH"><?=_("��������")?></option>
                                <option value="STAFF_AGE"><?=_("����")?></option>
                                <option value="STAFF_NATIVE_PLACE"><?=_("���ᣨʡ�ݣ�")?></option>
                                <option value="STAFF_NATIVE_PLACE2"><?=_("���ᣨ���У�")?></option>
                                <option value="BLOOD_TYPE"><?=_("Ѫ��")?></option>
                                <option value="STAFF_NATIONALITY"><?=_("����")?></option>
                                <option value="STAFF_MARITAL_STATUS"><?=_("����״��")?></option>
                                <option value="STAFF_POLITICAL_STATUS"><?=_("������ò")?></option>
                                <option value="WORK_STATUS"><?=_("��ְ״̬")?></option>
                                <option value="JOIN_PARTY_TIME"><?=_("�뵳ʱ��")?></option>
                                <option value="STAFF_PHONE"><?=_("��ϵ�绰")?></option>
                                <option value="STAFF_MOBILE"><?=_("�ֻ�����")?></option>
                                <!--<option value="STAFF_LITTLE_SMART"><?=_("С��ͨ")?></option>-->
                                <option value="STAFF_MSN">MSN</option>
                                <option value="STAFF_QQ">QQ</option>
                                <option value="STAFF_EMAIL"><?=_("�����ʼ�")?></option>
                                <option value="HOME_ADDRESS"><?=_("��ͥ��ַ")?></option>
                                <option value="JOB_BEGINNING"><?=_("�μӹ���ʱ��")?></option>
                                <option value="OTHER_CONTACT"><?=_("������ϵ��ʽ")?></option>
                                <option value="WORK_AGE"><?=_("�ܹ���")?></option>
                                <option value="STAFF_HEALTH"><?=_("����״��")?></option>
                                <option value="STAFF_DOMICILE_PLACE"><?=_("�������ڵ�")?></option>
                                <option value="STAFF_TYPE"><?=_("�������")?></option>
                                <option value="DATES_EMPLOYED"><?=_("��ְʱ��")?></option>
                                <option value="STAFF_HIGHEST_SCHOOL"><?=_("ѧ��")?></option>
                                <option value="STAFF_HIGHEST_DEGREE"><?=_("ѧλ")?></option>
                                <option value="GRADUATION_DATE"><?=_("��ҵʱ��")?></option>
                                <option value="STAFF_MAJOR"><?=_("רҵ")?></option>
                                <option value="GRADUATION_SCHOOL"><?=_("��ҵԺУ")?></option>
                                <option value="COMPUTER_LEVEL"><?=_("�����ˮƽ")?></option>
                                <option value="FOREIGN_LANGUAGE1"><?=_("��������")?>1</option>
                                <option value="FOREIGN_LANGUAGE2"><?=_("��������")?>2</option>
                                <option value="FOREIGN_LANGUAGE3"><?=_("��������")?>3</option>
                                <option value="FOREIGN_LEVEL1"><?=_("����ˮƽ")?>1</option>
                                <option value="FOREIGN_LEVEL2"><?=_("����ˮƽ")?>2</option>
                                <option value="FOREIGN_LEVEL3"><?=_("����ˮƽ")?>3</option>
                                <option value="STAFF_SKILLS"><?=_("�س�")?></option>
                                <option value="WORK_TYPE"><?=_("����")?></option>
                                <option value="ADMINISTRATION_LEVEL"><?=_("��������")?></option>
                                <option value="STAFF_OCCUPATION"><?=_("Ա������")?></option>
                                <option value="JOB_POSITION"><?=_("ְ��")?></option>
                                <option value="PRESENT_POSITION"><?=_("ְ��")?></option>
                                <option value="WORK_LEVEL"><?=_("ְ�Ƽ���")?></option>
                                <option value="JOB_AGE"><?=_("����λ����")?></option>
                                <option value="BEGIN_SALSRY_TIME"><?=_("��нʱ��")?></option>
                                <option value="LEAVE_TYPE"><?=_("���ݼ�")?></option>
                                <option value="RESUME"><?=_("����")?></option>
                                <option value="SURETY"><?=_("������¼")?></option>
                                <option value="CERTIFICATE"><?=_("ְ�����")?></option>
                                <option value="INSURE"><?=_("�籣�������")?></option>
                                <option value="BODY_EXAMIM"><?=_("����¼")?></option>
                                <option value="REMARK"><?=_("��ע")?></option>
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
                            <input type="button" value=" <?=_("ȫѡ")?> " onClick="func_select_all2();" class="SmallInput">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tfoot align="center" class="TableFooter">
        <td nowrap colspan="4" align="center">
            <input type="reset" value="<?=_("����")?>" class="BigButton" onClick="exreport()">
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
