<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

if($fld_str=="")
{
   $query = "SELECT ITEM_ID from SAL_ITEM";
   $cursor= exequery(TD::conn(),$query);
   $FLOW_COUNT=0;
   while($ROW=mysql_fetch_array($cursor))
   {
     	$STYLE.="S".$ROW["ITEM_ID"].",";
   }
   $STYLE.="ALL_BASE,PENSION_BASE,PENSION_U,PENSION_P,MEDICAL_BASE,MEDICAL_U,MEDICAL_P,FERTILITY_BASE,FERTILITY_U,UNEMPLOYMENT_BASE,UNEMPLOYMENT_U,UNEMPLOYMENT_P,INJURIES_BASE,INJURIES_U,HOUSING_BASE,HOUSING_U,HOUSING_P,INSURANCE_DATE,";
   $STYLE.="MEMO";
}
else
{  
	 //ȥ�����ڵ�","
   $STYLE=substr($fld_str,0,-1);
}


$HTML_PAGE_TITLE = _("���ʱ���");
include_once("inc/header.inc.php");
?>


<style type="text/css">
.small {  font-size: 9pt;}
</style>


<body>

<?
 $query = "SELECT count(*) from SAL_ITEM";
 $ITEM_COUNT=0;
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $ITEM_COUNT=$ROW[0];

 if($ITEM_COUNT==0)
 {
   Message(_("��ʾ"),_("��δ���幤����Ŀ"));
   Button_back();
   exit;
 }
?>

<table  bordercolor="#000000" style='border-collapse:collapse' border=1 cellspacing=0 cellpadding=2 bordercolor='#000000' class="small" align="center">

    <tr bgcolor="#D3E5FA">
      <td nowrap align="center"><b><?=_("����")?></b></td>
      <td nowrap align="center"><b><?=_("����")?></b></td>
      <td nowrap align="center"><b><?=_("��ɫ")?></b></td>
<?
 $STYLE_ARRAY=explode(",",$STYLE);
 $ARRAY_COUNT=sizeof($STYLE_ARRAY);
 $COUNT=0;
 if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
 for($I=0;$I < $ARRAY_COUNT;$I++)
 {
 	    if(substr($STYLE_ARRAY[$I],0,1)=="S")
 	    {
      	$query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='".substr($STYLE_ARRAY[$I],1)."'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
           	$ITEM_NAME=$ROW["ITEM_NAME"];
             $ITEM_ID[$COUNT]=$ROW["ITEM_ID"];
        }
        $COUNT++; 
        echo "<td nowrap align='center'><b>$ITEM_NAME</b></td>";  	
      }
      else
      {
      	
      	if($STYLE_ARRAY[$I]=="ALL_BASE")
      	{  $COUNT++;
      	   echo "<td nowrap align='center'><b>"._("���ջ���")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="PENSION_BASE")
      	{  $COUNT++;      	
      	   echo "<td nowrap align='center'><b>"._("���ϱ���")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="PENSION_U")
      	{  $COUNT++;      	
      	   echo "<td nowrap align='center'><b>"._("��λ����")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="PENSION_P")
      	{  $COUNT++;      	
      	   echo "<td nowrap align='center'><b>"._("��������")."</b></td>";
        }
      	if($STYLE_ARRAY[$I]=="MEDICAL_BASE")
      	{  $COUNT++;      	
      	   echo "<td nowrap align='center'><b>"._("ҽ�Ʊ���")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="MEDICAL_U")
      	{  $COUNT++;      	
      	   echo "<td nowrap align='center'><b>"._("��λҽ��")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="MEDICAL_P")
      	{  $COUNT++;      	
      	   echo "<td nowrap align='center'><b>"._("����ҽ��")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="FERTILITY_BASE")
      	{  $COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("��������")."</b></td>";      	   
        }
      	if($STYLE_ARRAY[$I]=="FERTILITY_U")
      	{  $COUNT++;  
      	   echo "<td nowrap align='center'><b>"._("��λ����")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="UNEMPLOYMENT_BASE")
      	{  $COUNT++;  
      	   echo "<td nowrap align='center'><b>"._("ʧҵ����")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="UNEMPLOYMENT_U")
      	{  $COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("��λʧҵ")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="UNEMPLOYMENT_P")
      	{  $COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("����ʧҵ")."</b></td>";
        }
      	if($STYLE_ARRAY[$I]=="INJURIES_BASE")
      	{  $COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("���˱���")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="INJURIES_U")
      	{  $COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("��λ����")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="HOUSING_BASE")
      	{  $COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("ס��������")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="HOUSING_U")
      	{  $COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("��λס��")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="HOUSING_P")
      	{  $COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("����ס��")."</b></td>";
      	}
      	if($STYLE_ARRAY[$I]=="INSURANCE_DATE")
      	{  $COUNT++;        	
      	   echo "<td nowrap align='center'><b>"._("Ͷ������")."</b></td>"; 
      	} 	
      }
      if($STYLE_ARRAY[$I]=="MEMO")
      {
      	 $COUNT++;
      	 echo "<td nowrap align='center'><b>"._("��ע")."</b></td>";
      }    
   }//end for
?>
    </tr>
<?
if($COPY_TO_ID!="")
{
   $COPY_TO_ID="'".str_replace(",","','",substr($COPY_TO_ID,0,-1))."'";
   $WHERE_STR.=" and USER.USER_ID in ($COPY_TO_ID)";
}
if($TOID!="" and $TOID!="ALL_DEPT")
{
   	 $TOID="'".str_replace(",","','",substr($TOID,0,-1))."'";
     $WHERE_STR.=" and DEPARTMENT.DEPT_ID in ($TOID)";
}  
if($DEPT_FLAG!=1)
{
    $query = "SELECT * from USER,USER_PRIV,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV".$WHERE_STR." order by DEPT_NO,PRIV_NO,USER_NAME";
}
else
{
  	$query = "SELECT * from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV and DEPT_ID=0";
}
$cursor= exequery(TD::conn(),$query);
$USER_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $USER_COUNT++;

    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $DEPT_ID=$ROW["DEPT_ID"];
    $USER_PRIV=$ROW["USER_PRIV"];

    if($DEPT_ID==0)
       $DEPT_NAME=_("��ְ��Ա/�ⲿ��Ա");
    else
    {
			 $DEPT_ID = intval($DEPT_ID);
       $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
       $cursor1= exequery(TD::conn(),$query1);
       if($ROW=mysql_fetch_array($cursor1))
          $DEPT_NAME=$ROW["DEPT_NAME"];
    }

    $query1 = "SELECT * from USER_PRIV where USER_PRIV='$USER_PRIV'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
         $USER_PRIV=$ROW["PRIV_NAME"];
?>
    <tr>
      <td nowrap align="center"><?=$DEPT_NAME?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td nowrap align="center"><?=$USER_PRIV?></td>
<?
   $query1="select * from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
   {
      for($I=0;$I< $COUNT;$I++)
      {
          $STR=$STYLE_ARRAY[$I];
          if($STR!="MEMO")
             $$STR=format_money(td_authcode($ROW[$STR],"DECODE"));
          else
             $$STR=$ROW["MEMO"];
       }
   }
   else
   {
       for($I=0; $I<$COUNT; $I++)
       {
           $STR=$STYLE_ARRAY[$I];
           $$STR="";
       }
   }

   for($I=0; $I< $COUNT; $I++)
   {
       $STR=$STYLE_ARRAY[$I];
       $STR_COUNT=$STR."_COUNT";
       $$STR_COUNT+=$$STR;

       if($$STR=="")
          $$STR="&nbsp;";
?>
      <td nowrap align="right"><?=$$STR?></td>
<?
   }
?>
    </tr>
<?
}

if($USER_COUNT==0)
{
?>
  <div align="center"><b><?=_("��δ�����û�")?></b></div><br>
<?
}
else
{
?>
  <tr bgcolor="#EEEEEE">
   <td nowrap align="center" colspan="3"><b><?=_("�ϼ�")?></b></td>
<?
   for($I=0;$I< $COUNT;$I++)
   {
      $STR="S".$ITEM_ID[$I];
      $STR_COUNT=$STR."_COUNT";
      //var_dump($$STR_COUNT);
      
      $DATA=format_money($$STR_COUNT);

      if($DATA=="")
         $DATA="&nbsp;";
?>
   <td nowrap align="right"><b><?=$DATA?></b></td>
<?
   }
?>
  </tr>
<?
 }
?>
</table>
</div>

</body>
</html>