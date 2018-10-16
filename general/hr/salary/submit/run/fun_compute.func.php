<?
function comput_allitem($FLOW_ID,$USER_ID)
{
$count=0;
$count1=0;
$length=0;
$query = "SELECT * from SAL_ITEM";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{ 
   if($ROW["ISCOMPUTER"]=="1")
   {
     	 $DATA_FORMULA[$count1]=$ROW["FORMULA"];
     	 $DATA_FORMULAID[$count1]=$ROW["ITEM_ID"];
     	 $count1++;
     	 //$Sequence[$count]="-1";
   }
   else
   {
       $Sequence[$count]=$ROW["ITEM_ID"];
       $length++;
       $count++;
   }
}   
$count=0;
$count1=0;
while(computearray($DATA_FORMULAID)!=0)
{
	  $ARRAY_COUNT=sizeof($DATA_FORMULA);
	  for($I=0;$I<$ARRAY_COUNT;$I++)
    {
	    if($DATA_FORMULAID[$I]!=0)
	    {
	    	 if (issonformula($DATA_FORMULA[$I],$Sequence)=="1")
	       {
	     	  $Sequence[$length+$count1]=$DATA_FORMULAID[$I];
	     	  $Compute_Formula[$count1]=$DATA_FORMULA[$I];
	     	  $DATA_FORMULAID[$I]=0;
	     	  $count1++;
	     	  continue;
	      }
	    }
   }
  $count++;
  if ($count>50)
   {
   	 $ARRAY_COUNT=sizeof($DATA_FORMULAID);
     for($I=0;$I<$ARRAY_COUNT;$I++)
     {
	     if ($DATA_FORMULAID[$I]!=0)
	       $DATA_FORMULA[$I]._("公式定义有误！！")."<br>";
     }
   	 return;
   }
 }
 

$ARRAY_COUNT=sizeof($Sequence);
$query = "SELECT * from SAL_DATA where FLOW_ID='$FLOW_ID'and USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{ 
   $USER_ID=$ROW["USER_ID"];
   for($I=0;$I< $ARRAY_COUNT;$I++)
   {
	     $Update_Date[$I]=$ROW["S".$Sequence[$I]];
   }  
   $Update_Date=compute_all($Update_Date,$Sequence,$Compute_Formula);
    
   $query="update SAL_DATA set ";
   for($I=0;$I< $ARRAY_COUNT;$I++)
   {
      $STR="S".$Sequence[$I];
      if($Update_Date[$I]=="")
         $Update_Date[$I]="0";
      $query.=$STR."=".$Update_Date[$I];
      if($I!=$ARRAY_COUNT-1)
         $query.=",";
   }
   $query.=" where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID'";
   exequery(TD::conn(),$query);
  //echo _("员工").$USER_ID._("的工资项目更新完成!!!")."<br>";
} 
 } 
 
 
function issonformula($formul,$ARRAY_Sequence)
{
   //$formul="[$1]+[$2]+[$45]*0.2+([$1]+[$2]+[$45])*0.88";
   $temp=explode("$",$formul);
   $ARRAY_COUNT=sizeof($temp);
   if($temp[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
	   if (substr($temp[$I],0,strpos($temp[$I],"]"))!="")
	    {
	    	 $ARRAY_COUNT1=sizeof($ARRAY_Sequence);
	    	 for($h=0;$h<$ARRAY_COUNT1;$h++)
	    	 {
	    	 	$tempid= substr($temp[$I],0,strpos($temp[$I],"]"));
	    	 	if ($ARRAY_Sequence[$h]==$tempid) break;
	    	 }
	       if ($h==$ARRAY_COUNT1)return "0";
	    }
   }
   return "1";
}

function computearray($FORMULAID)
 {
	 $ARRAY_COUNT=sizeof($FORMULAID);
   $SUM=0;
	 for($I=0;$I<$ARRAY_COUNT;$I++)
  {
	  $SUM=$SUM+$FORMULAID[$I];
  }
	return $SUM;
 }
 
 
 function compute_all($Update_Date,$Sequence,$Compute_Formula)
 {
	  $ARRAY_COUNT=sizeof($Sequence);
	  for($I=($ARRAY_COUNT-sizeof($Compute_Formula));$I<$ARRAY_COUNT;$I++)
   {
	   if (strlen(strrchr($Compute_Formula[$I-($ARRAY_COUNT-sizeof($Compute_Formula))],"<"))==0)
	    {
	   	    $temp=formula_swap($Sequence,$Compute_Formula[$I-($ARRAY_COUNT-sizeof($Compute_Formula))],$Update_Date);
	   	    eval("\$O=\"$temp\";");
	    }
	    else //个税计算
	    {
	    	$temp=str_replace("<","",formula_swap($Sequence,$Compute_Formula[$I-($ARRAY_COUNT-sizeof($Compute_Formula))],$Update_Date)); 
	      $temp=str_replace(">","",$temp);
	    	eval("\$O=\"$temp\";");
	      $O=compute_tax(round($O,2));
	    }
     $Update_Date[$I]=round($O,2);  
   }
	  return $Update_Date;
  }
 
 
 
function formula_swap($Sequence,$Formula,$Update_Date)
 {
   //echo $Formula;echo "<br>";
	 $Formula=str_replace("[","",$Formula); 
	 $Formula=str_replace("]","",$Formula);
   $Formula=str_replace("$","S",$Formula);
   $ARRAY_COUNT=sizeof($Sequence);
	 for($I=$ARRAY_COUNT-1;$I>=0;$I--)
   {
	   $temp="S".($I+1);
	   $index=findinarray($I+1,$Sequence);
	   if($Update_Date[$index] < 0)
	      $Update_Date[$index]= "(".$Update_Date[$index].")";
	   $Formula=str_replace($temp,$Update_Date[$index],$Formula);
   }
   return $Formula;
 }
 
function findinarray($a,$Sequence)
 {
	 
	 $ARRAY_COUNT=sizeof($Sequence);
	 for($I=0;$I<$ARRAY_COUNT;$I++)
   {
	    if($Sequence[$I]==$a){return $I;}
	     
   }
	
 } 
 
 
function compute_tax($cha)
{
   if ($cha<=0) { return 0;}
   if ($cha>0 && $cha<=1500) {return round(($cha*0.03),2);}
   if ($cha>1500 && $cha<=4500) { return round(($cha*0.1-105),2);}
   if ($cha>4500 && $cha<=9000) {return round(($cha*0.2-555),2);}
   if ($cha>9000 && $cha<=35000) {return round(($cha*0.25-1005),2);}
   if ($cha>35000 && $cha<=55000) {return round(($cha*0.3-2755),2);}
   if ($cha>55000 && $cha<=80000) {return round(($cha*0.35-5505),2);}
   if ($cha>80000) {return round(($cha*0.45-13505),2);}
}
?>
  