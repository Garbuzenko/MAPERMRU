<?if($isIndex!=true) exit(header('Location: /'));?>
<div class="mainpage" style="text-align: center;">
<p>
����� ������ ������ ����� �����. �� ������ ���������� ���� ���������� ������� ����� � ������� ��������� ������ ����. ����� ��������� ���������, ��� ����� ���������� ���� ��������� ��� ����.
</p>
</div>
<iframe width='100%' height='520' frameborder='0' src='https://mikegarbuzenko.cartodb.com/viz/290a79be-ba58-11e4-b8fa-0e4fddd5de28/embed_map' allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>
<div class="mainpage" style="text-align: center;">
<a href="<?=DOMAIN?>/modules/<?=$module?>/files/<?=$csv?>">������� CSV</a>
<span>��������� ����������: <?=$date?> �.</span>
</div>
<br /><br />
����� �� <a href="<?=DOMAIN?>/files/Programma_kapitalnogo_remonta_Permskogo_kraja.rtf">
������� ��������������� �����, ������������� �� ���������� ��������� ����, 
� ������� ���������� ����������� ����������� ������ 
</a>
<form action="" method="post" style="margin-top: 10px;">
<input type="text" name="address" placeholder="������� �����" autocomplete="off" />
<div id="address2" class="hidden"></div>
<input type="submit" name="search" value="������" />
</form>
<div id="search_result"></div>
<script type="text/javascript" src="<?=DOMAIN?>/modules/mainpage/js/mainpage.js"></script>