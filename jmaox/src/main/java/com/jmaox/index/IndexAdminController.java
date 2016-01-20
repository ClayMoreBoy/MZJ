package com.jmaox.index;

import com.jmaox.common.BaseController;
import com.jmaox.common.Constants;
import com.jmaox.interceptor.AdminUserInterceptor;
import com.jmaox.mission.Mission;
import com.jmaox.reply.Reply;
import com.jmaox.topic.Topic;
import com.jmaox.user.User;
import com.jfinal.aop.Before;

import java.util.List;

/**
 * Created by liuyang on 15/4/9.
 */
@Before(AdminUserInterceptor.class)
public class IndexAdminController extends BaseController {

    public void index() {
        //今日话题
        List<Topic> topics = Topic.me.findToday();
        setAttr("topics", topics);
        //今日回复
        List<Reply> replies = Reply.me.findToday();
        setAttr("replies", replies);
        //今日签到
        List<Mission> missions = Mission.me.findToday();
        setAttr("missions", missions);
        //今日用户
        List<User> users = User.me.findToday();
        setAttr("users", users);
        render("index.html");
    }

    public void logout() {
        removeSessionAttr(Constants.SESSION_ADMIN_USER);
        removeCookie(Constants.COOKIE_ADMIN_TOKEN);
        redirect(Constants.getBaseUrl() + "/");
    }
}
