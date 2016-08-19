{include file='public/fr/header.mobile.tpl'}

<!--nav classify_li-->
<nav class="classify_li">
    <ul>
    {foreach $brandList as $brand}
        <li><a href="/product/product/list?brand_id={$brand.id}">{$brand.brand_name}</a></li>
    {/foreach}
    </ul>
</nav>
<!--nav classify_li end-->

{include file='public/fr/footer.mobile.tpl'}