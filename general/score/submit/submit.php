<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?

  //-- �Ϸ��Լ��� --
  for($I=1;$I<=$ITEM_COUNT;$I++)
  {
    $NAME=$I."NAME";
    $MIN=$I."MIN";
    $MAX=$I."MAX";
    $value="value".$I;

    if($$value!="")
      if(!is_decimal($$value))
      {
        $MSG1 = sprintf(_("����� %s ��ʽ���ԣ�ӦΪ��������"),$$NAME);
        Message(_("����"),$MSG1);

        $URL="score_data.php?RECALL=1&GROUP_ID=$GROUP_ID&FLOW_ID=$FLOW_ID&USER_ID=$PARTICIPANT&OPERATION=$OPERATION";
         for($I=1;$I<=$ITEM_COUNT;$I++)
        {
          $value="value".$I;
          $URL.="&".$value."=".$$value;
          $MEMO=$I."MEMO";
          $URL.="&".$MEMO."=".$$MEMO;
        }

?>
      <br>
        <div align="center">
           <input type="button" value="<?=_("����")?>" class="BigButton" name="button" onClick="location='<?=$URL?>'">
        </div>

<?
         exit;
      }
      else
     {
    	  if($$value>$$MAX or $$MIN>$$value)
    	  {
    	  	 $MSG2 = sprintf(_("����� %s ��Χ���ԣ�ӦΪ%.1f �� %.1f"),$$NAME,$$MIN,$$MAX);
    	  	 Message(_("����"),$MSG2);
    	  	 $URL="score_data.php?RECALL=1&connstatus=1&GROUP_ID=$GROUP_ID&FLOW_ID=$FLOW_ID&USER_ID=$PARTICIPANT&OPERATION=$OPERATION";
            for($I=1;$I<=$ITEM_COUNT;$I++)
            {
              $value="value".$I;
              $URL.="&".$value."=".$$value;
              $MEMO=$I."MEMO";
              $URL.="&".$MEMO."=".$$MEMO;
            }

 ?>
         <br>
        <div align="center">
           <input type="button" value="<?=_("����")?>" class="BigButton" name="button" onClick="location='<?=$URL?>'">
        </div>

 <?
         exit;
    	  }

    	}

      }


  //-- ���� --
  if($OPERATION==1)
  {
     for($I=1;$I<=$ITEM_COUNT;$I++)
      {
         $value="value".$I;
         $SCORE.=$$value;
         $SCORE.=",";
         $MEMO=$I."MEMO";
         $MEMO_DATA.=$$MEMO;
         $MEMO_DATA.=",";
      }
     $RANK_DATE=date("Y-m-d",time());
     $query="insert into SCORE_DATE (FLOW_ID,RANKMAN,PARTICIPANT,SCORE,RANK_DATE,MEMO) values ('$FLOW_ID','".$_SESSION["LOGIN_USER_ID"]."','$PARTICIPANT','$SCORE','$RANK_DATE','$MEMO_DATA')";
  }
  else
  {
    for($I=1;$I<=$ITEM_COUNT;$I++)
      {
         $value="value".$I;
         $SCORE.=$$value;
         $SCORE.=",";
         $MEMO=$I."MEMO";
         $MEMO_DATA.=$$MEMO;
         $MEMO_DATA.=",";
      }
     $RANK_DATE=date("Y-m-d",time());
     $query="update SCORE_DATE set SCORE='$SCORE',RANK_DATE='$RANK_DATE',MEMO='$MEMO_DATA' where FLOW_ID='$FLOW_ID' and RANKMAN='".$_SESSION["LOGIN_USER_ID"]."' and PARTICIPANT='$PARTICIPANT'";

  }

  exequery(TD::conn(),$query);

        $query1="select USER_NAME from USER where USER_ID='$PARTICIPANT'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
           $USER_NAME=$ROW["USER_NAME"];

  $MSG3 = sprintf(_("%s �Ŀ����������ϱ�")."<br><br>"._("�����ѡ��������Ա"),$USER_NAME);
  Message(_("��ʾ"),$MSG3);
?>

<br>
</body>
</html>
<script>
 URL="user_list.php?FLOW_ID="+'<?=$FLOW_ID?>'+"&connstatus=1";
 parent.frames["user_list"].location=URL;
</script>