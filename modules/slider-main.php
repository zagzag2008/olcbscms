<!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" type="text/css" /-->
<link rel="stylesheet" href="/css/slick.min.css" type="text/css">
<!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css" type="text/css" /-->
<link rel="stylesheet" href="/css/slick-theme.min.css" type="text/css">
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script-->
<script src="/js/slick.min.js"></script>
<?php
	$page_css[] =<<<EOL
.slider { position: relative; height: auto;}
.slider img { width: 100%; height: auto; }
EOL;
?>
<script>
$(document).ready(function() {
	$('.slider').slick({ autoplay: true, autoplaySpeed: 7000, arrows: false, dots: false, pauseOnHover: true});
});
</script>
<div class="slider">
	<div><a href="https://olit.ol-cbs.ru/" target="_blank" title=""><img src="/modules/slider-main/olit.jpg" loading="lazy" alt="Оленегорск литературный" /></a></div>
	<div><a href="https://veteran.ol-cbs.ru" target="_blank" title=""><img src="/modules/slider-main/veteran.jpg" loading="lazy" alt="Война в судьбах оленегорцев" /></a></div>
	<div><a href="https://olenegorskovedenie.ol-cbs.ru/index.php/elektronnye-kollektsii/gazeta-zapolyarnaya-ruda" target="_blank" title=""><img src="/modules/slider-main/zapruda.jpg" loading="lazy" alt="Газета Заполярная руда" /></a></div>
	<div><a href="https://inkarta.ol-cbs.ru/" target="_blank" title=""><img src="/modules/slider-main/inkarta.jpg" loading="lazy" alt="Интерактивная карта Оленегорска" /></a></div>
	<div><a href="https://olenegorsk.ol-cbs.ru/" target="_blank" title=""><img src="/modules/slider-main/olenegorsk.jpg" loading="lazy" alt="Оленегорск: люди, события, факты" /></a></div>
</div>