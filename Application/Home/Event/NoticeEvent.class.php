<?php
/**
 * 通知事件处理
 * @version 2015092911
 * @author Justin <justin@jipu.com>
 */

namespace Home\Event;

class NoticeEvent{
  
  /**
   * 订单支付后的通知
   */
  function afterPaid($order){
    //管理员
    $this->_afterPaidToAdmin($order);
    //用户
    $this->_afterPaidToUser($order);
  }
  
  private function _afterPaidToAdmin($order){ 
    //短信通知供应商
    $where = array(
      'payment_id' => $order['payment_id'],
      'total_amount' => array('neq', 0.01),//过滤测试
    );
    $order_lists = M('Order')->field('supplier_ids')->where($where)->select();
    $supplier_ids = array_column($order_lists, 'supplier_ids');
    $map['supplier_id'] = array('in', $supplier_ids);
    $supplier_lists = M('Supplier')->field('notice_mobile,key')->where($map)->select();
    foreach($supplier_lists as $key=>$val){
      $url = SITE_URL.U('Supplier/index',array('key'=>$val['key']));
      send_yptpl_sms($val['notice_mobile'], 'NEWORDER_NOTIFY',array('key'=>$url));
    }
  }
  
  private function _afterPaidToUser($order){
    //微信通知用户
    A('WechatTplMsg', 'Event')->wechatTplNotice('paid', $order);
  }
}
