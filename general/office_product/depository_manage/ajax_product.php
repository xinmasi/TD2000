<?
include_once("inc/auth.inc.php");
include_once("../function_type.php");
include_once ("inc/utility.php");
include_once("inc/utility_org.php");
include_once("inc/utility_sms1.php");
switch ($action){
    case 'depository_del'://删除库
        $query= "DELETE FROM $name WHERE id='{$id}'";
        $cursor = exequery ( TD::conn (), $query );
        if ($cursor) {
            echo '1';
        }else{
            echo '2';
        }
        break;
    case 'office_del'://删除办公用品
        $pro_id = $_POST["PRO_ID"];

        if(substr($pro_id,-1,1)==",")
        {
            $pro_id = substr($pro_id,0,-1);
        }

        $query = "delete from office_transhistory where PRO_ID in ($pro_id)";
        exequery(TD::conn(),$query);

        $query = "delete from office_products where PRO_ID in ($pro_id)";
        $cursor = exequery(TD::conn(),$query);


        if($cursor)
        {
            echo '1';
        }else
        {
            echo '2';
        }
        break;
    case 'TRANSHISTORY_DEL'://删除申请记录
        $query  = "DELETE FROM $name WHERE trans_id='{$id}'";
        $cursor = exequery(TD::conn(),$query);
        if($cursor)
        {
            echo '1';
        }else
        {
            echo '2';
        }
        break;
    case 'MY_TRANSHISTORY_DEL'://库存管理---放弃操作功能
        //查出申领数据
        $query_select     = "SELECT * FROM `office_transhistory` WHERE trans_id='{$id}'";
        $cursor_select    = exequery(TD::conn(),$query_select);
        $res2_select      = mysql_fetch_array($cursor_select);
        $FACT_QTY         = $res2_select['FACT_QTY'];//申请数量
        $TRANS_QTY        = $res2_select['TRANS_QTY'];//申请数量
        $PRO_ID           = $res2_select['PRO_ID'];
        $TRANS_FLAG       = $res2_select['TRANS_FLAG'];
        $RETURN_STATUS    = $res2_select['RETURN_STATUS'];
        //撤销申领
        if($RETURN_STATUS!=1)
        {
            $query_del = "DELETE FROM `office_transhistory` WHERE trans_id='{$id}'";
            $cursor_del = exequery(TD::conn(),$query_del);
        }else
        {
            $query_del = "UPDATE office_transhistory SET  RETURN_STATUS=0,RETURN_DATE='0000-00-00' WHERE trans_id='{$id}'";
            $cursor_del = exequery(TD::conn(),$query_del);
        }

        if($TRANS_FLAG=='2' && $RETURN_STATUS=='1')
        {
            $NEW_PRO_STOCK = $FACT_QTY;
        }
        else
        {
            $NEW_PRO_STOCK = $TRANS_QTY;
        }

        //更新库存
        if($TRANS_FLAG!=5)
        {
            $query  = "UPDATE office_products SET pro_stock=pro_stock-($NEW_PRO_STOCK) where pro_id='{$PRO_ID}'";
            $cursor_update = exequery(TD::conn(),$query);
        }

        if($TRANS_FLAG=='1'|| $TRANS_FLAG=='2')
        {
            echo "1";
        }else
        {
            echo "2";
        }
        break;
    case 'update_grant':
        $arr = array();
        $arr = explode('_',$id);
        $query  ="UPDATE office_transhistory SET grant_status=1,GRANTOR='".$_SESSION["LOGIN_USER_ID"]."' WHERE trans_id='{$arr[0]}'";
        $cursor = exequery(TD::conn(),$query);
        //$query="UPDATE office_products SET pro_stock=pro_stock-$arr[1] where pro_id=$arr[2]";
        //$cursor = exequery ( TD::conn (), $query );
        if ($cursor) {
            echo '1';
        }else{
            echo '2';
        }
        break;
    case 'get_office_type':
        $html = '';
        //$html .= '<select name="OFFICE_PROTYPE" class="filed_info_input" id="OFFICE_PROTYPE" onchange = "depositoryOfProductsOne(this.value,\'2\');">';
        $html .=  '<option value="-1">'._("请选择").'</option>';
        if($id!="")
        {
            $query_type = "select * from OFFICE_TYPE where ID in ($id)";
            $cursor_type= exequery(TD::conn(),$query_type);
            while($ROW_TYPE=mysql_fetch_array($cursor_type))
            {
                $html .=  "<option value='".$ROW_TYPE['ID']."' >".$ROW_TYPE['TYPE_NAME']."</option>";
            }
        }
        // $html .=  '</select>';
        echo $html;
        break;
    case 'get_office':
        $html = '';
        //$html .= '<select name="PRO_ID" id="PRO_ID" class="filed_info_input">';
        $html .= '<option value="-1">'._("请选择").'</option>';
        if($_SESSION["LOGIN_USER_PRIV"]!=1)
        {
            $query1="((find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PRO_MANAGER) or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',PRO_DEPT)) or (PRO_MANAGER='' and PRO_DEPT='') or PRO_DEPT='ALL_DEPT')";
        }
        else
        {
            $query1="1=1";
        }
        if($flag==3)//归还时只显示标记为借用的商品
        {
            $query1.=" and OFFICE_PRODUCT_TYPE = 2";
        }
        $query_products = "select * from OFFICE_PRODUCTS where OFFICE_PROTYPE = '$id' and $query1";
        $cursor_products= exequery(TD::conn(),$query_products);
        while($ROW_PRODUCTS=mysql_fetch_array($cursor_products))
        {
            $html .= "<option value=".$ROW_PRODUCTS['PRO_ID'].">".$ROW_PRODUCTS['PRO_NAME']._("/库存").td_htmlspecialchars($ROW_PRODUCTS['PRO_STOCK'])."</option>";
        }
        if($type!='2')
        {
            //$html .= '</select>&nbsp;&nbsp;&nbsp;<input type="button" name="SelectPro" title='._("模糊选择").' value='._("模糊选择").' class="btn btn-small btn-primary" onClick="LoadWindow1()">';
        }
        echo $html;
        break;
    case 'get_office_type1':
        $html = '';
        //$html .= '<select name="OFFICE_PROTYPE1" class="filed_info_input" id="OFFICE_PROTYPE1" onchange = "depositoryOfProductsOne(this.value,\'1\');">';
        $html .=  '<option value="-1">'._("请选择").'</option>';
        if($id!="")
        {
            $query_type = "select * from OFFICE_TYPE where ID in ($id)";
            $cursor_type= exequery(TD::conn(),$query_type);
            while($ROW_TYPE=mysql_fetch_array($cursor_type))
            {
                $html .=  "<option value='".$ROW_TYPE['ID']."' >".$ROW_TYPE['TYPE_NAME']."</option>";
            }
        }
        //$html .=  '</select>';
        echo $html;
        break;
    case 'get_office1':
        $html = '';
        //$html .= '<select name="PRO_ID1" id="PRO_ID1" class="filed_info_input">';
        $html .= '<option value="-1">'._("请选择").'</option>';
        if($_SESSION["LOGIN_USER_PRIV"]!=1)
        {
            $query1="((find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PRO_MANAGER) or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',PRO_DEPT)) or (PRO_MANAGER='' and PRO_DEPT='') or PRO_DEPT='ALL_DEPT')";
        }
        else
        {
            $query1="1=1";
        }
        $query_products = "select * from OFFICE_PRODUCTS where OFFICE_PROTYPE = '$id' and $query1";
        $cursor_products= exequery(TD::conn(),$query_products);
        while($ROW_PRODUCTS=mysql_fetch_array($cursor_products))
        {
            $html .= "<option value=".$ROW_PRODUCTS['PRO_ID'].">".$ROW_PRODUCTS['PRO_NAME']._("/库存").td_htmlspecialchars($ROW_PRODUCTS['PRO_STOCK'])."</option>";
        }

        echo $html;
        break;
    case 'status':
        $all_id= get_transhistory($_SESSION['LOGIN_USER_ID']);
        $query="UPDATE office_transhistory SET ";
        $arr2=array();
        $arr2=explode('_',$id); //获取id传的值
        $num=count($arr2)-1;

        $pro_id_str   = substr($pro_id_str,0,-1);
        $fact_qty_str = substr($fact_qty_str,0,-1);
        $trans_id_str = substr($trans_id_str,0,-1);

        if($arr2[$num]=='a')//部门审批
        {
            $where = "dept_status=1";
        }
        else//管理员审批
        {
            $where = "trans_state=1";
        }
        if($cycle_no)//批量操作
        {
            $pro_id_array   = explode(",",$pro_id_str);
            $trans_id_array = explode(",",$trans_id_str);

            if($return_date!="0000-00-00")//归还
            {
                for($i=0;$i<count($trans_id_array);$i++)
                {
                    $sql = "SELECT a.*,b.PRO_NAME,b.PRO_STOCK FROM office_transhistory as a left join office_products as b on a.PRO_ID = b.PRO_ID WHERE a.TRANS_ID = '{$trans_id_array[$i]}'";
                    $res = exequery(TD::conn(),$sql);
                    $arr = mysql_fetch_array($res);

                    $query = "UPDATE office_transhistory SET RETURN_STATUS=1 WHERE TRANS_ID = '{$trans_id_array[$i]}'";
                    exequery(TD::conn(),$query);

                    $NEW_PRO_STOCK=$arr['PRO_STOCK']+$arr['FACT_QTY'];
                    $query="UPDATE OFFICE_PRODUCTS SET PRO_STOCK ='$NEW_PRO_STOCK' WHERE PRO_ID='{$pro_id_array[$i]}'";
                    exequery(TD::conn(),$query);

                    send_sms("",$_SESSION["LOGIN_USER_ID"],$arr['BORROWER'],75,sprintf(_("[%s]同意了您%s的归还申请"),$_SESSION["LOGIN_USER_NAME"],abs($arr['FACT_QTY']).$arr['PRO_UNIT'].$arr['PRO_NAME']),'office_product/apply/apply_list.php');

                }
            }
            else
            {
                for($i=0;$i<count($trans_id_array);$i++)
                {
                    $query="SELECT a.*,c.PRO_KEEPER,c.MANAGER from OFFICE_PRODUCTS a left outer join OFFICE_TYPE b on a.OFFICE_PROTYPE=b.ID left outer join OFFICE_DEPOSITORY c on b.TYPE_DEPOSITORY=c.ID WHERE a.PRO_ID='{$pro_id_array[$i]}'";
                    $cursor1= exequery(TD::conn(),$query);
                    if($ROW=mysql_fetch_array($cursor1))
                    {
                        $PRO_STOCK   = $ROW["PRO_STOCK"];
                        $PRO_PRICE   = $ROW["PRO_PRICE"];
                        $MANAGER     = $ROW['MANAGER'];
                        $PRO_KEEPER  = $ROW["PRO_KEEPER"];
                        $PRO_UNIT    = $ROW["PRO_UNIT"];
                        $PRO_NAME    = $ROW["PRO_NAME"];
                        $PRO_AUDITER = $ROW["PRO_AUDITER"];
                    }
                    $sql = "SELECT * FROM office_transhistory WHERE TRANS_ID = '{$trans_id_array[$i]}'";
                    $res = exequery(TD::conn(),$sql);
                    $arr = mysql_fetch_array($res);

                    //审批前验证库存
                    if($arr2[$num]!='a')
                    {
                        $NEW_PRO_STOCK=$PRO_STOCK-$arr['FACT_QTY'];
                        if($NEW_PRO_STOCK<0)
                        {
                            continue;
                        }
                    }
                    $query="UPDATE office_transhistory SET ".$where." WHERE CYCLE_NO = '$cycle_no' and TRANS_ID='{$trans_id_array[$i]}'";
                    exequery(TD::conn(),$query);

                    $BORROWER_NAME = td_trim(GetUserNameById($arr['BORROWER']));
                    $PRO_AUDITER==""?$MANAGER!=""?$sp=$MANAGER:"admin":$sp=$PRO_AUDITER;

                    if($arr2[$num]=='a')
                    {
                        if($arr['TRANS_STATE']==0)
                        {
                            $REMIND_URL="1:office_product/dept_approval/pending_list.php";
                            $SMS_CONTENT=sprintf(_("部门领导[%s]已批准%s的办公用品%s申请，请批示!"),$_SESSION["LOGIN_USER_NAME"],$BORROWER_NAME,$TRANS_FLAG_NAME);
                            send_sms("",$arr['BORROWER'],$sp,43,$SMS_CONTENT,$REMIND_URL);
                        }

                    }else
                    {
                        $NEW_PRO_STOCK=$PRO_STOCK-$arr['FACT_QTY'];
                        $query="UPDATE OFFICE_PRODUCTS SET PRO_STOCK ='$NEW_PRO_STOCK' WHERE PRO_ID='{$pro_id_array[$i]}'";
                        exequery(TD::conn(),$query);

                        if($PRO_KEEPER!="")
                        {
                            send_sms("",$_SESSION["LOGIN_USER_ID"],$PRO_KEEPER,75,sprintf(_("[%s]同意了[%s]%s的申请，请准备物品"),$_SESSION["LOGIN_USER_NAME"],$BORROWER_NAME,abs($arr['FACT_QTY']).$arr['PRO_UNIT'].$PRO_NAME),'');
                        }
                        send_sms("",$_SESSION["LOGIN_USER_ID"],$arr['BORROWER'],75,sprintf(_("[%s]同意了您%s的申请"),$_SESSION["LOGIN_USER_NAME"],abs($arr['FACT_QTY']).$arr['PRO_UNIT'].$PRO_NAME),'office_product/apply/apply_list.php');

                        if($MANAGER!="" && $ROW['PRO_STOCKR'] < $ROW['PRO_LOWSTOCKR'])
                        {
                            send_sms("",$_SESSION["LOGIN_USER_ID"],$MANAGER,43,sprintf(_("[%s]提醒您办公用品(%s)的数量低于最低警戒库存,请及时采购物品"),$_SESSION["LOGIN_USER_NAME"],$PRO_NAME),"1:office_product/inventory_manage/query_list.php?action=one");
                        }
                    }
                }
            }
        }
        else
        {
            $query="SELECT a.*,c.PRO_KEEPER,c.MANAGER from OFFICE_PRODUCTS a left outer join OFFICE_TYPE b on a.OFFICE_PROTYPE=b.ID left outer join OFFICE_DEPOSITORY c on b.TYPE_DEPOSITORY=c.ID WHERE a.PRO_ID='$pro_id_str'";
            $cursor1= exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor1))
            {
                $PRO_STOCK   = $ROW["PRO_STOCK"];
                $PRO_PRICE   = $ROW["PRO_PRICE"];
                $MANAGER     = $ROW['MANAGER'];
                $PRO_KEEPER  = $ROW["PRO_KEEPER"];
                $PRO_UNIT    = $ROW["PRO_UNIT"];
                $PRO_NAME    = $ROW["PRO_NAME"];
                $PRO_AUDITER = $ROW["PRO_AUDITER"];
            }
            $sql = "SELECT * FROM office_transhistory WHERE TRANS_ID = '$trans_id'";
            $res = exequery(TD::conn(),$sql);
            $arr = mysql_fetch_array($res);


            if($return_date!="0000-00-00")//归还
            {
                $query = "UPDATE office_transhistory SET RETURN_STATUS=1 WHERE TRANS_ID = '$trans_id'";
                exequery(TD::conn(),$query);

                $NEW_PRO_STOCK=$PRO_STOCK+$arr['FACT_QTY'];
                $query="UPDATE OFFICE_PRODUCTS SET PRO_STOCK ='$NEW_PRO_STOCK' WHERE PRO_ID='$pro_id_str'";
                exequery(TD::conn(),$query);

                send_sms("",$_SESSION["LOGIN_USER_ID"],$arr['BORROWER'],75,sprintf(_("[%s]同意了您%s的归还申请"),$_SESSION["LOGIN_USER_NAME"],abs($arr['FACT_QTY']).$arr['PRO_UNIT'].$PRO_NAME),'office_product/apply/apply_list.php');
            }
            else
            {
                //验证是否是复审批的
                if(isset($repeat) && $repeat =="repeat")
                {
                    echo "1";
                    exit;
                }
                //审批前验证库存
                if($arr2[$num]!='a')
                {
                    $NEW_PRO_STOCK=$PRO_STOCK-$arr['FACT_QTY'];
                    if($NEW_PRO_STOCK<0)
                    {
                        echo "2";
                        exit;
                    }
                }
                $query="UPDATE office_transhistory SET ".$where." WHERE TRANS_ID='$trans_id'";
                exequery(TD::conn(),$query);

                $BORROWER_NAME = td_trim(GetUserNameById($arr['BORROWER']));
                $PRO_AUDITER==""?$MANAGER!=""?$sp=$MANAGER:"admin":$sp=$PRO_AUDITER;

                if($arr2[$num]=='a')
                {
                    if($arr['TRANS_STATE']==0)
                    {
                        $REMIND_URL="1:office_product/dept_approval/pending_list.php";
                        $SMS_CONTENT=sprintf(_("部门领导[%s]已批准%s的办公用品%s申请，请批示!"),$_SESSION["LOGIN_USER_NAME"],$BORROWER_NAME,$TRANS_FLAG_NAME);
                        send_sms("",$arr['BORROWER'],$sp,43,$SMS_CONTENT,$REMIND_URL);
                    }

                }else
                {
                    $NEW_PRO_STOCK=$PRO_STOCK-$arr['FACT_QTY'];
                    $query="UPDATE OFFICE_PRODUCTS SET PRO_STOCK ='$NEW_PRO_STOCK' WHERE PRO_ID='$pro_id_str'";
                    exequery(TD::conn(),$query);

                    if($PRO_KEEPER!="")
                    {
                        send_sms("",$_SESSION["LOGIN_USER_ID"],$PRO_KEEPER,75,sprintf(_("[%s]同意了[%s]%s的申请，请准备物品"),$_SESSION["LOGIN_USER_NAME"],$BORROWER_NAME,abs($arr['FACT_QTY']).$arr['PRO_UNIT'].$PRO_NAME),'');
                    }
                    send_sms("",$_SESSION["LOGIN_USER_ID"],$arr['BORROWER'],75,sprintf(_("[%s]同意了您%s的申请"),$_SESSION["LOGIN_USER_NAME"],abs($arr['FACT_QTY']).$arr['PRO_UNIT'].$PRO_NAME),'office_product/apply/apply_list.php');

                    if($MANAGER!="" && $ROW['PRO_STOCKR'] < $ROW['PRO_LOWSTOCKR'])
                    {
                        send_sms("",$_SESSION["LOGIN_USER_ID"],$MANAGER,43,sprintf(_("[%s]提醒您办公用品(%s)的数量低于最低警戒库存,请及时采购物品"),$_SESSION["LOGIN_USER_NAME"],$PRO_NAME),"");
                    }
                }
            }
        }
        echo "1";
        break;
    case 'DEPOSITORY_NAME':
        if(isset($id))
        {
            $query="select id from office_depository where depository_name='{$DEPOSITORY_NAME}' and id!={$id}";
        }else
        {
            $query="select id from office_depository where depository_name='{$DEPOSITORY_NAME}'";
        }
        $cursor = exequery(TD::conn(),$query);
        if (mysql_num_rows($cursor)>0) {
            echo '1';
        }else{
            echo '2';
        }
        break;
    case 'get_pro_stock'://获取办公用品库存
        if(isset($id)){
            $arr_pro = get_product_num($id);
            $stock = $arr_pro['pro_stock'];
            if($stock != '')
            {
                echo $stock;
            }
            else
            {
                echo 'false';
            }
        }
        break;
    case 'check_pro_stock'://验证库存和调拨数量大小问题
        if(isset($id)){
            $query="select PRO_STOCK from office_products where PRO_ID='{$id}'";
            $cursor = exequery ( TD::conn (), $query );
            if($office_row = mysql_fetch_array($cursor))
            {
                if ($office_row['PRO_STOCK'] >= $trans_qty) {

                    echo '1';
                }else{
                    echo '2';
                }
            }
        }
        break;
    case 'TYPE_NAME':
        if(isset($id)){
            $query="select id from office_type where type_name='{$TYPE_NAME}' and id!={$id}";
        }else{
            $query="select id from office_type where type_name='{$TYPE_NAME}'";
        }
        $cursor = exequery ( TD::conn (), $query );
        if (mysql_num_rows($cursor)>0) {
            echo '1';
        }else{
            echo '2';
        }
        break;
    case 'project_num':
        $query="select sum(fact_qty) as num from office_transhistory where borrower='{$_SESSION['LOGIN_USER_ID']}' and pro_id='$pro_id' and trans_state=1 and trans_flag in (2,3)";
        $cursor = exequery ( TD::conn (), $query );
        if ($office_row = mysql_fetch_array($cursor)) {
            if($office_row['num']>0)
            {
                echo 1;
            }elseif($office_row['num']==0){
                echo 2;
            }else{
                $sum=$office_row['num']+$num;
                if($sum<=0)
                {
                    echo 3;
                }else{
                    echo 4;
                }
            }
        }else{
            echo '5';
        }
        break;
    case 'check_add':
        $where = "";
        if(!empty($PRO_ID))
        {
            $where = " AND PRO_ID!= '$PRO_ID'";
        }
        $query = "SELECT * FROM office_products WHERE OFFICE_PROTYPE in($OFFICE_DEPOSITORY) AND PRO_NAME = '$PRO_NAME' AND PRO_DESC = '$PRO_DESC' AND ALLOCATION = '0' ".$where;
        $cursor = exequery(TD::conn(),$query);
        if(mysql_affected_rows()>0)
        {
            echo 2;
        }else
        {
            echo 1;
        }
        break;
}

?>