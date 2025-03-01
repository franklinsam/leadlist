<?php

// Use the bootstrap file to handle OpenSSL issues
require __DIR__ . '/bootstrap.php';

// Forward Vercel requests to normal index.php
require __DIR__ . '/../public/index.php'; 