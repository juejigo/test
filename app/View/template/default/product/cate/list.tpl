{include file='public/fr/header.mobile.tpl'}

<!--nav classify_li-->
<nav class="classify_li">
    <ul>
    {foreach $cateList as $cate}
        <li><a href="/product/product/list?cate_id={$cate.id}">{$cate.cate_name}</a></li>
    {/foreach}
    </ul>
</nav>
<!--nav classify_li end-->

{include file='public/fr/footer.mobile.tpl'}