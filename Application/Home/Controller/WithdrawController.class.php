<?php
/**
* 提现控制器
* @author Justin <justin@jipu.com>
*/

namespace Home\Controller;

class WithdrawController extends HomeController{

  protected $where = null;

  protected function _initialize(){
    //记录当前页URL地址Cookie，点击我的登录完成后跳转至个人中心
    Cookie('__forward__', $_SERVER['REQUEST_URI']);
    parent::_initialize();
    //判断是否登录
    parent::login();
    //默认过滤条件
    $this->where = array(
      'uid' => UID,
      'status' => 1
    );
  }
  
  /**
   * 提现记录
   * @author Max.Yu <max@jipu.com>
   */
  public function index(){
    $lists = $this->lists('Common/WithdrawView', $this->where);
    $this->lists = A('UserAccount', 'Event')->getHiddenAccount($lists);
    //可提现余额
    $this->withdraw_finance = A('Finance', 'Event')->getWithDrawFinance();
    $this->meta_title = '提现记录';
    $this->display(IS_AJAX ? 'recordList' : null);  
  }
  
  /**
   * 提现详情
   * @author Max.Yu <max@jipu.com>
   */
  public function detail($id = 0){
    if($id){
      $this->data = D('Withdraw')->detail($id);
      $this->meta_title = '提现记录详情';
      $this->display();
    }
  }
  
  /**
   * 申请提现
   * @author Max.Yu <max@jipu.com>
   */
  public function add(){
    //判断是否绑定提现账户
    $bind = D('UserAccount')->checkBind();
    if(!$bind){
      if(is_mobile()){
        $this->redirect('UserAccount/add', array('type' =>'alipay'), 0);
      }else{
        $this->redirect('UserAccount/index', array('add_now' => 1), 0);
      }
    }
    //可提现余额
    $this->withdraw_finance = A('Finance', 'Event')->getWithDrawFinance();
    //($this->finance < $sdp_withdraw_limit) && $this->error('尚未达到最低提现额度'.$sdp_withdraw_limit.'元');
    
    //获取提现账户
    $lists = $this->lists('UserAccount', $this->where);
    $this->lists = A('UserAccount', 'Event')->getHiddenAccount($lists);
    $this->meta_title = '申请提现';
    $this->display();
  }

  function update(){
    if(IS_POST){
      $model = D('Withdraw');
      if(false !== $model->update()){
        $this->success('操作成功！', Cookie('__forward__'));
      }else{
        $error = $model->getError();
        $this->error(empty($error) ? '未知错误！' : $error);
      }
    }else{
      $this->redirect('index');
    }
  }

}
