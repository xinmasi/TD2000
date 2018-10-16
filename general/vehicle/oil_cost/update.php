<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
for($i=1;$i<=$COUNT;$i++)
{
   $DRIVER = "DRIVER_".$i;
   $MILEAGE = "MILEAGE_".$i;
   $OIL_COUNT = "OIL_COUNT_".$i;
   $PER_OIL_COUNT = "PER_OIL_COUNT_".$i;
   $ID = "ID_".$i;
   $DRIVER=$$DRIVER;
   $MILEAGE=$$MILEAGE;
   $OIL_COUNT=$$OIL_COUNT;
   $ID=$$ID;
   $PER_OIL_COUNT=$$PER_OIL_COUNT;        
   if($PER_OIL_COUNT==0)
   {
      if($MILEAGE!=0)
         $PER_OIL_COUNT = number_format($OIL_COUNT/$MILEAGE,2);
      else
         $PER_OIL_COUNT = 0;
   }
   $query = "UPDATE VEHICLE_OIL_USE SET MILEAGE='$MILEAGE',OIL_USE='$OIL_COUNT',PER_OIL_USE='$PER_OIL_COUNT' WHERE ID='$ID';";
   exequery(TD::conn(),$query);
}
Message("",_("修改成功！"));
?>
<div align="center">
<input type="button" class="BigButton" value="<?=_("返回")?>" onClick="location='index.php?YEAR=<?=$YEAR?>&MONTH=<?=$MONTH?>'">
</div>
