<extend name="child">
    <fragment name="body" type="after">
        hello world
        <?php $h = [1,2,3,4,5,6,6]; ?>
        <llist name="{$h}" id="i">
            <view name="testjs" />
        </llist>
    </fragment>
    <script leno-type="locale">
        console.log('child');
    </script>
</extend>
