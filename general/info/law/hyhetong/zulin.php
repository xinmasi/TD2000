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
      <div align="center"><?=_("租赁合同")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="zulin/zulinght01.php"><?=_("房屋租赁合同（一）")?></a></li>
        <li><a href="zulin/zulinght02.php"><?=_("房屋租赁合同（二）")?></a></li>
        <li><a href="zulin/zulinght03.php"><?=_("融资租赁合同（一）")?></a></li>
        <li><a href="zulin/zulinght04.php"><?=_("融资租赁合同（二）")?></a></li>
        <li><a href="zulin/zulinght05.php"><?=_("融资租赁合同（三）")?></a></li>
        <li><a href="zulin/zulinght06.php"><?=_("融资租赁合同（四）")?></a></li>
        <li><a href="zulin/zulinght07.php"><?=_("融资租赁合同（五）")?></a></li>
        <li><a href="zulin/zulinght08.php"><?=_("融资租赁合同（六）")?></a></li>
        <li><a href="zulin/zulinght09.php"><?=_("柜台租赁合同")?></a></li>
        <li><a href="zulin/zulinght10.php"><?=_("公寓租赁合同")?></a></li>
        <li><a href="zulin/zulinght11.php"><?=_("租赁委托合同")?></a></li>
        <li><a href="zulin/zulinght12.php"><?=_("租赁经营招标通告")?></a></li>
        <li><a href="zulin/zulinght13.php"><?=_("租赁经营投标程序及规则")?></a></li>
        <li><a href="zulin/zulinght14.php"><?=_("承租经营合作者协议")?></a></li>
        <li><a href="zulin/zulinght15.php"><?=_("租赁经营合同书")?></a></li>
      </ol>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>