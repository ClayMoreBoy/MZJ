package com.jmaox.notification;

import com.jmaox.common.BaseController;
import com.jmaox.common.Constants;
import com.jmaox.interceptor.UserInterceptor;
import com.jmaox.user.User;
import com.jfinal.aop.Before;

/**
 * Created by liuyang on 15/4/7.
 */
public class NotificationController extends BaseController {

    @Before(UserInterceptor.class)
    public void countnotread() {
        User user = getSessionAttr(Constants.USER_SESSION);
        if(user == null) {
            error(Constants.ResultDesc.FAILURE);
        } else {
            try {
                int count = Notification.me.countNotRead(user.getStr("id"));
                success(count);
            } catch (Exception e) {
                e.printStackTrace();
                error(Constants.ResultDesc.FAILURE);
            }
        }
    }
}
