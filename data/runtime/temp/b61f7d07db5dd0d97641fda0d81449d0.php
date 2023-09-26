<?php /*a:2:{s:122:"/www/wwwroot/kyhospital.sdwanyue.com/kyhospital.sdwanyue.com/public/themes/admin_simpleboot3/admin/registration/index.html";i:1694850606;s:111:"/www/wwwroot/kyhospital.sdwanyue.com/kyhospital.sdwanyue.com/public/themes/admin_simpleboot3/public/header.html";i:1695265665;}*/ ?>
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
      <li class="active"><a>订单列表</a></li>
    </ul>
    <form class="well form-inline margin-top-20" method="post" action="<?php echo url('index'); ?>">

      关键字：
      <input class="form-control" type="text" name="keyword" style="width: 200px;" value="<?php echo input('request.keyword'); ?>"
        placeholder="医生">
      订单状态：
      <select name="status" id="" class="form-control" style="width: 200px;">
        <option disabled selected>请选择</option>
        <option value="1" <?php if(input('request.status') == 1): ?>selected<?php endif; ?>>未支付</option>
        <option value="2" <?php if(input('request.status') == 2): ?>selected<?php endif; ?>>已支付</option>
      </select>
      <input type="submit" class="btn btn-primary" value="搜索" />
      <a class="btn btn-danger" href="<?php echo url('index'); ?>">清空</a>
    </form>
    <form method="post" class="js-ajax-form">
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>医生</th>
            <th>用户</th>
            <th>平台订单号</th>
            <th>金额</th>
            <th>支付状态</th>
            <th>支付时间</th>
            <th>日期</th>
            <?php if($type == 1): ?>
              <th>预约时间</th>
            <?php endif; ?>
            <th><?php echo lang('ACTIONS'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$vo): ?>
            <tr>
              <td><?php echo $vo['id']; ?></td>
              <td><?php echo $vo['doctor_name']; ?></td>
              <td><?php echo $vo['user_nickname']; ?></td>
              <td><?php echo $vo['orderno']; ?></td>
              <td><?php echo $vo['money']; ?>￥</td>
              <td><?php echo $vo['status']; ?></td>
              <td><?php echo $vo['paytime']; ?></td>
              <td width="10%"><?php echo date('Y-m-d',$vo['addtime']); ?></td>
              <?php if($type == 1): ?>

                <td width="10%"><?php echo $vo['servicetime']; ?></td>
              <?php endif; ?>
              <td>
                <a class="label label-success "
                  href="<?php echo url('registration/read',['id'=>$vo['id'],'type'=>$type]); ?>">订单信息</a>
                <?php if($type == 1): if($vo['status_s'] == 2): if($vo['complete'] == 0): ?>
                      <a class="btn btn-xs btn-warning js-ajax-dialog-btn"
                        href="<?php echo url('registration/complete',['id'=>$vo['id']]); ?>" data-msg="确定核销吗？">核销</a>
                      <?php else: ?>
                      <a class="label label-success" href="javascript:;">已核销</a>
                    <?php endif; ?>
                  <?php endif; ?>
                <?php endif; if($type == 2): if($vo['status_s'] == 2): if($vo['complete'] == 0): ?>
                      <a class="label label-success " href="<?php echo url('chat/index/index',['id'=>$vo['id']]); ?>"
                        target="_blank">问诊回复</a>
                      <?php else: ?>
                      <a class="label label-success " href="javascript:;">已完成</a>
                    <?php endif; ?>
                  <?php endif; ?>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo (isset($page) && ($page !== '')?$page:''); ?></div>
    </form>
  </div>
  <script src="/static/js/admin.js"></script>
</body>

</html>