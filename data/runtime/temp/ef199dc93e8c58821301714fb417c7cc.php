<?php /*a:2:{s:115:"/www/wwwroot/kyhospital.sdwanyue.com/kyhospital.sdwanyue.com/public/themes/admin_simpleboot3/admin/doctor/edit.html";i:1693624391;s:111:"/www/wwwroot/kyhospital.sdwanyue.com/kyhospital.sdwanyue.com/public/themes/admin_simpleboot3/public/header.html";i:1695265665;}*/ ?>
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
<style>
    .ba{
        margin-bottom: 10px;
        padding: 5px 0;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    #time{
        position: relative;
    }
    .add,
    .delete{
        right: 200px;
        position: absolute;
        height: 30px;
        width: 100px;
        border-radius: 10px;
        background-color: green;
        text-align: center;
        line-height: 30px;
    }
    .delete{
        background-color: red;
        top: 50px;
    }
    .add a,.delete a{
        color: #ffffff;
        text-decoration: none;
    }
</style>
<body>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo url('Doctor/index'); ?>">医生列表</a></li>
		<li class="active"><a href="<?php echo url('Doctor/Add'); ?>"><?php echo lang('EDIT'); ?></a></li>
    </ul>
    <form class="form-horizontal js-ajax-form margin-top-20" action="<?php echo url('Doctor/editpost',['id'=>$info['id']]); ?>" method="post">
        <div class="form-group">
            <label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>名称</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="input-name" name="name" value="<?php echo $info['name']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>职称</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="input-name" name="professional" value="<?php echo $info['professional']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>标识</label>
            <div class="col-md-6 col-sm-10">
                <select class="form-control"  name="signboard_id">
                    <?php if(is_array($signboard) || $signboard instanceof \think\Collection || $signboard instanceof \think\Paginator): $i = 0; $__LIST__ = $signboard;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <option  value="<?php echo $vo['id']; ?>"
                        <?php if($vo['id'] == $info['signboard_id']): ?>selected <?php endif; ?>  ><?php echo $vo['name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>头像</label>
            <div class="col-md-6 col-sm-10">
                <input type="hidden" name="img" id="thumb" value="<?php echo $info['img']; ?>">
                <a href="javascript:uploadOneImage('图片上传','#thumb');">
                    <?php if(empty($info['img'])): ?>
                        <img src="/themes/admin_simpleboot3/public/assets/images/default-thumbnail.png" id="thumb-preview"
                        width="135" style="cursor: hand"/>
                        <?php else: ?>
                        <img src="<?php echo cmf_get_image_preview_url($info['img']); ?>" id="thumb-preview"
                        width="135" style="cursor: hand"/>
                    <?php endif; ?>
                </a>
                <input type="button" class="btn btn-sm"
                       onclick="$('#thumb-preview').attr('src','/themes/admin_simpleboot3/public/assets/images/default-thumbnail.png');$('#thumb').val('');return false;"
                       value="取消图片">	
            </div>
        </div>
        <div class="form-group">
            <label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>介绍</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="input-name" name="content" value="<?php echo $info['content']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>擅长</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="input-name" name="message" value="<?php echo $info['message']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>费用</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="input-name" name="cost" value="<?php echo $info['cost']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>在线问诊费用</label>
            <div class="col-md-6 col-sm-10">
                <input type="text" class="form-control" id="input-name" name="online_cost" value="<?php echo $info['online_cost']; ?>">
            </div>
        </div>
        <div class="form-group" id="time">
            <label for="input-name" class="col-sm-2 control-label"><span class="form-required">*</span>预约时间段</label>
            <div class="col-md-6 col-sm-10" id="info">
              <?php if(is_array($info['info']) || $info['info'] instanceof \think\Collection || $info['info'] instanceof \think\Paginator): if( count($info['info'])==0 ) : echo "" ;else: foreach($info['info'] as $key=>$vo): ?>
                <div class="ba" index="<?php echo $key; ?>">
                   
                    <div class="form-group">
                        <label for="input-name" class="col-sm-3 control-label"><span class="form-required">*</span>开始时间</label>
                        <div class="col-md-3 col-sm-6">
                            <input type="time" name="info[<?php echo $key; ?>][time_start]" class="form-control" value="<?php echo $vo['time_start']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input-name" class="col-sm-3 control-label"><span class="form-required">*</span>结束时间</label>
                        <div class="col-md-3 col-sm-6">
                            <input type="time" name="info[<?php echo $key; ?>][time_end]" class="form-control" value="<?php echo $vo['time_end']; ?>">
                        </div>
                    </div>
                 </div>
              <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="add">
                <a href="javascript:;">添加时间段</a>
            </div>
            <div class="delete" hidden>
                <a href="javascript:;">删减</a>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary js-ajax-submit">添加</button>
            </div>
        </div>
    </form>
</div>
<script src="/static/js/admin.js"></script>
<script>
    let length = $('#info .ba').length;
        if(length+1>1){
            $('.delete').show()
        }
    $('.add').click(function(){
        let length = $('#info .ba').length;
        if(length+1>1){
            $('.delete').show()
        }
        let el = $('#info .ba').eq(-1).clone(true);
        let index = parseInt(el.attr('index')) + 1;
        // let text_week = `info[${index}][text_week]`;
        let time_start = `info[${index}][time_start]`;
        let time_end = `info[${index}][time_end]`;
        el.attr('index',index)
        // el.find('select').attr('name',text_week);
        // el.find('select').val(1);
        el.find('input').eq(0).attr('name',time_start);
        el.find('input').eq(0).val('')
        el.find('input').eq(1).attr('name',time_end);
        el.find('input').eq(1).val('')
        $('#info').append(el)
    })
    $('.delete').click(function(){
        let lenfth = $('#info .ba').length;
        if(lenfth-1 <=1 ){
            $('.delete').hide()
        }
        $('#info .ba').eq(-1).remove();
    })
</script>
</body>
</html>