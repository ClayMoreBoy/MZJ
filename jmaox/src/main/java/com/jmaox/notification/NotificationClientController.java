package com.jmaox.notification;

import com.jmaox.common.BaseController;
import com.jmaox.common.Constants;
import com.jmaox.user.User;
import com.jmaox.utils.StrUtil;
import com.jfinal.kit.PropKit;
import com.jfinal.plugin.activerecord.Page;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * Created by liuyang on 2015/6/27.
 */
public class NotificationClientController extends BaseController {

    public void countnotread() {
        String token = getPara("token");
        if(StrUtil.isBlank(token)) {
            error("请先登录");
        } else {
            //根据token获取用户信息
            User user = User.me.findByToken(token);
            if (user == null) {
                error("用户不存在，请退出重新登录");
            } else {
                int count = Notification.me.countNotRead(user.getStr("id"));
                success(count);
            }
        }
    }

    public void index() {
        String token = getPara("token");
        if(StrUtil.isBlank(token)) {
            error("请先登录");
        } else {
            //根据token获取用户信息
            User user = User.me.findByToken(token);
            if(user == null) {
                error("用户不存在，请退出重新登录");
            } else {
                List<Notification> notifications = Notification.me.findNotReadByAuthorId(user.getStr("id"));
                List<Notification> oldMessages = Notification.me.findReadByAuthorId(user.getStr("id"), 20);
                //将消息置为已读
                Notification.me.updateNotification(user.getStr("id"));
                Map<String, Object> map = new HashMap<String, Object>();
                map.put("notifications", notifications);
                map.put("oldMessages", oldMessages);
                success(map);
            }
        }
    }
}
