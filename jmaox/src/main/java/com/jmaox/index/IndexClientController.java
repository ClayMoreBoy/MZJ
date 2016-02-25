package com.jmaox.index;

import com.jmaox.common.BaseController;
import com.jmaox.topic.Topic;
import com.jfinal.kit.PropKit;
import com.jfinal.plugin.activerecord.Page;

/**
 * Created by Tomoya on 15/6/9.
 */
public class IndexClientController extends BaseController {

    public void index() {
        String tab = getPara("tab");
        String q = getPara("q");
        if(tab == null) tab = "all";
        Page<Topic> page = Topic.me.paginateOrderByCreate(getParaToInt("page", 1),
                getParaToInt("size", PropKit.use("config.properties").getInt("page_size")), tab, q, 1, null);
        success(page);
    }
}