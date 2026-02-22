<?php

$file = 'resources/views/frontend/home.blade.php';
$content = file_get_contents($file);
$content = str_replace('[#5d0080]', 'primary-800', $content);
file_put_contents($file, $content);
echo "Replaced colors.\n";
