<?php
//$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
$chars = '0123456789';
$length = 6;
$code = substr(str_shuffle($chars), 0, $length);

$_SESSION['captcha'] =  crypt($code, '$1$itchief$7');

$image = imagecreatefrompng(__DIR__ . '/captcha/bg.png');
$size = 36;
$color = imagecolorallocate($image, 0, 0, 0);
$font = __DIR__ . '/captcha/ARIALN.TTF';
$angle = rand(-7, 7);
$x = 30;
$y = 45 + $angle * 2;
imagefttext($image, $size, $angle, $x, $y, $color, $font, $code);
header('Cache-Control: no-store, must-revalidate');
header('Expires: 0');
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);