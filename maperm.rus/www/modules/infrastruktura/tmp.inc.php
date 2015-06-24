<?if($isIndex!=true) exit(header('Location: /'));?>
<div class="mainpage" style="text-align: center;">
<p>
Цены на новостройки нанесены на карту, таким образом Вы сможете оценить по критериям цена/расположения вариант квартиры на карте и сделать правильный выбор.
</p>
</div>
<script type="text/javascript">layers.add(<?=$layer?>);</script>
<div id="map" style="width: 100%; height: 700px;"></div>
<div class="mainpage" style="text-align: center;">
<a href="<?=DOMAIN?>/modules/<?=$module?>/files/<?=$csv?>">Скачать CSV</a>
<span>Источник: <a href="http://an-rakurs.ru" target="blank">an-rakurs.ru</a></span>
<span>Последнее обновление: <?=$date?> г.</span>
</div>