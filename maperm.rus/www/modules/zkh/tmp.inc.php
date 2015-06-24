<?if($isIndex!=true) exit(header('Location: /'));?>
<div class="mainpage" style="text-align: center;">
<p>
Карта износа жилого фонда Перми. Вы можете посмотреть дату очередного ремонта крыши и возраст постройки вашего дома. Очень интересно наблюдать, при каком президенте были построены эти дома.
</p>
</div>
<iframe width='100%' height='520' frameborder='0' src='https://mikegarbuzenko.cartodb.com/viz/290a79be-ba58-11e4-b8fa-0e4fddd5de28/embed_map' allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>
<div class="mainpage" style="text-align: center;">
<a href="<?=DOMAIN?>/modules/<?=$module?>/files/<?=$csv?>">Скачать CSV</a>
<span>Последнее обновление: <?=$date?> г.</span>
</div>
<br /><br />
Поиск по <a href="<?=DOMAIN?>/files/Programma_kapitalnogo_remonta_Permskogo_kraja.rtf">
перечню многоквартирных домов, расположенных на территории Пермского края, 
в которых необходимо осуществить капитальный ремонт 
</a>
<form action="" method="post" style="margin-top: 10px;">
<input type="text" name="address" placeholder="Введите адрес" autocomplete="off" />
<div id="address2" class="hidden"></div>
<input type="submit" name="search" value="Искать" />
</form>
<div id="search_result"></div>
<script type="text/javascript" src="<?=DOMAIN?>/modules/mainpage/js/mainpage.js"></script>