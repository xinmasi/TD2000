<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");


$HTML_PAGE_TITLE = _("考核数据");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" WIDTH="18" HEIGHT="18" align="absmiddle"><span class="big3"> <?=_("考核查询结果")?></span>
    </td>
  </tr>
</table>
<?
 $CUR_DATE=date("Y-m-d",time());
 $COUNT_RESULT=1;
 //============================被考核人员名称、部门、角色=======================================
 $CUR_DATE=date("Y-m-d",time());
$query8 = "SELECT * from SCORE_FLOW  where  find_in_set('$USER_ID',PARTICIPANT) and BEGIN_DATE >= '$DATE1' and END_DATE <='$DATE2' order by END_DATE";
$cursor8= exequery(TD::conn(),$query8);
while($ROW8=mysql_fetch_array($cursor8))
{
    $FLOW_ID=$ROW8["FLOW_ID"];	
    $GROUP_ID=$ROW8["GROUP_ID"];
    $ANONYMITY=$ROW8["ANONYMITY"];   
       	 
    $query7 = "SELECT PARTICIPANT from SCORE_DATE where FLOW_ID='$FLOW_ID' and PARTICIPANT='$USER_ID' group by PARTICIPANT";
    $cursor7= exequery(TD::conn(),$query7);
    $VOTE_COUNT=0;
    while($ROW7=mysql_fetch_array($cursor7))
    {
        $USER_ID_P[$VOTE_COUNT] = $ROW7["PARTICIPANT"];
        $query6="SELECT `USER_ID` , `USER_NAME` , PRIV_NAME, DEPT_NAME FROM `USER` a LEFT OUTER JOIN DEPARTMENT b ON a.DEPT_ID = b.DEPT_ID LEFT OUTER JOIN USER_PRIV c ON a.USER_PRIV = c.USER_PRIV where a.USER_ID='$USER_ID_P[$VOTE_COUNT]'";
        $cursor6= exequery(TD::conn(),$query6);
        if($ROW6=mysql_fetch_array($cursor6)) 
        {       
              $USER_NAME[$VOTE_COUNT]=$ROW6["USER_NAME"];
              $USER_PRIV[$VOTE_COUNT]=$ROW6["PRIV_NAME"];
              $USER_DEPT[$VOTE_COUNT]=$ROW6["DEPT_NAME"];
        }
        $VOTE_COUNT++;   
        $VOTE_COUNT;
    }
    if($VOTE_COUNT==0)
    {  
    	 $COUNT_RESULT=0;
       continue;
    }
   //============================考核项目========================================
    $query = "SELECT * from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
    $cursor= exequery(TD::conn(),$query);
    $VOTE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $ITEM_NAME[$VOTE_COUNT]=$ROW["ITEM_NAME"];
        $VOTE_COUNT++;
    }
   
   //===========================考核分数,评分人名称、部门、角色==================================
     $ARRAY_COUNT=sizeof($USER_ID);
   
     for($I=0;$I < $ARRAY_COUNT;$I++)
      {
      	 $query1 = "select MEMO,a.SCORE,USER_NAME,PRIV_NAME,DEPT_NAME FROM SCORE_DATE a LEFT OUTER JOIN USER b ON a.RANKMAN = b.USER_ID LEFT OUTER JOIN USER_PRIV c ON b.USER_PRIV = c.USER_PRIV LEFT OUTER JOIN DEPARTMENT d ON d.DEPT_ID = b.DEPT_ID where PARTICIPANT='$USER_ID_P[$I]' and FLOW_ID='$FLOW_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        //echo $USER_ID[$I]."<br>";
        $COUNT=0;
        while($ROW=mysql_fetch_array($cursor1))
        {
            $SCORE=$ROW["SCORE"];
            $MEMO=$ROW["MEMO"];
      
            //echo $SCORE."<br>";
      
            $MY_SCORE[$I][$COUNT]=explode(",",$SCORE);
            $MY_MEMO[$I][$COUNT]=explode(",",$MEMO);
            if($ANONYMITY=="0")
            {
             	 $RANK_NAME[$I][$COUNT]=$ROW["USER_NAME"];
               $RANK_PRIV[$I][$COUNT]=$ROW["PRIV_NAME"];
               $RANK_DEPT[$I][$COUNT]=$ROW["DEPT_NAME"];
            }
            else
            {
               $RANK_NAME[$I][$COUNT]="****";
               $RANK_PRIV[$I][$COUNT]="****";
               $RANK_DEPT[$I][$COUNT]="****";
            }
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
             if ($MY_SCORE[$I][$field][$count]<>0)
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
                   {$MY_AVE[$I][$count]=0;}
                else
                   {$MY_AVE[$I][$count]=round($MY_SCORESAM[$I][$count]/$MY_SCORECOUNT[$I][$count],2);}
               }
   
        }
   
   
   ?>
   
   <table width="100%" class="TableList">
   
      <thead class="TableHeader">
      	   <td nowrap align="center"><?=_("部门")?></td>
      	   <td nowrap align="center"><?=_("姓名")?></td>
      	   <td nowrap align="center"><?=_("角色")?></td>
   <?
            $ARRAY_COUNT=sizeof($ITEM_NAME);
             for($I=0;$I<$ARRAY_COUNT;$I++)
             {
   ?>
              <td nowrap align="center"><?=$ITEM_NAME[$I]?></td>
   
   <?
              }
   ?>
           <td nowrap align="center"><?=_("总计")?></td>
      </thead>
   <?
   
        $ARRAY_COUNT=sizeof($USER_NAME);
          for($I=0;$I<$ARRAY_COUNT;$I++)
             {$TOTAL=0;
   ?>
              <tr class="TableLine1"  style="cursor:hand" onClick="td_detail('<?=$FLOW_ID.$I?>');" title="<?=_("单击查看评分明细")?>">
              	<td align="center"><?=$USER_DEPT[$I]?></td>
              	<td align="center"><?=$USER_NAME[$I]?></td>
              	<td align="center"><?=$USER_PRIV[$I]?></td>
   <?
                  $ARRAY_COUNT1=sizeof($MY_AVE[$I]);
                  $colnumber=$ARRAY_COUNT1+4;
                  for($count=0;$count<$ARRAY_COUNT1;$count++)
                   {  $TOTAL=$TOTAL+$MY_AVE[$I][$count];
   ?>
               <td align="center"><?=$MY_AVE[$I][$count]?></td>
   
   <?
                    }
   ?>
                <td nowrap align="center"><?=$TOTAL?></td>
               </tr>
               <tr class="TableData" id=<?=$FLOW_ID.$I?> style="display:none" onDblClick="td_close('<?=$I?>');" title="<?=_("双击关闭子窗口")?>">
   
               <td align="left" colspan=<?=$colnumber?>>
               	<br>
               	<table border="0" width="60%" cellspacing="1" cellpadding="3" bgcolor="#000000" class="small">
   
                     <thead class="TableHeader">
      	                <td nowrap align="center"><?=_("部门")?></td>
      	                <td nowrap align="center"><?=_("评分人姓名")?></td>
      	                <td nowrap align="center"><?=_("角色")?></td>
   <?
                          $ARRAY_COUNT2=sizeof($ITEM_NAME);
                          for($I2=0;$I2<$ARRAY_COUNT2;$I2++)
                          {
   ?>
                            <td nowrap align="center"><?=$ITEM_NAME[$I2]?></td>
   
   <?
                           }
   ?>
   
                      </thead>
   <?
   
                            $SON_COUNT=sizeof($RANK_NAME[$I]);
                            for($SON_I=0;$SON_I<$SON_COUNT;$SON_I++)
                            {$SON_TOTAL=0;
   ?>
                             <tr class="TableData" >
              	              <td align="center"><?=$RANK_DEPT[$I][$SON_I]?></td>
                            	<td align="center"><?=$RANK_NAME[$I][$SON_I]?></td>
              	              <td align="center"><?=$RANK_PRIV[$I][$SON_I]?></td>
   <?
                             $SON_COUNT1=sizeof($MY_SCORE[$I][$SON_I]);
   
                             for($soncount=0;$soncount<$SON_COUNT1-1;$soncount++)
                             { if ($MY_SCORE[$I][$SON_I][$soncount]=="")
                             	{$SON_TOTAL=$SON_TOTAL+0;}
                               else
                               {$SON_TOTAL=$SON_TOTAL+$MY_SCORE[$I][$SON_I][$soncount];}
   
   ?>
                               <td align="center" nowrap><?=$MY_SCORE[$I][$SON_I][$soncount]?><br>
   <?
                               if($MY_MEMO[$I][$SON_I][$soncount]!="")
                               {
   ?>
                                <span class="big4">(<?=_("批注：")?><?=$MY_MEMO[$I][$SON_I][$soncount]?>)</span>
   <?
                               }
   ?>                            
                               
                               </td>
   
   <?
                             }
   ?>
   
                             </tr>
   <?
                           }
   ?>
                       </table>
                       <br>
               </td>
               </tr>
   <?
               }
}
if($COUNT_RESULT==0)
   MESSAGE("",_("无考核信息"));
?>


</table>
<br><br>
<div align="center">
	<input type="button"  class="BigButton" value="<?=_("关闭")?>" onclick="window.close();">
</div>
</body>
</html>
<script Language="JavaScript">

 function td_detail(I)
{
   if(document.all(I).style.display=="none")
      {document.all(I).style.display="";
       document.all(I).style.cursor="hand";
      	}
   else
   	document.all(I).style.display="none";

}

 function td_close(I)
{

   	document.all(I).style.display="none";

}
</script>