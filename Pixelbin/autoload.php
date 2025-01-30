<?php
// autoload.php
require(__DIR__ . "/Common/Constants.php");

spl_autoload_register(function ($class) {
    // Define the base directory for your project
    $baseDir = __DIR__ . "/";

    // Namespace prefix for your classes
    $prefix = 'Pixelbin\\';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relativeClass = substr($class, $len);

    if (str_contains($class, "Enum")) {
        $relativeClass = "Platform\\Enums";

        // Replace namespace separators with directory separators
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        // If the file exists, require it
        if (file_exists($file)) {
            require_once $file;
        }
    } else {
        // Replace namespace separators with directory separators
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        // If the file exists, require it
        if (file_exists($file)) {
            require $file;
        }
    }
});
