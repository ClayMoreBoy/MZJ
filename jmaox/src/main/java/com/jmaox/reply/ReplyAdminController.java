package com.jmaox.reply;

import com.jmaox.common.BaseController;
import com.jmaox.common.Constants;
import com.jmaox.interceptor.AdminUserInterceptor;
import com.jmaox.user.User;
import com.jfinal.aop.Before;
import com.jfinal.core.Controller;
import com.jfinal.kit.PropKit;
import com.jfinal.plugin.activerecord.tx.Tx;

/**
 * Created by liuyang on 15/4/11.
 */
@Before(AdminUserInterceptor.class)
public class ReplyAdminController extends BaseController {

    public void index() {
        setAttr("page", Reply.me.page(getParaToInt("p", 1), PropKit.use("config.properties").getInt("page_size")));
        render("index.html");
    }

    @Before(Tx.class)
    public void delete() {
        String id = getPara("id");
        try {
            Reply reply = Reply.me.findById(id);
            User user = User.me.findById(reply.get("author_id"));
            getModel(Reply.class).set("id", id).set("content", "回复已被删除").set("isdelete", 1).update();
            if(user.getInt("score") <= 2) {
                user.set("score", 0).update();
            } else {
                user.set("score", user.getInt("score") - 2).update();
            }
            success();
        } catch (Exception e) {
            e.printStackTrace();
            error(Constants.DELETE_FAILURE);
        }
    }
}
