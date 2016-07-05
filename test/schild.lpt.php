<?php
$h = [1,1,1,1,1,1,1,1,];
?>
<extend name="leno._layout.default">
    <fragment name="body">
        hello world
        <llist name="{$h}" id="i">
            <view name="test.testjs" />
        </llist>
    </fragment>
</extend>
