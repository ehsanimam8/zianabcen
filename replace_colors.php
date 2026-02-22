<?php

$files = [
    'resources/views/components/layouts/app.blade.php',
    'resources/views/livewire/student/dashboard.blade.php',
    'resources/views/livewire/student/lms/course-viewer.blade.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('emerald', 'primary', $content);
        $content = str_replace('teal', 'primary', $content);
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
