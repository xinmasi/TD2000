<?
/**
*   news.php�ļ�
*
*   �ļ�����������
*   1��������Ѷ��ʾ��
*
*   @author  ������
*
*   @edit_time  2013/10/16
*
*/
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("../../../proj/proj_priv.php");

?>
<script type="text/javascript">
    function delete_content(NEWS_ID,PROJ_ID)
    { 
        if(confirm("��ȷ��Ҫɾ��������Ϣ��"))
        {
            URL="delete.php?NEWS_ID=" +NEWS_ID+"&PROJ_ID="+PROJ_ID;
            window.location=URL;  
        }
     
     }
</script>
<body class="bodycolor">
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td>
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" align="absMiddle" width=20 height=20>
                <span class="big3"><?=_("��Ŀ��Ѷ")?></span>
             </td>
        </tr>
    </table>
    
<?php
    $i_proj_id = $_GET["PROJ_ID"];
    $id=$_GET['id'];
    $query=" select id,uid,news_time,content from proj_news where proj_id='$i_proj_id' and id='$id';";//������ӷ�ҳ���˴���ֻ��ʾ�������� order by id desc limit 0,6
    $cursor=exequery(TD::conn(),$query);
    $DETAIL_COUNT=0;
    while( $row= mysql_fetch_assoc($cursor))
    {
       
        $DETAIL_COUNT++;
        $news_id=$row['id'];//id
        $uid=$row['uid'];//��ѶϢ�˵�����
        $user_name = td_trim(GetUserNameByUid($uid));
        $content=$row['content'];//ѶϢ������
        $news_time=$row['news_time'];//ѶϢ��ʱ��
        $news_time = date("Y-m-d H:i:s",$news_time);
    
		if($DETAIL_COUNT==1)
		{
?>
            <table class="TableList" width="95%">
                <tr class="TableList" align="left">
                    <td nowrap class="TableContent"><?=_("��Ѷ��")?></td>
                    <td nowrap class="TableData"><?=$user_name?></td>
                </tr>
                <tr class="TableList" align="left">
                    <td nowrap class="TableContent" ><?=_("��Ѷʱ��")?></td>
                    <td nowrap class="TableData"><?=$news_time?></td>
                </tr>
                <tr class="TableList" align="left">
                    <td nowrap class="TableContent"><?=_("ѶϢ����")?></td>
                    <td class="TableData"><?=$content?></td>
                </tr>
            </table> 
<?php 
		}
        
        if($COMMENT_COUNT%2==1)
        {
            $TableLine="TableLine1";
        }
        else
        {
            $TableLine="TableLine2";
        } 

        if($_SESSION['LOGIN_UID']==$uid)
        {
?>
		<br/>
		<div align="center" style="width:80%">
			<input  type="button" onclick="delete_content(<?=$news_id?>,<?=$i_proj_id?> );" class="BigButtonA" value="ɾ��">
		</div>
<?php
        }

    }
	
	if($DETAIL_COUNT==0)
    {
        Message("",_("���޿�Ѷ��Ϣ"));
    }  
?>    
</body>
</html>