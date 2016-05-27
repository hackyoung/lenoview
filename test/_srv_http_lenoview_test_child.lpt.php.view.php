<?php $this->extend('father'); ?>
	<?php $this->startFragment('body', 'after'); ?>
        childs get
	<?php $this->endFragment(); ?>
<?php $this->parent->display(); ?>
