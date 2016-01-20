package com.jmaox.section;

import com.jmaox.common.BaseController;

/**
 * Created by liuyang on 2015/6/27.
 */
public class SectionClientController extends BaseController {

    public void index() {
        success(Section.me.findShow());
    }
}
