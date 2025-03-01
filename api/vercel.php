<?php

// This file is used to configure the PHP runtime for Vercel

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 'Off');
ini_set('log_errors', 'On');
ini_set('error_log', '/tmp/php_errors.log');

// Set timezone
date_default_timezone_set('UTC');

// Set memory limit
ini_set('memory_limit', '512M');

// Set upload limits
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');
ini_set('max_execution_time', '60');

// Forward to the main application
require __DIR__ . '/../public/index.php';