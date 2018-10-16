<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/header.inc.php");
if($LEAVE_BY_SENIORITY==1 || $ENTRY_RESET_LEAVE==1)
{
    $sql = "select a.USER_NAME from user as a left join hr_staff_info as b on a.USER_ID = b.USER_ID where b.DATES_EMPLOYED='0000-00-00' and a.DEPT_ID!=0";
    $cursor = exequery(TD::conn(),$sql);
    if(mysql_num_rows($cursor)>0)
    {
        while($ROW=mysql_fetch_array($cursor))
        {
            $staff_name.= $ROW['USER_NAME'].',';
        }
        $staff_name = rtrim($staff_name, ",");
        Message(_('警告'),_('以下员工的入职时间未填写，无法开启年假参数！</br>"'.$staff_name.'"'));
        //Button_Back();
        ?>
        <center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';"></center>
        <?
        exit;
    }else
    {
        $staff_name1 = "";
        $query="select USER_ID,USER_NAME from USER where DEPT_ID!='0' order by UID";
        $cursor=exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
        {
            $USER_ID    = $ROW['USER_ID'];
            $USER_NAME  = $ROW['USER_NAME'];

            $query2="SELECT USER_ID FROM hr_staff_info WHERE USER_ID='$USER_ID'";
            $cursor2 = exequery(TD::conn(),$query2);
            if($ROW2=mysql_fetch_array($cursor2))
            {
                continue;
            }else
            {
                $staff_name1 .= $USER_NAME.',';
            }
        }
        $staff_name1 = td_trim($staff_name1);

        if($staff_name1!="")
        {
            Message(_('警告'),_('以下员工未建立人事档案，无法开启年假参数！</br>"'.$staff_name1.'"'));
            //Button_Back();
            ?>
            <center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';"></center>
            <?
            exit;
        }

        $query = "SELECT * from attend_leave_param order by working_years asc";
        $cursor= exequery(TD::conn(),$query);
        if(!mysql_num_rows($cursor)>0)
        {
            Message(_('错误'),_('请先添加工龄享有年假天数设置信息!'));
            //Button_Back();
            ?>
            <center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';"></center>
            <?
            exit;
        }


        if($ENTRY_RESET_LEAVE==1 || $LEAVE_BY_SENIORITY==1)
        {
            set_sys_para(array("ENTRY_RESET_LEAVE"=>"1","LEAVE_BY_SENIORITY"=>"1"));
            Message(_("成功"),_("年假参数设置完成！"));
            //Button_Back();
            ?>
            <center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';"></center>
            <?
            exit;
        }
    }
}
else
{
    set_sys_para(array("ENTRY_RESET_LEAVE"=>"0","LEAVE_BY_SENIORITY"=>"0"));
    Message(_("成功"),_("年假参数设置完成！"));
    //Button_Back();
    ?>
        <center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php';"></center>
        <?
    exit;
}

?>
