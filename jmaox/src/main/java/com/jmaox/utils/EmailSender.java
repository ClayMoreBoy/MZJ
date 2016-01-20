package com.jmaox.utils;

import com.jfinal.kit.Prop;
import com.jfinal.kit.PropKit;
import java.io.UnsupportedEncodingException;

import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;
import java.util.Date;
import java.util.Properties;
import javax.mail.Message;
import javax.mail.MessagingException;
import javax.mail.PasswordAuthentication;
import javax.mail.Session;
import javax.mail.Transport;
import org.apache.commons.lang.StringUtils;
import org.apache.log4j.Logger;

/**
 * 发送普通邮件，接受普通邮件 发送带有附件的邮件，接收带有附件的邮件 发送html形式的邮件，接受html形式的邮件 发送带有图片的邮件等做了一个总结。
 */
public class EmailSender {

    private final Logger logger = Logger.getLogger(EmailSender.class);

    public static final String EMAIL_BODY_HEADER = "";
    // 邮箱服务器
    private final String smtp_host;
    private final String smtp_port;
    private final String MAIL_SUBJECT = "JMaoX系统邮件";
    private final String sender;
    private final String username;
    private final String password;
    private final String mail_from;

    private static EmailSender instance;

    public static EmailSender getInstance() {
        if (instance == null) {
            Prop prop = PropKit.getProp("config.properties");
            instance = new EmailSender(prop);
        }
        return instance;
    }

    public EmailSender(Prop prop) {
        this.smtp_host = prop.get("email.smtp");
        this.smtp_port = prop.get("email.smtp.port");
        this.username = prop.get("email.username");
        this.password = prop.get("email.password");
        this.mail_from = username;
        this.sender = prop.get("email.sender");
    }

    /**
     * 此段代码用来发送普通电子邮件
     *
     * @param subject
     * @param mailTos
     * @param mailBody
     * @return
     */
    public boolean send(String subject, String[] mailTos, String mailBody) {
        try {
            Properties props = new Properties(); // 获取系统环境
            props.put("mail.smtp.host", smtp_host);
            props.put("mail.smtp.port", smtp_port);
            props.put("mail.smtp.socketFactory.port", smtp_port);
            props.put("mail.smtp.socketFactory.class", "javax.net.ssl.SSLSocketFactory");
            props.put("mail.smtp.auth", "true");
            props.put("mail.smtp.connectiontimeout", "5000");
            props.put("mail.smtp.timeout", "10000");

            Session session = Session.getDefaultInstance(props,
                    new javax.mail.Authenticator() {
                        @Override
                        protected PasswordAuthentication getPasswordAuthentication() {
                            return new PasswordAuthentication(username, password);
                        }
                    });
            Message message = new MimeMessage(session);
            message.setFrom(new InternetAddress(mail_from, sender, "utf-8"));
            message.setRecipients(Message.RecipientType.TO, InternetAddress.parse(StringUtils.join(mailTos, ",")));
            message.setSubject(subject == null ? MAIL_SUBJECT : subject); // 设置邮件主题
            message.setText(mailBody); // 设置邮件正文
            message.setSentDate(new Date()); // 设置邮件发送日期

            Transport.send(message);

            return true;
        } catch (MessagingException | UnsupportedEncodingException | RuntimeException ex) {
            logger.error(null, ex);
        }
        return false;
    }

    public static void sendMail(String title, String[] mailTo, String content) {
        String mailBody = EMAIL_BODY_HEADER + content;
        EmailSender.getInstance().send(title, mailTo, mailBody);
    }

    public static void main(String[] args) {
        Properties props = new Properties();
        props.put("mail.smtp.host", "smtp.jmaox.com");
        props.put("mail.smtp.socketFactory.port", "465");
        props.put("mail.smtp.socketFactory.class",
                "javax.net.ssl.SSLSocketFactory");
        props.put("mail.smtp.auth", "true");
        props.put("mail.smtp.port", "465");
        Session session = Session.getDefaultInstance(props,
                new javax.mail.Authenticator() {
                    @Override
                    protected PasswordAuthentication getPasswordAuthentication() {
                        return new PasswordAuthentication("service@jmaox.com", "Jmaox2016");
                    }
                });

        try {

            Message message = new MimeMessage(session);
            message.setFrom(new InternetAddress("service@jmaox.com"));
            message.setRecipients(Message.RecipientType.TO,
                    InternetAddress.parse("maozjun@163.com"));
            message.setSubject("Testing Subject");
            message.setText("Test Mail");

            Transport.send(message);

            System.out.println("Done");

        } catch (MessagingException e) {
            throw new RuntimeException(e);
        }
    }

}
