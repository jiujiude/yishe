<?php
/**
 * 提现模型
 * @version 2015080716 
 * @author Justin <justin@jipu.com>
 */

namespace Home\Model;

class WithdrawModel extends HomeModel{
  
  /**
   * 自动验证规则
   * @var array
   */
  protected $_validate = array(
    array('account_id', 'require', '请选择提现账户', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('amount', 'require', '提现金额不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    array('amount', 'checkAmount', '提现金额不能低于最低提现额', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
    array('amount', 'checkFinance', '提现金额不能大于可提现余额', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
    array('amount', 'checkMultiple', '提现金额必须是1的整倍数', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
  );

  /**
   * 自动完成规则
   * @var array
   */
  protected $_auto = array(
    array('uid', 'is_login', self::MODEL_INSERT, 'function'),
    array('create_time', NOW_TIME, self::MODEL_BOTH),
  );
  
  function detail($id, $uid = UID){
    $where = array(
      'id' => $id,
      'uid' => $uid,
    );
    return D('WithdrawView')->where($where)->find();
  }
  
  /**
   * 检测提现值是否大于最低提现额
   */
  function checkAmount(){
    return I('post.amount') >= C('SDP_WITHDRAW_LIMIT');
  }
  
  /**
   * 检测提现值是否大于余额
   */
  function checkFinance(){
    return I('post.amount') <= A('Finance', 'Event')->getWithDrawFinance();
  }
  
  function checkMultiple(){
    return is_integer(I('post.amount') / 1);
  }

  /**
   * 插入成功后的回调方法
   */
  protected function _after_insert($data, $options) {
    //减余额
    M('Member')->where('uid='.$data['uid'])->setDec('finance', $data['amount']);
    
    $data_finance = array(
      'uid' => $data['uid'],
      'order_id' => '',
      'type' => 'withdraw',
      'amount' => $data['amount'],
      'flow' => 'out',
      'memo' => '提现',
      'create_time' => NOW_TIME
    );
    $update = M('Finance')->add($data_finance);
  }
  
}
