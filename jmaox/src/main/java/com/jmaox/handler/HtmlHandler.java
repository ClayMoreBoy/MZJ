package com.jmaox.handler;

import com.jfinal.handler.Handler;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 * Created by liuyang on 15/4/8.
 */
public class HtmlHandler extends Handler {

    @Override
    public void handle(String s, HttpServletRequest httpServletRequest, HttpServletResponse httpServletResponse, boolean[] booleans) {
        if (!s.contains("ueditor-1_4_3_1") && s.lastIndexOf(".html") != -1) {
            s = s.substring(0, s.indexOf(".html"));
        }
        nextHandler.handle(s, httpServletRequest, httpServletResponse, booleans);
    }
}
