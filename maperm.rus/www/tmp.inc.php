<?if($isIndex!=true) exit(header('Location: /'));?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
    <title><?=$title?></title>
    <meta name="description" content="<?=$description?>" />
    <link rel="stylesheet" href="<?=DOMAIN?>/css/<?=$stylecss?>" />
    <link href='//cdn1.editmysite.com/editor/fonts/Bebas/font.css?2' rel='stylesheet' type='text/css' />
    <script type="text/javascript" src="<?=DOMAIN?>/js/<?=$scripts?>"></script>
    <script type="text/javascript" src="<?=DOMAIN?>/js/ya.js"></script>
    <script src="http://api-maps.yandex.ru/1.1/?wizard=constructor" type="text/javascript"></script>
    <script type="text/javascript">window.onload = function () {layers.init("#map", 56.28552, 58.01741, 12);}</script>
</head>
<body>
<div id="top-menu">
<table id="top">
<tr>
<td id="logo">
<a href="http://maperm.ru"><span id="wsite-title">MAPERM.RU</span></a>
</td>
<td class="item">
<ul class="dropdown">
<li class="dropdown-top"><a class="dropdown-top" href="<?=DOMAIN?>/budget">������</a></li>
<li class="dropdown-top"><a class="dropdown-top" href="<?=DOMAIN?>/school">�����������</a>
<ul class="dropdown-inside">
<li><a href="<?=DOMAIN?>/school">�����</a></li>
<li><a href="<?=DOMAIN?>/dou">������� ����</a></li>
<li><a href="<?=DOMAIN?>/udo">������ � ������</a></li>
</ul>
</li>
<li class="dropdown-top"><a class="dropdown-top" href="<?=DOMAIN?>/pharmacy">��������</a></li>
<li class="dropdown-top"><a class="dropdown-top" href="<?=DOMAIN?>/infrastruktura">��������������</a>
<ul class="dropdown-inside">
<li><a href="<?=DOMAIN?>/avito">������ �������</a></li>
<li><a href="<?=DOMAIN?>/roads">������ �����</a></li>
<li><a href="<?=DOMAIN?>/infrastruktura">�����������</a></li>
<li><a href="<?=DOMAIN?>/video">�����������</a></li>
</ul>
</li>
<li class="dropdown-top"><a class="dropdown-top" href="<?=DOMAIN?>/zkh">���</a></li>
<li class="dropdown-top"><a class="dropdown-top" href="<?=DOMAIN?>/opendata">�������� ������</a></li>
</ul>
</td>
</tr>
</table>
</div>
<div class="header_img" style="background-image: url('img/zkh.jpg') !important;">
</div>
<table class="content">
<tr>
<td>
<?=$content?>
</td>
</tr>
</table>
<div id="footer">
<div class="footer_info">
<div>
<a href="http://te-st.ru" target="_blank">
<img src="<?=DOMAIN?>/img/te-st-logo.svg" width="100" class="te-st" /> 
</a>
</div>

<div>
<li>������</li>
<li><a href="<?=DOMAIN?>/budget">�����������</a></li>
<li><a href="<?=DOMAIN?>/budget">���������</a></li>
</div>
<div>
<li>�����������</li>
<li><a href="<?=DOMAIN?>/school">�����</a></li>
<li><a href="<?=DOMAIN?>/dou">������� ����</a></li>
<li><a href="<?=DOMAIN?>/udo">������/������</a></li>
</div>
<div>
<li>��������������</li>
<li><a href="<?=DOMAIN?>/avito">������ �������</a></li>
<li><a href="<?=DOMAIN?>/roads">������</a></li>
<li><a href="<?=DOMAIN?>/infrastruktura">������������</a></li>
<li><a href="<?=DOMAIN?>/video">�����������</a></li>
</div>
<div>
<li>���</li>
<li><a href="<?=DOMAIN?>/zkh">�����</a></li>
<li><a href="<?=DOMAIN?>/zkh">���� ���������</a></li>
</div>
<div>
<li>��������</li>
<li><a href="<?=DOMAIN?>/pharmacy">�������� ���������</a></li>
</div>

<div>
<!-- Yandex.Metrika informer--> 
<a href="https://metrika.yandex.ru/stat/?id=28722306&amp;from=informer"
target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/28722306/3_0_FFE820FF_F3C800FF_0_pageviews"
style="width:88px; height:31px; border:0;" alt="������.�������" title="������.�������: ������ �� ������� (���������, ������ � ���������� ����������)" /></a>
<!-- /Yandex.Metrika informer -->
<br /><br />
<div class="pluso" data-background="transparent" data-options="small,square,line,horizontal,nocounter,theme=04" data-services="vkontakte,odnoklassniki,facebook,twitter,google,moimir"></div>
</div>

</div>
</div>

<script type="text/javascript">(function() {
  if (window.pluso)if (typeof window.pluso.start == "function") return;
  if (window.ifpluso==undefined) { window.ifpluso = 1;
    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
    s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
    s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
    var h=d[g]('body')[0];
    h.appendChild(s);
  }})();</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter28722306 = new Ya.Metrika({id:28722306,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/28722306" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

</body>
</html>