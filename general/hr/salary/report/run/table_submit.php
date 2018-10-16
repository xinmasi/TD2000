<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("fun_compute.func.php");

$HTML_PAGE_TITLE = _("工资数据提交");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query = "SELECT USER_ID from USER where DEPT_ID='$DEPT_ID'";
$cursor= exequery(TD::conn(),$query);
$USER_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $USER_ID=$ROW["USER_ID"];
    $OPERATION_ID=$USER_ID."_OPERATION";
    $query3 = "SELECT ITEM_ID from sal_item where ITEM_NAME='税前工资';";
    $cursor3= exequery(TD::conn(),$query3);
    if($ROW3=mysql_fetch_array($cursor3))
    {
        $SAL_DATA_NUMBER=$USER_ID."_".$ROW3["ITEM_ID"];
    }
    if($$OPERATION_ID==1)
    {

        $query1="insert into SAL_DATA(FLOW_ID,USER_ID,IS_DEPT_INPUT,IS_FINA_INPUT";
        $STYLE_ARRAY=explode(",",$STYLE);
        $ARRAY_COUNT=sizeof($STYLE_ARRAY);
        $COUNT=0;
        if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
        for($I=0;$I<$ARRAY_COUNT;$I++)
            $query1.=",S".$STYLE_ARRAY[$I];
        $query1.=",REPORT) values ('".$FLOW_ID."','$USER_ID','1','1',";

        for($I=0;$I<$ARRAY_COUNT;$I++)
        {
            $STR=$USER_ID."_".$STYLE_ARRAY[$I];
            if($$STR=="")
                $$STR="0";
            $query1.=$$STR;
            if($I!=$ARRAY_COUNT-1)
                $query1.=",";
        }
        if(substr($query1,-1)==",")
        {
            $query1.="'".$$SAL_DATA_NUMBER."')";
        }
        else
        {
            $query1.=",'".$$SAL_DATA_NUMBER."')";
        }
    }
    else
    {
        $query1="update SAL_DATA set ";
        $STYLE_ARRAY=explode(",",$STYLE);
        $ARRAY_COUNT=sizeof($STYLE_ARRAY);
        if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
        for($I=0;$I<$ARRAY_COUNT;$I++)
        {
            $STR="S".$STYLE_ARRAY[$I];
            $STR_VALUE=$USER_ID."_".$STYLE_ARRAY[$I];
            if($$STR_VALUE=="")
                $$STR_VALUE="0";
            $query1.=$STR."=".$$STR_VALUE;
//      $query1.= ",REPORT='".$$SAL_DATA_NUMBER."'";
            //if($I!=$ARRAY_COUNT-1)
            $query1.=",";
        }
        $query1.="IS_DEPT_INPUT='1',REPORT='".$$SAL_DATA_NUMBER."' where FLOW_ID='".$FLOW_ID."' and USER_ID='$USER_ID'";
    }
    exequery(TD::conn(),$query1);
}
$DEPT_NAME=GetDeptNameById($DEPT_ID);
Message(_("提示"),sprintf(_("部门 %s 员工工资数据已上报%s请继续选择其他部门"),$DEPT_NAME, "<br><br>"));
Button_Back();
?>
</body>
</html>
