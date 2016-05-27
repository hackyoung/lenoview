<?php $this->extend('child'); ?>
    <?php $this->startFragment('body', 'after'); ?>
        hello world
        <?php $h = [1,2,3,4,5,6,6]; ?>
        <?php $h = $h ?? []; foreach($h as $i) { ?>
            <?php $this->view('v', new \Leno\View('testjs'))->display(); ?>
        <?php } ?>
    <?php $this->endFragment(); ?>
    <?php self::beginJsContent(); ?>
        console.log('child');
    <?php self::endJsContent(); ?>
<?php $this->parent->display(); ?>
