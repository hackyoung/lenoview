<?php $this->extend('child'); ?>
    <?php $this->startFragment('body', 'after'); ?>
        hello world
    <?php $this->endFragment(); ?>
<?php $this->parent->display(); ?>
