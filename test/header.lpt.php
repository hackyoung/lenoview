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
    ],
];
?>
<extend name="leno._layout.default">
    <fragment name="head" type="after">
        <script src="http://code.jquery.com/jquery-2.2.4.min.js"></script>
    </fragment>
	<fragment name="body">
        <view name="leno._element.header" data="{$content}" />
        <div style="height: 1000px">
        </div>
        <style>
            body {
                margin: 0px;
                padding: 0px;
            }
        </style>
	</fragment>
</extend>
