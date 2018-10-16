<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>
<style>
html,body{
    overflow: hidden;
    height: 100%;
}
#west{
    width:0;
    position: absolute;
    top:0;
    left:0;
    bottom:0;
    overflow: auto;
}
#center{
    position: absolute;
    top:30px;
    bottom:0;
    left:0;
    right:0;
    overflow: hidden;
}
#center iframe{
    width: 100%;
    height: 100%;
    display: block;
    position: absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
} 
* html{
    padding-right:0;
}
* html #west{
    height:100%;
}
* html #center{
    position: relative;
}
</style>



<body bgcolor="#E8E8E8" topmargin="5">

<center>

 <form method="post" action="bookno_info.php?USER_ID=<?=$USER_ID?>&LEND_FLAG=<?=$LEND_FLAG?>" target="bookno_info" name="form1">
  <?=_("编号/书名：")?>
  <input type="text" name="BOOK_NO" size="10" class="BigInput">
  <input type="submit" name="Submit" value="<?=_("模糊查询")?>" class="BigButton">
 </form>
</center>
<div id="center">    
    <iframe name="bookno_info" src="bookno_info.php?USER_ID=<?=$USER_ID?>&LEND_FLAG=<?=$LEND_FLAG?>" frameborder="0"></iframe>
</div>
</body>
</html>
