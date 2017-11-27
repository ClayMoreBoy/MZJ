//得到随机数
function excutenum() {
	var j = Math.floor(Math.random() * 10); //得到0到9的随机数
	return j;
}
//所有动画对象
var animateMethod = {};
var intervalSsc = null; //动画对象
var animateID = {}; //记录动画的ID
var time = 600; //超时时间
//公共头和尾部页面
var publicHeadOrf = {};
animateMethod.loadingList = function(obj, flag) {
	//true：开启；false：关闭
	var loadhtml = '<div id="loadingbox"></div>';
	if(flag) {
		$(obj).append(loadhtml);
	} else {
		$(obj).find("#loadingbox").remove();
	}
}
animateMethod.pk10OpenAnimate = function(id) {
	var time = 600;
	var jnumberid = $(id + " .numberbox").find("li");
	$(id).find(".opentyle").show();
	$(id).find(".cuttime").hide(); //隐藏倒计时
	var intervalPk10 = setInterval(function() {
		var arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
		var res = [];
		var lilength = 10;
		time--;
		for(var i = 0; i < lilength; i++) {
			var j = Math.floor(Math.random() * arr.length);
			res[i] = arr[j];
			arr.splice(j, 1);
		}
		var lihtml = "";
		for(var a = 0; a < lilength; a++) {
			var className = res[a] < lilength ? "nub0" + res[a] : "nub" + res[a];
			if(a == lilength - 1) {
				lihtml += "<li style='margin-right: 0px;' class='" + className + "'></li>"
			} else {
				lihtml += "<li class='" + className + "'></li>"
			}
		}
		$(id).find(".numberbox").empty();
		$(id).find(".numberbox").append(lihtml);
		if(time == 100) {
			$("#waringbox").show(300); //显示网络waring提示
		}
	}, 100);
	animateID[id] = intervalPk10; //向动画数据中放动画
}
animateMethod.pk10end = function(arr, id) {
	var arr = arr;
	var id = id;
	var arrlength = arr.length;
	var time = 0;
	var jnumberid = $(id).find(".numberbox");
	$(jnumberid).empty();
	var timer = setInterval(function() {
		var lastli = "";
		if(time < arrlength) {
			if(time == arrlength - 1) {
				lastli = "li_after";
			}
			var lihtml = "<li class='nub" + arr[time] + " " + lastli + "'><i style='font-size:10px; display:none'>" + arr[time] + "</i></li>";
			//			
			$(jnumberid).append(lihtml);
			time += 1;
		} else {
			clearInterval(timer);
		}
	}, 60);
	$(id).find(".opentyle").hide();
	$(id).find(".cuttime").show(); //隐藏倒计时
	$("#waringbox").hide("200");
}
animateMethod.sscAnimate = function(id) {
	if(id == "#klsf" || id == "gdklsf") {
		$(id).find(".numred").removeClass("numred");
	}
	var kajianhao = $(id).find(".kajianhao");
	var time = 600;
	//传入要执行动画的ID
	$(id).find(".opentyle").show();
	$(id).find(".cuttime").hide(); //隐藏倒计时
	var intervalSsc = setInterval(function() {
		$(id).find(".kajianhao li:last-child").css({
			"margin-right": "0"
		});
		//var arr = [1, 2, 3, 4, 5];
		var lilength = $(kajianhao).find("li").length;
		//console.log("lilength"+lilength);
		time--;
		for(var i = 0; i < lilength; i++) {
			//还原上次的定位
			$(kajianhao).find("li").eq(i).css({
				paddingTop: '0px'
			});
			$(kajianhao).find("li").eq(i).css({
				lineHeight: '0px'
			});
			//为li产生随机数字
			$(kajianhao).find("li").eq(i).text(excutenum());
			//追加动画
			var runspac = 50 * excutenum();
			$(kajianhao).find("li").eq(i).stop().animate({
				paddingTop: '35px'
			}, runspac);
			$(kajianhao).find("li").eq(i).stop().animate({
				lineHeight: '60px'
			}, 100);
		}
		if(time == 100) {
			$("#waringbox").show(300); //显示网络waring提示
		}
	}, 100);
	//记录动画的ID
	animateID[id] = intervalSsc;
}
//时时彩终极动画
animateMethod.sscAnimateEnd = function(num, id) {
	//传入要执行动画的最终号码和动画区域ID
	num = num.split(",");
	var kajianhao = $(id).find(".kajianhao");
	$(kajianhao).find("li").removeClass("numred");
	//终极真实数据显示
	for(var i = 0, len = num.length; i < len; i++) {
		if(num[i] >= 19) {
			$(kajianhao).find("li").eq(i).addClass("numred");
		};
		if(i < num.length) {
			$(kajianhao).find("li:last-child").css({
				"margin-right": "0"
			});
		}
		//还原上次的定位
		$(kajianhao).find("li").eq(i).css({
			paddingTop: '0px'
		});
		//为li产生随机数字
		$(kajianhao).find("li").eq(i).text(num[i]);
		//追加动画
		var runspac = 50 * excutenum();
		$(kajianhao).find("li").eq(i).stop().animate({
			lineHeight: '36px'
		}, runspac);
	}
	$("#waringbox").hide("200");
}
animateMethod.kuai3Animate = function(id) {
	var time = 600;
	//传入要执行动画的ID
	$(id).find(".opentyle").show();
	$(id).find(".cuttime").hide(); //隐藏倒计时
	intervalSsc = setInterval(function() {
		time--;
		var li = $(id).find(".kajianhao li");
		for(var i = 0, len = li.length; i < len; i++) {
			var sixnum = excutenum1_6();
			//还原上次的定位
			$(id).find(".kajianhao li").eq(i).className = "num" + sixnum * 1 + 1;
			//追加动画
			var runspac = 1 * sixnum;
			var poistY = kuaicase(excutenum1_6() * 1 + 1)
			$(id).find(".kajianhao li").eq(i).stop().animate({
				"backgroundPositionY": poistY
			}, runspac);
		}
		//生成一个定时任务
		//kuaisan.parm.lilength++;
		if(time == 100) {
			$("#waringbox").show(300); //显示网络waring提示
		}
	}, 100)
	animateID[id] = intervalSsc;
}
//时时彩终极动画
animateMethod.kuai3AnimateEnd = function(num, id) {
	num = num.split(",");
	//终极真实数据显示
	for(var i = 0, len = num.length; i < len; i++) {
		//还原上次的定位
		$(id).find("li").eq(i).css({
			paddingTop: '0px'
		});
		//为li产生随机数字
		//$(id).find("li").eq(i).text(num[i]);
		$(id).find("li")[i].className = ("num" + num[i]);
		$($(id).find("li")[i]).css({
			"background-position-y": ""
		});
		//追加动画
		var runspac = 50 * excutenum();
		$(id).find("li").eq(i).stop().animate({
			lineHeight: '36px'
		}, runspac);
	}
	$("#waringbox").hide("200");
}
animateMethod.cqncAnimate = function(id) {
	var time = 600;
	var jnumberid = $(id).find(".kajianhao ul");
	$(id).find(".opentyle").show();
	$(id).find(".cuttime").hide(); //隐藏倒计时
	intervalSsc = setInterval(function() {
		var arr = [01, 02, 03, 04, 05, 06, 07, 08];
		var res = [];
		var lilength = 10;
		time--;
		for(var i = 0; i < lilength; i++) {
			var j = Math.floor(Math.random() * arr.length);
			res[i] = arr[j];
			arr.splice(j, 1);
		}
		var lihtml = "";
		for(var a = 0; a < lilength; a++) {
			lihtml += "<li class='" + "ncnum0" + res[a] + "'></li>"
		}
		$(jnumberid).empty();
		$(jnumberid).append(lihtml);
		if(time == 100) {
			$("#waringbox").show(300); //显示网络waring提示
		}
	}, 100);
	animateID[id] = intervalSsc;
}
animateMethod.cqncAnimateEnd = function(num, id) {
	var id = id;
	num = num.split(",");
	var arrlength = num.length;
	var time = 0;
	var jnumberid = $(id).find(".kajianhao ul");
	$(jnumberid).html("");
	var timer = setInterval(function() {
		if(time < arrlength) {
			var lihtml = "<li class='ncnum" + num[time] + "'><i style='font-size:10px;display:none'>" + num[time] + "</i></li>";
			$(jnumberid).append(lihtml);
			time++;
		} else {
			clearInterval(timer);
		}
	}, 100);
	$(id).find(".opentyle").hide();
	$(id).find(".cuttime").show(); //隐藏倒计时
	$("#waringbox").hide("200");
}

function excutenum1_6() {
	var j = Math.floor(Math.random() * 6); //得到1到6的随机数
	return j;
}

function kuaicase(num) {
	switch(num) {
		case 1:
			return "0px";
			break;
		case 2:
			return "-43px";
			break;
		case 3:
			return "-86px";
			break;
		case 4:
			return "-129px";
			break;
		case 5:
			return "-172px";
			break;
		case 6:
			return "-215px";
			break;
	}
}