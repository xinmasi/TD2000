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
      <div align="center"><?=_("行政诉讼类")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="xzsusong/xinzhenss00.php"><?=_("行政判决书（一审行政案件用）")?></a></li>
        <li><a href="xzsusong/xinzhenss01.php"><?=_("行政判决书（二审维持原判或改判用）")?></a></li>
        <li><a href="xzsusong/xinzhenss02.php"><?=_("行政判决书（再审行政案件用）")?></a></li>
        <li><a href="xzsusong/xinzhenss03.php"><?=_("行政裁定书（不予受理起诉用）")?></a></li>
        <li><a href="xzsusong/xinzhenss04.php"><?=_("行政裁定书（驳回起诉用）")?></a></li>
        <li><a href="xzsusong/xinzhenss05.php"><?=_("行政裁定书（停止执行具体行政行为或驳回申请用）")?></a></li>
        <li><a href="xzsusong/xinzhenss06.php"><?=_("行政裁定书（准许或不准撤诉用）")?></a></li>
        <li><a href="xzsusong/xinzhenss07.php"><?=_("行政赔偿调解书（一审行政赔偿案件用）")?></a></li>
        <li><a href="xzsusong/xinzhenss08.php"><?=_("行政裁定书（二审发回重审用）")?></a></li>
        <li><a href="xzsusong/xinzhenss09.php"><?=_("行政裁定书（提起再审用）")?></a></li>
        <li><a href="xzsusong/xinzhenss10.php"><?=_("行政诉讼起诉书")?></a></li>
        <li><a href="xzsusong/xinzhenss11.php"><?=_("行政诉讼答辩状")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>