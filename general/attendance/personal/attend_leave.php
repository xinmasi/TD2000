<?
//获取当前人员管理部门
$query2 = "select MANAGERS,DEPT_ID_STR from attend_leave_manager WHERE FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',MANAGERS)";
$cursor2=exequery(TD::conn(),$query2);
while($ROW2=mysql_fetch_array($cursor2))
{
    $MANAGERS1.=$ROW2["MANAGERS"];
    if($ROW2["DEPT_ID_STR"]=="ALL_DEPT")
    {
        $DEPT_ID_STR .= $ROW2["DEPT_ID_STR"].",";
    }
    else
    {
        $DEPT_ID_STR .= $ROW2["DEPT_ID_STR"];
    }

}

if($DEPT_ID_STR && !strstr($DEPT_ID_STR,"ALL_DEPT"))
{
    if($batch=="on")
    {
        if($COPY_TO_ID!="")
        {
            $USER_NAME="";
            $LEAVE_USER_ID=trim($COPY_TO_ID,',');
            $LEAVE_USER_ID_ARRAY= explode(',', $LEAVE_USER_ID);
            for($i=0;$i<count($LEAVE_USER_ID_ARRAY);$i++)
            {
                $query="SELECT DEPT_ID,USER_NAME FROM user WHERE USER_ID = '".$LEAVE_USER_ID_ARRAY[$i]."' and USER_ID != '".$_SESSION["LOGIN_USER_ID"]."'";
                $cursor = exequery(TD::conn(),$query);
                while($ROW=mysql_fetch_array($cursor))
                {
                    if(!find_id($DEPT_ID_STR,$ROW["DEPT_ID"]))
                    {
                        $USER_NAME.=$ROW["USER_NAME"].",";
                    }
                }
            }
            if($USER_NAME)
            {
                Message(_("错误"),_($USER_NAME."不在你所管辖的部门"));
                Button_Back();
                exit;
            }
        }
    }
    else
    {
        if($TO_ID!="")
        {
            $LEAVE_USER_ID = $TO_ID;
            $query="SELECT DEPT_ID,USER_NAME FROM user WHERE USER_ID = '".$LEAVE_USER_ID."' and USER_ID != '".$_SESSION["LOGIN_USER_ID"]."' and !FIND_IN_SET(DEPT_ID,'".$DEPT_ID_STR."')";
            $cursor = exequery(TD::conn(),$query);
            while($ROW=mysql_fetch_array($cursor))
            {
                $USER_NAME.=$ROW["USER_NAME"].",";
            }
            if($USER_NAME)
            {
                Message(_("错误"),_($USER_NAME."不在你所管辖的部门"));
                Button_Back();
                exit;
            }
        }
    }
}
?>