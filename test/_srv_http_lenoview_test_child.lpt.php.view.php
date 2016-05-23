<?php $this->extend('father'); ?>
	<?php $this->startFragment('body', 'replace'); ?>
        childs get
	<?php $this->endFragment(); ?>
<?php $this->parent->display(); ?>
