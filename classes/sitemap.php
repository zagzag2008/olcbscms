<?php
$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;
$urlset = $dom->createElement('urlset');
$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
$dom->appendChild($urlset);

// Добавление URL из БД (пример)
$dbh = new PDO('mysql:host=localhost;dbname=your_db', 'user', 'pass');
foreach ($dbh->query('SELECT id, date_edit FROM articles') as $row) {
    $url = $dom->createElement('url');
    $loc = $dom->createElement('loc', 'https://example.com/articles/' . $row['id'] . '.html');
    $url->appendChild($loc);
    // Добавьте <lastmod> и <priority> аналогично
    $urlset->appendChild($url);
}

$dom->save('sitemap.xml');  // Или echo $dom->saveXML();
?>