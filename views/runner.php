<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0" />
<meta name="robots" content="noindex, nofollow" />
<meta name="googlebot" content="noindex">
<title><?php esc_html_e('AutoQA - Flow 1', 'codecorun-test-automation'); ?></title>
<style>
body, html {
    margin: 0;
}
iframe.cauto-runner-area-frame {
    width: 100%;
    height: calc(100vh - 7px);
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
<footer>
</footer>
</html>