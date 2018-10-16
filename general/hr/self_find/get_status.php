<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

class USER_STATUS
{
    private static $arr_user_status = array();
    private static $last_get_status_time = 0;
    private static $_obj_instance = NULL;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function get_instance()
    {
        if(!(self::$_obj_instance instanceof self))
        {
            self::$_obj_instance = new USER_STATUS();
        }

        return self::$_obj_instance;
    }
    //��ȡԱ��״̬
    public static function get_status()
    {
        $ct = date("Y-m-d H:i:s", time());
        $dt = date("Y-m-d", time());
        $hi = date("H:i", time());

        if(empty(self::$arr_user_status))
        {
            self::$arr_user_status = TD::get_cache("user_status");
            self::$last_get_status_time = TD::get_cache("user_status_time");
        }
        if(empty(self::$arr_user_status))
        {
            $sql = "select UID, USER_ID, USER_NAME, DEPT_NAME from user cross join department on user.DEPT_ID = department.DEPT_ID where user.DEPT_ID <> '0' order by user.DEPT_ID, USER_ID";
            $cursor = exequery(TD::conn(), $sql);
            //status: 0: �ڹ�λ�ϣ�1�������2�����3���Ӱࣻ4�����
            while($row = mysql_fetch_array($cursor))
            {
                self::$arr_user_status[$row["USER_ID"]] = array("name" => $row["USER_NAME"], "dept" => $row["DEPT_NAME"], "changed" => "0", "status" => 0, "desc" => "","uid"=>$row["UID"],"allow"=>0);
            }
        }else
        {
            foreach(self::$arr_user_status as $s_key => $s_val)
            {
                //��ȡ�����е����ݣ���change����Ϊ0��
                self::$arr_user_status[$s_key]["changed"] = "0";
                $sql = "SELECT UID FROM user WHERE USER_ID = '$s_key'";
                $cursor = exequery(TD::conn(), $sql);
                if($row = mysql_fetch_array($cursor))
                {
                    self::$arr_user_status[$s_key]["uid"] = $row['UID'];
                    
                    $query_oa  = "SELECT * FROM module_priv WHERE UID = '{$row['UID']}' AND MODULE_ID = 0";
                    //module_priv �Ǹ��ձ�
                    $cursor_oa = exequery(TD::conn(),$query_oa);
                    if($arr=mysql_fetch_array($cursor_oa))
                    {
                        $DEPT_ID1 = $arr['DEPT_ID'];//����
                        $PRIV_ID1 = $arr['PRIV_ID'];//��ɫ
                        $USER_ID1 = $arr['USER_ID'];//��Ա
                        $PRIV1    = $DEPT_ID1."|".$PRIV_ID1."|".$USER_ID1;
                        if(check_priv($PRIV1))
                        {
                            self::$arr_user_status[$s_key]["priv"] = 1;
                        }else
                        {
                            self::$arr_user_status[$s_key]["priv"] = 0;
                        }
                    }
                    else
                    {
                        self::$arr_user_status[$s_key]["priv"] = 1;
                    }
                }
                $sql = "SELECT * FROM user_duty WHERE  duty_date = '$dt' and uid = '{$s_val['uid']}'";
                $cursor = exequery(TD::conn(), $sql);
                if(mysql_affected_rows()>0)
                {
                    //self::$arr_user_status[$s_key]["status"] = 0;
                }
                else
                {
                    self::$arr_user_status[$s_key]["status"] = 5;
                }
            }

            //С��1���ӽ������������Ԫ���ݷ���
             if(time() - self::$last_get_status_time <= 60)
            {
                return array_to_json(self::$arr_user_status);
            }
        }

        $his = date("H:i:s", time());
        //mktime(hour,minute,second,month,day,year,is_dst);
        $st = mktime (0,0,0,date("m")  ,date("d"),date("Y"));
        //����
        $sql = "select * from attend_evection where EVECTION_DATE1 <= '$ct' and EVECTION_DATE2 >= '$ct'";
        $cursor = exequery(TD::conn(), $sql);
        while($row = mysql_fetch_array($cursor))
        {
            self::$arr_user_status[$row["USER_ID"]]["allow"] = $row[ALLOW];
            if(self::$arr_user_status[$row["USER_ID"]]["changed"] == "0" && $row[ALLOW]=="1")
            {
                self::$arr_user_status[$row["USER_ID"]]["status"] = "2";
                self::$arr_user_status[$row["USER_ID"]]["changed"] = "1";
                self::$arr_user_status[$row["USER_ID"]]["desc"] = "����ص㣺$row[EVECTION_DEST] �������ɣ�$row[REASON]";
            }else if(self::$arr_user_status[$row["USER_ID"]]["changed"] == "0" && self::$arr_user_status[$row["USER_ID"]]["status"] != "2" && $row[ALLOW]=="0")
            {
                self::$arr_user_status[$row["USER_ID"]]["status"] = "2";
                self::$arr_user_status[$row["USER_ID"]]["changed"] = "1";
                self::$arr_user_status[$row["USER_ID"]]["desc"] = "������";
            }else if (self::$arr_user_status[$row["USER_ID"]]["changed"] == "0" && $row[ALLOW]=="2")
            {
                self::$arr_user_status[$row["USER_ID"]]["status"] = "0";
                self::$arr_user_status[$row["USER_ID"]]["changed"] = "0";
                self::$arr_user_status[$row["USER_ID"]]["desc"] = "";
            }
        }

        //���
        $sql = "select * from attend_out where SUBMIT_TIME like '$dt%' and (OUT_TIME1 <= '$hi' and OUT_TIME2 >= '$hi')";
        $cursor = exequery(TD::conn(), $sql);
        while($row = mysql_fetch_array($cursor))
        {
            self::$arr_user_status[$row["USER_ID"]]["allow"] = $row[ALLOW];
            if(self::$arr_user_status[$row["USER_ID"]]["changed"] == "0" && $row[ALLOW]=="1")
            {
                self::$arr_user_status[$row["USER_ID"]]["status"] = "1";
                self::$arr_user_status[$row["USER_ID"]]["changed"] = "1";
                self::$arr_user_status[$row["USER_ID"]]["desc"] = "���ԭ��$row[OUT_TYPE]";
            }else if(self::$arr_user_status[$row["USER_ID"]]["changed"] == "0" && self::$arr_user_status[$row["USER_ID"]]["status"] != "1" && $row[ALLOW]=="0")
            {
                self::$arr_user_status[$row["USER_ID"]]["status"] = "1";
                self::$arr_user_status[$row["USER_ID"]]["changed"] = "1";
                self::$arr_user_status[$row["USER_ID"]]["desc"] = "������";
            }else if (self::$arr_user_status[$row["USER_ID"]]["changed"] == "0" && $row[ALLOW]=="2")
            {
                self::$arr_user_status[$row["USER_ID"]]["status"] = "0";
                self::$arr_user_status[$row["USER_ID"]]["changed"] = "0";
                self::$arr_user_status[$row["USER_ID"]]["desc"] = "";
            }
        }

        //���
        $sql = "select * from attend_leave where LEAVE_DATE1 <= '$ct' and LEAVE_DATE2 >= '$ct'";
        $cursor = exequery(TD::conn(), $sql);
        while($row = mysql_fetch_array($cursor))
        {
            self::$arr_user_status[$row["USER_ID"]]["allow"] = $row[ALLOW];
            if(self::$arr_user_status[$row["USER_ID"]]["changed"] == "0" &&  $row[ALLOW]=="1" )
            {
                self::$arr_user_status[$row["USER_ID"]]["status"] = "4";
                self::$arr_user_status[$row["USER_ID"]]["changed"] = "1";
                self::$arr_user_status[$row["USER_ID"]]["desc"] = "���ԭ��$row[LEAVE_TYPE]";
            }else if(self::$arr_user_status[$row["USER_ID"]]["changed"] == "0" && self::$arr_user_status[$row["USER_ID"]]["status"] != "4" && $row[ALLOW]=="0")
            {
                self::$arr_user_status[$row["USER_ID"]]["status"] = "4";
                self::$arr_user_status[$row["USER_ID"]]["changed"] = "1";
                self::$arr_user_status[$row["USER_ID"]]["desc"] = "������";
            }else if(self::$arr_user_status[$row["USER_ID"]]["changed"] == "0" && $row[ALLOW]=="2"){
                self::$arr_user_status[$row["USER_ID"]]["status"] = "0";
                self::$arr_user_status[$row["USER_ID"]]["changed"] = "0";
                self::$arr_user_status[$row["USER_ID"]]["desc"] = "";
            }
        }

        //�Ӱ�
        $sql = "select * from attendance_overtime where START_TIME <= '$ct' and END_TIME >= '$ct'";
        $cursor = exequery(TD::conn(), $sql);
        while($row = mysql_fetch_array($cursor))
        {
            self::$arr_user_status[$row["USER_ID"]]["allow"] = $row[ALLOW];
            if(self::$arr_user_status[$row["USER_ID"]]["changed"] == "0" && $row[ALLOW]=="1" )
            {
                self::$arr_user_status[$row["USER_ID"]]["status"] = "3";
                self::$arr_user_status[$row["USER_ID"]]["changed"] = "1";
                self::$arr_user_status[$row["USER_ID"]]["desc"] = "�Ӱ����ݣ�$row[OVERTIME_CONTENT]";
            }else if(self::$arr_user_status[$row["USER_ID"]]["changed"] == "0" && self::$arr_user_status[$row["USER_ID"]]["status"] != "3" && $row[ALLOW]=="0")
            {
                self::$arr_user_status[$row["USER_ID"]]["status"] = "3";
                self::$arr_user_status[$row["USER_ID"]]["changed"] = "1";
                self::$arr_user_status[$row["USER_ID"]]["desc"] = "������";
            }else if(self::$arr_user_status[$row["USER_ID"]]["changed"] == "0" && $row[ALLOW]=="2")
            {
                self::$arr_user_status[$row["USER_ID"]]["status"] = "0";
                self::$arr_user_status[$row["USER_ID"]]["changed"] = "0";
                self::$arr_user_status[$row["USER_ID"]]["desc"] = "";
            }
        }

    //�ճ̰���
    //$sql = "select * from affair where (BEGIN_TIME <= '$st' and END_TIME >= '$st') and (BEGIN_TIME_TIME <= '$his' and END_TIME_TIME >= '$his')";
    //$cursor = exequery(TD::conn(), $sql);
    //while($row = mysql_fetch_array($cursor))
    //{
    //    if($arr_user_status[$row["USER_ID"]]["changed"] == "0")
    //    {
    //        $arr_user_status[$row["USER_ID"]]["status"] = "3";
    //        $arr_user_status[$row["USER_ID"]]["changed"] = "1";
    //        $arr_user_status[$row["USER_ID"]]["desc"] = "�������ݣ�$row[CONTENT]";
    //    }
    //}

        self::$last_get_status_time = time();
        $ttl = strtotime(date('Y-m-j 23:59:59')) - time();
       TD::set_cache("user_status", self::$arr_user_status, $ttl);
        TD::set_cache("user_status_time", self::$last_get_status_time, $ttl);
       // TD::delete_cache("user_status");
        //TD::delete_cache("user_status_time");
        return array_to_json(self::$arr_user_status);
    }
}

echo USER_STATUS::get_status();
?>