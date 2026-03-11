<?php

namespace Plugin;

class MenuTop {
    private $html = '';
    public function __construct() {}

    public function onInit() {
        global $page;
        $page->style[] = <<<EOL
.menu-main { width: 100%; display: flex; justify-content: center; flex-wrap: wrap; flex-direction: row; margin: 0; list-style-type: none; margin-block-start: 0; margin-block-end: 0; padding-inline-start: 0; align-items: stretch; z-index: 2; }
.menu-main A:hover { text-decoration: none; }
.menu-main LI { list-style-type: none; }

.menu-main > LI { display: block; position: relative; margin: 3px; }
.menu-main > LI > A { display: block; padding: 5px; height: 100%; display: flex; align-items: center; text-align: center; }
.menu-main > LI > UL.menu-dropdown { display: none; position: absolute; flex-direction: column; list-style-type: none; margin-block-start: 0; margin-block-end: 0; padding-inline-start: 0; z-index: 1; }
.menu-main > LI:hover > UL.menu-dropdown { display: flex; background-color: #fff; width: max-content; min-width: 100%; }
.menu-main > LI > UL.menu-dropdown > LI {  }
.menu-main > LI > UL.menu-dropdown > LI > A { display: block; width: 100%; padding: 5px; }
EOL;

        $this->html = '<UL class="menu-main user-select-none">';

			$menu_file = realpath(dirname(__FILE__) . "/../pages/menu-top.md");
        if (file_exists($menu_file)) {
            $markdown = file_get_contents($menu_file);
            $parsed = $this->parseMarkdown($markdown);
            foreach ($parsed as $item) {
					$item['title'] = str_replace('/', '<br>', $item['title']);
                $this->html .= '<LI><A href="' . htmlspecialchars($item['url']) . '">' . $item['title'] . '</A>';
                if (isset($item['items']) && is_array($item['items'])) {
                    $this->html .= '<UL class="menu-dropdown">';
                    foreach ($item['items'] as $child) {
							  $child['title'] = str_replace('/', '<br>', $child['title']);
                        $this->html .= '<LI><A href="' . htmlspecialchars($child['url']) . '">' . $child['title'] . '</A></LI>';
                    }
                    $this->html .= '</UL>';
                }
                $this->html .= '</LI>';
            }
        }

        $this->html .= '</UL>';
    }

	private function parseMarkdown($markdown) {
        $lines = explode("\n", trim($markdown));
        $result = [];
        $current = null;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Топ-уровень: # Название (url)
            if (preg_match('/^# (.+?)(?:\s*\(([^)]+)\))?$/u', $line, $matches)) {
                if ($current !== null) {
                    $result[] = $current;
                }
                $title = trim($matches[1]);
                $url = isset($matches[2]) ? trim($matches[2]) : '#';
                $current = ['title' => $title, 'url' => $url];
                continue;
            }

            // Подпункт: ## Название (url) — только если есть текущий
            if ($current !== null && preg_match('/^## (.+?)(?:\s*\(([^)]+)\))?$/u', $line, $matches)) {
                $title = trim($matches[1]);
                $url = isset($matches[2]) ? trim($matches[2]) : '#';
                if (!isset($current['items'])) {
                    $current['items'] = [];
                }
                $current['items'][] = ['title' => $title, 'url' => $url];
            }
        }

        if ($current !== null) {
            $result[] = $current;
        }

        return $result;
    }

    public function onRender() {
        global $page;
        $page->template = preg_replace('/\{MenuTop\}/', $this->html, $page->template);
    }
}
