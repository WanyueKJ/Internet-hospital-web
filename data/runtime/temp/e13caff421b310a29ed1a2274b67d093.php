<?php /*a:2:{s:116:"/www/wwwroot/kyhospital.sdwanyue.com/kyhospital.sdwanyue.com/public/themes/admin_simpleboot3/admin/health/index.html";i:1693617102;s:111:"/www/wwwroot/kyhospital.sdwanyue.com/kyhospital.sdwanyue.com/public/themes/admin_simpleboot3/public/header.html";i:1695265665;}*/ ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <!-- Set render engine for 360 browser -->
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- HTML5 shim for IE8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->


  <link href="/themes/admin_simpleboot3/public/assets/themes/<?php echo cmf_get_admin_style(); ?>/bootstrap.min.css" rel="stylesheet">
  <link href="/themes/admin_simpleboot3/public/assets/simpleboot3/css/simplebootadmin.css" rel="stylesheet">
  <link href="/static/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  <style>
    form .input-order {
      margin-bottom: 0px;
      padding: 0 2px;
      width: 42px;
      font-size: 12px;
    }

    form .input-order:focus {
      outline: none;
    }

    .table-actions {
      margin-top: 5px;
      margin-bottom: 5px;
      padding: 0px;
    }

    .table-list {
      margin-bottom: 0px;
    }

    .form-required {
      color: red;
    }

    .bottom ul {
      display: flex;
      justify-content: center;
    }

    .bottom li {
      margin-right: 10px;
      color: aliceblue;
      list-style: none;
    }

    .bottom li a {
      color: #979292;
    }

    .bottom p {
      text-align: center;
      color: #979292;
    }
  </style>
  <?php 
    $cmf_version=cmf_version();
    if (strpos(cmf_version(), '6.') === 0) {
    $_app=app()->http->getName();
    }else{
    $_app=request()->module();
    }
   ?>
  <script type="text/javascript">
    //全局变量
    var GV = {
      ROOT: "/",
      WEB_ROOT: "/",
      JS_ROOT: "static/js/",
      APP: '<?php echo $_app; ?>'/*当前应用名*/
    };
  </script>
  <script src="/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js"></script>
  <script src="/static/js/wind.js"></script>
  <script src="/themes/admin_simpleboot3/public/assets/js/bootstrap.min.js"></script>
  <script>
    Wind.css('artDialog');
    Wind.css('layer');
    $(function () {
      $("[data-toggle='tooltip']").tooltip({
        container: 'body',
        html: true,
      });
      $("li.dropdown").hover(function () {
        $(this).addClass("open");
      }, function () {
        $(this).removeClass("open");
      });
    });
  </script>
  <?php if(APP_DEBUG): ?>
    <style>
      #think_page_trace_open {
        z-index: 9999;
      }
    </style>
  <?php endif; ?>
</head>
<body>
<div class="wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a>科普列表</a></li>
        <li><a href="<?php echo url('health/Add'); ?>"><?php echo lang('ADD'); ?></a></li>
    </ul>
    <form class="well form-inline margin-top-20" method="post" action="<?php echo url('health/index'); ?>">
        
        关键字：
        <input class="form-control" type="text" name="keyword" style="width: 200px;" value="<?php echo input('request.keyword'); ?>"
               placeholder="标题">
        <input type="submit" class="btn btn-primary" value="搜索"/>
        <a class="btn btn-danger" href="<?php echo url('health/index'); ?>">清空</a>
    </form>
    <form method="post" class="js-ajax-form" action="<?php echo url('health/listOrder'); ?>">
        <div class="table-actions">
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"><?php echo lang('SORT'); ?></button>
        </div>
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th width="8%"><?php echo lang('SORT'); ?></th>
                <th width="10%">ID</th>
                <th width="20%">标题</th>
                <th width="20%">封面</th>
                <th width="20%">发布时间</th>
                <th><?php echo lang('ACTIONS'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): ?>
                <tr>
                    <td>
                        <input type="text" name="sort[<?php echo $vo['id']; ?>]" value="<?php echo $vo['sort']; ?>" class="input input-order">
                    </td>
                    <td><?php echo $vo['id']; ?></td>
                    <td><?php echo $vo['name']; ?></td>
                    <td>
                        <img src="<?php echo cmf_get_image_preview_url($vo['image']); ?>" id="thumb-preview" width="40" style="cursor: hand"/>
                    </td>
                    <td><?php echo date('Y-m-d H:i:s',$vo['addtime']); ?></td>
                    <td>
                        <a class="btn btn-xs btn-primary" href="<?php echo url('health/edit',['id'=>$vo['id']]); ?>"><?php echo lang('EDIT'); ?></a>
                        <a class="btn btn-xs btn-danger js-ajax-delete" class="" href="<?php echo url('health/delete',['id'=>$vo['id']]); ?>"><?php echo lang('DELETE'); ?></a>
                    </td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
        
        <div class="table-actions">
            <button class="btn btn-primary btn-sm js-ajax-submit" type="submit"><?php echo lang('SORT'); ?></button>
        </div>
        <div class="pagination"><?php echo (isset($page) && ($page !== '')?$page:''); ?></div>
    </form>
</div>
<script src="/static/js/admin.js"></script>
</body>
</html>