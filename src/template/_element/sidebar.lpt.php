<?php
/**
 * @lpt_name _element.sidebar
 * @param content [
 *      [
 *          'url' => '',
 *          'icon' => '',
 *          'title' => '',
 *          'subtitle' => '',
 *          'items' => [
 *
 *          ]
 *      ]
 * ]
 */
?>
<ul id="leno-navbar-{$id}" class="leno-navbar">
    <llist name="{$content}" id="item">
        <li class="leno-navbar-item">
        <a href="{$item.url}" title="{$item.subtitle}">
            <img src="{$item.icon}" />
            <span>{$item.title}</span>
        </a>
        <notempty name="{$item.items}">
            <?php $item_content = ['content' =>$item['items']]; ?>
            <view name="_element.sidebar" data="{$item_content}" />
        </notempty>
        </li>
    </llist>
</ul>
<script>
var navbar = (function(open) {

    var open = open || 0;

    console.log(open);
    var $node;

    var init = function(opts) {
        if(opts.id == null) {
            throw 'need id to init';
        }
        $node = $('#leno-navbar-'+opts.id);
        $node.find('li a').click(function() {
            if($(this).next().hasClass('leno-navbar')) {
                var $p = $(this).parent().parent();
                $p.find('>li').removeClass('open');
                $(this).parent().addClass('open');
                return false;
            }
            var url = $(this).attr('href');
            window.location.href = $(this).attr('href');
        });
        var $li = $($node.find('>li').get(open));
        if($li.length > 0) {
            $li.addClass('open');
        }
    }
    return {init: init};
})({$_GET.navbarid});
</script>
<style>
.leno-navbar {
    background-color: #444;
    color: white;
    transition: all 0.2s ease-in;
    -moz-transition: all 0.2s ease-in;
    -webkit-transition: all 0.2s ease-in;
}

.leno-navbar a {
    color: white;
    text-decoration: none;
    width: 100%;
    height: 100%;
    display: block;
}

.leno-navbar a:hover {
    background-color: #222;
}

.leno-navbar .leno-navbar-item {
    list-style: none;
    width: 100%;
    line-height: 40px;
}

.leno-navbar .leno-navbar-item img {
    vertical-align: middle;
}

.leno-navbar .leno-navbar-item .leno-navbar {
    display: none;
}

.leno-navbar .leno-navbar-item.open .leno-navbar {
    display: block;
}
</style>
