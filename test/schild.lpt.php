<?php
$h = [1,1,1,1,1,1,1,1,];
?>
<extend name="test.father">
    <fragment name="body">
        hello world
    </fragment>
    <llist name="{$h}" id="i">
        <view name="test.testjs" />
    </llist>
</extend>
