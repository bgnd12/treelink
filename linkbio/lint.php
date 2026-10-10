<?php
foreach(glob(__DIR__.'/storage/framework/views/*.php') as $f) {
    exec('"C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe" -l ' . escapeshellarg($f), $out, $ret);
    if($ret !== 0) {
        echo "Error in $f\n";
        echo implode("\n", $out) . "\n";
    }
}
