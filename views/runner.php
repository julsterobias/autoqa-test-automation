<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0" />
        <meta name="robots" content="noindex, nofollow" />
        <meta name="googlebot" content="noindex">
        <title><?php echo esc_html('AutoQA - '.$data['title']); ?></title>
        <?php wp_head(); ?>
    </head>
    <body>
        <?php if ($data['url']): ?>
        <iframe src="<?php echo esc_attr($data['url']); ?>" data-flow-id="<?php echo esc_attr($data['flow_id']); ?>" data-runner-id="<?php echo esc_attr($data['runner_id']); ?>" class="cauto-runner-area-frame"></iframe>
        <?php endif; ?>
    </body>
</html>