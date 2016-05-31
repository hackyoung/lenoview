<?php
/**
 * @param [
 *      'user' => ['home' => '', 'portrait' => ''],
 *      'menu' => [
 *          [
 *              'id' => '',
 *              'title' => '',
 *              'href' => '',
 *          ]
 *      ]
 * ]
 */
?>
<header class="leno-global-header">
    <div>
        <a href="<?php echo ($user["home"] ?? ''); ?>">
            <img src="<?php echo ($user["portrait"] ?? ''); ?>" />
        </a>
        <ul class="menu">
            <?php $__number__ = 0; $menu = $menu ?? []; foreach($menu as $item) { ?>
                <li><a href="<?php echo ($item["href"] ?? ''); ?>"><?php echo ($item["title"] ?? ''); ?></a></li>
            <?php $__number__++; } ?>
        </ul>
    </div>
</header>
<?php self::beginCssContent(); ?>
.leno-global-header {
    width: 100%;
    height: 400px;
    background-image: url(http://pics.sc.chinaz.com/files/pic/pic9/201311/apic1843.jpg);
    background-repeat: no-repeat;
    background-size: 100%;
    background-position: center center;
    background-color: #444;
    box-shadow: 0px -50px 50px -50px rgba(0, 0, 0, 0.7) inset;
    -moz-box-shadow: 0px -50px 50px -50px rgba(0, 0, 0, 0.7) inset;
    -webkit-box-shadow: 0px -50px 50px -50px rgba(0, 0, 0, 0.7) inset;
    border-bottom: 1px solid #999;
    color: white;
}

.leno-global-header>div {
    position: absolute;
    top: 335px;
    line-height: 50px;
    padding-left: 30px;
    text-shadow: 0px 0px 3px rgba(0, 0, 0, 0.6);
}

.leno-global-header>div a {
    text-decoration: none;
}

.leno-global-header>div.fixed {
    position: fixed;
    top: 0px;
    width: 100%;
    padding-left: 120px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5);
    background-image: linear-gradient(top, #fff, #f0f0f0);
    background-image: -moz-linear-gradient(top, #fff, #f0f0f0);
    background-image: -webkit-linear-gradient(top, #fff, #f0f0f0);
    text-shadow: 0px 0px 3px rgba(255, 255, 255, 0.6);
}

.leno-global-header>div.fixed .menu li a {
    color: #444;
}

.leno-global-header>div>a>img {
    vertical-align: middle;
    width: 80px;
    height: 80px;
    border: 1px solid #999;
}

.leno-global-header ul.menu {
    padding: 0px;
    margin: 0px;
    display: inline-block;
}

.leno-global-header ul.menu li {
    height: 50px;
    padding: 0px 20px;
    list-style: none;
    display: inline-block;
}

.leno-global-header ul.menu li a {
    color: white;
    text-decoration: none;
}
<?php self::endCssContent(); ?>
<?php self::beginJsContent(''); ?>
var change_top = false;
$(window).scroll(function() {
    var $scroll_div = $('.leno-global-header>div');
    if(change_top) {
        $scroll_div.removeClass('fixed');
        $scroll_div.css('top', '319px');
        change_top = false;
    }
    var top = $(window).scrollTop();
    var div_top = parseInt($scroll_div.css('top'));
    var real_top = div_top - top;
    if(real_top >= 0) {
        var rate = (319 - real_top)/319;
        $scroll_div.css('top', 335 - rate*(335 - 319));
        $scroll_div.css('padding-left', rate*100 + 20);
        $scroll_div.find('.menu li').css('padding-left', rate*5 + 20);
        $scroll_div.find('.menu li').css('padding-right', rate*5 + 20);
    } else {
        $scroll_div.addClass('fixed').css('top', '0px');
        change_top = true;
    }
});
<?php self::endJsContent(); ?>
