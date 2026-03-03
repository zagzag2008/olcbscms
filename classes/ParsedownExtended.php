<?php
require_once('Parsedown.php');

class ParsedownExtended extends Parsedown {
/*	public function getTextLevelElements() {
		return $this->textLevelElements;
	}
*/
    public function __construct()
    {
        $this->BlockTypes[':'][] = 'cardBlock';
    }

    protected function blockCardBlock($Line, $Block = null)
    {
        if (preg_match('/^:::?\s*card\s*$/', $Line['text'])) {
            return [
                'char' => $Line['text'][0],
                'identified' => true,
                'lines' => [],
                'count' => 0,
                'markup' => ''  // ← Будет заполнено в Complete
            ];
        }
    }

    protected function blockCardBlockContinue($Line, array $Block)
    {
        if (preg_match('/^:::?\s*$/', $Line['text'])) {
            $Block['closed'] = true;
            return $Block;
        }

        if ($Block['count'] < 3) {
            $Block['lines'][] = trim($Line['text']);
            $Block['count']++;
        }
        return $Block;
    }

    protected function blockCardBlockComplete($Block)
    {
        $title = isset($Block['lines'][0]) ? $Block['lines'][0] : '';
        $link  = isset($Block['lines'][1]) ? $Block['lines'][1] : '#';
        $img   = isset($Block['lines'][2]) ? $Block['lines'][2] : '';

        $html = sprintf(
            '<div class="card w-25"><a href="%s"><img class="card-img-top" src="%s" alt="%s"><div class="card-body"><p class="card-text">%s</p></div></a></div>',
            htmlspecialchars($link),
            htmlspecialchars($img),
            htmlspecialchars($title),
            htmlspecialchars($title)
        );

        $Block['markup'] = $html;  // ← ПРЯМО В markup!
        return $Block;
    }


	protected function inlineImage($Excerpt) {
		if (!isset($Excerpt['text'][1]) or $Excerpt['text'][1] !== '[') { return; }
		$Excerpt['text']= substr($Excerpt['text'], 1);
		$Link = $this->inlineLink($Excerpt);
		if ($Link === null) { return; }

		$Inline = array(
			'extent' => $Link['extent'] + 1,
			'element' => array(
				'name' => 'img',
				'attributes' => array(
					'src' => $Link['element']['attributes']['href'],
					'alt' => $Link['element']['handler']['argument'],
				),
				'autobreak' => true,
			),
		);
		$Inline['element']['attributes'] += $Link['element']['attributes'];
		unset($Inline['element']['attributes']['href']);

		// размер миниатюры указывается в поле title, например ![alt](/mediateka/audio/audiogid.jpg "220x")
		if (!isset($Inline['element']['attributes']['title'])) { return $Inline; }

		$size = $Inline['element']['attributes']['title'];
		if (preg_match('/(\d*)x(\d*)/', $size, $matches)) {
			if ($matches[1] > 0) $Inline['element']['attributes']['width'] = $matches[1];
			if ($matches[2] > 0) $Inline['element']['attributes']['height'] = $matches[2];
			unset ($Inline['element']['attributes']['title']);
		}
		return $Inline;
	}
}