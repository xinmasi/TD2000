<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
$CUR_DATE=date("Y-m-d",time());

//============================考核人员名称、部门、角色=======================================
$CUR_DATE=date("Y-m-d",time());
$query = "SELECT * from SCORE_DATE where FLOW_ID='$FLOW_ID' group by PARTICIPANT";

$cursor= exequery(TD::conn(),$query);
$VOTE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{

    $USER_ID[$VOTE_COUNT]=$ROW["PARTICIPANT"];
    $query1="SELECT `USER_ID` , `USER_NAME` , PRIV_NAME, DEPT_NAME FROM `USER` a LEFT OUTER JOIN DEPARTMENT b ON a.DEPT_ID = b.DEPT_ID LEFT OUTER JOIN USER_PRIV c ON a.USER_PRIV = c.USER_PRIV where a.USER_ID='$USER_ID[$VOTE_COUNT]'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $USER_NAME[$VOTE_COUNT]=$ROW["USER_NAME"];
    $USER_PRIV[$VOTE_COUNT]=$ROW["PRIV_NAME"];
    $USER_DEPT[$VOTE_COUNT]=$ROW["DEPT_NAME"];
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

for($I=0;$I<$ARRAY_COUNT;$I++)
{
    $query1 = "select SCORE from SCORE_DATE  where PARTICIPANT='$USER_ID[$I]' and FLOW_ID='$FLOW_ID'";
    $cursor1= exequery(TD::conn(),$query1);

    $COUNT=0;
    while($ROW=mysql_fetch_array($cursor1))
    {
        $SCORE=$ROW["SCORE"];

        $MY_SCORE[$I][$COUNT]=explode(",",$SCORE);
        $COUNT++;
    }
}

$USER_COUNT=sizeof($USER_ID);
$field_count=sizeof($MY_SCORE[0][0]);

for ($count=0;$count<$field_count;$count++)
{
    for($I=0;$I<$USER_COUNT;$I++)
    {
        $RECORD_COUNT= sizeof($MY_SCORE[$I]);

        for ($field=0;$field<$RECORD_COUNT;$field++)
        {
            $MY_SCORESAM[$I][$count]=$MY_SCORESAM[$I][$count]+$MY_SCORE[$I][$field][$count];
            //if($MY_SCORE[$I][$field][$count]<>0)
            $MY_SCORECOUNT[$I][$count]=$MY_SCORECOUNT[$I][$count]+1;
        }
    }

}
//--------------求取平均分----------
$ARRAY_COUNT=sizeof($USER_NAME);
for($I=0;$I<$ARRAY_COUNT;$I++)
{
    $ARRAY_COUNT1=sizeof($MY_SCORESAM[$I]);
    for($count=0;$count<$ARRAY_COUNT1-1;$count++)
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
for($I=0;$I<$ARRAY_COUNT;$I++)
{
    $TOTAL=0;
    $ARRAY_COUNT1=sizeof($MY_AVE[$I]);
    for($count=0;$count<$ARRAY_COUNT1;$count++)
    {
        $TOTAL=$TOTAL+$MY_AVE[$I][$count];
    }
    $USER_SAM[$I]=$TOTAL;

}
$ARRAY_STATIS=explode(",",$STATICS);
//$ARRAY_STATIS=array("100","90","89","80","79","70","69","60","59");
$ARRAY_COUNT=sizeof($ARRAY_STATIS);

$USER_SAM_COUNT=sizeof($USER_SAM);
$temp=0;
$a=0;
for ($i=0;$i<$ARRAY_COUNT;$i=$i+2)
{
    if(($i+1)<$ARRAY_COUNT)
    {
        $ARRAY_FIELD[$temp]=$ARRAY_STATIS[$i]."-".$ARRAY_STATIS[$i+1];
        $FIELD_VALUE=0;
        for($count=0;$count<$USER_SAM_COUNT;$count++)
        {
            if($USER_SAM[$count]<=$ARRAY_STATIS[$i] and $USER_SAM[$count]>=$ARRAY_STATIS[$i+1])
            {
                $FIELD_VALUE++;
                $USER_COUNTNAME[$temp][$a]=$USER_ID[$count];
                $a=$a+1;}
        }
        $graphValues[$temp]=$FIELD_VALUE;

    }
    else
    {$ARRAY_FIELD[$temp]=$ARRAY_STATIS[$i]."-"."0";
        $FIELD_VALUE=0;
        for($count=0;$count<$USER_SAM_COUNT;$count++)
        {
            if($USER_SAM[$count]<=$ARRAY_STATIS[$i] and $USER_SAM[$count]>=0)
            {
                $FIELD_VALUE++;
                $USER_COUNTNAME[$temp][$a]=$USER_ID[$count];
                $a=$a+1;}
        }
        $graphValues[$temp]=$FIELD_VALUE;

    }

    $temp++;
    $a=0;
}

//$graphValues=array("233","433","301","169","87");
//----------MAXgraphValues-------
$ARRAY_COUNT=sizeof($graphValues);
$MAX_Values=$graphValues[0];
for($I=1;$I<$ARRAY_COUNT;$I++)
{
    if ($graphValues[$I]>$MAX_Values)$MAX_Values=$graphValues[$I];
}

$TOTAL=ceil($MAX_Values/20)*20;
if ($TOTAL==0)$TOTAL=20;
$PERT=$TOTAL/20;
$field1[0]=$TOTAL;
$temp=1;
while($TOTAL>0)
{
    $TOTAL=$TOTAL-$PERT;
    $field1[$temp]=$TOTAL;

    $temp=$temp+1;
}
ob_end_clean();
//------------绘图---------------
$TOTAL=20;
$imgWidth=(sizeof($ARRAY_FIELD)*50+20)+2;
$imgHeight=(25*20+20)+5;
$fieldcount=sizeof($ARRAY_FIELD);
// Define .PNG image
header("Content-type: image/png");
$aveWidth=50; //11*40+20;
$aveHeight=25;   //25*20+20;
// Create image and define colors
$image=imagecreate($imgWidth, $imgHeight);
$colorWhite=imagecolorallocate($image, 255, 255, 255);
$colorGrey=imagecolorallocate($image, 192, 192, 192);
$colorDarkBlue=imagecolorallocate($image, 104, 157, 228);
$colorLightBlue=imagecolorallocate($image, 184, 212, 250);
// Create border around image
imageline($image, 20, 0, 20, $aveHeight*$TOTAL, $colorGrey);
imageline($image, 20, 0, $fieldcount*$aveWidth+20, 0, $colorGrey);
imageline($image, $fieldcount*$aveWidth+20, 0, $fieldcount*$aveWidth+20, $aveHeight*$TOTAL, $colorGrey);
imageline($image, 20, $aveHeight*$TOTAL, $fieldcount*$aveWidth+20, $aveHeight*$TOTAL, $colorGrey);
// Create grid
for ($i=1; $i<sizeof($ARRAY_FIELD); $i++){
    imageline($image, $i*$aveWidth+20, 0, $i*$aveWidth+20, $aveHeight*$TOTAL, $colorGrey); //竖线

}
for ($i=1;$i<20;$i++){
    imageline($image, 20, $i*$aveHeight, $fieldcount*$aveWidth+20, $i*$aveHeight, $colorGrey); //横线
    imagestring($image,3, 0, ($i-1)*$aveHeight,$field1[$i-1], $colorDarkBlue); //输出内容
}
imagestring($image,3, 0, ($i-1)*$aveHeight,$field1[19], $colorDarkBlue);

// Create bar charts
//imagefilledrectangle($image, 0, 450, 40, 500, $colorDarkBlue);

for ($i=0; $i<10; $i++){

    imagefilledrectangle($image, $i*$aveWidth+20, ($aveHeight*$TOTAL-($graphValues[$i]/$PERT)*$aveHeight), ($i+1)*$aveWidth+20, $aveHeight*$TOTAL, $colorDarkBlue);
    imagefilledrectangle($image, ($i*$aveWidth)+1+20, ($aveHeight*$TOTAL-($graphValues[$i]/$PERT)*$aveHeight)+1, (($i+1)*$aveWidth)-5+20, $aveHeight*$TOTAL-2, $colorLightBlue);

    imagestring($image,3, $i*$aveWidth+15+20, ($aveHeight*$TOTAL-($graphValues[$i]/$PERT)*$aveHeight)-15, $graphValues[$i], $colorDarkBlue); //输出内容
    imagestring($image,3, $i*$aveWidth+20, $imgHeight-15,$ARRAY_FIELD[$i], $colorDarkBlue); //输出内容
}
// Output graph and clear image from memory
imagepng($image);
imagedestroy($image);


?>

