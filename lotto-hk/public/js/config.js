//var src = "http://192.168.1.140/";
var url = { //前台接口url 
//      config140: src + "six-api/smallSix/",
//      config140_2: src + "six-api/",
//      photoUrl: "http://six-upload-test.s3-website-ap-northeast-1.amazonaws.com/",
//      photoUrl_2: "http://six-upload-test.s3-website-ap-northeast-1.amazonaws.com/",

    config140_2: "https://1680660.com/",
    index_adurl: "http://www.1680210.com/html/shishicai_jisu/ssc_index.html", //首页广告图链接
    news_adurl: "http://www.1680210.com/html/jisusaiche/pk10kai.html", //新闻页广告图链接  
    zoo_Aimg:"../static/index/picture/AA.jpg",  //开奖历史页面 生肖对照表两张图片url-----一年更换一次
    zoo_Bimg:"../static/index/picture/BB.jpg"

}
url.config140 = url.config140_2 + "smallSix/"
url.photoUrl="http://images.6hch.com/"
url.photoUrl_2="http://images.6hch.com/"
var tools={};
var config = {
    publicUrl: url.photoUrl_2,
    pageSizenum: 300, //分页条目数
    ifDebugger: false
}
var oldLog = console.log; //重写 console.log
console.log = function() {
    if(config.ifDebugger) {
        oldLog.apply(console, arguments);
    } else {
        return
    }

}
function sleep(numberMillis) {
    var now = new Date();
    var exitTime = now.getTime() + numberMillis;
    while(true) {
        now = new Date();
        if(now.getTime() > exitTime)
            return;
    }
}
var publictools = {};
publictools.getToken = function() {
    return window.localStorage.token;
}

$(function() {

    // $("#headdivbox").load("public/head.html", function() {
    //     console.log("request is over!");
    // });
    // $("#fooderbox").load("public/fooder.html", function() {
    //     console.log("request is over!");
    // });
})

//小六当前开奖
var proto = {
    issuc: "2017019",
    Zoo: ["", "鼠", "牛", "虎", "兔", "龙", "蛇", "马", "羊", "猴", "鸡", "狗", "猪"],
    fiveLineArr: ["", "金", "木", "水", "火", "土"],
    colorArr: ["", "#F8253E", "#1FC26B", "#0093E8"],
    colorEng: ["", "red", "green", "blue"],
    colorCh: ["", "红", "蓝", "绿"],
    jiaqzs: ["", "家", "野"],
    boy_girl: ["", "男", "女"],
    top_bottom: ["", "天", "地"],
    four_season: ["", "春", "夏", "秋", "冬"],
    cqsh: ["", "琴", "棋", "书", "画"]
}

//后面增加的用户反馈与客服联系
$("#fooderbox,.footer").on("click", "#gotop", function() {
    $('body,html').animate({
        scrollTop: 0
    }, 500);
    $(this).hide();
    return false;
});
$(document).scroll(function() {
    if($(this).scrollTop() > 10) {
        $("#gotop").show();
    } else {
        $("#gotop").hide();
    }
});
$("#fooderbox,.footer").on("mousemove", ".fankuicon", function() { //用户反馈按钮 移出 this 内容=="用户反馈"
    $(this).html("用户</br>反馈")
})
$("#fooderbox,.footer").on("mouseout", ".fankuicon", function() { //用户反馈按钮 移出 this 内容==""
    $(this).html("")
})

$("#fooderbox,.footer").on("click", ".bar_remove", function() { //右上角X ，点击关闭窗口
    $("#modal").hide();
})
$("#fooderbox,.footer").on("click", ".fankuicon", function() { //点击用户反馈按钮打开窗口
    $("#modal").show();
})

// 保存用户反馈信息
$("#fooderbox").on("click", "#btn_upload", function() {
    var fanku = {
        linkType: $("#fooderbox #select_op option:selected").val(),
        nickName: $("#fooderbox #first_input").val(),
        linkNumber: $("#fooderbox #fanku_text").val(),
        fedBack: $("#fooderbox #advice").val()
    }
    console.log(fanku)
    fankuFun(fanku)
})
$(".footer").on("click", "#btn_upload", function() {
    var fanku = {
        linkType: $(".footer #select_op option:selected").val(),
        nickName: $(".footer #first_input").val(),
        linkNumber: $(".footer #fanku_text").val(),
        fedBack: $(".footer #advice").val()
    }
    console.log(fanku)
    fankuFun(fanku)
})

function fankuFun(fanku) {
    $.ajax({
        type: "get",
        url: url.config140_2 + "fedBack/saveFedBack.do",
        async: true,
        data: fanku,
        dataType: "json",
        success: function(data) {
            console.log(data);
            alert(data.result.message);
            if(data.errorCode == 0) {
                $("#modal").hide();
            }
        },
        error: function(err) {
            console.log(err);
        }
    });

}

//判断是不是移动设备
tools.browserRedirect = function() {
    var sUserAgent = navigator.userAgent.toLowerCase();
    var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
    var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
    var bIsMidp = sUserAgent.match(/midp/i) == "midp";
    var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
    var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
    var bIsAndroid = sUserAgent.match(/android/i) == "android";
    if(sUserAgent.indexOf("android") > 0) {
        bIsAndroid = true;
    }
    var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
    var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
    if(bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
        var urlstr = window.location.href;
        var noAdvertiseWWW = "6hch.com";
        //noAdvertiseWWW = "192.168";//测试
        if(urlstr.indexOf(noAdvertiseWWW) != -1) {
            window.location.href = "http://m.6hch.com";
        }else{
            window.location.href = "http://m.6hch.com";
        }
    } else {
        window.location.href = "index.html";
    }
}