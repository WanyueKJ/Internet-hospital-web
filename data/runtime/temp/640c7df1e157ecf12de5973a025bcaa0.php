<?php /*a:1:{s:115:"/www/wwwroot/kyhospital.sdwanyue.com/kyhospital.sdwanyue.com/public/themes/admin_simpleboot3/admin/index/index.html";i:1695264194;}*/ ?>
<!DOCTYPE html>
<html lang="zh_CN" style="overflow: hidden;">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Set render engine for 360 browser -->
  <meta name="renderer" content="webkit">
  <meta charset="utf-8">
  <title>万岳互联网医院管理后台</title>
  <meta name="description" content="This is page-header (.page-header &gt; h1)">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- HTML5 shim for IE8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
  <link href="/themes/admin_simpleboot3/public/assets/themes/<?php echo cmf_get_admin_style(); ?>/bootstrap.min.css" rel="stylesheet">
  <link href="/themes/admin_simpleboot3/public/assets/simpleboot3/css/simplebootadmin.css" rel="stylesheet">
  <link href="/static/font-awesome/css/font-awesome.min.css?page=index" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="/themes/admin_simpleboot3/public/assets/themes/<?php echo cmf_get_admin_style(); ?>/simplebootadminindex.min.css">
  <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  <style>
    /*-----------------导航hack--------------------*/
    .nav-list>li.open {
      position: relative;
    }

    .nav-list>li.open .back {
      display: none;
    }

    .nav-list>li.open .normal {
      display: inline-block !important;
    }

    .nav-list>li.open a {
      padding-left: 7px;
    }

    .nav-list>li .submenu>li>a {
      background: #fff;
    }

    .nav-list>li .submenu>li a>[class*="fa-"]:first-child {
      left: 20px;
    }

    .nav-list>li ul.submenu ul.submenu>li a>[class*="fa-"]:first-child {
      left: 30px;
    }

    /*----------------导航hack--------------------*/
  </style>

  <script>
    //全局变量
    var GV = {
      HOST: "<?php echo (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] !== '')?$_SERVER['HTTP_HOST']:''); ?>",
      ROOT: "/",
      WEB_ROOT: "/",
      JS_ROOT: "static/js/"
    };
  </script>
  <?php $submenus=$menus; 
    if (!function_exists('getsubmenu')) {
    function getsubmenu($submenus){

   if(!(empty($submenus) || (($submenus instanceof \think\Collection || $submenus instanceof \think\Paginator ) && $submenus->isEmpty()))): foreach($submenus as $menu){ ?>
    <li>
      <?php 
        $menu_name=lang($menu['lang']);
        $menu_name=$menu['lang']==$menu_name?$menu['name']:$menu_name;
       if(empty($menu['items'])){ ?>
      <a href="javascript:openapp('<?php echo $menu['url']; ?>','<?php echo $menu['id']; ?>','<?php echo $menu_name; ?>',true);">
        <i class="fa fa-<?php echo (isset($menu['icon']) && ($menu['icon'] !== '')?$menu['icon']:'desktop'); ?>"></i>
        <span class="menu-text"> <?php echo $menu_name; ?> </span>
      </a>
      <?php }else{ ?>
      <a href="#" class="dropdown-toggle">
        <i class="fa fa-<?php echo (isset($menu['icon']) && ($menu['icon'] !== '')?$menu['icon']:'desktop'); ?> normal"></i>
        <span class="menu-text normal"> <?php echo $menu_name; ?> </span>
        <b class="arrow fa fa-angle-right normal"></b>
        <i class="fa fa-reply back"></i>
        <span class="menu-text back">返回</span>

      </a>

      <ul class="submenu">
        <?php getsubmenu1($menu['items']) ?>
      </ul>
      <?php } ?>

    </li>

    <?php } ?>
  <?php endif; 
    }
    }
   
    if (!function_exists('getsubmenu1')) {
    function getsubmenu1($submenus){
   foreach($submenus as $menu){ ?>
  <li>
    <?php 
      $menu_name=lang($menu['lang']);
      $menu_name=$menu['lang']==$menu_name?$menu['name']:$menu_name;
     if(empty($menu['items'])){ ?>
    <a href="javascript:openapp('<?php echo $menu['url']; ?>','<?php echo $menu['id']; ?>','<?php echo $menu_name; ?>',true);">
      <i class="fa fa-caret-right"></i>
      <span class="menu-text">
        <?php echo $menu_name; ?>
      </span>
    </a>
    <?php }else{ ?>
    <a href="#" class="dropdown-toggle">
      <i class="fa fa-caret-right"></i>
      <span class="menu-text">
        <?php echo $menu_name; ?>
      </span>
      <b class="arrow fa fa-angle-right"></b>
    </a>
    <ul class="submenu">
      <?php getsubmenu2($menu['items']) ?>
    </ul>
    <?php } ?>

  </li>

  <?php } }
    }
   
    if (!function_exists('getsubmenu2')) {
    function getsubmenu2($submenus){ foreach($submenus as $menu){ ?>
  <li>
    <?php 
      $menu_name=lang($menu['lang']);
      $menu_name=$menu['lang']==$menu_name?$menu['name']:$menu_name;
     ?>

    <a href="javascript:openapp('<?php echo $menu['url']; ?>','<?php echo $menu['id']; ?>','<?php echo $menu_name; ?>',true);">
      &nbsp;<i class="fa fa-angle-double-right"></i>
      <span class="menu-text">
        <?php echo $menu_name; ?>
      </span>
    </a>
  </li>

  <?php } }
    }
   if(APP_DEBUG): ?>
    <style>
      #think_page_trace_open {
        left: 0 !important;
        right: initial !important;
      }
    </style>
  <?php endif; ?>

</head>

<body style="min-width:900px;overflow: hidden;">
  <div id="loading"><i class="loadingicon"></i><span><?php echo lang('LOADING'); ?></span></div>
  <div id="right-tools-wrapper">
    <!--<span id="right_tools_clearcache" title="清除缓存" onclick="javascript:openapp('<?php echo url('admin/Setting/clearcache'); ?>','right_tool_clearcache','清除缓存');"><i class="fa fa-trash-o right_tool_icon"></i></span>-->
    <!--<span id="refresh-wrapper" title="<?php echo lang('REFRESH_CURRENT_PAGE'); ?>"><i-->
    <!--class="fa fa-refresh right_tool_icon"></i></span>-->
  </div>
  <div class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a href="<?php echo url('admin/index/index'); ?>" class="navbar-brand"
          style="min-width: 200px;text-align: center;">开源互联网医院</a>
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>

      <div class="navbar-collapse collapse" id="navbar-main">
        <div class="pull-left" style="position: relative;">
          <a id="task-pre" class="task-changebt"><i class="fa fa-chevron-left"></i></a>
          <div id="task-content">
            <ul class="nav navbar-nav cmf-component-tab" id="task-content-inner">
              <li class="cmf-component-tabitem noclose" app-id="0" app-url="<?php echo url('main/index'); ?>" app-name="首页">
                <a class="cmf-tabs-item-text"><?php echo lang('HOME'); ?></a>
              </li>
            </ul>
            <div style="clear:both;"></div>
          </div>
          <a id="task-next" class="task-changebt"><i class="fa fa-chevron-right"></i></a>
        </div>

        <ul class="nav navbar-nav navbar-right simplewind-nav">
          <li class="light-blue" style="border-left:none;display: none;" id="close-all-tabs-btn">
            <a id="close-wrapper" href="javascript:void(0);" title="<?php echo lang('CLOSE_TOP_MENU'); ?>"
              style="color:#fff;font-size: 16px">
              <i class="fa fa-times right_tool_icon"></i>
            </a>
          </li>
          <li class="light-blue" style="border-left:none;">
            <a id="refresh-wrapper" href="javacript:void(0);" title="<?php echo lang('REFRESH_CURRENT_PAGE'); ?>"
              style="color:#fff;font-size: 16px">
              <i class="fa fa-refresh right_tool_icon"></i>
            </a>
          </li>
          <li class="light-blue dropdown" style="border-left:none;">
            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
              <?php if(isset($admin['avatar']) && $admin['avatar']): ?>
                <img class="nav-user-photo" width="30" height="30" src="<?php echo cmf_get_user_avatar_url($admin['avatar']); ?>"
                  alt="<?php echo $admin['user_login']; ?>">
                <?php else: ?>
                <img class="nav-user-photo" width="30" height="30" src="/themes/admin_simpleboot3/public/assets/images/logo-18.png"
                  alt="<?php echo (isset($admin['user_login']) && ($admin['user_login'] !== '')?$admin['user_login']:''); ?>">
              <?php endif; ?>
              <span class="user-info">
                <?php echo lang('WELCOME_USER',array('user_nickname' => empty($admin['user_nickname'] )? $admin['user_login'] :
                $admin['user_nickname'])); ?>
              </span>
              <i class="fa fa-caret-down"></i>
            </a>
            <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
              <?php if(cmf_auth_check(cmf_get_current_admin_id(),'admin/Setting/site')): ?>
                <li>
                  <a href="javascript:openapp('<?php echo url('setting/site'); ?>','index_site','<?php echo lang('ADMIN_SETTING_SITE'); ?>');"><i
                      class="fa fa-cog"></i> <?php echo lang('ADMIN_SETTING_SITE'); ?></a>
                </li>
              <?php endif; if(cmf_auth_check(cmf_get_current_admin_id(),'admin/user/userinfo')): ?>
                <li>
                  <a
                    href="javascript:openapp('<?php echo url('user/userinfo'); ?>','index_userinfo','<?php echo lang('ADMIN_USER_USERINFO'); ?>');"><i
                      class="fa fa-user"></i> <?php echo lang('ADMIN_USER_USERINFO'); ?></a>
                </li>
              <?php endif; if(cmf_auth_check(cmf_get_current_admin_id(),'admin/Setting/password')): ?>
                <li>
                  <a
                    href="javascript:openapp('<?php echo url('setting/password'); ?>','index_password','<?php echo lang('ADMIN_SETTING_PASSWORD'); ?>');"><i
                      class="fa fa-lock"></i> <?php echo lang('ADMIN_SETTING_PASSWORD'); ?></a>
                </li>
              <?php endif; ?>
              <li><a href="<?php echo url('Public/logout'); ?>"><i class="fa fa-sign-out"></i> <?php echo lang('LOGOUT'); ?></a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-container container-fluid">

    <div class="sidebar" id="sidebar">
      <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <!-- <a class="btn btn-sm btn-warning" href="/"
               title="<?php echo lang('WEBSITE_HOME_PAGE'); ?>"
               target="_blank"
               data-toggle="tooltip">
                <i class="fa fa-home"></i>
            </a> -->
        <!--
            <?php if(cmf_auth_check(cmf_get_current_admin_id(),'portal/AdminCategory/index')): ?>
                <a class="btn btn-sm btn-success" href="javascript:openapp('<?php echo url('portal/AdminCategory/index'); ?>','index_termlist','文章分类管理');" title="文章分类管理">
                    <i class="fa fa-th"></i>
                </a>
            <?php endif; if(cmf_auth_check(cmf_get_current_admin_id(),'portal/AdminArticle/index')): ?>
                <a class="btn btn-sm btn-info" href="javascript:openapp('<?php echo url('portal/AdminArticle/index'); ?>','index_postlist','文章管理');" title="文章管理">
                    <i class="fa fa-pencil"></i>
                </a>
            <?php endif; ?>
            -->

        <?php if(cmf_auth_check(cmf_get_current_admin_id(),'user/AdminAsset/index')): ?>
          <a class="btn btn-sm btn-info"
            href="javascript:openapp('<?php echo url('user/AdminAsset/index'); ?>','userAdminAssetindex','资源管理',true);" title="资源管理"
            data-toggle="tooltip">
            <i class="fa fa-file"></i>
          </a>
        <?php endif; if(cmf_auth_check(cmf_get_current_admin_id(),'admin/Setting/clearcache')): ?>
          <a class="btn btn-sm btn-danger"
            href="javascript:openapp('<?php echo url('admin/Setting/clearcache'); ?>','index_clearcache','<?php echo lang('ADMIN_SETTING_CLEARCACHE'); ?>',true);"
            title="<?php echo lang('ADMIN_SETTING_CLEARCACHE'); ?>" data-toggle="tooltip">
            <i class="fa fa-trash-o"></i>
          </a>
        <?php endif; if(cmf_auth_check(cmf_get_current_admin_id(),'admin/RecycleBin/index')): ?>
          <a class="btn btn-sm btn-danger"
            href="javascript:openapp('<?php echo url('admin/RecycleBin/index'); ?>','index_recycle','回收站',true);" title="回收站"
            data-toggle="tooltip">
            <i class="fa fa-recycle"></i>
          </a>
        <?php endif; if(APP_DEBUG): ?>
          <a class="btn btn-sm btn-default"
            href="javascript:openapp('<?php echo url('admin/Menu/index'); ?>','index_menu','<?php echo lang('ADMIN_MENU_INDEX'); ?>',true);"
            title="<?php echo lang('ADMIN_MENU_INDEX'); ?>" data-toggle="tooltip">
            <i class="fa fa-list"></i>
          </a>
        <?php endif; ?>

      </div>
      <div id="nav-wrapper">
        <ul class="nav nav-list">
          <?php echo getsubmenu($submenus); ?>
        </ul>
      </div>

    </div>

    <div class="main-content">
      <div class="page-content" id="content">
        <iframe src="<?php echo url('Main/index'); ?>" style="width:100%;height: 100%;" frameborder="0" id="appiframe-0"
          class="appiframe"></iframe>
      </div>
    </div>
  </div>

  <script src="/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js"></script>
  <script src="/static/js/wind.js"></script>
  <script src="/themes/admin_simpleboot3/public/assets/js/bootstrap.min.js"></script>
  <script src="/static/js/admin.js"></script>
  <script src="/themes/admin_simpleboot3/public/assets/simpleboot3/js/adminindex.js"></script>
  <script>
    $(function () {
      $("[data-toggle='tooltip']").tooltip();
      $("li.dropdown").hover(function () {
        $(this).addClass("open");
      }, function () {
        $(this).removeClass("open");
      });

      var menus = '<?php echo $menus_js_var; ?>';
      //读取url参数。尝试执行菜单功能。
      if (typeof (menus) != "undefined") {

        var tw = window.top;
        var twa = tw.location.href.split("#");
        var url = twa[1];
        var urlTmp = url;
        if (url != null) {
          //去掉/ 去掉_ 全部小写。
          urlTmp = urlTmp.replace(/[\\/|_|]/g, "");
          urlTmp = urlTmp.replace(".html", "");
          var menu = menus[urlTmp];
          if (menu) {
            openapp(url, menu.id + menu.app, menu.name, true);
          }
        }
      }




    });

    var ismenumin = $("#sidebar").hasClass("menu-min");
    $(".nav-list").on("click", function (event) {
      var closest_a = $(event.target).closest("a");
      if (!closest_a || closest_a.length == 0) {
        return
      }
      if (!closest_a.hasClass("dropdown-toggle")) {
        if (ismenumin && "click" == "tap" && closest_a.get(0).parentNode.parentNode == this) {
          var closest_a_menu_text = closest_a.find(".menu-text").get(0);
          if (event.target != closest_a_menu_text && !$.contains(closest_a_menu_text, event.target)) {
            return false
          }
        }
        return
      }
      var closest_a_next = closest_a.next().get(0);
      if (!$(closest_a_next).is(":visible")) {
        var closest_ul = $(closest_a_next.parentNode).closest("ul");
        if (ismenumin && closest_ul.hasClass("nav-list")) {
          return
        }
        closest_ul.find("> .open > .submenu").each(function () {
          if (this != closest_a_next && !$(this.parentNode).hasClass("active")) {
            $(this).slideUp(150).parent().removeClass("open")
          }
        });
      }
      if (ismenumin && $(closest_a_next.parentNode.parentNode).hasClass("nav-list")) {
        return false;
      }
      $(closest_a_next).slideToggle(150).parent().toggleClass("open");
      return false;
    });



  </script>
</body>

</html>