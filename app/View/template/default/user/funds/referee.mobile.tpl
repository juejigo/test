{include file='public/fr/header.mobile.tpl'}
<div class="menu">
   <a class="back" href="/user/funds/income"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">推荐人数</div>
   <a class="btn_tj" href="#"><img src="{$smarty.const.URL_WEB}webapp/images/btn_bza.png"></a>
</div>
<div class="menber">
	<!--
    <div class="mem_zsy"><p>推荐人数（人）</p>222<div class="xzny">
	-->
    <div class="mem_zsy"><p>推荐人数（人）</p>{$count}<div class="xzny">
	<select>
		<option>选择年月</option>
		<option>2015年1月</option>
		<option>2015年2月</option>
	</select>
	</div></div>
    <div class="my_zsy">
		{foreach $refereeList as $i => $referee}
        <div class="msbox">
              <div class="ms_info">
                  <h2>{$referee.account}({$referee.referee_count})</h2>
                 <p>{$referee.dateline}</p>
              </div>
        </div>
		{/foreach}
		<!--
        <div class="msbox">
              <div class="ms_info">
                 <h2>会员A</h2>
                 <p>2014-12-12 12：23:32</p>
              </div>
        </div>
        <div class="msbox">
              <div class="ms_info">
                 <h2>会员B</h2>
                 <p>2014-12-12 12：23:32</p>
              </div>
        </div>
        <div class="msbox">
              <div class="ms_info">
                 <h2>会员C</h2>
                 <p>2014-12-12 12：23:32</p>
              </div>
        </div>
		-->
    </div>
</div>
{include file='public/fr/footer.mobile.tpl'}