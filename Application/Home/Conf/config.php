<?php
/**
 * 前台配置文件
 * 所有除开系统级别的前台配置
 * @author Max.Yu <max@jipu.com>
 */

return array(

  /**
   * 预先加载的标签库
   */
  'TAGLIB_PRE_LOAD' => 'OT\\TagLib\\Article,OT\\TagLib\\Think',
    
  /**
   * 主题设置
   */
  'DEFAULT_THEME' => 'default',  // 默认模板主题名称

  /**
   * 用户共享登陆
   */
  'USER_SHARE'=>array(
    'SNS.JIPUSHOP.COM'=>array(
        'loginRedirect'=>'http://sns.jipushop.com/index.php?app=public&mod=Api&act=loginRedirect&from=%s&uid=%s&time=%s&sign=%s',
        'loginCheack'=>'http://sns.jipushop.com/index.php?app=public&mod=Api&act=loginCheack&from=%s&uid=%s&time=%s&sign=%s',
        'thirdLogin'=>'http://sns.jipushop.com/index.php?app=public&mod=Api&act=thirdLogin&from=%s&username=%s&password=%s&sign=%s',
        'email'=>'email',
        'username'=>'phone',
        'password'=>'password'
    ),
  ),
  /**
   * 数据缓存设置
   */
  'DATA_CACHE_PREFIX' => 'ccys_', // 缓存前缀
  'DATA_CACHE_TYPE'   => 'File', // 数据缓存类型

  /**
   * 文件上传相关配置
   */
  'DOWNLOAD_UPLOAD' => array(
    'mimes'    => '', //允许上传的文件MiMe类型
    'maxSize'  => 5*1024*1024, //上传的文件大小限制 (0-不做限制)
    'exts'     => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml', //允许上传的文件后缀
    'autoSub'  => true, //自动子目录保存文件
    'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
    'rootPath' => './Uploads/Download/', //保存根路径
    'savePath' => '', //保存路径
    'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
    'saveExt'  => '', //文件保存后缀，空则使用原后缀
    'replace'  => false, //存在同名是否覆盖
    'hash'     => true, //是否生成hash编码
    'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
  ), //下载模型上传配置（文件上传类配置）

  /**
   * 图片上传相关配置
   */
  'PICTURE_UPLOAD' => array(
    'mimes'    => '', //允许上传的文件MiMe类型
    'maxSize'  => 2*1024*1024, //上传的文件大小限制 (0-不做限制)
    'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
    'autoSub'  => true, //自动子目录保存文件
    'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
    'rootPath' => './Uploads/Picture/', //保存根路径
    'savePath' => '', //保存路径
    'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
    'saveExt'  => '', //文件保存后缀，空则使用原后缀
    'replace'  => false, //存在同名是否覆盖
    'hash'     => true, //是否生成hash编码
    'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
  ), //图片上传相关配置（文件上传类配置）

  /**
   * 编辑器图片上传相关配置
   */
  'EDITOR_UPLOAD' => array(
    'mimes'    => '', //允许上传的文件MiMe类型
    'maxSize'  => 2*1024*1024, //上传的文件大小限制 (0-不做限制)
    'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
    'autoSub'  => true, //自动子目录保存文件
    'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
    'rootPath' => './Uploads/Editor/', //保存根路径
    'savePath' => '', //保存路径
    'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
    'saveExt'  => '', //文件保存后缀，空则使用原后缀
    'replace'  => false, //存在同名是否覆盖
    'hash'     => true, //是否生成hash编码
    'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
  ),

  /**
   * 模板相关配置
   */
  'TMPL_PARSE_STRING' => array(
    '__STATIC__' => __ROOT__ . '/Public/Static',
    '__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Addons',
  ),

  /**
   * SESSION 和 COOKIE 配置
   */
  'SESSION_PREFIX' => 'ccys_home', //session前缀
  'COOKIE_PREFIX'  => 'ccys_home_', // Cookie前缀 避免冲突

  /**
   * 附件相关配置
   * 附件是规划在插件中的，所以附件的配置暂时写到这里
   * 后期会移动到数据库进行管理
   */
  'ATTACHMENT_DEFAULT' => array(
    'is_upload'     => true,
    'allow_type'    => '0,1,2', //允许的附件类型 (0-目录，1-外链，2-文件)
    'driver'        => 'Local', //上传驱动
    'driver_config' => null, //驱动配置
  ), //附件默认配置

  'ATTACHMENT_UPLOAD' => array(
    'mimes'    => '', //允许上传的文件MiMe类型
    'maxSize'  => 5*1024*1024, //上传的文件大小限制 (0-不做限制)
    'exts'     => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml', //允许上传的文件后缀
    'autoSub'  => true, //自动子目录保存文件
    'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
    'rootPath' => './Uploads/Attachment/', //保存根路径
    'savePath' => '', //保存路径
    'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
    'saveExt'  => '', //文件保存后缀，空则使用原后缀
    'replace'  => false, //存在同名是否覆盖
    'hash'     => true, //是否生成hash编码
    'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
  ), //附件上传配置（文件上传类配置）

  /*错误页面模板*/
  'TMPL_ACTION_ERROR' => 'Public/jump', // 默认错误跳转对应的模板文件
  'TMPL_ACTION_SUCCESS' => 'Public/jump', // 默认成功跳转对应的模板文件
  'TMPL_EXCEPTION_FILE' => 'Public/exception',// 异常页面的模板文件

);
