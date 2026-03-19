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
	<script src="/js/marked.umd.js"></script>

	<!-- {system} вынести потом в плагины? в шаблоне явно не к месту -->
	<script>
	// обработчик markdown для отображения страницы и просмотрщика в редакторе
	// добавлены обработчики совственных markdown блоков
	var customMarkdownParser = function (text) {
		// Рендер карточек ::: card (+3 строки следом: заголовок, ссылка, картинка)
		text = text.replace(/^:::?\s*card\s*\n([^\n]*?)\n([^\n]*?)\n([^\n]*?)\n$/gm, function (match, title, link, img, offset, string) {
			if (img.startsWith('/')) img = img.substr(1);
			if (img.split('/')[0] == 'images') {
				img = img.split('/').slice(1).join('/');
			}
			return '<div class="card w-25"><A href="'+link+'"><img class="card-img-top" src="'+img+'" alt="'+title+'"><div class="card-body"><p class="card-text">'+title+'</p></div></A></div>';
		});

		var renderer = new marked.Renderer();

		// iframe preview
		// Переопределяем обработку HTML-тегов
/*		renderer.html = function(html) {
			console.log(html);
			 // Находим все iframe в HTML
			 const iframeRegex = /<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi;
	 
			 html.raw = html.raw.replace(iframeRegex, function(iframeTag) {
				  // Добавляем data-processed атрибут для отслеживания
				  if (mode == 'view') {
					return iframeTag.replace('<iframe', '<iframe data-processed="true"');
				  } else {
					return '<B>{IFRAME}</B>';
				  }
			 });
			 
			 return html.raw;
		};
*/

		// Для обработки через html() - перехватываем сырой HTML
		renderer.html = function(html) {
			if (mode == 'edit') {
				if (html.raw.indexOf('<iframe') != -1) return `<svg width="100%" style="aspect-ratio: 16 / 9; border: 1px solid #ccc;">
  <rect width="100%" height="100%" fill="#f0f0f0" stroke="#333" stroke-width="2"/>
  <text x="150" y="55" font-family="Arial, sans-serif" font-size="32" font-weight="bold" 
        fill="#333" text-anchor="middle" dominant-baseline="middle">iframe</text>
</svg>`;
				if (html.raw.indexOf('<\/iframe') != -1) return '';
			}
		  //return html.raw.replace(/<iframe/g, '<iframe data-custom="true"');
		  return html.raw;
		};


		// Рендер картинок с указанием размеров =ШИРИНАxBЫСОТА
		renderer.image = function(img) {
			//console.log(img);
			var width = '', height = '';
			if (img.title && img.title.startsWith('=')) {
				var dims = img.title.slice(1).split('x').map(function(v) { return v.trim(); }).filter(Boolean);
				if (dims[0] > 0) width = ' width="' + dims[0] + '"';
				if (dims[1] > 0) height = ' height="' + dims[1] + '"';
			}

			// обрабатываем пути
			if (img.href.indexOf('/') == -1) {
				// #### локальный путь, указано только имя картинки. подставляем папку материала
				const path = window.location.pathname.split('/');
				if (mode != 'view') path.pop();
				img.href = '/pages' + path.join('/') + '.images/' + img.href;
			} else {
				// #### пока оставляем без изменений. ну случай если путь указан абсолютный или относительный
			}
			return '<img src=\"' + img.href + '\" alt=\"' + img.text + '\"' + width + height + (img.title ? ' title="' + img.text + '"' : '') + '>';
		};
		return marked.parse(text, { renderer: renderer });
	}
	
	// редактор. функция отправки страницы
	function page_save(editor) {
		editor.codemirror.setOption("readOnly", true);
		var markdown = editor.value();

		// AJAX запрос
		var xhr = new XMLHttpRequest();
		var sef = window.location.pathname.split('/');
		sef[sef.length - 1] = 'save';
		xhr.open('POST', sef.join('/'), true); 
		xhr.responseType = 'json';
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) editor.codemirror.setOption("readOnly", false);
			
		};
		xhr.onload = function() {
			if (xhr.status === 200) {
				var data = xhr.response;
				var sef = window.location.pathname.split('/');
				sef.pop();
				if (data.status == 'success') window.location.href = sef.join('/'); 
			}
		}

		xhr.send(JSON.stringify({markdown: markdown}));
	}
	</script>
	<!-- {/system} -->
	<style>
BODY { /*background-color: #EEEEFF;*/ }
#bg-main { background-image: url('/images/bg.jpg'); background-size: cover; background-repeat: no-repeat; background-position: top; }
.menu-main { background-color: #588CD3; border-top: 1px solid var(--white); }
.menu-main > LI > A { color: var(--white); font-size: 16pt; background-color: #3D5F96; line-height: 18pt;}
.menu-main > LI > A:hover { background-color: #014BAC; }
.menu-main > LI > .menu-dropdown > LI > A { background-color: #3D5F96; color: var(--white); }
.menu-main > LI > .menu-dropdown > LI > A:hover { background-color: #014BAC; }
.user-select-none { user-select: none; }
.footer { padding-top: 10px; }
.footer A { color: var(--yellow); }

.content table { width: 100%; }
.content iframe { width: 100%; height: auto; aspect-ratio: 16/9; }
.content::selection { color: var(--light); background-color: var(--primary); }
.content P { font-size: 18pt; text-indent: 20px; text-align: justify; }

.button { padding: 3px; background-color: #666; color: #fff; }
a.button:hover { background-color: #333; color: #fff; text-decoration: none; }
textarea#page_editor { width: 100%; height: 700px; }

.card-group > .card { -ms-flex: 0 1 25%; flex: 0 1 25%; }
button.table { width: unset; color: unset; }

img.float-left, img[src*="#left"], img[alt$="<"] {
    float: left;
    margin: 0 15px 15px 0;
    max-width: 40%;
    height: auto;
}
img.float-right, img[src*="#right"], img[alt$=">"] {
    float: right;
    margin: 0 0 15px 15px;
    max-width: 40%;
    height: auto;
}
{style}
	</style>
</head>
<body>
<div class="container">
<?php if ($user->isAuth()): ?>
	<div class="row g-0 user-select-none">
		<div class="col-12 border p-0">
			Пользователь: <?php echo $user->user_login; ?> <A href="/logout">Выход</A>
		</div>
	</div>
<?php endif; ?>
	<div class="row g-0 user-select-none">
		<div class="col-12 p-0">
			<A href="<?php echo $controller->base_path; ?>/"><img class="w-100" src="/images/header_default.png" alt="Саамский свет"></A>
		</div>
	</div>
<?php /*if ($controller->path == '/' && $controller->page == 'index'): ?>
	<div class="row g-0 user-select-none">
		<div class="col-12 border p-0">
			<img class="w-100" src="/images/home.png" alt="Страницы саамской культуры">
		</div>
	</div>
<?php endif;*/ ?>
	<div class="row g-0 p-0 user-select-none">
		<?php if ($user->isAuth()): ?><A href="/menu-top/edit">Правка меню</A><?php endif; ?>{MenuTop}
	</div>
	<div class="row g-0 p-2 content bg-white">
<?php if ($user->isAuth() && $controller->mode == 'view'): ?>
		<div class="col-12 p-0 user-select-none">
			<A href="<?php echo($controller->path . '' . $controller->page); ?>/edit">Правка</A>
		</div>
<?php endif; ?>
		<div class="col-12">
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