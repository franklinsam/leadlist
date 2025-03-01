const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

// Ensure the output directories exist
const outputDir = path.join(__dirname, '.vercel', 'output');
const functionsDir = path.join(outputDir, 'functions', 'api', 'index.func');
const staticDir = path.join(outputDir, 'static');

if (!fs.existsSync(functionsDir)) {
  fs.mkdirSync(functionsDir, { recursive: true });
}

if (!fs.existsSync(staticDir)) {
  fs.mkdirSync(staticDir, { recursive: true });
}

// Use the Vercel-specific composer.json file
console.log('Using Vercel-specific composer.json file...');
if (fs.existsSync('vercel-composer.json')) {
  fs.copyFileSync('vercel-composer.json', 'composer.json');
}

// Run composer install
console.log('Installing PHP dependencies...');
execSync('composer install --no-dev --optimize-autoloader', { stdio: 'inherit' });

// Run npm install and build
console.log('Installing and building frontend assets...');
execSync('npm ci && npm run build', { stdio: 'inherit' });

// Copy public files to static output
console.log('Copying public files to static output...');
execSync(`cp -r public/* ${staticDir}/`, { stdio: 'inherit' });

// Create the PHP function
console.log('Creating PHP function...');
fs.writeFileSync(
  path.join(functionsDir, 'index.php'),
  `<?php
// Set custom library paths to help find OpenSSL
putenv('LD_LIBRARY_PATH=/var/task/lib:/var/task/lib/php/extensions/no-debug-non-zts-20190902');

// Disable SSL verification for curl
if (!defined('CURL_SSLVERSION_TLSv1_2')) {
    define('CURL_SSLVERSION_TLSv1_2', 6);
}

// Set default curl options
$defaultCurlOptions = [
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2
];

// Forward Vercel requests to normal index.php
require __DIR__ . '/../../../../public/index.php';`
);

// Create the VC config
console.log('Creating VC config...');
fs.writeFileSync(
  path.join(functionsDir, '.vc-config.json'),
  JSON.stringify({
    runtime: 'vercel-php@0.4.0',
    handler: 'index.php',
    launcherType: 'Nodejs',
    regions: ['all'],
    memory: 1024,
    maxDuration: 60,
    environment: {
      LD_LIBRARY_PATH: '/var/task/lib:/var/task/lib/php/extensions/no-debug-non-zts-20190902'
    }
  }, null, 2)
);

// Create the output config
console.log('Creating output config...');
fs.writeFileSync(
  path.join(outputDir, 'config.json'),
  JSON.stringify({
    version: 3,
    routes: [
      { src: '/build/(.*)', dest: '/build/$1' },
      { src: '/favicon.ico', dest: '/favicon.ico' },
      { src: '/robots.txt', dest: '/robots.txt' },
      { src: '/(css|js|images|fonts|vendor|storage)/(.*)', dest: '/$1/$2' },
      { src: '/(.*)', dest: '/api/index.php' }
    ],
    env: {
      APP_ENV: 'production',
      APP_DEBUG: 'false',
      LD_LIBRARY_PATH: '/var/task/lib:/var/task/lib/php/extensions/no-debug-non-zts-20190902'
    }
  }, null, 2)
);

console.log('Build completed successfully!'); 