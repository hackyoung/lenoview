<extend name="Layout.default">
	<fragment name="body">
		这是schild提供的
		<div>{$div}</div>
        <eq name="hello" value="world" const="true">
            hello world
        </eq>
        <ul>
            <llist name="hello" id="h">
                <in name="$h" value="hello">
                    in
                </in>
                <nin name="$h" value="hello">
                       hhh
                </nin>
                <eq name="h" value="3" const="true">
                    <li>三</li>
                </eq>
                <neq name="h" value="3" const="true">
                    <li>{$h}</li>
                </neq>
            </llist>
        </ul>
	</fragment>
</extend>
