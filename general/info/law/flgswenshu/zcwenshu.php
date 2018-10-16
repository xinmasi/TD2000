<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>
<style type="text/css">
<!--a            { text-decoration: none; font-size: 9pt; color: black; font-family: 宋体 }
.text        { font-size: 9pt; font-family: 宋体 }
.text1       { color: #0000A0; font-size: 11pt; font-family: 宋体 }
.text2       { color: #008080; font-size: 9pt; font-family: 宋体 }
.text3       { color: #0F8A91; font-size: 11pt; font-family: 宋体 }
.l100        { line-height: 14pt; font-size: 9pt }
td           { font-family: 宋体; font-size: 9pt; line-height: 13pt }
input        { font-size: 9pt; font-family: 宋体 }
p            { font-size: 9pt; font-family: 宋体 }
--></style>

<BODY class="bodycolor">

<BR>
<table width="500" class="TableBlock" align="center">
  <tr> 
    <td height="27" class="TableHeader"> 
      <div align="center"><?=_("仲裁文书")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="zcwenshu/zhongcai01.php"><?=_("撤销仲裁裁决申请书")?></a></li>
        <li><a href="zcwenshu/zhongcai02.php"><?=_("仲裁申请书")?></a></li>
        <li><a href="zcwenshu/zhongcai03.php"><?=_("仲裁反申请书")?></a></li>
        <li><a href="zcwenshu/zhongcai04.php"><?=_("仲裁保全担保书")?></a></li>
        <li><a href="zcwenshu/zhongcai05.php"><?=_("回避申请书")?></a></li>
        <li><a href="zcwenshu/zhongcai06.php"><?=_("指定仲裁员函")?></a></li>
        <li><a href="zcwenshu/zhongcai07.php"><?=_("延期审理申请书")?></a></li>
        <li><a href="zcwenshu/zhongcai08.php"><?=_("仲裁保全申请书")?></a></li>
        <li><a href="zcwenshu/zhongcai09.php"><?=_("仲裁委托代理协议")?></a></li>
        <li><a href="zcwenshu/zhongcai10.php"><?=_("执行仲裁裁决申请书")?></a></li>
        <li><a href="zcwenshu/zhongcai11.php"><?=_("仲裁代理授权委托书")?></a></li>
        <li><a href="zcwenshu/zhongcai12.php"><?=_("仲裁协议书")?></a></li>
        <li><a href="zcwenshu/zhongcai13.php"><?=_("仲裁答辩状")?></a></li>
        <li><a href="zcwenshu/zhongcai14.php"><?=_("证据保全申请书")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>