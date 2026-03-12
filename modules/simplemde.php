<script src="/js/marked.umd.js"></script>
<!--link rel="stylesheet" href="/css/simplemde.css"-->
<!--script src="/js/simplemde.min.js"></script-->

<link rel="stylesheet" href="/npm/easymde/dist/easymde.min.css">
<script src="/npm/easymde/dist/easymde.min.js"></script>
<script src="/js/turndown.js"></script>
<script>

var customMarkdownParser = function (text) {
	// Рендер карточек ::: card
/*
::: card
заголовок
ссылка
картинка
*/
	text = text.replace(/^:::?\s*card\s*\n([^\n]*?)\n([^\n]*?)\n([^\n]*?)\n$/gm, function (match, title, link, img, offset, string) {
		if (img.startsWith('/')) img = img.substr(1);
		if (img.split('/')[0] == 'images') {
			img = img.split('/').slice(1).join('/');
		}
		return '<div class="card w-25"><A href="'+link+'"><img class="card-img-top" src="'+img+'" alt="'+title+'"><div class="card-body"><p class="card-text">'+title+'</p></div></A></div>';
	});
	
    var renderer = new marked.Renderer();

	// Рендер картинок с указанием размеров =ШИРИНАxBЫСОТА
	renderer.image = function(img) {
		var width = '', height = '';
		if (img.title && img.title.startsWith('=')) {
			var dims = img.title.slice(1).split('x').map(function(v) { return v.trim(); }).filter(Boolean);
			if (dims[0] > 0) width = ' width="' + dims[0] + '"';
			if (dims[1] > 0) height = ' height="' + dims[1] + '"';
		}

		// обрабатываем пути
		if (img.href.startsWith('/')) img.href = img.href.substr(1);
		if (img.href.split('/')[0] == 'images') {
			img.href = '/' + img.href;
		} else {
			//console.log(img.href.split('/'));
		}
		//console.log(img.href);
		return '<img src=\"' + img.href + '\" alt=\"' + img.text + '\"' + width + height + (img.title ? ' title="' + img.text + '"' : '') + '>';
	};
	return marked.parse(text, { renderer: renderer });
}

function page_save(editor) {
	//editor.codemirror.options.readOnly = true;
	editor.codemirror.setOption("readOnly", true);
	var markdown = editor.value();

	// URL для отправки материала
	const sef = window.location.pathname.split('/');
	sef.pop(); // удаляем команду edit
	if (sef[sef.length - 1] == 'index') { sef.pop(); } // удаляем index если есть
	
	// AJAX запрос
	var xhr = new XMLHttpRequest();
	xhr.open('POST', sef.join('/') + '/save', true); 
	xhr.responseType = 'json';
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) editor.codemirror.setOption("readOnly", false);
		
	};
	xhr.onload = function() {
		if (xhr.status === 200) {
			var data = xhr.response;
			if (data.status == 'success') window.location.href = sef.join('/'); 
		}
	}

	xhr.send(JSON.stringify({markdown: markdown}));
}

$(document).ready(function() {
// Turndown для Word мусора
var turndownService = new TurndownService({
  headingStyle: 'atx',
  bulletListMarker: '-',
  codeBlockStyle: 'fenced',
  emDelimiter: '*',
  strongDelimiter: '**',
  // Убираем лишние переносы
  blankReplacement: function(content, node) {
    return '';
  },
  // Правильные абзацы
  paragraph: {
    filter: 'p',
    replacement: function(content) {
      return '\n\n' + content + '\n\n';
    }
  }
});
	
//var simplemde = new SimpleMDE({
var simplemde = new EasyMDE({
	element: document.getElementById("page_editor"),
	forceSync: true,
	forcePasteAsPlainText: true,
	renderingConfig: {
		singleLineBreaks: true,
		codeSyntaxHighlighting: false,
	},
	status: false, /* statusbar */
	//status: ["autosave", "lines", "words", "cursor"], 
	//autosave: { enabled: true, uniqueId: "olcbsru", delay: 1000 },
	insertTexts: {
		horizontalRule: ["", "\n\n-----\n\n"],
		image: ["![](", " \"=0x150\")"],
		link: ["[", "](https://)"],
		table: ["", "\n\n| Заголовок 1 | Заголовок 2 | Заголовок 3 |\n| -------- | -------- | -------- |\n| Текст     | Текст      | Текст     |\n\n"],
	},
	previewRender: function(plainText) {
		return customMarkdownParser(plainText); // Returns HTML from a custom parser
	},
	showIcons: ["table","horizontal-rule"],
	spellChecker: false, /* русский не поддерживает? */
	/*styleSelectedText: false,*/
	/*lineWrapping: false,*/
	toolbarTips: false,
	toolbar: [ 
		{name: "page_save", action: page_save, className: "fa fa-save", title: "Сохранить" },
		"|", "bold", "italic", "heading", "|", "unordered-list", "ordered-list", "link", "image", "table", 
		{name: 'card', action: function(editor) { const cm = editor.codemirror; cm.replaceSelection('::: card\nТекст\nСсылка\nКартинка\n:::'); }, className: 'fa fa-clone', title: 'Вставить карточку'}, 
		"horizontal-rule", "|", "preview", "side-by-side", "fullscreen",
],
/*	autofocus: true,
	blockStyles: {
		bold: "__",
		italic: "_"
	},
	indentWithTabs: false,
	initialValue: "Hello world!",
	parsingConfig: {
		allowAtxHeaderWithoutSpace: true,
		strikethrough: false,
		underscoresBreakWords: true,
	},
	previewRender: function(plainText, preview) { // Async method
		setTimeout(function(){
			preview.innerHTML = customMarkdownParser(plainText);
		}, 250);

		return "Loading...";
	},
	shortcuts: {
		drawTable: "Cmd-Alt-T"
	},
	status: ["autosave", "lines", "words", "cursor", {
		className: "keystrokes",
		defaultValue: function(el) {
			this.keystrokes = 0;
			el.innerHTML = "0 Keystrokes";
		},
		onUpdate: function(el) {
			el.innerHTML = ++this.keystrokes + " Keystrokes";
		}
	}], // Another optional usage, with a custom status bar item that counts keystrokes
	tabSize: 4,
*/
}); // simplemde.config

// Обрабатываем вставку из Word
// Удаляем комментарии и Word-мусор ПЕРЕД Turndown
function cleanWordHtml(html) {
  // Удаляем HTML-комментарии
  html = html.replace(/<!--[\s\S]*?-->/g, '');
  html = html.replace(/<!.*?>/g, '');
  // Удаляем Word-стили и атрибуты
  //html = html.replace(/<span[^>]*>([^<]*)<\/span>/gi, '$1');
  html = html.replace(/(&nbsp;|\s+)/gi, ' ');
  html = html.replace(/style="[^"]*"/gi, '');
  html = html.replace(/class="[^"]*Mso[^"]*"/gi, '');
  // br → два переноса только внутри параграфов
//  html = html.replace(/<br[^>]*>/gi, '\n\n');
  return html;
}

// Paste обработчик
simplemde.codemirror.getWrapperElement().addEventListener('paste', function(event) {
  var htmlData = event.clipboardData.getData('text/html');
//  var textData = event.clipboardData.getData('text/plain');
  
  if (htmlData) {
    // Очистка Word мусора
    var cleanHtml = cleanWordHtml(htmlData);
    var markdown = turndownService.turndown(cleanHtml);
    
    // Финальная очистка Markdown
    markdown = markdown
      .replace(/\n{4,}/g, '\n\n')  // убираем лишние переносы
      .replace(/^\s+|\s+$/g, '')  // убираем пробелы по краям
		.replace(/\u00B7/g, '\u002a');
    
	simplemde.codemirror.replaceSelection(markdown);
	event.preventDefault();
	event.stopPropagation(); // Дополнительная защита
	return false;    
  }
}, true); // true - выполнять раньше других обработчиков

}); // dom.reaady

</script>