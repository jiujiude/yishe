<?php
/**
 * 支付宝网银支付驱动类
 * @version 2014092216
 * @author Max.Yu <max@jipu.com>
 */

namespace Think\Pay\Driver;

class Alipaywap extends \Think\Pay\Pay {

  protected $gateway = 'http://wappaygw.alipay.com/service/rest.htm?';
  protected $verify_url = 'http://notify.alipay.com/trade/notify_query.do?';

  protected $config = array(
    'email' => '',
    'key' => '',
    'partner' => ''
  );

  public function check(){
    if(!$this->config['email'] || !$this->config['key'] || !$this->config['partner']){
      E("支付宝设置有误！");
    }
    return true;
  }

  public function buildRequestForm($data){
    // 请求业务参数详细
    $req_data = '<direct_trade_create_req><notify_url>'.$this->config['notify_url'].'</notify_url><call_back_url>'.$this->config['return_url'].'</call_back_url><seller_account_name>'.$this->config['email'].'</seller_account_name><out_trade_no>'.$data['out_trade_no'].'</out_trade_no><subject>'.$data['subject'].'</subject><total_fee>'.$data['total_fee'].'</total_fee><merchant_url>'.$this->config['merchant_url'].'</merchant_url></direct_trade_create_req>';
    $param_token = array(
      'service' => 'alipay.wap.trade.create.direct',
      'partner' => $this->config['partner'],
      'sec_id' => strtoupper('MD5'),
      'format' => 'xml',
      'v' => '2.0',
      'req_id' => date('Ymdhis'),
      'req_data' => $req_data,
      '_input_charset' => 'utf-8',
    );

    $html_text = $this->_buildHttp($param_token, $this->gateway, 'get');
    $html_text = urldecode($html_text);
    //解析远程模拟提交后返回的信息
    $para_html_text = $this->parseResponse($html_text);

    // 获取request_token
    $request_token = $para_html_text['request_token'];
    // 业务详细
    $req_data = '<auth_and_execute_req><request_token>'.$request_token.'</request_token></auth_and_execute_req>';

    // 构造要请求的参数数组
    $parameter = array(
      'service' => 'alipay.wap.auth.authAndExecute',
      'partner' => $this->config['partner'],
      'sec_id' => strtoupper('MD5'),
      'format' => 'xml',
      'v' => '2.0',
      'req_id' => date('Ymdhis'),
      'req_data' => $req_data,
      '_input_charset'  => 'utf-8'
    );
    // 建立请求
    $html_text = $this->_buildForm($parameter, $this->gateway, 'get');
    return $html_text;
  }

  /**
   * 除去数组中的空值和签名参数
   * @param $para 签名参数组
   * return 去掉空值与签名参数后的新签名参数组
   */
  function paraFilter($para) {
    $para_filter = array();
    while (list ($key, $val) = each ($para)) {
      if($key == "sign" || $key == "sign_type" || $val == "")continue;
      else  $para_filter[$key] = $para[$key];
    }
    return $para_filter;
  }

  /**
   * 对数组排序
   * @param $para 排序前的数组
   * return 排序后的数组
   */
  function argSort($para) {
    ksort($para);
    reset($para);
    return $para;
  }

  /**
   * 异步通知时，对参数做固定排序
   * @param $para 排序前的参数组
   * @return 排序后的参数组
   */
  function sortNotifyPara($para) {
    $para_sort['service'] = $para['service'];
    $para_sort['v'] = $para['v'];
    $para_sort['sec_id'] = $para['sec_id'];
    $para_sort['notify_data'] = $para['notify_data'];
    return $para_sort;
  }

  /**
   * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
   * @param $para 需要拼接的数组
   * return 拼接完成以后的字符串
   */
  function createLinkstring($para) {
    $arg  = "";
    while (list ($key, $val) = each ($para)) {
      $arg.=$key."=".$val."&";
    }
    //去掉最后一个&字符
    $arg = substr($arg,0,count($arg)-2);
    
    //如果存在转义字符，那么去掉转义
    if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
    
    return $arg;
  }

  /**
   * 验证签名
   * @param $prestr 需要签名的字符串
   * @param $sign 签名结果
   * @param $key 私钥
   * return 签名结果
   */
  function md5Verify($prestr, $sign, $key) {
    $prestr = $prestr . $key;
    $mysgin = md5($prestr);

    if($mysgin == $sign) {
      return true;
    }else {
      return false;
    }
  }

  /**
   * 获取返回时的签名验证结果
   * @param $para_temp 通知返回来的参数数组
   * @param $sign 返回的签名结果
   * @param $isSort 是否对待签名数组排序
   * @return 签名验证结果
   */
  function getSignVeryfy($para_temp, $sign, $isSort) {
    //除去待签名参数数组中的空值和签名参数
    $para = $this->paraFilter($para_temp);
    
    //对待签名参数数组排序
    if($isSort){
      $para = $this->argSort($para);
    }else{
      $para = $this->sortNotifyPara($para);
    }
    
    //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
    $prestr = $this->createLinkstring($para);
    
    $isSgin = false;
    $isSgin = $this->md5Verify($prestr, $sign, $this->config['key']);
        
    return $isSgin;
  }

  /**
   * 针对notify_url验证消息是否是支付宝发出的合法消息
   * @return 验证结果
   */
  public function verifyNotify($notify){
    $doc = new \DOMDocument();
    $doc->loadXML($notify['notify_data']);
    $notify_id = $doc->getElementsByTagName("notify_id")->item(0)->nodeValue;
    //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
    $responseTxt = 'true';
    if(!empty($notify_id)){
      $responseTxt = $this->getResponse($notify_id);
    }

    //生成签名结果
    if(!empty($notify_id)){
      // 异步通知时，采用sortNotifyPara方法
      $isSign = $this->getSignVeryfy($notify, $notify["sign"], false);
    }else{
      $isSign = $this->getSignVeryfy($notify, $notify["sign"], true);
    }

    if(preg_match("/true$/i", $responseTxt) && $isSign){
      $this->setInfo($notify);
      return true;
    }else{
      return false;
    }
  }

  protected function setInfo($notify){
    $info = array();
    // 异步回调返回结果为xml
    if($notify['notify_data']){
      $doc = new \DOMDocument();
      $doc->loadXML($notify['notify_data']);
      $trade_status = $doc->getElementsByTagName("trade_status")->item(0)->nodeValue;
      $out_trade_no = $doc->getElementsByTagName("out_trade_no")->item(0)->nodeValue;
      $trade_no = $doc->getElementsByTagName("trade_no")->item(0)->nodeValue;
      $total_fee = $doc->getElementsByTagName("total_fee")->item(0)->nodeValue;
      $info['status'] = (($trade_status == 'TRADE_SUCCESS') || ($trade_status == 'TRADE_FINISHED')) ? true : false;
      $info['out_trade_no'] = $out_trade_no;
      $info['trade_no'] = $trade_no;
      $info['total_fee'] = $total_fee;
    // 同步回调返回结果为数组
    }elseif($notify['result'] == 'success'){
      $info['status'] = true;
      $info['out_trade_no'] = $notify['out_trade_no'];
      $info['trade_no'] = $notify['trade_no'];
    }
    $this->info = $info;
  }

  function getHttpResponseGET($url, $cacert_url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
    curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
    $responseText = curl_exec($curl);
    //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
    curl_close($curl);
    
    return $responseText;
  }

  /**
   * 获取远程服务器ATN结果,验证返回URL
   * @param $notify_id 通知校验ID
   * @return 服务器ATN结果
   * 验证结果集：
   * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
   * true 返回正确信息
   * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
   */
  protected function getResponse($notify_id){
    $partner = $this->config['partner'];
    $veryfy_url = $this->verify_url.'partner='.$partner.'&notify_id='.$notify_id;
    $responseTxt = $this->getHttpResponseGET($veryfy_url, $this->config['cacert']);
    return $responseTxt;
  }

  /**
   * 解析远程模拟提交后返回的信息
   * @param $str_text 要解析的字符串
   * @return 解析结果
   */
  protected function parseResponse($str_text){
    //以“&”字符切割字符串
    $para_split = explode('&',$str_text);
    //把切割后的字符串数组变成变量与数值组合的数组
    foreach($para_split as $item){
      //获得第一个=字符的位置
      $nPos = strpos($item,'=');
      //获得字符串长度
      $nLen = strlen($item);
      //获得变量名
      $key = substr($item,0,$nPos);
      //获得数值
      $value = substr($item,$nPos+1,$nLen-$nPos-1);
      //放入数组中
      $para_text[$key] = $value;
    }
    
    if(!empty($para_text['res_data'])){
      //token从res_data中解析出来（也就是说res_data中已经包含token的内容）
      $doc = new \DOMDocument();
      $doc->loadXML($para_text['res_data']);
      $para_text['request_token'] = $doc->getElementsByTagName('request_token')->item(0)->nodeValue;
    }
    return $para_text;
  }

}
