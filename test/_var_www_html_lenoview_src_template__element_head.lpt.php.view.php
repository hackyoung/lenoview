<title><?php echo ($title ?? ''); ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo ($keywords ?? ''); ?>" />
<meta name='description' content="<?php echo ($description ?? ''); ?>" />
<meta name='author' content="<?php echo ($author ?? ''); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<?php $__number__ = 0; $js = $js ?? []; foreach($js as $j) { ?>
    <?php self::beginJsContent($j); ?><?php self::endJsContent(); ?>
<?php $__number__++; } ?>
<?php $__number__ = 0; $css = $css ?? []; foreach($css as $c) { ?>
    <link href="<?php echo ($c ?? ''); ?>" rel="stylesheet" type="text/css" />
<?php $__number__++; } ?>
