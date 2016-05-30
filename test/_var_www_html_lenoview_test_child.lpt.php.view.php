<?php
$content = [
    'id' => 'test',
    'content' => [
        [
            'url' => '',
            'icon' => '',
            'title' => '1',
            'subtitle' => 'test1',
            'items' => [
                [
                    'url' => '',
                    'title' => 'sub-1',
                    'items' => [
                        [
                            'url' => '',
                            'title' => 'sub-1',
                        ], [
                            'url' => '',
                            'title' => 'sub-2',
                        ], [
                            'url' => '',
                            'title' => 'sub-3',
                        ], [
                            'url' => '',
                            'title' => 'sub-4',
                        ]
                    ],
                ], [
                    'url' => '',
                    'title' => 'sub-2',
                ], [
                    'url' => '',
                    'title' => 'sub-3',
                ], [
                    'url' => '',
                    'title' => 'sub-4',
                ]
            ]
        ], [
            'url' => '',
            'icon' => '',
            'title' => '1',
            'subtitle' => 'test1',
            'items' => [
                [
                    'url' => '',
                    'title' => 'sub-1',
                ], [
                    'url' => '',
                    'title' => 'sub-2',
                ], [
                    'url' => '',
                    'title' => 'sub-3',
                ], [
                    'url' => '',
                    'title' => 'sub-4',
                ]
            ]
        ], [
            'url' => '',
            'icon' => '',
            'title' => '1',
            'subtitle' => 'test1',
            'items' => [
                [
                    'url' => '',
                    'title' => 'sub-1',
                ], [
                    'url' => '',
                    'title' => 'sub-2',
                ], [
                    'url' => '',
                    'title' => 'sub-3',
                ], [
                    'url' => '',
                    'title' => 'sub-4',
                ]
            ]
        ]
    ]
];
?>
<?php $this->extend('_layout.default'); ?>
    <?php $this->startFragment('head', 'after'); ?>
        <?php self::beginJsContent('http://code.jquery.com/jquery-2.2.4.min.js'); ?><?php self::endJsContent(); ?>
    <?php $this->endFragment(); ?>
	<?php $this->startFragment('body', 'replace'); ?>
        <div class="sidebar-container">
            <?php $this->view('v', new \Leno\View('_element.sidebar', $content))->display(); ?>
        </div>
        <?php self::beginJsContent(''); ?>
            navbar.init({id: 'test'});
        <?php self::endJsContent(); ?>
        <?php self::beginCssContent(); ?>
        .sidebar-container {
            display: inline-block;
            width: 400px;
        }
        <?php self::endCssContent(); ?>
	<?php $this->endFragment(); ?>
<?php $this->parent->display(); ?>
