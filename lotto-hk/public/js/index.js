$(function() {
    var setTime = setTimeout(function() {
        $(".xy").css({
            "color": "#fff",
            "background": "#ED2842"
        });
        if($(".xy").length != 0) {
            clearTimeout(setTime)
        }
    }, 100)
})
var indexFunObj = {};
var nextIssue = 0; //存放下一期的期号
$(function() {
    //初始化时间控件
    $(".header>ul>li:first-child").find("a").css("color", "red");
    $('#datebox').calendar({
        zIndex: 999,
    });
    //属性参照表
    attrTable();
    var arr, bottomArr;
    //  var arr = [22, 14, 22, 31, 19, 5, 14, 27, 19, 39, 40, 27, 14, 29, 19, 48, 14, 34, 30, 41];
    //  var bottomArr = [121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 137, 138, 139, 140];
    var periods = 20;
    $.ajax({
        type: "get",
        url: url.config140 + "findSpecialNumberTrend.do",
        data: {
            "periods": periods
        },
        dataType: "json",
        success: function(data) {
            console.log(data);
            if(data.result.data == "") {
                return false
            }
            bottomArr = data.result.data.issues;
            arr = data.result.data.numbers;
            //          console.log(arr);

            var ctx = c1.getContext('2d'); // 获得画笔
            //设置字体样式
            ctx.font = '14px Arial Regular';
            ctx.textBaseline = 'alphabetic';
            ctx.shadowBlur = '';

            //绘制 x y 轴主杆
            ctx.beginPath();
            ctx.strokeStyle = '#B4B4B4';
            ctx.lineWidth = 1;
            ctx.lineJoin = 'round'; //round bevel miter  --箭头样式
            ctx.moveTo(810, 325.5);
            ctx.lineTo(50.5, 325.5);           
            ctx.lineTo(50.5, 25.5);
            ctx.stroke();

            //绘制内容行5线
            var x = 75.5,
                y = 800,
                z = 50,
                t = 50;
            for(var i = 0; i < 5; i++) {
                ctx.beginPath();               
                ctx.strokeStyle = '#DEDEDE';
                ctx.lineWidth = 1;
                drawDashedLine(ctx, z, x, y, x,4);
                ctx.stroke();

                //绘制 x 轴文字
                ctx.font = '16px Arial Regular';
                ctx.fillStyle = "#999999"
                ctx.fillText(t, z - 25, x);
                t -= 10;
                x += 50;
                if(t == 0) {
                    ctx.fillText(t, z - 25, x);
                }
            }
            // 绘制虚线函数！！
            function drawDashedLine(ctx, x1, y1, x2, y2, dashLength) {
                dashLength = dashLength === undefined ? 5 : dashLength;
                var deltaX = x2 - x1;
                var deltaY = y2 - y1;
                var numDashes = Math.floor(
                    Math.sqrt(deltaX * deltaX + deltaY * deltaY) / dashLength);
                for(var i = 0; i < numDashes; ++i) {
                    ctx[i % 2 === 0 ? 'moveTo' : 'lineTo']
                        (x1 + (deltaX / numDashes) * i, y1 + (deltaY / numDashes) * i);
                }
                ctx.stroke();
            };

            //绘制 y 轴小杆杆与 y 轴的数据
            var a = 70,
                b = 325,
                d = 315;
            ctx.font = '14px Arial Regular';
            for(var c = 0; c < bottomArr.length; c++) {
                ctx.beginPath();
                ctx.moveTo(a, b);
                ctx.lineTo(a, d);
                ctx.strokeStyle = '#ccc';
                ctx.lineWidth = 0.5;
                ctx.stroke();
                ctx.fillText(bottomArr[c], a - 10, b + 20);
                a += 38;
            }

            //数据
            var arr2 = [];

            //绘制圆点
            var h = 70;
            for(var f = 0; f < arr.length; f++) {
                var k = 0;
                ctx.beginPath();
                if(arr[f] > 50) {
                    k = 25 + (50 - (50 * (((arr[f] - 50) * 10) / 100)));
                } else if(arr[f] > 40) {
                    k = 75 + (50 - (50 * (((arr[f] - 40) * 10) / 100)));
                } else if(arr[f] > 30) {
                    k = 125 + (50 - (50 * (((arr[f] - 30) * 10) / 100)));
                } else if(arr[f] > 20) {
                    k = 175 + (50 - (50 * (((arr[f] - 20) * 10) / 100)));
                } else if(arr[f] > 10) {
                    k = 225 + (50 - (50 * (((arr[f] - 10) * 10) / 100)));
                } else {
                    k = 275 + (50 - (50 * ((arr[f] * 10) / 100)));
                }
                arr2.push(k); //保存当前 y 坐标信息, 为绘制折线做准备
                ctx.arc(h, k, 4, 0, 2 * Math.PI);
                ctx.fillStyle = '#FC223B';
                ctx.fill();
                ctx.fillStyle = '#666666';
                ctx.fillText(arr[f], h - 8, k - 13); // 每个点的数据

                h += 38;

            }

            //绘制折线
            var j = 70;
            ctx.beginPath();
            for(var f = 0; f < arr2.length; f++) {
                ctx.lineTo(j, arr2[f]);
                ctx.strokeStyle = '#F8213B';
                ctx.lineWidth = 0.8;
                j += 38;
            }

            ctx.stroke();
        }
    })
});

function formatDate(date) {
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    m = m < 10 ? '0' + m : m;
    var d = date.getDate();
    d = d < 10 ? ('0' + d) : d;
    return y + '-' + m + '-' + d;
};

function attrTable() {
    $(".propTable").on("click", "ul>li", function(e) {
        e.preventDefault();
        $(this).addClass("checked").siblings().removeClass("checked");
        var tableId = $(this).find("a").attr("href");
        console.log(tableId);
        $("#" + tableId).css("display", "table").siblings("table").css("display", "none");
    })
}

//小六当前开奖
var thisText = "";
sessionArr = {}, sessionArr.numberCode = [], sessionArr.zooCode = [], sessionArr.color = [];
var ifAnimate = false;

indexFunObj.ifAnimateFun = function(thisTime, severTime, data, IGKbet, ifAnimate) {

    var thTime = thisTime.split(" ");
    var neTime = severTime.split(" ");

    if(thTime[0] == neTime[0]) { // 年月日相同
        thTime = thTime[1].split(":")
        neTime = neTime[1].split(":")
        console.log(thTime, neTime);
        if(thTime[0] == neTime[0]) { // 时相同
            $(".kjspr .data").hide();
            $("#kjType").css("color", "red").text(thisText);
            var minu = (thTime[1] * 1) - (neTime[1] * 1) - 1; // 得到分————开奖分 - 服务器分
            var secound = 60 - (neTime[2] * 1); //得到秒
            console.log(thTime, neTime);
            var setInt = setInterval(function() {
                secound -= 2;
                if(secound < 0) {
                    secound = 59;
                    minu -= 1
                }
                console.log(minu, secound)
                fenzhong(minu)
            }, 2000)

            function fenzhong(minu) {
                //              console.log(minu)
                if(minu >= 2) { //大于或等于
                    $("#kjType").text("请不要走开，今天晚上21：30开奖...")
                } else if(minu >= 1) {
                    $("#kjType").text("准备报码，请稍后...")
                } else if(minu >= 0) {
                    $("#kjType").text("节目广告中...")
                } else if(minu >= -1) {
                    $("#kjType").text("主持人解说中...")
                } else if(minu >= -2 || minu >= -6) {
                    $("#kjType").text("正在搅珠中...");
                    thisText = "正在搅珠中...";
                    ifAnimate = true;
                    clearInterval(setInt)
                    var timeOut = setTimeout(function() {
                        TishIssuc(ifAnimate,true);
                        clearTimeout(timeOut);
                    }, 1000)
                } else {
                    $(".predrawCode").html(data.result.data.preDrawIssue)
                    indexFunObj.elseFun(data, IGKbet, false)
                    clearInterval(setInt);
                    $(".kjspr .data").show();
                    $("#kjType").css("color", "#333").text("开奖结果");
                }
            }

            $(".predrawCode").text(data.result.data.drawIssue);
        } else {
            $(".predrawCode").html(data.result.data.preDrawIssue)
            indexFunObj.elseFun(data, IGKbet, ifAnimate)
        }
    } else {
        $(".predrawCode").html(data.result.data.preDrawIssue)
        indexFunObj.elseFun(data, IGKbet, ifAnimate)
    }
}
indexFunObj.elseFun = function(data, IGKbet, ifAnimate) {
    var firstSpan = $(".sh_xzlist>li>span:first-child"); //html-生肖标签
    var twoSpan = $(".sh_xzlist>li>span:last-child"); //html-五行标签
    var jnumber = $("#jnumber>li:not(.addpic)") //html-开奖号标签
    var i = 0;
    if(ifAnimate) { // 如果是当前开奖动画 
        var animInterval = setInterval(function() {
            animFun(i);
            i++;
            if(data.result.data.fiveElements==""){
                clearInterval(animInterval);
                return false;
            }
            if(i >= 7) {
                $("#kjType").text("开奖结果").css("color", "#333")
                clearInterval(animInterval);                
            }
            console.log(i)
        }, 800)
    } else { //  没有开奖效果
        for(var c = 0; c < 7; c++) {
            animFun(c)
        }
    }
    function animFun(i) {
        if(IGKbet.ThisCode[i]==undefined || proto.fiveLineArr[data.result.data.fiveElements[i]]==undefined){
            return false;
        }
        if(data.result.data.fiveElements[i]==undefined){
            return false
        }
        firstSpan[i].innerHTML = (proto.Zoo[data.result.data.chineseZodiac[i]]); //生肖
        twoSpan[i].innerHTML = (proto.fiveLineArr[data.result.data.fiveElements[i]]); //五行
        jnumber[i].className = proto.colorEng[data.result.data.color[i]]; //颜色         
        jnumber[i].innerHTML = IGKbet.ThisCode[i] > 9 ? IGKbet.ThisCode[i] : "0" + IGKbet.ThisCode[i] //开奖号码
    }
    $(".kjspr>.data").html((data.result.data.preDrawTime).slice(0, 10)) //上期开奖时间与期号 

    ifAnimate = false;
}

function TishIssuc(ifAnimate,circulation) {
    $.ajax({
        type: "get",
        url: url.config140 + "findSmallSixInfo.do",
        //      data: {
        //          issue: nextIssue
        //      },
        dataType: "json",
        success: function(data) {
            if(circulation){
                if(data.result.data.type!=4){
                    var timeajax=setTimeout(function(){
                        TishIssuc(true,true);
                        clearTimeout(timeajax)
                    },1000)
                }
            }
            console.log(data);
            nextIssue = data.result.data.drawIssue; //下一期开奖期号 
            var IGKbet = {};
            IGKbet.nextTime = data.result.data.drawTime; //下期的开奖时间
            IGKbet.ThisCode = data.result.data.preDrawCode.split(","); //获得当前开奖数组
            indexFunObj.ifAnimateFun(data.result.data.drawTime, data.result.data.serverTime, data, IGKbet, ifAnimate)
            //下期开奖的时间
            $(".nextcode").next().html(data.result.data.drawIssue).next().next().html("&nbsp;&nbsp;" + (IGKbet.nextTime).slice(5, 7) + "月" + (IGKbet.nextTime).slice(8, 10) + "日&nbsp;" + (IGKbet.nextTime).slice(11, 13) + "时" + (IGKbet.nextTime).slice(14, 16) + "分")
        }
    })
}
TishIssuc(ifAnimate,false);

function ready() {
    var date = new Date();
    var year = date.getFullYear();
    var moth = date.getMonth() + 1;
    var years = date.getFullYear();
    $("#date_year>p").html(year + "/" + moth);
    dateFun(year, moth);

    // 加载首页广告琏接
    //$("#index_ad").attr("href", url.index_adurl)
}

ready()
$("#date_year").on("click", "button", function(e) {
    if($(e.target).attr("class") == "date_lt") { //往左 减数
        var year_mon = $("#date_year>p").html().split("/");
        var y = Number(year_mon[0]);
        m = Number(year_mon[1]);
        m -= 1;
        if(m < 1) {
            m = 12;
            y -= 1;
        }
        $("#date_year>p").html(y + "/" + m);
        dateFun(y, m)
        console.log(y + "-" + m)
    } else { //往右 加数 
        var year_mon = $("#date_year>p").html().split("/");
        var y = Number(year_mon[0]);
        m = Number(year_mon[1]);
        m += 1;
        if(m > 12) {
            m = 1;
            y += 1;
        }
        $("#date_year>p").html(y + "/" + m);
        dateFun(y, m)
    }
})

function dateFun(y, m) { //ajax 加载传入年月的数据
    if((m * 1) < 10) {
        m = "0" + m;
    }
    console.log(m);
    $.ajax({
        type: "get",
        url: url.config140 + "queryLotteryDate.do",
        data: {
            "ym": y + "-" + m
        },
        success: function(data) {
            //              console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if(data.result.data == "") {
                return false;
            }
            var thisDate = new Date().toLocaleDateString().replace("年", "/").replace("月", "/").replace("日", "").split("/");
            var date = new Date();
            var year = date.getFullYear();
            var moth = date.getMonth() + 1;
            var day = date.getDate();
            var hour = date.getHours();
            var minu = date.getMinutes();
            console.log(hour, minu)
            var html = "";
            $.each(data.result.data.kjDate, function(i, d) {
                if(d[0] == 0) {
                    d[0] = " ";
                } else if(d[0] < 10) {
                    d[0] = "0" + d[0]
                }

                if(Number(y) >= year & Number(m) >= moth & Number(d[0]) >= day) {
                    if(d[1] == 1) {
                        d[1] = "red"
                    } else {
                        d[1] = "";
                    }

                } else if(Number(y) >= year & Number(m) > moth) {
                    if(d[1] == 1) {
                        d[1] = "red"
                    } else {
                        d[1] = "";
                    }

                } else {
                    if(d[1] == 1) {
                        d[1] = "grey"
                    } else {
                        d[1] = "";
                    }
                }
                if(Number(y) == year & Number(m) == moth & Number(d[0]) == day & hour >= 21 & minu >= 35 || Number(y) == year & Number(m) == moth & Number(d[0]) == day & hour > 21) {
                    if(d[1] == "red") {
                        d[1] = "grey"
                    } else {
                        d[1] = "";
                    }
                }
                html += "<li class='" + d[1] + "'>" + d[0] + "</li>"
                $("#date_day>ul").html(html);
            })
            if(data.result.data.kjDate.length > 35) {
                $(".box .box_firstr .timeboxbg #date_day>ul>li").css("margin-bottom", "4px")
            }
        }
    });
}

function ycFun(nextIssue) {

    $.ajax({
        type: "get",
        url: url.config140_2 + "mapDepot/findHomePageMapDepotList.do",
        data: {
            issue: nextIssue
        },
        success: function(data) {
            data = JSON.parse(data);
            console.log(data);
            if(data.result.data == "") {
                console.log("mapDepot/findHomePageMapDepotList.do==============空");
                return false;
            }
            var html = "",
                html2 = "";
            $.each(data.result.data, function(i, n) {
                var str = n.issue.toString();
                var newIssue = str.slice(str.length - 3);

                html += "<li><a href='html/yuctk.html?sue=" + n.issue + "&Pid=" + n.newspaperId + "'><img src='" + url.photoUrl + n.imageA + "' /><span>第" + newIssue + "期 " + n.newspaperName + "</span></a></li>";

                if(i > 5) {
                    html2 += "<li><a href='html/yuctk.html?issue=" + n.issue + "&Pid=" + n.newspaperId + "'>第" + newIssue + "期 " + n.newspaperName + "</a></li>";

                }
            })
            $(".yctk_more>ul").html(html);
            $(".drawlist>ul").html(html2);

            $(".yctk_more").ready(function() {
                Kright = $(".yctk_more>ul>li").length - 6;
                console.log(Kright);
                if(Kright <= 0) {
                    $(".spanlabel>.right").addClass("disabled");
                }
                $(".yctk_more>ul").css({
                    "width": $(".yctk_more>ul>li").length * 196 + "px"

                });
            })
        }
    })
}
$(".spanlabel>span").mouseenter(function(e) {
    if($(this).hasClass("right")) {

        $(this).find("img").attr("src", "img/next_2.png");

    } else if($(this).hasClass("left")) {

        $(this).find("img").attr("src", "img/prev_2.png")

    }
})
$(".spanlabel>span").mouseleave(function(e) {
    if($(this).hasClass("right")) {

        $(this).find("img").attr("src", "img/next.png");

    } else if($(this).hasClass("left")) {

        $(this).find("img").attr("src", "img/prev.png")

    }
})

var liWidth = 196;
var viewCount = 6;
var Klfet = 0,
    Kright = 0,
    leftpx = 0;

$(".spanlabel").on("click", "span:not('.disabled'),span:not('.disabled')>img", function(e) { // 计算可滑动左右的个数

    if($(e.target).attr("class") == "right" || $(e.target).parent().attr("class") == "right") {
        if(Kright >= 6) {
            leftpx += viewCount * liWidth;
            Klfet += viewCount;
            Kright -= viewCount;
        } else {
            leftpx += Kright * liWidth;
            Klfet += Kright;
            Kright -= Kright;
        }

    } else if($(e.target).attr("class") == "left" || $(e.target).parent().attr("class") == "left") {
        if(Klfet >= 6) {
            leftpx -= viewCount * liWidth;
            Kright += viewCount;
            Klfet -= viewCount;
        } else {
            leftpx -= Klfet * liWidth;
            Kright += Klfet;
            Klfet -= Klfet;
        }

        $(".yctk_more>ul").css("left", leftpx + "px");
    }
    if(Klfet > 0) {
        $(".left").removeClass("disabled");
    } else {
        $(".left").addClass("disabled");
    }
    if(Kright > 0) {
        $(".right").removeClass("disabled");
    } else {
        $(".right").addClass("disabled");
    }
    $(".yctk_more>ul").css("left", -leftpx + "px");
    //  console.log(Kright, Klfet, leftpx);
})
//$(function() {
//  var issueInt = setInterval(function() {
ycFun(nextIssue);
//      if(nextIssue != 0) {
//          clearInterval(issueInt)
//      }
//  }, 1000)
//})

$(".propKinds>li:nth-child(2)").one("click", function() { //请求生肖对照表
    $.ajax({
        type: "get",
        url: url.config140_2 + "smallSix/findChineseZodiac.do",
        async: true,
        dataType: 'json',
        success: function(data) {
            console.log(data);
            if(data.result.data == "") {
                return false
            }
            var zoo = data.result.data.animals.split(",");
            var html = "";
            for(var i = 0; i < zoo.length; i++) {
                if(i == 0) {
                    html += "<th colspan='3'>" + zoo[i] + "</th><th class='itemspace' rowspan='3'></th>";
                } else if(i == 11) {
                    html += "<th colspan='2'>" + zoo[i] + "</th>";
                } else {
                    html += "<th colspan='2'>" + zoo[i] + "</th><th class='itemspace' rowspan='3'></th>";
                }
            }
            $(".zoodata").html(html);
        }
    });
    console.log("1")
})
$(".propKinds>li:nth-child(3)").one("click", function() { //请求五行对照表
    $.ajax({
        type: "get",
        url: url.config140_2 + "smallSix/findFiveElements.do",
        async: true,
        dataType: 'json',
        success: function(data) {
            console.log(data);
            if(data.result.data == "") {
                return false
            }
            var one = data.result.data.metalNumber.split(","),
                two = data.result.data.woodNumber.split(","),
                three = data.result.data.waterNumber.split(","),
                four = data.result.data.fireNumber.split(","),
                five = data.result.data.earthNumber.split(","),
                onehtml = "",
                twohtml = "",
                threehtml = "",
                fourhtml = "",
                fivehtml = "",
                onehtml2 = "",
                twohtml2 = "",
                threehtml2 = "",
                fourhtml2 = "",
                fivehtml2 = "";
            var td = "<td></td>"
            for(var i = 0; i < 10; i++) {
                //                  console.log(one[i]);
                if(i < 5) {
                    onehtml += "<td>" + one[i] + "</td>";
                    twohtml += "<td>" + two[i] + "</td>";
                    threehtml += "<td>" + three[i] + "</td>";
                    fourhtml += "<td>" + four[i] + "</td>";
                    fivehtml += "<td>" + five[i] + "</td>"
                } else {
                    onehtml2 += "<td>" + one[i] + "</td>";
                    twohtml2 += "<td>" + two[i] + "</td>";
                    threehtml2 += "<td>" + three[i] + "</td>";
                    fourhtml2 += "<td>" + four[i] + "</td>";
                    if(five[i] != undefined) {
                        fivehtml2 += "<td>" + five[i] + "</td>";
                    } else {
                        fivehtml2 += "<td>&nbsp;</td>";
                    }

                }
            }
            var html = "<tr>" + onehtml + td + twohtml + td + threehtml + td + fourhtml + td + fivehtml + "</tr>" +
                "<tr>" + onehtml2 + td + twohtml2 + td + threehtml2 + td + fourhtml2 + td + fivehtml2 + "</tr>";
            $(".fiveNumber").html(html)

        }
    });
})
// $(function() { //  请求资迅栏列表大岗
//     $.ajax({
//         type: "get",
//         url: url.config140_2 + "programa/findDisplay.do",
//         async: false,
//         dataType: "json",
//         success: function(data) {
//             if(data.result.data == "") {
//                 return false
//             }
//             console.log(data);
//             $(".yctj>div>h1").text(data.result.data[0].name).next().find("a").attr("href", "html/news_list.html?" + data.result.data[0].id);
//             LanmuFun(data.result.data[0].id, "yctj")
//             $(".zjxs>div>h1").text(data.result.data[1].name).next().find("a").attr("href", "html/news_list.html?" + data.result.data[1].id);
//             LanmuFun(data.result.data[1].id, "zjxs")
//             //          $(".ltrt>div>h1").text(data.result.data[2].name).next().find("a").attr("href", "html/news_list.html?" + data.result.data[1].id);
//             //          LanmuFun(data.result.data[2].id, "ltrt")
//
//         }
//     });
//
// })

// function LanmuFun(id, elem) {
//     $.ajax({
//         type: "get",
//         url: url.config140_2 + "news/findNewsByPIdForPage.do",
//         async: false,
//         data: {
//             programaId: id,
//             pageNo: "",
//             pageSize: 5 //  首页资迅栏信息显示5条
//         },
//         dataType: "json",
//         success: function(data) {
//             $("." + elem + ">ul").html("");
//             console.log(data);
//             if(data.result.data == "") {
//                 return false;
//             }
//             var html = "";
//             $.each(data.result.data.list, function(i, p) {
//                 html += "<li> <a href='html/news_detail.html?" + p.newsId + "'>" + p.title + "</a></li>";
//
//             });
//             $("." + elem + ">ul").html(html);
//
//         }
//     });
// }
$(".box_third>div>div>span").on("click", "a", function() {
    sessionStorage.setItem("bread_title", $(this).parent().prev().text());
})

$(".box_third").on("click", "div>ul>li>a", function() {
    sessionStorage.setItem("bread_text", $(this).text());
    sessionStorage.setItem("bread_title", $(this).parent().parent().prev().find("h1").text());
})