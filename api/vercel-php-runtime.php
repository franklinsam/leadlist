<?php

// Set custom library paths to help find OpenSSL
putenv('LD_LIBRARY_PATH=/var/task/lib:/var/task/lib/php/extensions/no-debug-non-zts-20210902');

// Load the main application
require __DIR__ . '/../public/index.php'; 