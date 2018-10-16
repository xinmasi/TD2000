<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("积分排行榜行榜");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'hr';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
    $rank_info = array();
    $query="SELECT hr_integral_oa.USER_ID,SUM(hr_integral_oa.SUM) as SUM1 from hr_integral_oa,USER where hr_integral_oa.USER_ID=USER.USER_ID and USER.DEPT_ID != '0' GROUP BY hr_integral_oa.USER_ID";
    $cursor=exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $USRE_ID_RANK=$ROW["USER_ID"];
        $SUM1=$ROW["SUM1"];
        if($USRE_ID_RANK !="")
        {
            $rank_info[$USRE_ID_RANK]=$SUM1;
        }    
    }
    $query="SELECT hr_integral_data.USER_ID,SUM(hr_integral_data.INTEGRAL_DATA) as SUM2 from hr_integral_data,USER where hr_integral_data.USER_ID=USER.USER_ID and USER.DEPT_ID != '0' GROUP BY hr_integral_data.USER_ID";
    $cursor=exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $USRE_ID_RANK=$ROW["USER_ID"];
        $SUM2=$ROW["SUM2"];
        if($USRE_ID_RANK !="")
        {
            if(array_key_exists($USRE_ID_RANK,$rank_info))
            {
                $rank_info[$USRE_ID_RANK]=$SUM2;
            }
            else
            {
                 $rank_info[$USRE_ID_RANK]+=$SUM2;
            }
        }    
    }   
//	$query = "SELECT USER_ID,SUM(SUM1) AS SUMALL
//		from ((SELECT hr_integral_oa.USER_ID,SUM(hr_integral_oa.SUM) as SUM1 from hr_integral_oa,USER where hr_integral_oa.USER_ID=USER.USER_ID and USER.DEPT_ID != '0' GROUP BY hr_integral_oa.USER_ID )
//		UNION
//		(SELECT hr_integral_data.USER_ID,SUM(hr_integral_data.INTEGRAL_DATA) as SUM1 from hr_integral_data,USER where hr_integral_data.USER_ID=USER.USER_ID and USER.DEPT_ID != '0'  GROUP BY hr_integral_data.USER_ID)) AS INTEGRAL_TEM
//		GROUP BY INTEGRAL_TEM.USER_ID ORDER BY SUMALL DESC limit 0,10";
//	$cursor= exequery(TD::conn(),$query);
 	 arsort($rank_info);
        $USER_COUNT=0;
        if(!empty($rank_info))
        {
            	 foreach($rank_info as $rank_key=>$rank_value)
                {
                       $USER_COUNT++;
                       $USER_ID=$rank_key;
                       $SUMALL=round($rank_value,2);

                       $query_user="select USER_NAME,DEPT_ID,USER_PRIV,SEX from USER where USER_ID='$USER_ID'";
                       $cursor_user=exequery(TD::conn(),$query_user);
                       if($ROW_USER=mysql_fetch_array($cursor_user))
                       {
                               $USER_NAME=$ROW_USER["USER_NAME"];
                               $DEPT_ID1=$ROW_USER["DEPT_ID"];
                               $USER_PRIV=$ROW_USER["USER_PRIV"];
                               $SEX=$ROW_USER["SEX"];
                       }

                   if($DEPT_ID1 != '0' && $DEPT_ID1 !="")
                   {
                       $query1 = "SELECT * from DEPARTMENT where DEPT_ID='".$DEPT_ID1."'";
                           $cursor1= exequery(TD::conn(),$query1);
                           if($ROW=mysql_fetch_array($cursor1))
                             $DEPT_NAME=$ROW["DEPT_NAME"];
                   }

                   $DEPT_LONG_NAME=dept_long_name($DEPT_ID1);
                   $MODULE_BODY.='<li><span style="width:25%;height:20px;font-size:12px;display:block;float:left">'.$USER_NAME.'</span><span style="width:25%;height:20px;font-size:12px;display:block;float:left">'.$SUMALL.'</span>';
                   if($USER_COUNT==1)
                               $MODULE_BODY.='<span style="font-size:12px;display:block;float:left;color:red">'._("第").$USER_COUNT._("名").'</span></li>';

                   else if($USER_COUNT==2)
                               $MODULE_BODY.='<span style="font-size:12px;display:block;float:left;color:blue">'._("第").$USER_COUNT._("名").'</span></li>';

                   else if($USER_COUNT==3)
                               $MODULE_BODY.='<span style="font-size:12px;display:block;float:left;color:green">'._("第").$USER_COUNT._("名").'</span></li>';
                       else
                               $MODULE_BODY.='<span style="font-size:12px;display:block;float:left">'._("第").$USER_COUNT._("名").'</span></li>';

               }
        }
        else
        {
            $MODULE_BODY.="<li>"._("无积分信息")."</li>";
        }
	 
}
$MODULE_BODY='<ul>'.$MODULE_BODY.'</ul>';
?>
