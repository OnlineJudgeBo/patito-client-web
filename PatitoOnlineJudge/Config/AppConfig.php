<?php

namespace PatitoOnlineJudge\Config;

class AppConfig
{
    public static $DB_HOST = "patito-db";
    public static $DB_NAME = "jol";
    public static $DB_USER = "root";
    public static $DB_PASS = "root";
    public static $OJ_NAME = "Patito Online Judge";
    public static $OJ_HOME = "./";
    public static $OJ_ADMIN = "starsaminf@gmail.com";
    public static $OJ_DATA = "/home/judge/data";
    public static $OJ_BBS = "discuss";
    public static $OJ_ONLINE = true;
    public static $OJ_LANG = "es";
    public static $OJ_SIM = true;
    public static $OJ_DICT = false;
    //1mC 2mCPP 4mPascal 8mJava 16mRuby 32mBash 1008 for security reason to mask all other language
    public static $OJ_LANGMASK = 32692;
    public static $OJ_EDITE_AREA = true; //true: syntax highlighting is active
    public static $OJ_AUTO_SHARE = false; //true: One can view all AC submit if he/she has ACed it onece.
    public static $OJ_CSS = "hoj.css";
    public static $OJ_SAE = false; //using sina application engine
    public static $OJ_VCODE = true;
    public static $OJ_APPENDCODE = false;
    public static $OJ_MEMCACHE = false;
    public static $OJ_MEMSERVER = "127.0.0.1";
    public static $OJ_MEMPORT = 11211;
    public static $SAE_STORAGE_ROOT = "";
    public static $OJ_TEMPLATE = "ZaDuckOJ";
    public static $OJ_LOGIN_MOD = "hustoj";
    public static $OJ_RANK_LOCK_PERCENT = 0;
    public static $OJ_SHOW_DIFF = true;
    public static $OJ_TEST_RUN = false;
    public static $user_smtp = "acm.icpc.umsa@gmail.com";
    public static $pas_smtp = "qmrtolnhjblhijau";
    /*Recaptcha*/
    public static $privatekey = "6Lck5fsSAAAAAMd7mhFjPdK_TUkG0MSEZx0ysx8C";
    public static $publickey = "6Lck5fsSAAAAACzXdscia6ygFYYwVyev0xllRWjA";
}
