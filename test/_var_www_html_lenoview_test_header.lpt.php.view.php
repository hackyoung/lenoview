<?php
$content = [
    'user' => [
        'home' => '',
        'portrait' => 'https://avatars1.githubusercontent.com/u/3030341?v=3&s=460'
    ],
    'menu' => [
        [
            'id' => 'test_1',
            'title' => '任务',
            'href' => ''
        ], [
            'id' => 'test_1',
            'title' => '工人',
            'href' => ''
        ], [
            'id' => 'test_1',
            'title' => '文章',
            'href' => ''
        ], [
            'id' => 'test_1',
            'title' => '个人信息',
            'href' => ''
        ], [
            'id' => 'test_1',
            'title' => '设置',
            'href' => ''
        ],
    ]
];
?>
<?php $this->extend('_layout.default'); ?>
    <?php $this->startFragment('head', 'after'); ?>
        <?php self::beginJsContent('http://code.jquery.com/jquery-2.2.4.min.js'); ?><?php self::endJsContent(); ?>
        <?php self::beginJsContent('http://localhost/test/public/lib/leno/js/leno.js'); ?><?php self::endJsContent(); ?>
        <link href="http://localhost/test/public/lib/leno/css/leno.css" />
    <?php $this->endFragment(); ?>
	<?php $this->startFragment('body', 'replace'); ?>
        <?php $this->view('v', new \Leno\View('_element.header', $content))->display(); ?>
        <div style="height: 1000px">
        </div>
        <?php self::beginCssContent(); ?>
            body {
                margin: 0px;
                padding: 0px;
            }
        <?php self::endCssContent(); ?>
	<?php $this->endFragment(); ?>
<?php $this->parent->display(); ?>
