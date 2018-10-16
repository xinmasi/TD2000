<?
/**
*   proj_notice.php文件
*
*   文件内容描述：
*   1、页面消息提示；
*
*   @author  马舒宁
*
*   @edit_time  2013/10/15
*
*/
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("../../../proj/proj_priv.php");
?>
<script type="text/javascript">
 function delete_content(NEWS_ID,PROJ_ID){
     
     if(confirm("您确认要删除该条信息吗？")){
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
   <span class="big3"><?=_("项目快讯")?></span>
  </td>
 </tr>
</table>
<?php
    $i_proj_id = $_GET["PROJ_ID"];

    $query=" select id,uid,news_time,content from proj_news where proj_id='$i_proj_id' order by id desc;";
    $cursor=exequery(TD::conn(),$query);
    $DETAIL_COUNT=0;
    while( $row= mysql_fetch_assoc($cursor))
    {
        $DETAIL_COUNT++;
        $news_id=$row['id'];//id
        $uid=$row['uid'];//发讯息人的名字
        $user_name = td_trim(GetUserNameByUid($uid));
        $content=$row['content'];//讯息的内容
        $news_time=$row['news_time'];//讯息的时间
        $news_time = date("Y-m-d H:i:s",$news_time);
        if($DETAIL_COUNT==1)
        {
?>
            <table class="TableList" width="95%" align="center">
                <tr class="TableHeader"> 	 
                    <td nowrap align="center"><?=_("发讯人")?></td>
                    <td nowrap align="center"><?=_("讯息内容")?></td>
                    <td nowrap align="center"><?=_("发讯时间")?></td>
                    <td nowrap align="center"><?=_("操作")?></td>
                </tr>
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
?>
                <tr class="<?=$TableLine?>">
                    <td nowrap align="center"><?=$user_name?></td>
                    <td style="word-break:break-all;" align="left"><?=$content?></td>
                    <td nowrap align="center" width="160"><?=$news_time?></td> 
                    <td nowrap align="center">
                    <?php
                        if($_SESSION['LOGIN_UID']==$uid){
                            echo "<a href='javascript:delete_content($news_id,$i_proj_id);'>删除</a>";
                        }
                    ?>

                    </td> 
                </tr>   
<?php
    }
    if($DETAIL_COUNT==0)
    {
        Message("",_("暂无快讯信息"));
    }
    else
    {
?>
                </table>
<?php
    }
?>
    
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td>
            <img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="20" HEIGHT="20" align="absmiddle">
            <span class="big3"> <?=_("添加快讯")?></span>
        </td>
    </tr>
</table>
 
<form action="add.php?PROJ_ID=<?=$i_proj_id?>"  method="post" name="form1" onsubmit="return checkForm();">
    <table class="TableList" width="95%"  align="center" >  
        <tr>
            <td class="TableData">信息内容：</td>
            <td><textarea name="content" class="BigInput" cols="80" rows="6"></textarea></td>
        </tr>
        <tr>
            <td class="TableControl"  align="center" colspan="2">
            <input class="BigButtonA" type="submit" value="确定" /></td>
        </tr>
    <table> 
</form>
</body>
</html>