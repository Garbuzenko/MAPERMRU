<?if($isIndex!=true) exit(header('Location: /'));?>
<div class="mainpage" style="text-align: center;">
<p>
���� �� ����������� �������� �� �����, ����� ������� �� ������� ������� �� ��������� ����/������������ ������� �������� �� ����� � ������� ���������� �����.
</p>
</div>
<script type="text/javascript">layers.add(<?=$layer?>);</script>
<div id="map" style="width: 100%; height: 700px;"></div>
<div class="mainpage" style="text-align: center;">
<a href="<?=DOMAIN?>/modules/<?=$module?>/files/<?=$csv?>">������� CSV</a>
<span>��������: <a href="http://an-rakurs.ru" target="blank">an-rakurs.ru</a></span>
<span>��������� ����������: <?=$date?> �.</span>
</div>