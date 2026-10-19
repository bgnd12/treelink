<?php
$imgPath = 'C:/Users/akbar/.gemini/antigravity-ide/brain/14a22051-1a74-4f92-ae90-8fd1444c8fe6/.user_uploaded/media_1792222575804.png';
if (!file_exists($imgPath)) {
    die("File not found\n");
}

$src = imagecreatefrompng($imgPath);
$w = imagesx($src);
$h = imagesy($src);

// 1. Crop the entire left art (from x=0 to 580, y=0 to 525)
$leftArt = imagecrop($src, ['x' => 0, 'y' => 0, 'width' => 580, 'height' => 525]);
if ($leftArt) {
    imagepng($leftArt, __DIR__ . '/public/images/auth-left-art.png');
    imagedestroy($leftArt);
    echo "Saved auth-left-art.png\n";
}

// 2. Crop just the boy in the circle (approx center of circle in the 1024x525 image)
// Let's locate the boy circle in the image:
// The boy is approximately from x=170 to 410, y=90 to 330
$boyCircle = imagecrop($src, ['x' => 170, 'y' => 85, 'width' => 240, 'height' => 240]);
if ($boyCircle) {
    imagepng($boyCircle, __DIR__ . '/public/images/auth-boy-circle.png');
    imagedestroy($boyCircle);
    echo "Saved auth-boy-circle.png\n";
}

// 3. Let's also crop the background (dark green/navy leaves background)
// The background of the entire screen
$src = imagecreatefrompng($imgPath);
$rgb = imagecolorat($src, 20, 20);
$c = imagecolorsforindex($src, $rgb);
echo sprintf("Color top-left: #%02x%02x%02x\n", $c['red'], $c['green'], $c['blue']);
$rgb2 = imagecolorat($src, 500, 50);
$c2 = imagecolorsforindex($src, $rgb2);
echo sprintf("Color top-mid: #%02x%02x%02x\n", $c2['red'], $c2['green'], $c2['blue']);
$rgb3 = imagecolorat($src, 1000, 500);
$c3 = imagecolorsforindex($src, $rgb3);
echo sprintf("Color bottom-right: #%02x%02x%02x\n", $c3['red'], $c3['green'], $c3['blue']);
imagedestroy($src);
echo "Done!\n";
