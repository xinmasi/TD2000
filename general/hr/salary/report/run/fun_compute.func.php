<?
function comput_allitem($FLOW_ID)
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
	      echo $DATA_FORMULA[$I]._("公式定义有误！")."<br>";
     }
   	 return;
   }
 }
 

$ARRAY_COUNT=sizeof($Sequence);
$query = "SELECT * from SAL_DATA where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
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
     $query.=" where FLOW_ID=$FLOW_ID and USER_ID='$USER_ID'";
     exequery(TD::conn(),$query);
     echo _("员工").$USER_ID._("的工资项目更新完成!!!")."<br>";
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
	   	    eval("\$O=$temp;");
	   	    //echo $Compute_Formula[$I-($ARRAY_COUNT-sizeof($Compute_Formula))].":".$temp."=".$O."<br>";
	    }
	    else //个税计算
	    {
	    	$temp=str_replace("<","",formula_swap($Sequence,$Compute_Formula[$I-($ARRAY_COUNT-sizeof($Compute_Formula))],$Update_Date)); 
	      $temp=str_replace(">","",$temp);
	    	//echo $temp."<br>";
	    	eval("\$O=$temp;");
	      $O=compute_tax(round($O,2));
	    }
     $Update_Date[$I]=round($O,2);  
   }
	  return $Update_Date;
 }
 
function formula_swap($Sequence,$Formula,$Update_Date)
 {
	 $Formula=str_replace("[","",$Formula); 
	 $Formula=str_replace("]","",$Formula);
   $Formula=str_replace("$","S",$Formula);
   $ARRAY_COUNT=sizeof($Sequence);
	 for($I=$ARRAY_COUNT-1;$I>=0;$I--)
   {
	   $temp="S".($I+1);
	   $index=findinarray($I+1,$Sequence);
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
   if ($cha>0 && $cha<=500) {return round(($cha*0.05),2);}
   if ($cha>500 && $cha<=2000) { return round(($cha*0.1-25),2);}
   if ($cha>2000 && $cha<=5000) {return round(($cha*0.15-125),2);}
   if ($cha>5000 && $cha<=20000) {return round(($cha*0.2-375),2);}
   if ($cha>20000 && $cha<=40000) {return round(($cha*0.25-1375),2);}
   if ($cha>40000 && $cha<=60000) {return round(($cha*0.30-3375),2);}
   if ($cha>60000 && $cha<=80000) {return round(($cha*0.35-6375),2);}
   if ($cha>80000 && $cha<=100000) {return round(($cha*0.4-10375),2);}
   if ($cha>100000 && $cha>100000) {return round(($cha*0.45-15375),2);}
}
?>
  