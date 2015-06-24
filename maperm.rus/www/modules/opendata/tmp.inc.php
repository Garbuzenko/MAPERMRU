<div class="mainpage">
<h2>Список открытых данных нашего сайта</h2>

<?if(count($opendata) > 0):?>
<?foreach($opendata as $o):?>
<p style="border-bottom: 1px solid #626262; padding-bottom: 10px;">
<strong><?=$o['title']?></strong>
<br />
<?=$o['desc']?>
<br />
Источник: <a href="<?=$o['source']?>" target="blank"><?=$o['source']?></a>
<br />
<?foreach($o['csv'] as $k=>$v):?>
<a href="<?=DOMAIN?>/modules/<?=$o['mod']?>/files/<?=$k?>">Скачать CSV</a>
<span>Последнее обновление: <?=$v?> г.</span>
<br />
<?endforeach;?>
</p>
<?endforeach;?>
<?endif;?>
</div>