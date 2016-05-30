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
<?php if(empty($level ?? null)) { ?>
    <?php $this->startFragment('top', 'replace'); ?>hello world<?php $this->endFragment(); ?>
<?php } ?>
<ul id="leno-navbar-<?php echo ($id ?? ''); ?>" class="leno-navbar">
    <?php $__number__ = 0; $content = $content ?? []; foreach($content as $item) { ?>
        <li class="leno-navbar-item" data-level="<?php echo ($level ?? ''); ?>-<?php echo ($__number__ ?? ''); ?>">
        <a href="<?php echo ($item["url"] ?? ''); ?>" title="<?php echo ($item["subtitle"] ?? ''); ?>">
            <img src="<?php echo ($item["icon"] ?? ''); ?>" />
            <span><?php echo ($item["title"] ?? ''); ?></span>
        </a>
        <?php if(!empty($item["items"])) { ?>
            <?php $item_content = ['level' => ($level ?? '').'-'.$__number__, 'content' =>$item['items']]; ?>
            <?php $this->view('v', new \Leno\View('_element.sidebar', $item_content))->display(); ?>
        <?php } ?>
        </li>
    <?php $__number__++; } ?>
</ul>
<?php self::beginJsContent(''); ?>
var navbar = (function(open) {
    var open = (open || '-0').split('-');
    var $node;
    var init = function(opts) {
        if(opts.id == null) {
            throw 'need id to init';
        }
        $node = $('#leno-navbar-'+opts.id);
        $node.find('li a').click(function() {
            var $p = $(this).parent();
            if($(this).next().hasClass('leno-navbar')) {
                $p.parent().find('>li').removeClass('open');
                $p.addClass('open');
                return false;
            }
            var url = $(this).attr('href');
            if(/\?/.test(url)) {
                url += '&navbarid='+$p.attr('data-level');
            } else {
                url += '?navbarid='+$p.attr('data-level');
            }
            window.location.href = url;
            return false;
        });
        var the_open = [];
        for(var i = 0; i < open.length; ++i) {
            the_open.push(open[i]);
            var selector = the_open.join('-');
            if(selector.length > 0) {
                $('[data-level='+the_open.join('-')+']').addClass('open');
            }
        }
        var selector = the_open.join('-');
        if(selector.length > 0) {
            $('[data-level='+the_open.join('-')+']').addClass('selected');
        }
    }
    return {init: init};
})('<?php echo ($_GET["navbarid"] ?? ''); ?>');
<?php self::endJsContent(); ?>
<?php self::beginCssContent(); ?>
.leno-navbar {
    background-color: #444;
    overflow: hidden;
    color: white;
    margin: 0px;
    padding: 0px;
    transition: all 0.2s ease-in;
    -moz-transition: all 0.2s ease-in;
    -webkit-transition: all 0.2s ease-in;
}

.leno-navbar a {
    color: #999;
    text-decoration: none;
    padding-left: 20px;
    width: 100%;
    height: 100%;
    display: inline-block;
}

.leno-navbar a:hover {
    background-color: #222;
}

.leno-navbar .leno-navbar-item.open>a {
    color: white;
}

.leno-navbar .leno-navbar-item {
    list-style: none;
    width: 100%;
    line-height: 40px;
}

.leno-navbar .leno-navbar-item .leno-navbar {
    margin-left: 20px;
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
