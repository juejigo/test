
      {include file='public/fr/header.tpl'}
  <!--中间内容-->
  <div class="main">
      <div class="about_box">
        <!-- 左侧列表 -->
        <div class="about_box_left">
            <dl>
              <dt>
                <h2>了解我们</h2>
                <p>ABOUT US</p>
              </dt>

              <dd  {if $new_id == 1925 }class="active" {/if}><a href="/static/html/aboutus?id=1925"><i class="gy"></i>关于我们</a></dd>

                <dd {if $new_id == 1926 }class="active" {/if}><a href="/static/html/aboutus?id=1926"><i class="sy"></i>使用说明</a></dd>

                <dd  {if $new_id == 1927 }class="active" {/if}><a href="/static/html/aboutus?id=1927"><i class="lx"></i>联系我们</a></dd>

                <dd  {if $new_id == 1928 }class="active" {/if}><a href="/static/html/aboutus?id=1928"><i class="fv"></i>法律公告</a></dd>

                 <dd  {if $new_id == 1929 }class="active" {/if}><a href="/static/html/aboutus?id=1929"><i class="mz"></i>免责声明</a></dd>

            </dl>
        </div>
        <!-- 左侧列表 /-->
        <!--右侧内容-->
        <div class="about_box_right">
            <h2 class="title">{$new.title}</h2>
            <div class="info">
				{$new.content}
            </div>
        </div>
        <!--右侧内容 /-->
      </div>
  </div>
  <!--中间内容 /-->
   {include file='public/fr/footer.tpl'}
