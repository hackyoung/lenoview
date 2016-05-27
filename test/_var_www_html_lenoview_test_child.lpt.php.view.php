<?php $this->extend('_layout.default'); ?>
	<?php $this->startFragment('body', 'replace'); ?>
        childs get
	<?php $this->endFragment(); ?>
    <script src="hjfkal"><?php self::endJsContent(); ?>
    <?php self::beginJsContent(); ?>
        console.log('hello world');
    <?php self::endJsContent(); ?>
    <?php self::beginCssContent(); ?>
        .main {
            fjakl
        }
    <?php self::endCssContent(); ?>
<?php $this->parent->display(); ?>
