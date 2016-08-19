
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
	<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
	<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
	<div class="page-sidebar navbar-collapse collapse">
		<!-- BEGIN SIDEBAR MENU -->
		<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
		<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
		<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
		<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
			<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
			<li class="sidebar-toggler-wrapper">
				<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				<div class="sidebar-toggler">
				</div>
				<!-- END SIDEBAR TOGGLER BUTTON -->
			</li>
			<!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
			<li class="sidebar-search-wrapper">
				<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
				<!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
				<!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
				<form class="sidebar-search " action="extra_search.html" method="POST">
					<a href="javascript:;" class="remove">
					<i class="icon-close"></i>
					</a>
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Search...">
						<span class="input-group-btn">
						<a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
						</span>
					</div>
				</form>
				<!-- END RESPONSIVE QUICK SEARCH FORM -->
			</li>
		 {foreach $submenus as $controName => $controValue}
			 <li class="{if $openSub == $controName}active open{/if}">
				<a href="javascript:;">
				<i class="{$controValue['icon']}"></i>
				<span class="title">{$controName}</span>
				<span class="arrow">
				</a>
 				{$controValue=array_splice($controValue,1)}
				<ul class="sub-menu" id="sub">
					{foreach $controValue as $actionName => $actionValue}
						<li class="{if $currSub == $actionName}active{/if}">
							<a href="{$actionValue['url']}">{$actionName}</a>
						</li>	
					{/foreach}
				</ul>
			</li>
		 {/foreach}
		 
			
		</ul>
		<!-- END SIDEBAR MENU -->
	</div>
</div>


<!-- END SIDEBAR -->




<!-- 
					{if $controller != $actionValue['controller'] && $action != $actionValue['action'] && $module== 'votecp' &&$controller == 'player'}
						<li class="active open">
						<a href="javascript:;">
							<i class="icon-basket"></i>
							<span class="title">投票</span>
							{if $controller == 'vote'}<span class="selected"></span>{/if}
							<span class="arrow">
							</a>
							<ul class="sub-menu">
									<li class="{if $controller == 'vote' || $controller == 'player'|| $controller == 'record'|| $controller == 'comment'}active{/if}">
										<a href="/votecp/vote/list">
										投票列表 </a>
									</li>
							</ul>
						</li>
						<li class="{if $controller == 'scrath' || $controller == 'card'}active open{/if}">
							<a href="javascript:;">
							<i class="icon-basket"></i>
							<span class="title">刮刮卡</span>
							{if $controller == 'scrath'|| $controller == 'card'}<span class="selected"></span>{/if}
							<span class="arrow">
							</a>
							<ul class="sub-menu">
									<li class="{if $controller == 'scrath' || $controller == 'card'}active{/if}">
										<a href="/scrathcp/scrath/list">
										刮刮卡列表 </a>
									</li>
							</ul>
						</li>					
					
					{/if} -->