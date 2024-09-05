<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0" />
        <meta name="robots" content="noindex, nofollow" />
        <meta name="googlebot" content="noindex">
        <title><?php echo esc_html('AutoQA - '.$data['title']); ?></title>
        <style>
            body, html {
                margin: 0;
                padding: 0;
                overflow-y: hidden;
            }
            iframe.cauto-runner-area-frame {
                width: 100%;
                height: 100vh;
                border: 0;
                background-color: #CCC;
            }
        </style>
    </head>
    <body>
        <?php if ($data['url']): ?>
        <iframe src="<?php echo esc_attr($data['url']); ?>" data-flow-id="<?php echo esc_attr($data['flow_id']); ?>" data-runner-id="<?php echo esc_attr($data['runner_id']); ?>" class="cauto-runner-area-frame"></iframe>
        <?php endif; ?>
    </body>
</html>