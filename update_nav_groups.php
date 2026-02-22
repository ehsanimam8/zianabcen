<?php

$resourcesPath = __DIR__ . '/app/Filament/Resources';

$groups = [
    'CMS' => 'Content Management (CMS)',
    'LMS' => 'Learning Management (LMS)',
    'SIS' => 'Student Information (SIS)'
];

function updateResourceGroup($file, $groupName) {
    if (is_dir($file)) return;
    
    $content = file_get_contents($file);
    if (!str_contains($content, 'class ') || !str_contains($content, 'extends Resource')) return;

    // Remove any defined property
    $content = preg_replace('/protected static \??(?:string\|BackedEnum\|null|string|\\\\?UnitEnum\|string\|null)? \$navigationGroup = \'.*?\';/', '', $content);
    
    // Add getNavigationGroup method if it doesn't exist
    if (!str_contains($content, 'function getNavigationGroup')) {
        $method = "
    public static function getNavigationGroup(): ?string
    {
        return '{$groupName}';
    }
";
        $content = preg_replace('/(protected static string\|BackedEnum\|null \$navigationIcon = .*?;|protected static \?string \$navigationIcon = .*?;)/', "$1\n{$method}", $content);
    } else {
        // Replace existing getNavigationGroup body
        $content = preg_replace('/public static function getNavigationGroup\(\): \?string\s*\{\s*return \'.*?\';\s*\}/', "public static function getNavigationGroup(): ?string\n    {\n        return '{$groupName}';\n    }", $content);
    }
    
    file_put_contents($file, $content);
}

foreach ($groups as $dir => $groupName) {
    $dirPath = $resourcesPath . '/' . $dir;
    if (!is_dir($dirPath)) continue;

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath));
    foreach ($iterator as $file) {
        if ($file->isFile() && str_ends_with($file->getFilename(), 'Resource.php')) {
            updateResourceGroup($file->getPathname(), $groupName);
        }
    }
}

echo "Navigation groups updated again.\n";
