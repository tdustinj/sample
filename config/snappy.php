<?php

if (env('APP_ENV') === "local") {
  $basePDFPath = 'vendor/profburial/wkhtmltopdf-binaries-osx/bin/wkhtmltopdf-amd64-osx';
  $baseImagePath = 'vendor/profburial/wkhtmltopdf-binaries-osx/bin/wkhtmltopdf-amd64-osx';
} else {
  $basePDFPath = 'vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64';
  $baseImagePath = 'vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64';
}

return array(
  'pdf' => array(
    'enabled' => true,
    'binary' => base_path($basePDFPath),
    'timeout' => false,
    'options' => array(),
    'env'     => array(),
  ),
  'image' => array(
    'enabled' => true,
    'binary' => base_path($baseImagePath),
    'timeout' => false,
    'options' => array(),
    'env'     => array(),
  ),
);
