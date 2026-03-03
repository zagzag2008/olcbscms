<!DOCTYPE html>
<html lang="ru-ru">
<head>
	<base href="https://saam.ol-cbs.ru">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Оленегорсковедение</title>
	<link rel="stylesheet" href="/css/bootstrap.min.css">
	<script src="/js/jquery-3.4.1.slim.min.js"></script>
	<!--script src="/js/popper.min.js"></script-->
	<script src="/js/bootstrap.min.js"></script>

	<script>
	$(document).ready(function() {

	});
	</script>
	<style>
BODY { background-color: #EEEEFF; }

.menu-main > LI > A { color: var(--white); font-size: 16pt; background-color: #1D7ABF; line-height: 18pt;}
.menu-main > LI > A:hover { background-color: #014BAC; }
.menu-main > LI > .menu-dropdown > LI > A { background-color: #1D7ABF; color: var(--white); }
.menu-main > LI > .menu-dropdown > LI > A:hover { background-color: #014BAC; }
.user-select-none { user-select: none; }
.footer { padding-top: 10px; }
.footer A { color: var(--yellow); }
.content iframe { width: 100%; height: auto; aspect-ratio: 1.77; }
.content::selection { color: var(--light); background-color: var(--primary); }
.button { padding: 3px; background-color: #666; color: #fff; }
a.button:hover { background-color: #333; color: #fff; text-decoration: none; }
textarea#page_editor { width: 100%; height: 700px; }

.card-group > .card { -ms-flex: 0 1 25%; flex: 0 1 25%; }

{page-css}
	</style>
</head>
<body>
<div class="container">
<?php if ($user->isAuth()): ?>
	<div class="row g-0 user-select-none">
		<div class="col-12 border p-0">
			Пользователь: <?php echo $user->user_login; ?>
		</div>
	</div>
<?php endif; ?>
	<div class="row g-0 user-select-none">
		<div class="col-12 p-0">
			<img class="w-100" src="/images/header_default.png" alt="Саамский свет">
		</div>
	</div>
<?php if (false && $cmd == 'home'): ?>
	<div class="row g-0 user-select-none">
		<div class="col-12 border p-0">
			{module-slider-main}
		</div>
	</div>
<?php endif; ?>
	<div class="row g-0 py-2 user-select-none">
		{module-menu-main}
	</div>
	<div class="row g-0 p-2 content bg-white">
<?php if ($user->isAuth() && $mode == 'view'): ?>
		<div class="col-12 p-0 user-select-none">
			{admin-breadcrumbs}
		</div>
<?php endif; ?>
		<div class="col-12">
			{debug}
			{content}
		</div>
	</div>
	<div class="row g-0 footer text-light bg-dark user-select-none">
		<div class="col-12">
			<P>
				Использование материалов разрешено при условии сохранения копирайта и наличии ссылки на сайт<BR>
				&copy; 2026 МУК "ЦБС", г. Оленегорск<BR>
				Контакты для связи: <A href="mailto:bibl@ol-cbs.ru?subject=Оленегорсковедение">bibl@ol-cbs.ru</A> телефон: <A href="tel:88155253784">8(81552)53784</A>
			</P>
		</div>
	</div>
</div>
</body>
</html>