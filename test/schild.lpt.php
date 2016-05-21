<extend name="hello_world">
    <fragment name="hello" />
    <fragment name="hello">
        <notempty name=":hello.world($hello, $world)">
        </notempty>
        <empty name=":hello.world($hello, $world)">
            {$hello.world}
            {:hello.world($hello)}
            <llist id="hello" name="$hello.hello">
            </llist>
        </empty>
        <in name="$hello" value="$world">
        </in>
        <nin name="hello" value=":world.w()">
        </nin>
        <neq name="$hello" value="$world">
        </neq>
        <eq name=":hello.h()" value="$world">
        </eq>
        <view name="hello" data="$hello.world" extend_data='true' />
    </fragment>
    <fragment name="world" value="">
    </fragment>
</extend>
