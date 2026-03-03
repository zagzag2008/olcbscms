<?php
	$page->add_style(<<<EOL
.menu-main { width: 100%; display: flex; justify-content: center; flex-wrap: wrap; flex-direction: row; margin: 0; list-style-type: none; margin-block-start: 0; margin-block-end: 0; padding-inline-start: 0; align-items: stretch; z-index: 2; }
.menu-main A:hover { text-decoration: none; }
.menu-main LI { list-style-type: none; }

.menu-main > LI { display: block; position: relative; margin: 3px; }
.menu-main > LI > A { display: block; padding: 5px; height: 100%; display: flex; align-items: center; text-align: center; }
.menu-main > LI > UL.menu-dropdown { display: none; position: absolute; flex-direction: column; list-style-type: none; margin-block-start: 0; margin-block-end: 0; padding-inline-start: 0; z-index: 1; }
.menu-main > LI:hover > UL.menu-dropdown { display: flex; background-color: #fff; width: max-content; min-width: 100%; }
.menu-main > LI > UL.menu-dropdown > LI {  }
.menu-main > LI > UL.menu-dropdown > LI > A { display: block; width: 100%; padding: 5px; }
    
EOL
);
?>
<UL class="menu-main user-select-none"><?php
	$menu_main = array(
		array('title' => 'Главная', 'url' => '#'),
		array('title' => 'О городе', 'url' => '#', 'items' => array(
			array('title' => 'Мой город', 'url' => '#'),
			array('title' => 'История', 'url' => '#'),
			array('title' => 'Прогулки по городу', 'url' => '#'),
			array('title' => 'Вспоминают старожилы', 'url' => '#'),
		)),
		array('title' => 'Краеведческая <br>база данных', 'url' => '/kray', 'items' => array (
			array('title' => 'ЭКМИ', 'url' => '/kray/ekmi')
		)),
		array('title' => 'Интернет <br>проекты', 'url' => '#', 'items' => array(
			array('title' => 'Война в судьбах Оленегорцев', 'url' => 'https://veteran.ol-cbs.ru/'),
			array('title' => 'Слово о земляках', 'url' => 'https://zemlyaki.ol-cbs.ru'),
			array('title' => 'Оленегорск: <br>Люди.События.Факты', 'url' => 'https://olenegorsk.ol-cbs.ru/'),
			array('title' => 'Оленегорск литературный', 'url' => 'https://olit.ol-cbs.ru/'),
			array('title' => 'Интерактивная карта', 'url' => 'https://inkarta.ol-cbs.ru'),
		)),
		array('title' => 'Наши <br>издания', 'url' => '#', 'items' => array(
			array('title' => 'О городе', 'url' => '/nashi-izdaniya/o-gorode')
		)),
		array('title' => 'Электронная <br>библиотека', 'url' => '#'),
		array('title' => 'Календари <br>памятных дат', 'url' => '#'),
		array('title' => 'Электронные <br>коллекции', 'url' => '#'),
		array('title' => 'Медиатека', 'url' => '/mediateka', 'items' => array (
			array('title' => 'Игры', 'url' => '/mediateka/igry'),
			array('title' => 'Викторины', 'url' => '/mediateka/viktoriny'),
			array('title' => 'Видео', 'url' => '/mediateka/video'),
			array('title' => 'Аудио', 'url' => '/mediateka/audio'),
		)),
	);
	
	foreach ($menu_main as $item) {
/*
		if (substr($item['url'], 0, 1) == '/') $item['url'] = "/$srp" . $item['url'];
		echo '<LI><A href="' . $item['url'] . '">' . $item['title'] . '</A>';
		if (isset($item['items']) && is_array($item['items'])) {
			echo '<UL class="menu-dropdown">';
			foreach ($item['items'] as $child) {
				if (substr($child['url'], 0, 1) == '/') $child['url'] = "/$srp" . $child['url'];
				echo '<LI><A href="' . $child['url'] . '">' . $child['title'] . '</A></LI>';
			}
			echo '</UL>';
		}
		echo '</LI>' . PHP_EOL;
*/
		//if (substr($item['url'], 0, 1) == '/') $item['url'] = "/$srp" . $item['url'];
		echo '<LI><A href="' . $item['url'] . '">' . $item['title'] . '</A>';
		if (isset($item['items']) && is_array($item['items'])) {
			echo '<UL class="menu-dropdown">';
			foreach ($item['items'] as $child) {
				if (substr($child['url'], 0, 1) == '/') $child['url'] = $child['url']; // "/$srp" . $child['url'];
				echo '<LI><A href="' . $child['url'] . '">' . $child['title'] . '</A></LI>';
			}
			echo '</UL>';
		}
		echo '</LI>' . PHP_EOL;
	}
?></UL>