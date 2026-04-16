<?php
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "dirname(SCRIPT_NAME): " . dirname($_SERVER['SCRIPT_NAME']) . "\n";

function debug_url($path = '')
{
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $basePath = dirname($scriptName);
    
    echo "Initial basePath: '$basePath'\n";
    
    if ($basePath === '/' || $basePath === '\\') {
        $basePath = '';
    } else {
        $basePath = rtrim($basePath, '/\\');
    }
    
    echo "Processed basePath: '$basePath'\n";

    return $basePath . '/' . ltrim($path, '/');
}

echo "url('/menu'): " . debug_url('/menu') . "\n";
