<!doctype html>
<html>
    <head>
        <?php $this->startFragment('head', 'replace'); ?>
            <?php $this->view('v', new \Leno\View('_element.head', $__head__))->display(); ?>
        <?php $this->endFragment(); ?>
    </head>
    <body>
        <?php $this->startFragment('body', 'replace'); ?>
        <?php $this->endFragment(); ?>
        <?php $this->startFragment('js', 'replace'); ?>
        <?php $this->endFragment(); ?>
        <?php $this->startFragment('css', 'replace'); ?>
        <?php $this->endFragment(); ?>
    </body>
</html>
