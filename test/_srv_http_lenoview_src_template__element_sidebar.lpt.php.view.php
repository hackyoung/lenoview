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
<ul id="leno-navbar-<?php echo ($id ?? ''); ?>" class="leno-navbar">
    <?php $content = $content ?? []; foreach($content as $item) { ?>
        <li class="leno-navbar-item">
        <a href="<?php echo ($item["url"] ?? ''); ?>">
            <img src="<?php echo ($item["icon"] ?? ''); ?>" />
            <span><?php echo ($item["title"] ?? ''); ?></span>
        </a>
        <?php if(!empty($item["items"])) { ?>
            <?php $item_content = ['content' =>$item['items']]; ?>
            <?php $this->view('v', new \Leno\View('_element.sidebar', $item_content))->display(); ?>
        <?php } ?>
        </li>
    <?php } ?>
</ul>
<?php self::beginJsContent(''); ?>
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
})(<?php echo ($_GET["navbarid"] ?? ''); ?>);
<?php self::endJsContent(); ?>
<?php self::beginCssContent(); ?>
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
<?php self::endCssContent(); ?>
