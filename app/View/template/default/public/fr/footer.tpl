<!--底部-->
  <div class="footer">
    <div class="footer_nav">
        <div class="footer_nav_main">
          <ul>
            <li><a href="/static/html/aboutus?id=1925">关于我们</a></li>
            <li><a href="/static/html/aboutus?id=1926">使用说明</a></li>
            <li><a href="/static/html/aboutus?id=1927">联系我们</a></li>
            <li><a href="/static/html/aboutus?id=1928">法律公告</a></li>
            <li><a href="/static/html/aboutus?id=1929">免费声明</a></li>
          </ul>
          <div class="go_top" onclick="goTop();"></div>
        </div>
    </div>
    <div class="footer_main">
      <div class="footer_info">
        <img src="{$smarty.const.URL_MIX}company/images/footer_logo.png" class="logo">
        <div class="info">
          <p>服务热线</p>
          <p class="bg"><img src="{$smarty.const.URL_MIX}company/images/telkf.png"></p>
          <p>地址：{$site.address}</p>
          <p>电话：{$site.telephone}</p>
        </div>
        <div class="ewm">
          <img src="{$site.app_image}">
          <p>手机APP</p>
        </div>
        <div class="ewm">
          <img src="{$site.weixin_image}">
          <p>微信</p>
        </div>
      </div>
      <div class="footer_copyright">{$site.copyright}</div>
    </div>
  </div>
  <!--底部/-->
  </body>
</html>
<script src="{$smarty.const.URL_JS}lib/jquery-1.9.1.min.js"></script>
<script src="{$smarty.const.URL_JS}index/index/datetimepicker.min.js"></script>
<script src="{$smarty.const.URL_JS}index/index/jquery.SuperSlide.js"></script>
<script src="{$smarty.const.URL_JS}index/index/lazyload.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_JS}public/fr.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_JS}public/{$module}.js"></script>

<!-- 美恰 -->
  <script type='text/javascript'>
    (function(m, ei, q, i, a, j, s) {
        m[a] = m[a] || function() {
            (m[a].a = m[a].a || []).push(arguments)
        };
        j = ei.createElement(q),
            s = ei.getElementsByTagName(q)[0];
        j.async = true;
        j.charset = 'UTF-8';
        j.src = i + '?v=' + new Date().getUTCDate();
        s.parentNode.insertBefore(j, s);
    })(window, document, 'script', '//static.meiqia.com/dist/meiqia.js', '_MEIQIA');
    _MEIQIA('entId', 25304);
</script>
<!-- 站长统计 -->
<div style="display:none;">
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1260159051'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/z_stat.php%3Fid%3D1260159051 ' type='text/javascript'%3E%3C/script%3E"));</script>
</div>


