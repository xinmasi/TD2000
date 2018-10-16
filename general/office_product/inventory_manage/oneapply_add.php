<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_sms1.php");
include_once("../function_type.php");

include_once("inc/header.inc.php");


//申请人
if($TO_ID!="")
{
    $BORROWER_NAME = substr(GetUserNameById($TO_ID),0,-1);
}

//添加
if(empty($TRANS_ID))
{
    if(!isset($PRO_ID))
    {
        $PRO_NAME_ARRAY = explode("/",$PRO_NAME);
        $where = "a.PRO_NAME = '{$PRO_NAME_ARRAY[0]}'";
    }else
    {
        $where = "a.PRO_ID='$PRO_ID'";
    }

    $query="select a.PRO_UNIT,a.PRO_ID,a.PRO_NAME,a.PRO_STOCK,a.PRO_PRICE,c.PRO_KEEPER,c.MANAGER from office_products a left outer join office_type b on a.OFFICE_PROTYPE=b.ID left outer join office_depository c on b.TYPE_DEPOSITORY=c.ID where ".$where;
    $res = exequery(TD::conn(),$query);
    $arr = mysql_fetch_array($res);

    $this_time = date("Y-m-d",time());

    $query="SELECT * FROM office_products WHERE PRO_ID='{$arr['PRO_ID']}'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor))
    {
        $PRO_LOWSTOCK = $ROW['PRO_LOWSTOCK'];
        $PRO_MAXSTOCK = $ROW['PRO_MAXSTOCK'];
        $OFFICE_TYPE  = $ROW['OFFICE_PRODUCT_TYPE'];
    }
    //库存维护
    if($TRANS_FLAG!="-1" && $TRANS_FLAG2=="-1")
    {
        //采购
        if($TRANS_FLAG =='0')
        {
            $sql="INSERT INTO office_transhistory(PRO_ID,BORROWER,TRANS_FLAG,TRANS_QTY,REMARK,TRANS_DATE,OPERATOR,TRANS_STATE,PRO_KEEPER,FACT_QTY) values ('{$arr['PRO_ID']}','','$TRANS_FLAG','$TRANS_QTY','$REMARK','$this_time','{$_SESSION['LOGIN_USER_ID']}','1','{$arr['PRO_KEEPER']}','$TRANS_QTY')";
            exequery(TD::conn(),$sql);

            $NEW_PRO_STOCK=$arr['PRO_STOCK']+$TRANS_QTY;
            $query="UPDATE office_products SET PRO_STOCK ='$NEW_PRO_STOCK' WHERE PRO_ID='{$arr['PRO_ID']}'";
            exequery(TD::conn(),$query);

            $SMS_CONTENT=sprintf(_("[%s]采购入库了%s,数量：%s"),$_SESSION["LOGIN_USER_NAME"],$arr['PRO_NAME'],$TRANS_QTY.$arr['PRO_UNIT']);

            if($SMS_REMIND=="on" && $arr['MANAGER']!="")
            {
                send_sms("",$_SESSION["LOGIN_USER_ID"],$arr['MANAGER'],43,$SMS_CONTENT,"1:office_product/inventory_manage/query_list.php?action=one");
            }
            if ($SMS2_REMIND=="on" && $arr['MANAGER']!="")
            {
                send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$arr['MANAGER'],$SMS_CONTENT,43);
            }
            if($NEW_PRO_STOCK > $PRO_MAXSTOCK && $arr['MANAGER']!="" && $PRO_MAXSTOCK != 0)
            {
                send_sms("",$_SESSION["LOGIN_USER_ID"],$arr['MANAGER'],43,sprintf(_("[%s]提醒您办公用品(%s)的数量已经高于最高警戒库存"),$_SESSION["LOGIN_USER_NAME"],$arr['PRO_NAME']),"1:office_product/inventory_manage/query_list.php?action=one");
            }
        }
        //维护
        if($TRANS_FLAG =='5')
        {
            $AVAILABLE = strtotime($_POST['REP_TIME1'])."|".strtotime($_POST['REP_TIME2']);

            $sql="INSERT INTO office_transhistory(PRO_ID,BORROWER,TRANS_FLAG,TRANS_QTY,REMARK,TRANS_DATE,OPERATOR,TRANS_STATE,PRO_KEEPER,AVAILABLE) values ('{$arr['PRO_ID']}','','$TRANS_FLAG','0','$REMARK','$this_time','{$_SESSION['LOGIN_USER_ID']}','1','{$arr['PRO_KEEPER']}','$AVAILABLE')";
            exequery(TD::conn(),$sql);
            //修改办公用品表属性
            $sql1 = "UPDATE office_products SET AVAILABLE = '$AVAILABLE' WHERE PRO_ID = '{$arr['PRO_ID']}'";
            exequery(TD::conn(),$sql1);

            $SMS_CONTENT=sprintf(_("[%s]提醒你办公用品%s需要在[%s]-[%s]维护,备注:%s"),$_SESSION["LOGIN_USER_NAME"],$arr['PRO_NAME'],$_POST['REP_TIME1'],$_POST['REP_TIME2'],$_POST['REMARK']);

            if($SMS_REMIND=="on" && $arr['MANAGER']!="")
            {
                send_sms("",$_SESSION["LOGIN_USER_ID"],$arr['MANAGER'],46,$SMS_CONTENT,"office_product/inventory_manage/query_list.php?action=one");

            }
            if ($SMS2_REMIND=="on" && $arr['MANAGER']!="")
            {
                send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$arr['MANAGER'],$SMS_CONTENT,46);
            }

        }
        //报废
        if($TRANS_FLAG =='4')
        {
            $TRANS_QTY1 = $TRANS_QTY*(-1);
            $sql="INSERT INTO office_transhistory(PRO_ID,BORROWER,TRANS_FLAG,TRANS_QTY,REMARK,TRANS_DATE,OPERATOR,TRANS_STATE,PRO_KEEPER,FACT_QTY) values ('{$arr['PRO_ID']}','','$TRANS_FLAG','$TRANS_QTY1','$REMARK','$this_time','{$_SESSION['LOGIN_USER_ID']}','1','{$arr['PRO_KEEPER']}','$TRANS_QTY')";
            exequery(TD::conn(),$sql);

            $NEW_PRO_STOCK=$arr['PRO_STOCK']+$TRANS_QTY1;
            $query="UPDATE office_products SET PRO_STOCK ='$NEW_PRO_STOCK' WHERE PRO_ID='{$arr['PRO_ID']}'";
            exequery(TD::conn(),$query);

            if($arr['MANAGER']!="" && $NEW_PRO_STOCK < $PRO_LOWSTOCK)
            {
                send_sms("",$_SESSION["LOGIN_USER_ID"],$arr['MANAGER'],43,sprintf(_("[%s]提醒您办公用品(%s)的数量低于最低警戒库存,请及时采购物品"),$_SESSION["LOGIN_USER_NAME"],$arr['PRO_NAME']),"1:office_product/inventory_manage/query_list.php?action=one");
            }
        }
    }
    //代登记
    if($TRANS_FLAG=="-1" && $TRANS_FLAG2!="-1")
    {
        //领用/借用
        if($TRANS_FLAG2==1)
        {
            if($OFFICE_TYPE=='1' || $OFFICE_TYPE=='2')//领用
            {
                if($OFFICE_TYPE=='2')
                {
                    $sql2="select * from office_transhistory where PRO_ID='{$arr['PRO_ID']}' and BORROWER='$TO_ID' and TRANS_FLAG='2' and TRANS_STATE='0'";
                    $re1=exequery(TD::conn(),$sql2);
                    if(mysql_affected_rows()>0)
                    {
                        Message(_("错误"),_("此办公用品有借用申请正在处理中,无法重复登记"));
                        Button_Back();
                        exit;
                    }
                }
                $TRANS_QTY1 = $TRANS_QTY*(-1);

                $query="INSERT INTO office_transhistory(PRO_ID,BORROWER,TRANS_FLAG,TRANS_QTY,REMARK,TRANS_DATE,OPERATOR,TRANS_STATE,FACT_QTY,PRO_KEEPER) VALUES ('{$arr['PRO_ID']}','$TO_ID','$OFFICE_TYPE','$TRANS_QTY1','$REMARK','$this_time','{$_SESSION['LOGIN_USER_ID']}',1,'$TRANS_QTY','{$arr['PRO_KEEPER']}')";
                exequery(TD::conn(),$query);

                $NEW_PRO_STOCK=$arr['PRO_STOCK']-$TRANS_QTY;
                $query="UPDATE office_products SET PRO_STOCK ='$NEW_PRO_STOCK' WHERE PRO_ID='{$arr['PRO_ID']}'";
                exequery(TD::conn(),$query);

                if($SMS_REMIND=="on" && $arr['PRO_KEEPER']!="")
                {
                    send_sms("",$_SESSION["LOGIN_USER_ID"],$arr['PRO_KEEPER'],75,sprintf(_("[%s]同意了[%s]%s的申请，请准备物品"),$_SESSION["LOGIN_USER_NAME"],$BORROWER_NAME,$TRANS_QTY.$arr['PRO_UNIT'].$arr['PRO_NAME']),'1:office_product/grant/query.php');
                }
                if($SMS2_REMIND=="on" && $arr['PRO_KEEPER']!="")
                {
                    send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$arr['PRO_KEEPER'],sprintf(_("[%s]同意了[%s]%s的申请，请准备物品"),$_SESSION["LOGIN_USER_NAME"],$BORROWER_NAME,$TRANS_QTY.$arr['PRO_UNIT'].$arr['PRO_NAME']),75);
                }

                if($arr['MANAGER']!="" && $NEW_PRO_STOCK <= $PRO_LOWSTOCK)
                {
                    send_sms("",$_SESSION["LOGIN_USER_ID"],$arr['MANAGER'],43,sprintf(_("[%s]提醒您办公用品(%s)的数量低于最低警戒库存,请及时采购物品"),$_SESSION["LOGIN_USER_NAME"],$arr['PRO_NAME']),"1:office_product/inventory_manage/query_list.php?action=one");
                }
            }
        }
        //归还
        if($TRANS_FLAG2==3)
        {
            $sql="SELECT * FROM office_transhistory WHERE BORROWER = '$TO_ID' AND TRANS_STATE = 1 AND TRANS_FLAG = 2 AND GRANT_STATUS = 1 AND RETURN_STATUS = 0 AND RETURN_DATE = '0000-00-00' AND PRO_ID = '{$arr['PRO_ID']}'";
            $cursor= exequery(TD::conn(),$sql);
            if(mysql_affected_rows()>0)
            {
                if($rows=mysql_fetch_assoc($cursor))
                {
                    $query = "UPDATE office_transhistory SET RETURN_STATUS = 1,RETURN_DATE = '$this_time' WHERE TRANS_ID = '{$rows['TRANS_ID']}'";
                    exequery(TD::conn(),$query);

                    $NEW_PRO_STOCK=$arr['PRO_STOCK']+$rows['FACT_QTY'];
                    $query="UPDATE office_products SET PRO_STOCK ='$NEW_PRO_STOCK' WHERE PRO_ID='{$rows['PRO_ID']}'";
                    exequery(TD::conn(),$query);
                }
            }
            else
            {
                Message(_("错误"),_("没有此办公用品的借用信息"));
                ?>
                <br><center><input type="button" class="BigButtonA" value="<?=_("返回")?>" onclick="javascript:window.location='query_list.php?action=two'"></center>
                <?
                exit;
            }
        }
    }
}
else
{
    $NEW_PRO_STOCK = "";
    $sql = "SELECT a.TRANS_FLAG,a.TRANS_QTY,a.FACT_QTY,a.GRANT_STATUS,a.RETURN_STATUS,b.PRO_ID,b.PRO_NAME,b.PRO_LOWSTOCK,b.PRO_MAXSTOCK,b.PRO_STOCK,c.MANAGER FROM office_transhistory as a left join  office_products as b on a.PRO_ID = b.PRO_ID left join office_depository as c on FIND_IN_SET(b.OFFICE_PROTYPE,c.OFFICE_TYPE_ID) WHERE TRANS_ID = '$TRANS_ID'";
    $res = exequery(TD::conn(),$sql);
    $arr = mysql_fetch_array($res);

    if($arr['TRANS_FLAG'] == '0')//采购入库
    {
        $NEW_PRO_STOCK = $TRANS_QTY_OLD - $TRANS_QTY;
        $query = "UPDATE office_transhistory SET TRANS_QTY='$TRANS_QTY',REMARK='$REMARK',FACT_QTY='$TRANS_QTY' WHERE TRANS_ID = '$TRANS_ID'";
        exequery(TD::conn(),$query);

        $query="UPDATE office_products SET PRO_STOCK = PRO_STOCK-($NEW_PRO_STOCK) WHERE PRO_ID='{$arr['PRO_ID']}'";
        exequery(TD::conn(),$query);

        $NEW_PRO_STOCK<0 ? $op=_("采购入库") : $op=_("减少了");

        $SMS_CONTENT=sprintf(_("[%s]%s%s库存,数量：%s"),$_SESSION["LOGIN_USER_NAME"],$op,$arr['PRO_NAME'],abs($NEW_PRO_STOCK).$arr['PRO_UNIT']);

        if($SMS_REMIND=="on" && $arr['MANAGER']!="")
        {
            send_sms("",$_SESSION["LOGIN_USER_ID"],$arr['MANAGER'],43,$SMS_CONTENT,"office_product/inventory_manage/query_list.php?action=one");
        }
        if ($SMS2_REMIND=="on" && $arr['MANAGER']!="")
        {
            send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$arr['MANAGER'],$SMS_CONTENT,43);
        }
    }
    if($arr['TRANS_FLAG'] == '4')//报废
    {
        $NEW_PRO_STOCK = $TRANS_QTY_OLD - $TRANS_QTY;

        $query = "UPDATE office_transhistory SET TRANS_QTY=$TRANS_QTY*(-1),REMARK='$REMARK',FACT_QTY='$TRANS_QTY' WHERE TRANS_ID = '$TRANS_ID'";
        exequery(TD::conn(),$query);

        $query="UPDATE office_products SET PRO_STOCK = PRO_STOCK+($NEW_PRO_STOCK) WHERE PRO_ID='{$arr['PRO_ID']}'";
        exequery(TD::conn(),$query);

        $PRO_STOCK1 = $arr['PRO_STOCK']+($NEW_PRO_STOCK);
        if($arr['MANAGER']!="" && $PRO_STOCK1 < $arr['PRO_LOWSTOCK'])
        {
            send_sms("",$_SESSION["LOGIN_USER_ID"],$arr['MANAGER'],43,sprintf(_("[%s]提醒您办公用品(%s)的数量低于最低警戒库存,请及时采购物品"),$_SESSION["LOGIN_USER_NAME"],$arr['PRO_NAME']),"1:office_product/inventory_manage/query_list.php?action=one");
        }
    }
    if($arr['TRANS_FLAG'] == '1' || $arr['TRANS_FLAG'] == '2')//领用借用
    {
        $NEW_PRO_STOCK = $TRANS_QTY_OLD - $TRANS_QTY;

        $query = "UPDATE office_transhistory SET TRANS_QTY=$TRANS_QTY*(-1),REMARK='$REMARK',FACT_QTY='$TRANS_QTY' WHERE TRANS_ID = '$TRANS_ID'";
        exequery(TD::conn(),$query);

        $query="UPDATE office_products SET PRO_STOCK = PRO_STOCK+($NEW_PRO_STOCK) WHERE PRO_ID='{$arr['PRO_ID']}'";
        exequery(TD::conn(),$query);

        if($SMS_REMIND=="on" && $arr['PRO_KEEPER']!="")
        {
            send_sms("",$_SESSION["LOGIN_USER_ID"],$arr['PRO_KEEPER'],75,sprintf(_("[%s]同意了[%s]%s的申请，请准备物品"),$_SESSION["LOGIN_USER_NAME"],$BORROWER_NAME,$TRANS_QTY.$arr['PRO_UNIT'].$arr['PRO_NAME']),'1:office_product/grant/query.php');
        }
        if($SMS2_REMIND=="on" && $arr['PRO_KEEPER']!="")
        {
            send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$arr['PRO_KEEPER'],sprintf(_("[%s]同意了[%s]%s的申请，请准备物品"),$_SESSION["LOGIN_USER_NAME"],$BORROWER_NAME,$TRANS_QTY.$arr['PRO_UNIT'].$arr['PRO_NAME']),75);
        }
    }

}

//跳转
if($TRANS_FLAG2 != '-1' && $TRANS_FLAG == '-1')
{
    header("location: query_list.php?action=two&id={$TRANS_ID}");
}
if($TRANS_FLAG2 == '-1' && $TRANS_FLAG != '-1')
{
    header("location: query_list.php?action=one&id={$TRANS_ID}");
}
header("location: query_list.php?action={$action}");