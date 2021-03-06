<?php if (!defined('THINK_PATH')) exit(); if(IS_SUPPLIER): ?><div class="span<?php echo ($addons_config["width"]); ?>">
	<div class="columns-mod">
		<div class="hd cf">
			<h5><?php echo ($addons_config["title"]); ?></h5>
			<div class="title-opt">
			</div>
		</div>
		<div class="bd">
			<div class="user-guide">
        <div class="item">
					<ul>
						<h4><span class="num">1</span>首页</h4>
						<li><a href="<?php echo U('Index/index');?>">快速开始</a></li>
					</ul>
				</div>
				<div class="item">
					<ul>
						<h4><span class="num">2</span>订单管理</h4>
						<li><a href="<?php echo U('Order/index');?>">订单列表</a></li>
						<li><a href="<?php echo U('Ship/index');?>">发货单</a></li>
					</ul>
				</div>
				<div class="item">	
					<ul>
						<h4><span class="num">3</span>物流管理</h4>
						<li><a href="<?php echo U('DeliveryTpl/index');?>">运费模板</a></li>
					</ul>
				</div>
        <div class="item">	
					<ul class="last">
						<h4><span class="num">4</span>设置</h4>
            <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
            <li><a href="<?php echo U('User/updatePassword');?>">修改登录密码</a></li>
						<li><a href="<?php echo U('Supplier/index');?>">供应商信息设置</a></li>
            
					</ul>
				</div>
      </div>
    </div>
  </div>
</div>

 <?php else: ?>
<div class="span<?php echo ($addons_config["width"]); ?>">
	<div class="columns-mod">
		<div class="hd cf">
			<h5><?php echo ($addons_config["title"]); ?></h5>
			<div class="title-opt">
			</div>
		</div>
		<div class="bd">
			<div class="user-guide">
				<div class="item">
					<ul>
						<h4><span class="num">1</span>系统设置</h4>
						<li><a href="<?php echo U('Config/group', array('id' => 1));?>">店铺基本配置</a></li>
						<li><a href="<?php echo U('Channel/index', array('id' => 1));?>">网站导航管理</a></li>
						<li><a href="<?php echo U('Config/group', array('id' => 2));?>">微信公众号配置</a></li>
						<li><a href="<?php echo U('WechatMenu/index');?>">微信自定义菜单设置</a></li>
						<li><a href="<?php echo U('WechatMsg/index');?>">微信消息设置</a></li>
					</ul>
				</div>
				<div class="item">	
					<ul>
						<h4><span class="num">2</span>商品管理</h4>
						<li><a href="<?php echo U('Item/index');?>">商品列表</a></li>
						<li><a href="<?php echo U('ItemCategory/index');?>">商品分类管理</a></li>
						<li><a href="<?php echo U('ItemProperty/index');?>">商品属性管理</a></li>
						<li><a href="<?php echo U('ItemProperty/index', array('type' => 'specification'));?>">商品规格管理</a></li>
						<li><a href="<?php echo U('Item/recycle');?>">商品回收站</a></li>
					</ul>
				</div>
				<div class="item">
					<ul>
						<h4><span class="num">3</span>订单管理</h4>
						<li><a href="<?php echo U('Order/index');?>">订单列表</a></li>
						<li><a href="<?php echo U('Payment/index');?>">收款单</a></li>
						<li><a href="<?php echo U('Ship/index');?>">发货单</a></li>
						<li><a href="<?php echo U('Order/recycle');?>">订单回收站</a></li>
					</ul>
				</div>
				<div class="item">
					<ul>
						<h4><span class="num">4</span>营销管理</h4>
						<li><a href="<?php echo U('Coupon/index');?>">优惠券管理</a></li>
						<li><a href="<?php echo U('CouponUser/index');?>">优惠券发放</a></li>
						<li><a href="<?php echo U('Card/index');?>">礼品卡管理</a></li>
						<li><a href="<?php echo U('Activity/index');?>">专场管理</a></li>
						<li><a href="<?php echo U('Advertise/index');?>">广告管理</a></li>
						<li><a href="<?php echo U('Invite/index');?>">邀请管理</a></li>
					</ul>
				</div>
        <div class="item">
					<ul class="last">
						<h4><span class="num">5</span>用户管理</h4>
						<li><a href="<?php echo U('User/index');?>">用户列表</a></li>
						<!--li><a href="<?php echo U('User/wechat');?>">微信粉丝列表</a></li-->
						<li><a href="<?php echo U('AuthManager/index');?>">权限管理</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div><?php endif; ?>