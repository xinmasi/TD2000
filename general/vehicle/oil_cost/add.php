<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
for($i=1;$i<=$COUNT;$i++)
{
	$DRIVER = "DRIVER_".$i;
	$MILEAGE = "MILEAGE_".$i;
	$OIL_COUNT = "OIL_COUNT_".$i;
$DRIVER=$$DRIVER;
$MILEAGE=$$MILEAGE;
$OIL_COUNT=$$OIL_COUNT;
if($MILEAGE!=0)
   $PER_OIL_USE = number_format($OIL_COUNT/$MILEAGE,2);
else
   $PER_OIL_USE = 0;
   
if($DRIVER=="")
   continue;
else
   $query = "INSERT INTO VEHICLE_OIL_USE (DRIVER,YEAR,MONTH,MILEAGE,OIL_USE,PER_OIL_USE)VALUES('$DRIVER', '$YEAR', '$MONTH','$MILEAGE', '$OIL_COUNT','$PER_OIL_USE');";
exequery(TD::conn(),$query);
}
Message("",_("添加成功！"));
?>
<div align="center">
<input type="button" class="BigButton" value="<?=_("返回")?>" onClick="location='index.php?YEAR=<?=$YEAR?>&MONTH=<?=$MONTH?>'">
</div>
