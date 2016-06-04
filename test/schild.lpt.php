<?php
$pager = [
    'current' => 4,
    'total' => 10
];
?>
<extend name="test.child">
    <fragment name="hello" type="after">hello world</fragment>
    <view name="leno._element.pager" data="{$pager}" />
</extend>
