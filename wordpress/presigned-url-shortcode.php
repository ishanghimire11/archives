custom-shortcode.php

<?php
if (!class_exists('Aws\\S3\\S3Client')) {
  require_once 'aws-sdk/aws-autoloader.php';
}

function generate_presigned_url_shortcode($atts)
{
  $credentials = new Aws\Credentials\Credentials(
    AWS_ACCESS_KEY_ID,
    AWS_SECRET_ACCESS_KEY
  );

  $id = isset($atts['id']) ? $atts['id'] : '';

  $s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region' => 'ap-southeast-1',
    'endpoint' => 'https://s3.ap-southeast-1.wasabisys.com',
    'credentials' => $credentials,
  ]);

  $bucket = 'eco-themes';

  $cmd = $s3->getCommand('GetObject', [
    'Bucket' => $bucket,
     'Key' => $id . '.zip',
  ]);

  $request = $s3->createPresignedRequest($cmd, '+10 minutes');

  $presignedUrl = (string) $request->getUri();

    
    return '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Theme Download Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f5f5f5;">
  <div class="container" style="max-width: 600px; margin: auto; padding: 20px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
    <img src="https://ecobees.net/wp-content/uploads/2024/05/site-icon.png" alt="EcoBees" style="display: block; width: 100%; border-radius: 10px 10px 0 0;">
    <h2 style="color: black; margin-top: 20px;">Theme Download Confirmation</h2>
    <p>Thank you for your interest in our WordPress theme! We\'re thrilled to confirm that the download link for the theme file is provided below:</p>
    <p><a href="' . esc_url($presignedUrl) . '" style="display: inline-block; padding: 10px 20px; background-color: #fdd150; color: #000; text-decoration: none; border-radius: 5px;">Download Theme</a></p>
    <br>
    <p>Please feel free to download the file at your convenience. Should you encounter any difficulties during the download process or have any questions regarding the theme\'s installation or features, don\'t hesitate to reach out to us.</p>
    <p>Additionally, if you require any support or customization to tailor the theme to your specific needs, our dedicated team is here to assist you. You can contact us through the following channel:</p>
    <p>Support: <a href="https://ecobees.net/products/eco-themes/support/" style="color: #007bff;">EcoBees theme support</a></p>
    <p>We value your business and are committed to providing you with exceptional service. Thank you for choosing our theme.</p>
  </div>
</body>
</html>';
}

add_shortcode('presigned_url', 'generate_presigned_url_shortcode');