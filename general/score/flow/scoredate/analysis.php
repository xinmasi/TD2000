<?php
include_once("inc/auth.inc.php");
include("inc/FusionCharts/FusionCharts.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
if($TO_ID!="ALL_DEPT")  //未选中全体部门
{
    if ($TO_ID!="")  //选择若干部门
    {
        $DEPT_ID=$TO_ID;
        if (substr($DEPT_ID,-1)==",")
            $DEPT_ID=substr($DEPT_ID,0,-1);
    }
    else  //未选择部门
    {
        $DEPT_ID="";
        $condition.=" and b.DEPT_ID !='0'";
    }
    if($DEPT_ID!="")
    {
        $DEPT_ID="(".$DEPT_ID.")";
        $condition.=" and b.DEPT_ID in $DEPT_ID";
    }
}
else  //选中全体部门
{
    $condition.=" and b.DEPT_ID !='0'";
}
if($CHOSE==1)
{

    $HTML_PAGE_TITLE = _("简单柱状图测试");
    include_once("inc/header.inc.php");
    ?>
    <script language="Javascript" src="<?=MYOA_JS_SERVER?>/inc/FusionCharts/FusionCharts.js"></script>

    <body class="bodycolor">
    <center>
        <?
        //分数
        if($SUMFIELD==1)
        {
            //取得考核任务中的用户的信息
            $query = "SELECT * from SCORE_DATE where FLOW_ID='$FLOW_ID' group by PARTICIPANT";
            $cursor= exequery(TD::conn(),$query);
            $VOTE_COUNT=0;
            while($ROW=mysql_fetch_array($cursor))
            {

                $USER_ID[$VOTE_COUNT]=$ROW["PARTICIPANT"];
                $query1="SELECT `USER_ID` , `USER_NAME` , PRIV_NAME, DEPT_NAME FROM `USER` a LEFT OUTER JOIN DEPARTMENT b ON a.DEPT_ID = b.DEPT_ID LEFT OUTER JOIN USER_PRIV c ON a.USER_PRIV = c.USER_PRIV where a.USER_ID='$USER_ID[$VOTE_COUNT]'".$condition;
                $cursor1= exequery(TD::conn(),$query1);
                if($ROW=mysql_fetch_array($cursor1))
                {
                    $USER_NAME[$VOTE_COUNT]=$ROW["USER_NAME"];
                    $USER_PRIV[$VOTE_COUNT]=$ROW["PRIV_NAME"];
                    $USER_DEPT[$VOTE_COUNT]=$ROW["DEPT_NAME"];
                }
                $VOTE_COUNT++;

            }
            //============================考核项目========================================
            $query = "SELECT * from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
            $cursor= exequery(TD::conn(),$query);
            $VOTE_COUNT=0;
            $TOTAL_SAM=0;
            while($ROW=mysql_fetch_array($cursor))
            {
                $ITEM_NAME[$VOTE_COUNT]=$ROW["ITEM_NAME"];
                $TOTAL_SAM=$TOTAL_SAM+$ROW["MAX"];
                $VOTE_COUNT++;
            }

            //===========================考核分数==================================
            $ARRAY_COUNT=sizeof($USER_ID);

            for($I=0;$I< $ARRAY_COUNT;$I++)
            {
                $query1 = "select SCORE from SCORE_DATE  where PARTICIPANT='$USER_ID[$I]' and FLOW_ID='$FLOW_ID'";
                $cursor1= exequery(TD::conn(),$query1);

                $COUNT=0;
                while($ROW=mysql_fetch_array($cursor1))
                {
                    $SCORE=$ROW["SCORE"];

                    $MY_SCORE[$I][$COUNT]=explode(",",$SCORE); //$MY_SCORE[$I][$COUNT]记录某一个人在某个考核任务中的得分
                    $COUNT++;
                }
            }

            $USER_COUNT=sizeof($USER_ID);
            $field_count=sizeof($MY_SCORE[0][0]);

            for ($count=0;$count< $field_count;$count++)
            {
                for($I=0;$I< $USER_COUNT;$I++)
                {
                    $RECORD_COUNT= sizeof($MY_SCORE[$I]);

                    for ($field=0;$field< $RECORD_COUNT;$field++)
                    {
                        $MY_SCORESAM[$I][$count]=$MY_SCORESAM[$I][$count]+$MY_SCORE[$I][$field][$count];
                        //if ($MY_SCORE[$I][$field][$count]<>0)
                            $MY_SCORECOUNT[$I][$count]=$MY_SCORECOUNT[$I][$count]+1;
                    }
                }

            }

            //--------------求取平均分----------
            $ARRAY_COUNT=sizeof($USER_NAME);
            for($I=0;$I< $ARRAY_COUNT;$I++)
            {
                $ARRAY_COUNT1=sizeof($MY_SCORESAM[$I]);
                for($count=0;$count< $ARRAY_COUNT1-1;$count++)
                {

                    if($MY_SCORECOUNT[$I][$count]=="")
                    {
                        $MY_AVE[$I][$count]=0;
                    }
                    else
                    {
                        $MY_AVE[$I][$count]=round($MY_SCORESAM[$I][$count]/$MY_SCORECOUNT[$I][$count],2);
                    }
                }

            }

            //-------------求取总分----------------
            $ARRAY_COUNT=sizeof($USER_NAME);
            for($I=0;$I< $ARRAY_COUNT;$I++)
            {
                $TOTAL=0;
                $ARRAY_COUNT1=sizeof($MY_AVE[$I]);
                for($count=0;$count< $ARRAY_COUNT1;$count++)
                {
                    $TOTAL=$TOTAL+$MY_AVE[$I][$count];
                }
                $USER_SAM[$I]=$TOTAL;

            }
            $ARRAY_STATIS=explode(",",$SCORE_RANGE);

            $ARRAY_COUNT=sizeof($ARRAY_STATIS);

            $USER_SAM_COUNT=sizeof($USER_SAM);
            $j=0;
            for ($i=0;$i< $ARRAY_COUNT;$i=$i+2)
            {
                if (($i+1)< $ARRAY_COUNT)
                {
                    $ARRAY_FIELD[$temp]=$ARRAY_STATIS[$i]."-".$ARRAY_STATIS[$i+1];
                    $arrData[$i][1]= $ARRAY_FIELD[$temp];
                    $FIELD_VALUE=0;
                    $USER_COUNTID=array();
                    for($count=0;$count< $USER_SAM_COUNT;$count++)
                    {
                        if($USER_SAM[$count]>=$ARRAY_STATIS[$i] and $USER_SAM[$count]<=$ARRAY_STATIS[$i+1])
                        {
                            $FIELD_VALUE++;
                            $USER_COUNTID[count($USER_COUNTID)]=$USER_ID[$count];
                        }
                    }
                    $STAFF_ID_STR=implode(",",$USER_COUNTID);
                    $arrData[$i][2]=$FIELD_VALUE;
                    $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;

                }
                else
                {
                    $ARRAY_FIELD[$temp]=$ARRAY_STATIS[$i]."-"."0";
                    $FIELD_VALUE=0;
                    $arrData[$i][1]= $ARRAY_FIELD[$temp];
                    for($count=0;$count< $USER_SAM_COUNT;$count++)
                    {
                        if($USER_SAM[$count]<=$ARRAY_STATIS[$i] and $USER_SAM[$count]>=0)
                        {
                            $FIELD_VALUE++;
                            $USER_COUNTID[count($USER_COUNTID)]=$USER_ID[$count];
                        }
                    }
                    $arrData[$i][2]=$FIELD_VALUE;
                    $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;

                }
                $FIELD_VALUE_COUNT=0;
                $USER_COUNTID_TEMP=array();
                for($count=0;$count< $USER_SAM_COUNT;$count++)
                {
                    if($USER_SAM[$count]< $ARRAY_STATIS[0] and $USER_SAM[$count] > $ARRAY_STATIS[$USER_SAM_COUNT-1])
                    {
                        $arrData[$i][1]= _("其他");
                        $FIELD_VALUE_COUNT++;
                        $USER_COUNTID_TEMP[count($USER_COUNTID_TEMP)]=$USER_ID[$count];
                    }
                }
                if($FIELD_VALUE_COUNT!=0)
                {
                    $arrData[$i][2]=$FIELD_VALUE_COUNT;
                    $STAFF_ID_STR_TEMP=implode(",",$USER_COUNTID_TEMP);
                    $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR_TEMP;
                }
            }
            $strXML = "<chart caption='"._("按分数统计（人）")."' formatNumberScale='0'>";
        }

        if(empty($arrData))
            Message("",_("无记录"));
        else
        {
            foreach ($arrData as $arSubData)
                $strXML .= "<set label='" . $arSubData[1] . "' value='" . $arSubData[2] . "' />";
            $strXML .= "</chart>";
            echo renderChart(MYOA_JS_SERVER."/inc/FusionCharts/Pie3D.swf", "", $strXML, "FactorySum", "600", "300", false, false);
        }
        ?>
    </center>
    </body>
    </html>
    <?
}
if($CHOSE==2){

    $HTML_PAGE_TITLE = _("简单柱状图测试");
    include_once("inc/header.inc.php");
    ?>

    <script language="jAVASCRIPT" src="<?=MYOA_JS_SERVER?>/inc/FusionCharts/FusionCharts.js"></script>

    <body class="bodycolor">
    <?//分数
    if($SUMFIELD==1)
    {

        //取得考核任务中的用户的信息
        $query = "SELECT * from SCORE_DATE where FLOW_ID='$FLOW_ID' group by PARTICIPANT";
        $cursor= exequery(TD::conn(),$query);
        $VOTE_COUNT=0;
        while($ROW=mysql_fetch_array($cursor))
        {

            $USER_ID[$VOTE_COUNT]=$ROW["PARTICIPANT"];
            $query1="SELECT `USER_ID` , `USER_NAME` , PRIV_NAME, DEPT_NAME FROM `USER` a LEFT OUTER JOIN DEPARTMENT b ON a.DEPT_ID = b.DEPT_ID LEFT OUTER JOIN USER_PRIV c ON a.USER_PRIV = c.USER_PRIV where a.USER_ID='$USER_ID[$VOTE_COUNT]'".$condition;
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
            {
                $USER_NAME[$VOTE_COUNT]=$ROW["USER_NAME"];
                $USER_PRIV[$VOTE_COUNT]=$ROW["PRIV_NAME"];
                $USER_DEPT[$VOTE_COUNT]=$ROW["DEPT_NAME"];
            }
            $VOTE_COUNT++;

        }
        //============================考核项目========================================
        $query = "SELECT * from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
        $cursor= exequery(TD::conn(),$query);
        $VOTE_COUNT=0;
        $TOTAL_SAM=0;
        while($ROW=mysql_fetch_array($cursor))
        {
            $ITEM_NAME[$VOTE_COUNT]=$ROW["ITEM_NAME"];
            $TOTAL_SAM=$TOTAL_SAM+$ROW["MAX"];
            $VOTE_COUNT++;
        }

        //===========================考核分数==================================
        $ARRAY_COUNT=sizeof($USER_ID);

        for($I=0;$I< $ARRAY_COUNT;$I++)
        {
            $query1 = "select SCORE from SCORE_DATE  where PARTICIPANT='$USER_ID[$I]' and FLOW_ID='$FLOW_ID'";
            $cursor1= exequery(TD::conn(),$query1);

            $COUNT=0;
            while($ROW=mysql_fetch_array($cursor1))
            {
                $SCORE=$ROW["SCORE"];

                $MY_SCORE[$I][$COUNT]=explode(",",$SCORE); //$MY_SCORE[$I][$COUNT]记录某一个人在某个考核任务中的得分
                $COUNT++;
            }
        }

        $USER_COUNT=sizeof($USER_ID);
        $field_count=sizeof($MY_SCORE[0][0]);

        for ($count=0;$count< $field_count;$count++)
        {
            for($I=0;$I< $USER_COUNT;$I++)
            {
                $RECORD_COUNT= sizeof($MY_SCORE[$I]);

                for ($field=0;$field< $RECORD_COUNT;$field++)
                {
                    $MY_SCORESAM[$I][$count]=$MY_SCORESAM[$I][$count]+$MY_SCORE[$I][$field][$count];
                    //if ($MY_SCORE[$I][$field][$count]<>0)
                        $MY_SCORECOUNT[$I][$count]=$MY_SCORECOUNT[$I][$count]+1;
                }
            }

        }

        //--------------求取平均分----------
        $ARRAY_COUNT=sizeof($USER_NAME);
        for($I=0;$I< $ARRAY_COUNT;$I++)
        {
            $ARRAY_COUNT1=sizeof($MY_SCORESAM[$I]);
            for($count=0;$count< $ARRAY_COUNT1-1;$count++)
            {

                if($MY_SCORECOUNT[$I][$count]=="")
                {
                    $MY_AVE[$I][$count]=0;
                }
                else
                {
                    $MY_AVE[$I][$count]=round($MY_SCORESAM[$I][$count]/$MY_SCORECOUNT[$I][$count],2);
                }
            }

        }

        //-------------求取总分----------------
        $ARRAY_COUNT=sizeof($USER_NAME);
        for($I=0;$I< $ARRAY_COUNT;$I++)
        {
            $TOTAL=0;
            $ARRAY_COUNT1=sizeof($MY_AVE[$I]);
            for($count=0;$count< $ARRAY_COUNT1;$count++)
            {
                $TOTAL=$TOTAL+$MY_AVE[$I][$count];
            }
            $USER_SAM[$I]=$TOTAL;

        }
        $ARRAY_STATIS=explode(",",$SCORE_RANGE);

        $ARRAY_COUNT=sizeof($ARRAY_STATIS);

        $USER_SAM_COUNT=sizeof($USER_SAM);
        $j=0;
        for ($i=0;$i< $ARRAY_COUNT;$i=$i+2)
        {
            if (($i+1)< $ARRAY_COUNT)
            {
                $ARRAY_FIELD[$temp]=$ARRAY_STATIS[$i]."-".$ARRAY_STATIS[$i+1];
                $arrData[$i][1]= $ARRAY_FIELD[$temp];
                $FIELD_VALUE=0;
                $USER_COUNTID=array();
                for($count=0;$count< $USER_SAM_COUNT;$count++)
                {
                    if($USER_SAM[$count]>=$ARRAY_STATIS[$i] and $USER_SAM[$count]<=$ARRAY_STATIS[$i+1])
                    {
                        $FIELD_VALUE++;
                        $USER_COUNTID[count($USER_COUNTID)]=$USER_ID[$count];
                    }
                }
                $STAFF_ID_STR=implode(",",$USER_COUNTID);
                $arrData[$i][2]=$FIELD_VALUE;
                $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;

            }
            else
            {
                $ARRAY_FIELD[$temp]=$ARRAY_STATIS[$i]."-"."0";
                $FIELD_VALUE=0;
                $arrData[$i][1]= $ARRAY_FIELD[$temp];
                for($count=0;$count< $USER_SAM_COUNT;$count++)
                {
                    if($USER_SAM[$count]<=$ARRAY_STATIS[$i] and $USER_SAM[$count]>=0)
                    {
                        $FIELD_VALUE++;
                        $USER_COUNTID[count($USER_COUNTID)]=$USER_ID[$count];
                    }
                }
                $arrData[$i][2]=$FIELD_VALUE;
                $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;

            }
            $FIELD_VALUE_COUNT=0;
            $USER_COUNTID_TEMP=array();
            for($count=0;$count< $USER_SAM_COUNT;$count++)
            {
                if($USER_SAM[$count]< $ARRAY_STATIS[0] and $USER_SAM[$count] > $ARRAY_STATIS[$USER_SAM_COUNT-1])
                {
                    $arrData[$i][1]= _("其他");
                    $FIELD_VALUE_COUNT++;
                    $USER_COUNTID_TEMP[count($USER_COUNTID_TEMP)]=$USER_ID[$count];
                }
            }
            if($FIELD_VALUE_COUNT!=0)
            {
                $arrData[$i][2]=$FIELD_VALUE_COUNT;
                $STAFF_ID_STR_TEMP=implode(",",$USER_COUNTID_TEMP);
                $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR_TEMP;
            }
        }
        $strXML = "<chart caption='"._("按年龄统计（人）")."' formatNumberScale='0'>";
    }

    if(empty($arrData))
        Message("",_("无记录"));
    else
    {
        foreach ($arrData as $arSubData)
            $strXML .= "<set label='" . $arSubData[1] . "' value='" . $arSubData[2] . "' />";
        $strXML .= "</chart>";

        echo renderChart(MYOA_JS_SERVER."/inc/FusionCharts/Column3D.swf", "", "$strXML", "myFirst", "600", "300", false, false);
    }
    ?>
    </BODY>
    </HTML>
    <?
}
if($CHOSE==3)
{

    $HTML_PAGE_TITLE = _("统计表");
    include_once("inc/header.inc.php");
    ?>
    <body class="bodycolor">
    <?

    //按分数统计，组织sql语句
    if($SUMFIELD==1)
    {
        //取得考核任务中的用户的信息
        $query = "SELECT * from SCORE_DATE where FLOW_ID='$FLOW_ID' group by PARTICIPANT";
        $cursor= exequery(TD::conn(),$query);
        $VOTE_COUNT=0;
        while($ROW=mysql_fetch_array($cursor))
        {

            $USER_ID[$VOTE_COUNT]=$ROW["PARTICIPANT"];
            $query1="SELECT `USER_ID` , `USER_NAME` , PRIV_NAME, DEPT_NAME FROM `USER` a LEFT OUTER JOIN DEPARTMENT b ON a.DEPT_ID = b.DEPT_ID LEFT OUTER JOIN USER_PRIV c ON a.USER_PRIV = c.USER_PRIV where a.USER_ID='$USER_ID[$VOTE_COUNT]'".$condition;
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
            {
                $USER_NAME[$VOTE_COUNT]=$ROW["USER_NAME"];
                $USER_PRIV[$VOTE_COUNT]=$ROW["PRIV_NAME"];
                $USER_DEPT[$VOTE_COUNT]=$ROW["DEPT_NAME"];
            }
            $VOTE_COUNT++;

        }
        //============================考核项目========================================
        $query = "SELECT * from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
        $cursor= exequery(TD::conn(),$query);
        $VOTE_COUNT=0;
        $TOTAL_SAM=0;
        while($ROW=mysql_fetch_array($cursor))
        {
            $ITEM_NAME[$VOTE_COUNT]=$ROW["ITEM_NAME"];
            $TOTAL_SAM=$TOTAL_SAM+$ROW["MAX"];
            $VOTE_COUNT++;
        }

        //===========================考核分数==================================
        $ARRAY_COUNT=sizeof($USER_ID);

        for($I=0;$I< $ARRAY_COUNT;$I++)
        {
            $query1 = "select SCORE from SCORE_DATE  where PARTICIPANT='$USER_ID[$I]' and FLOW_ID='$FLOW_ID'";
            $cursor1= exequery(TD::conn(),$query1);

            $COUNT=0;
            while($ROW=mysql_fetch_array($cursor1))
            {
                $SCORE=$ROW["SCORE"];

                $MY_SCORE[$I][$COUNT]=explode(",",$SCORE); //$MY_SCORE[$I][$COUNT]记录某一个人在某个考核任务中的得分
                $COUNT++;
            }
        }

        $USER_COUNT=sizeof($USER_ID);
        $field_count=sizeof($MY_SCORE[0][0]);

        for ($count=0;$count< $field_count;$count++)
        {
            for($I=0;$I< $USER_COUNT;$I++)
            {
                $RECORD_COUNT= sizeof($MY_SCORE[$I]);

                for ($field=0;$field< $RECORD_COUNT;$field++)
                {
                    $MY_SCORESAM[$I][$count]=$MY_SCORESAM[$I][$count]+$MY_SCORE[$I][$field][$count];
                    //if ($MY_SCORE[$I][$field][$count]<>0)
                        $MY_SCORECOUNT[$I][$count]=$MY_SCORECOUNT[$I][$count]+1;
                }
            }

        }

        //--------------求取平均分----------
        $ARRAY_COUNT=sizeof($USER_NAME);
        for($I=0;$I< $ARRAY_COUNT;$I++)
        {
            $ARRAY_COUNT1=sizeof($MY_SCORESAM[$I]);
            for($count=0;$count< $ARRAY_COUNT1-1;$count++)
            {

                if($MY_SCORECOUNT[$I][$count]=="")
                {
                    $MY_AVE[$I][$count]=0;
                }
                else
                {
                    $MY_AVE[$I][$count]=round($MY_SCORESAM[$I][$count]/$MY_SCORECOUNT[$I][$count],2);
                }
            }

        }

        //-------------求取总分----------------
        $ARRAY_COUNT=sizeof($USER_NAME);
        for($I=0;$I< $ARRAY_COUNT;$I++)
        {
            $TOTAL=0;
            $ARRAY_COUNT1=sizeof($MY_AVE[$I]);
            for($count=0;$count< $ARRAY_COUNT1;$count++)
            {
                $TOTAL=$TOTAL+$MY_AVE[$I][$count];
            }
            $USER_SAM[$I]=$TOTAL;

        }
        $ARRAY_STATIS=explode(",",$SCORE_RANGE);

        $ARRAY_COUNT=sizeof($ARRAY_STATIS);

        $USER_SAM_COUNT=sizeof($USER_SAM);
        $j=0;
        for ($i=0;$i< $ARRAY_COUNT;$i=$i+2)
        {
            if (($i+1)< $ARRAY_COUNT)
            {
                $ARRAY_FIELD[$temp]=$ARRAY_STATIS[$i]."-".$ARRAY_STATIS[$i+1];
                $arrData[$i][1]= $ARRAY_FIELD[$temp];
                $FIELD_VALUE=0;
                $USER_COUNTID=array();
                for($count=0;$count< $USER_SAM_COUNT;$count++)
                {
                    if($USER_SAM[$count]>=$ARRAY_STATIS[$i] and $USER_SAM[$count]<=$ARRAY_STATIS[$i+1])
                    {
                        $FIELD_VALUE++;
                        $USER_COUNTID[count($USER_COUNTID)]=$USER_ID[$count];
                    }
                }
                $STAFF_ID_STR=implode(",",$USER_COUNTID);
                $arrData[$i][2]=$FIELD_VALUE;
                $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR."&GROUP_ID=$GROUP_ID&FLOW_ID=$FLOW_ID";

            }
            else
            {
                $ARRAY_FIELD[$temp]=$ARRAY_STATIS[$i]."-"."0";
                $FIELD_VALUE=0;
                $arrData[$i][1]= $ARRAY_FIELD[$temp];
                for($count=0;$count< $USER_SAM_COUNT;$count++)
                {
                    if($USER_SAM[$count]<=$ARRAY_STATIS[$i] and $USER_SAM[$count]>=0)
                    {
                        $FIELD_VALUE++;
                        $USER_COUNTID[count($USER_COUNTID)]=$USER_ID[$count];
                    }
                }
                $arrData[$i][2]=$FIELD_VALUE;
                $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR;

            }
            $FIELD_VALUE_COUNT=0;
            $USER_COUNTID_TEMP=array();
            for($count=0;$count< $USER_SAM_COUNT;$count++)
            {
                if($USER_SAM[$count]< $ARRAY_STATIS[0] and $USER_SAM[$count] > $ARRAY_STATIS[$USER_SAM_COUNT-1])
                {
                    $arrData[$i][1]= _("其他");
                    $FIELD_VALUE_COUNT++;
                    $USER_COUNTID_TEMP[count($USER_COUNTID_TEMP)]=$USER_ID[$count];
                }
            }
            if($FIELD_VALUE_COUNT!=0)
            {
                $arrData[$i][2]=$FIELD_VALUE_COUNT;
                $STAFF_ID_STR_TEMP=implode(",",$USER_COUNTID_TEMP);
                $arrData[$i][3]="detail.php?STAFF_ID_STR=".$STAFF_ID_STR_TEMP."?GROUP_ID=$GROUP_ID&FLOW_ID=$FLOW_ID";
            }
        }
        $strXML = _("按分数统计（人）");
    }

    if(empty($arrData))
    Message("",_("无记录"));
    else
    {
    echo "<br><center><b>".$strXML."</b></center><br>";
    ?>
    <TABLE align="center" width="600" class="TableBlock">
        <?
        foreach ($arrData as $arSubData)
        {
            ?>
            <tr>
                <td class='TableContent' width='300'><?=$arSubData[1]?></td>
                <td class='TableData' align='center'>
                    <a href="javascript:;" onClick="window.open('<?=$arSubData[3]?>','','height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes');">
                        <?=$arSubData[2]?></a>
                </td>
            </tr>
            <?
        }
        }
        ?>
    </TABLE>
    </BODY>
    </HTML>
<?}?>