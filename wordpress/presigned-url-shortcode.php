<?php
if (!class_exists('Aws\\S3\\S3Client')) {
  require_once 'aws-autoloader.php';
}

function generate_presigned_url_shortcode($atts)
{
  // Access from env
  $credentials = new Aws\Credentials\Credentials(
    AWS_ACCESS_KEY_ID,
    AWS_SECRET_ACCESS_KEY
  ); 
  

  $id = isset($atts['id']) ? $atts['id'] : '';

  $s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region' => 'ap-southeast-1', // Enter region
    'endpoint' => 'https://s3.ap-southeast-1.wasabisys.com', // Enter endpoint
    'credentials' => $credentials,
  ]);

  $bucket = 'my-bucket'; // bucket-name

  $cmd = $s3->getCommand('GetObject', [
    'Bucket' => $bucket,
     'Key' => $id, // id is passed through shortcode
  ]);

  $request = $s3->createPresignedRequest($cmd, '+10 minutes'); // set link expiry time

  $presignedUrl = (string) $request->getUri();

    
    return '<!DOCTYPE html>
              <html lang="en">
                <head>
                  <meta charset="UTF-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1.0">
                  <title>Wasabi presigend URL link</title>
               </head>
              
               <body>
                <a href="' . esc_url($presignedUrl) . '">Presigned Url</a>
                </body>
              </html>';
}

add_shortcode('presigned_url', 'generate_presigned_url_shortcode');