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
<extend name="_layout.default">
    <fragment name="head" type="after">
        <script src="http://code.jquery.com/jquery-2.2.4.min.js"></script>
    </fragment>
	<fragment name="body">
        <div class="sidebar-container">
            <view name="_element.sidebar" data="{$content}" />
        </div>
        <script>
            navbar.init({id: 'test'});
        </script>
        <style>
        .sidebar-container {
            display: inline-block;
            width: 400px;
        }
        </style>
	</fragment>
</extend>
