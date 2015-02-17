<?php /* Smarty version 2.6.26, created on 2013-12-11 07:01:38
         compiled from index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./header.html", 'smarty_include_vars' => array('title' => "帮帮网")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
_templates/<?php echo $this->_tpl_vars['THEME']; ?>
/css/map.css">
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
_templates/<?php echo $this->_tpl_vars['THEME']; ?>
/css/search.css">
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
_templates/<?php echo $this->_tpl_vars['THEME']; ?>
/css/common.css">
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
_templates/<?php echo $this->_tpl_vars['THEME']; ?>
/css/header.css">
<script type="text/javascript" src="<?php echo $this->_tpl_vars['BASE_URL']; ?>
js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['BASE_URL']; ?>
js/jquery.confirm.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['BASE_URL']; ?>
js/script.js"></script>

<!-- search -->
<link href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
_templates/<?php echo $this->_tpl_vars['THEME']; ?>
/css/search_new.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $this->_tpl_vars['BASE_URL']; ?>
js/selectbox.js"></script>
<!-- 循环 -->
<script type="text/javascript" src="<?php echo $this->_tpl_vars['BASE_URL']; ?>
js/keke.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
_templates/<?php echo $this->_tpl_vars['THEME']; ?>
/css/style2013.css">

<?php echo '
<script type="text/javascript"> $(document).ready(function() {$(\'#mySle\').selectbox();});</script>
<SCRIPT type=text/javascript>
$(document).ready(function() {
	(function animloop(){requestAnimFrame(animloop);render();})();
})
window.requestAnimFrame = (function(){
	return  window.requestAnimationFrame || window.webkitRequestAnimationFrame || 
		window.mozRequestAnimationFrame  || window.oRequestAnimationFrame      || 
		window.msRequestAnimationFrame   || function( callback ){window.setTimeout(callback, 1000 / 30);};
})();
var i=0;
function render() {
	$("#slide2")
		.animate({ opacity: 1 }, 3000)
		.animate({ right: "2000px" }, 10000)
		.animate({ right: "-200px" }, 8000)
		.animate({opacity: 1}, {duration: 0,\'complete\': function(){i++;if (i>10){location.reload();}}});
};
</SCRIPT>
'; ?>

<META name=GENERATOR content="MSHTML 8.00.6001.19403"></HEAD>	
<BODY>
<div id="content">
	<div class="simple_style" id="wrapper" style="margin-left:5px;">
        <div class="wrapper clearfix">
            <div class="container_24 clearfix mb_10">
                <div class="guyongkb box default clearfix mb_10">
                    <div class="tilelgy pad10 clearfix">
                        <span class="tlil fl_l"><img class="fl_l" src="<?php echo $this->_tpl_vars['BASE_URL']; ?>
_templates/<?php echo $this->_tpl_vars['THEME']; ?>
/img/niligi.gif" alt="介绍网站" original-title=""></span>
                        <span class="fl_r" style="margin-top:-2px;">
                        	<a href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
requirement/" target="_blank"><img class="fl_l" src="<?php echo $this->_tpl_vars['BASE_URL']; ?>
_templates/<?php echo $this->_tpl_vars['THEME']; ?>
/img/mffburw_hover.gif" alt="免费发布" height="50" width="200"  original-title=""></a>
						</span>
                    </div>
                    <div class="pad10">
                        <ul id="marqueebox0" class="clearfix">
                        	<?php unset($this->_sections['list']);
$this->_sections['list']['name'] = 'list';
$this->_sections['list']['loop'] = is_array($_loop=20) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['list']['show'] = true;
$this->_sections['list']['max'] = $this->_sections['list']['loop'];
$this->_sections['list']['step'] = 1;
$this->_sections['list']['start'] = $this->_sections['list']['step'] > 0 ? 0 : $this->_sections['list']['loop']-1;
if ($this->_sections['list']['show']) {
    $this->_sections['list']['total'] = $this->_sections['list']['loop'];
    if ($this->_sections['list']['total'] == 0)
        $this->_sections['list']['show'] = false;
} else
    $this->_sections['list']['total'] = 0;
if ($this->_sections['list']['show']):

            for ($this->_sections['list']['index'] = $this->_sections['list']['start'], $this->_sections['list']['iteration'] = 1;
                 $this->_sections['list']['iteration'] <= $this->_sections['list']['total'];
                 $this->_sections['list']['index'] += $this->_sections['list']['step'], $this->_sections['list']['iteration']++):
$this->_sections['list']['rownum'] = $this->_sections['list']['iteration'];
$this->_sections['list']['index_prev'] = $this->_sections['list']['index'] - $this->_sections['list']['step'];
$this->_sections['list']['index_next'] = $this->_sections['list']['index'] + $this->_sections['list']['step'];
$this->_sections['list']['first']      = ($this->_sections['list']['iteration'] == 1);
$this->_sections['list']['last']       = ($this->_sections['list']['iteration'] == $this->_sections['list']['total']);
?>
                            <li>
                            	<?php if ($this->_tpl_vars['lives'][$this->_sections['list']['index']]['live_id']): ?><strong class="cea5">生活服务:</strong>&nbsp;<a href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
live/show/<?php echo $this->_tpl_vars['lives'][$this->_sections['list']['index']]['live_id']; ?>
/" target="_blank"><?php echo $this->_tpl_vars['lives'][$this->_sections['list']['index']]['live_title']; ?>
</a><?php endif; ?>
							</li>
                            <li>
                            	<?php if ($this->_tpl_vars['jobs'][$this->_sections['list']['index']]['job_id']): ?><strong class="cea5">求人招聘:</strong>&nbsp;<a href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
job/show/<?php echo $this->_tpl_vars['jobs'][$this->_sections['list']['index']]['job_id']; ?>
/" target="_blank"><?php echo $this->_tpl_vars['jobs'][$this->_sections['list']['index']]['job_title']; ?>
</a><?php endif; ?>
                            </li>
							<?php endfor; endif; ?>
                        </ul>
                    </div>
                </div>
                <script type="text/javascript">
                    startmarquee(48, 100, 2000, 0);/**startmarquee(一次滚动高度,速度,停留时间,图层标记);**/
                </script>
            </div>
        </div>
    </div>
	<div id="sidezuo">
		<a href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
live/">
		<img src="<?php echo $this->_tpl_vars['BASE_URL']; ?>
_templates/<?php echo $this->_tpl_vars['THEME']; ?>
/img/rencai.png" alt="生活服务">
		</a>
	</div>
	<div id="sideyou">
		<a href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
job/">
		<img src="<?php echo $this->_tpl_vars['BASE_URL']; ?>
_templates/<?php echo $this->_tpl_vars['THEME']; ?>
/img/gongzuo.png" alt="求人招聘">
		</a>
	</div>
	<div id="wrapper">
		<div id="main" style="margin-top:-120px;">
		<input type="hidden" value="<?php echo $this->_tpl_vars['BASE_URL']; ?>
" id="url" name="url"/>
		</div>
	</div>
	<!-- search -->
	<div class="searchbox" style="margin-bottom:-150px; margin-top:-90px;">
	  <div class="searchZone clearfix">
	    <form action="#" target="_blank">
	      <fieldset style="border: 0;margin: 0; padding: 0;">
	        <label>
				<input type="text" class="text" name="keyword" onblur="" value="" placeholder="請輸入関鍵字" style="margin-left:8px;margin-top:2px;"/>
	        </label>
	        <div class="left">
	          <select style="display: none;" name="mySle" id="mySle">
	            <option selected="selected" value="0">全站搜索</option>
	            <option value="1">生活服務</option>
	            <option value="2">求人招聘</option>
	            <option value="3">人才/企业庫</option>
	          </select>
	        </div>
	        <label>
	          <button type="submit">快给我搜一下</button>
	        </label>
	      </fieldset>
	    </form>
	  </div>
	</div>
	<center style="font-size:18px;margin-top:-200px;">我们励志为在日华人，提供一个在线服务交易，信息互助的平台!</center>
	<br><br><br><br>
</div>
<div id="foot1">
<div id="animationbox">
<IMG id="slide2" src="<?php echo $this->_tpl_vars['BASE_URL']; ?>
_templates/<?php echo $this->_tpl_vars['THEME']; ?>
/img/gou8.png" alt="sample"/> 
</div>

<div id="side-app-promote" class="side-fixed">
	<a href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
requirement/" target="_blank">
	<div style="text-align: left; padding-left: 20px;" class="small">QR码扫一扫</div>
	<div class="qrcode">
		<img src="<?php echo $this->_tpl_vars['BASE_URL']; ?>
_templates/<?php echo $this->_tpl_vars['THEME']; ?>
/img/sidebar_qr.png">
	</div>
	<div class="small">免费发布</div>
	<div class="small">生活需求</div>
	</a>
</div>

<div class="wrap" >
	<div style="width: 48%;top:22px;">
		<ul style="z-index:99;">
			<li><a href="#">运营会社</a></li>
			<li><a href="#">关于个人情报管理</a></li>
			<li><a href="#">利用规约</a></li>
			<li><a href="#">网站导航</a></li>
		</ul>
	</div>
	<div style="width: 37%;top:22px;">
		copyright(c) 2012 KIWA co.,ltd. ALL Rights Reserved.
	</div>
</div>
</div>
</body>
</html>