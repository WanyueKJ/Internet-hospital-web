
var init = 1;
var ws;
websocket()

//切换聊天用户
$('.conLeft li').on('click', function () {
  init = 1;
  $(this).addClass('bg').siblings().removeClass('bg');
  var intername = $(this).children('.liRight').children('.intername').text();
  var infor = $(this).children('.liRight').children('.infor').text();
  $('.headName').text(intername + ' -- ' + infor);
  $('.newsList').html('');
  websocket()
})


//消息发送
$('.sendBtn').on('click', function () {
  var news = $('#dope').val();
  if (news == '') {
    alert('不能为空');
  } else {
    $('#dope').val('');
    var str = '';
    str += '<li>' +
      '<div class="nesHead" style="width: 3px;"></div>' +
      '<div class="news"><img class="jiao" src="' + path + 'img/two.jpg">' + news + '</div>' +
      '</li>';
    $('.newsList').append(str);
    $('.RightCont').scrollTop($('.RightCont')[0].scrollHeight);

    let el = $('.bg');
    var fid = 0;
    var tid = el.attr('data-id');
    var oid = el.find('.infor').attr('oid');
    let data_ = {
      fid: fid,
      tid: tid,
      oid: oid,
      content: news
    }
    data_ = JSON.stringify(data_)
    ws.send(data_);
  }
})


//添加自己信息
function addain (vl) {
  var str = '';
  str += '<li>' +
    '<div class="nesHead" style="width: 3px;"></div>' +
    '<div class="news"><img class="jiao" src="' + path + 'img/two.jpg">' + vl + '</div>' +
    '</li>';
  $('.newsList').append(str);
  $('.RightCont').scrollTop($('.RightCont')[0].scrollHeight);
}

//添加别人信息
function addother (vl) {
  var answer = '';
  answer += '<li>' +
    '<div class="answerHead" style="width: 3px;"></div>' +
    '<div class="answers"><img class="jiao" src="' + path + 'img/one.jpg">' + vl + '</div>' +
    '</li>';
  $('.newsList').append(answer);
  $('.RightCont').scrollTop($('.RightCont')[0].scrollHeight);
}


//重新赋值当前websocket对象窗口
function websocket () {
  ws = new WebSocket(url_());
  ws.onmessage = function (e) {
    if (init) {
      init = 0;
      let vl = JSON.parse(e.data);
      if (vl.status == 2) {
        $('.inputBox').hide()
      } else if (vl.status == 1) {
        $('.inputBox').show()
      }
      let data = vl.data
      data = data.sort(function (a, b) { return a.addtime - b.addtime });

      $('#dope').val('');
      for (let i = 0; i < data.length; i++) {
        if (data[i]['signboard'] == 'ain') {
          let vl = data[i]['content'];
          addain(vl)
        } else {
          let vl = data[i]['content'];
          addother(vl)
        }
      }
    } else {
      let data = JSON.parse(e.data);
      if (data['status'] == 2) {
        $('.inputBox').hide()
        return;
      }
      let vl = data['data']['content'];
      addother(vl)
    }
  }
}

//获取请求url
function url_ () {
  let el = $('.bg');
  $('.conRight').find('.headName').text(el.find('.intername').text() + ' -- ' + el.find('.infor').text());
  let tid = el.attr('data-id');
  let oid = el.find('.infor').attr('oid');
  let url = webscoket_ + `?fid=0&tid=${tid}&oid=${oid}`;
  return url
}