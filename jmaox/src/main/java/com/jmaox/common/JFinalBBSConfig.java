package com.jmaox.common;

import com.jmaox.user.UserClientController;
import com.jmaox.user.User;
import com.jmaox.user.AdminUser;
import com.jmaox.user.UserAdminController;
import com.jmaox.user.UserController;
import com.jmaox.collect.Collect;
import com.jmaox.collect.CollectClientController;
import com.jmaox.collect.CollectController;
import com.jmaox.handler.HtmlHandler;
import com.jmaox.index.IndexAdminController;
import com.jmaox.index.IndexClientController;
import com.jmaox.index.IndexController;
import com.jmaox.interceptor.CommonInterceptor;
import com.jmaox.label.Label;
import com.jmaox.label.LabelAdminController;
import com.jmaox.label.LabelController;
import com.jmaox.label.LabelTopicId;
import com.jmaox.link.Link;
import com.jmaox.link.LinkAdminController;
import com.jmaox.mission.Mission;
import com.jmaox.mission.MissionAdminController;
import com.jmaox.mission.MissionClientController;
import com.jmaox.mission.MissionController;
import com.jmaox.notification.Notification;
import com.jmaox.notification.NotificationClientController;
import com.jmaox.notification.NotificationController;
import com.jmaox.reply.Reply;
import com.jmaox.reply.ReplyAdminController;
import com.jmaox.reply.ReplyClientController;
import com.jmaox.reply.ReplyController;
import com.jmaox.section.Section;
import com.jmaox.section.SectionAdminController;
import com.jmaox.section.SectionClientController;
import com.jmaox.topic.Topic;
import com.jmaox.topic.TopicAdminController;
import com.jmaox.topic.TopicClientController;
import com.jmaox.topic.TopicController;
import com.jmaox.valicode.ValiCode;
import com.jfinal.config.Constants;
import com.jfinal.config.*;
import com.jfinal.ext.interceptor.SessionInViewInterceptor;
import com.jfinal.plugin.activerecord.ActiveRecordPlugin;
import com.jfinal.plugin.druid.DruidPlugin;
import com.jfinal.plugin.druid.DruidStatViewHandler;
import com.jfinal.plugin.ehcache.EhCachePlugin;

/**
 * API引导式配置
 */
public class JFinalBBSConfig extends JFinalConfig {

    /**
     * 配置常量
     * @param me
     */
    @Override
    public void configConstant(Constants me) {
        // 加载少量必要配置，随后可用getProperty(...)获取值
        loadPropertyFile("config.properties");
        me.setDevMode(getPropertyToBoolean("devMode", false));
        me.setUploadedFileSaveDirectory(com.jmaox.common.Constants.UPLOAD_DIR);
        me.setMaxPostSize(2048000);
    }

    /**
     * 配置路由
     * @param me
     */
    @Override
    public void configRoute(Routes me) {
        me.add("/", IndexController.class, "ftl");	// 第三个参数为该Controller的视图存放路径
        me.add("/topic", TopicController.class, "ftl");
        me.add("/user", UserController.class, "ftl");
        me.add("/mission", MissionController.class, "ftl");
        me.add("/reply", ReplyController.class, "ftl");
        me.add("/collect", CollectController.class, "ftl");
        me.add("/notification", NotificationController.class, "ftl");
        me.add("/label", LabelController.class, "ftl");
        //添加后台路由
        adminRoute(me);
        //添加客户端路由
        clientRoute(me);
    }

    //后台路由配置
    public void adminRoute(Routes me) {
        me.add("/admin", IndexAdminController.class, "ftl/admin");
        me.add("/admin/topic", TopicAdminController.class, "ftl/admin/topic");
        me.add("/admin/reply", ReplyAdminController.class, "ftl/admin/reply");
        me.add("/admin/user", UserAdminController.class, "ftl/admin/user");
        me.add("/admin/section", SectionAdminController.class, "ftl/admin/section");
        me.add("/admin/link", LinkAdminController.class, "ftl/admin/link");
        me.add("/admin/mission", MissionAdminController.class, "ftl/admin/mission");
        me.add("/admin/label", LabelAdminController.class, "ftl/admin/label");
    }

    public void clientRoute(Routes me) {
        me.add("/api/index", IndexClientController.class);
        me.add("/api/topic", TopicClientController.class);
        me.add("/api/reply", ReplyClientController.class);
        me.add("/api/user", UserClientController.class);
        me.add("/api/notification", NotificationClientController.class);
        me.add("/api/section", SectionClientController.class);
        me.add("/api/collect", CollectClientController.class);
        me.add("/api/mission", MissionClientController.class);
    }

    /**
     * 配置插件
     * @param me
     */
    @Override
    public void configPlugin(Plugins me) {
        // 配置C3p0数据库连接池插件
        DruidPlugin druidPlugin = new DruidPlugin(getProperty("jdbcUrl"), getProperty("user"), getProperty("password").trim());
        druidPlugin.setFilters("stat,wall");
        me.add(druidPlugin);

        me.add(new EhCachePlugin());
        
        ActiveRecordPlugin arp = new ActiveRecordPlugin(druidPlugin);
        arp.setShowSql(getPropertyToBoolean("showSql", false));
        me.add(arp);
        arp.addMapping("topic", Topic.class);	// 映射blog 表到 Blog模型
        arp.addMapping("reply", Reply.class);
        arp.addMapping("user", User.class);
        arp.addMapping("mission", Mission.class);
        arp.addMapping("collect", Collect.class);
        arp.addMapping("notification", Notification.class);
        arp.addMapping("admin_user", AdminUser.class);
        arp.addMapping("section", Section.class);
        arp.addMapping("link", Link.class);
        arp.addMapping("valicode", ValiCode.class);
        arp.addMapping("label", Label.class);
        arp.addMapping("label_topic_id", LabelTopicId.class);
    }

    /**
     * 配置全局拦截器
     * @param me
     */
    @Override
    public void configInterceptor(Interceptors me) {
        me.add(new SessionInViewInterceptor());
        me.add(new CommonInterceptor());
    }

    /**
     * 配置处理器
     * @param me
     */
    @Override
    public void configHandler(Handlers me) {
        //配置druid的监听，可以在浏览器里输入http://localhost:8080/druid 查看druid监听的数据
        me.add(new DruidStatViewHandler("/druid"));
        me.add(new HtmlHandler());
    }

}
