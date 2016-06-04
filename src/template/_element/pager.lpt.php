<?php
    /**
     * @param base_url
     * @param total
     * @param page
     * @param page_size
     */
    $base_url = $base_url ?? '';
    var_dump($base_url);
    $page = $page ?? $_GET['page'] ?? 1;
    $page_size = $page_size ?? $_GET['page_size'] ?? 10;
    $shows = [];
    $show_begin = ($page - 2) > 0 ? ($page - 2) : 1;
    $show_end = ($page + 2) < $total ? ($page + 2) : $total;
    for($i = $show_begin; $i <= $show_end; ++$i) {
        $the = ['page' => $i];
        if($i == $page) {
            $the['current'] = 1;
        }
        $shows[] = $the;
    }
    $handle_begin = [1, 2];
    $handle_end = [$total, $total - 1];

    function getUrlOfPage($page, $page_size, $url)
    {
        if(!preg_match('/\?/', $url)) {
            $url .= '?';
        }
        return $url .= implode('&', [
            'page='.$page,
            'page_size='.$page_size,
        ]);
    }
?>
<ul class="pager">
    <neq name="{$show_begin}" value="1">
        <li><a href="{:getUrlOfPage(1, $page_size, $base_url)}">1</a></li>
    </neq>
    <nin name="{$show_begin}" value="{$handle_begin}">
        <li>...</li>
    </nin>
    <llist name="{$shows}" id="show">
        <empty name="{$show.current}">
            <li><a href="{:getUrlOfPage($show['page'], $page_size, $base_url)}">{$show.page}</a></li>
        </empty>
        <notempty name="{$show.current}">
            <li><a class="current" href="{:getUrlOfPage($show['page'], $page_size, $base_url)}">{$show.page}</a></li>
        </notempty>
    </llist>
    <nin name="{$show_end}" value="{$handle_end}">
        <li>...</li>
    </nin>
    <neq name="{$show_end}" value="{$total}">
        <li><a href="{:getUrlOfPage($total, $page_size, $base_url)}">{$total}</a></li>
    </neq>
</ul>
<style>
    ul.pager {
        margin: 0px;
        text-align: center;
    }
    ul.pager>li {
        list-style: none;
        display: inline-block;
    }
    ul.pager>li>a {
        box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
        padding: 0px 5px;
        background-color: white;
    }
    ul.pager>li>a.current {
        background-color: yellow;
    }
</style>
