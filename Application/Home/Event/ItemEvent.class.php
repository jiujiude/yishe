<?php
/**
 * 商品事件类
 * @version 2015102015
 * @author Max.Yu <max@jipu.com>
 */

namespace Home\Event;

class ItemEvent{

  /**
   * 格式化列表
   * @param array $data 需要格式化的数据
   * @return array $result 格式化以后的数据
   * @author Max.Yu <max@jipu.com>
   */
  public function formatList($data){
    if($data){
      foreach($data as &$value){
        if($value['thumb']){
          $value['cover_path'] = get_cover($value['thumb'], 'path');
        }
      }
    }
    return $data;
  }

  /**
   * 获取赠品信息
   * @param array $items 商品集合数组
   * @param boolean $use_min 最低购买数量是否参与计算
   * @return array 赠品数组
   * @author Max.Yu <max@jipu.com>
   */
  public function getSendItems($items = array(), $use_min = true){
    $send = array();
    foreach($items as $item){
      $where = array(
        'item_id' => $item[0],
        'status' => 1,
        'start_time' => array('elt', NOW_TIME),
        'expire_time' => array('egt', NOW_TIME - 86400),
      );
      //最低购买数量
      if($use_min){
        $item[1] = $item[1] ? : 1;
        $where['min_num'] = array('elt', $item[1]);
      }
      $send_item = M('BuySend')->where($where)->getField('send_item');
      if($send_item){
        $send_item = json_decode($send_item, true);
        foreach($send_item as $s){
          if(isset($send[$s['id']])){
            $send[$s['id']]['num'] += $s['num'];
          }else{
            $s['item_info'] = get_item_info($s['id']);
            $send[$s['id']] = $s;
          }
        }
      }
    }
    return $send;
  }
  
  
  /**
   * 批量获取 商品秒杀信息
   * 具体的商品规格来判断价格在Ordermodel中的getItemPrice去判断
   * @param int $ids                商品id
   * @param int $key                作为数组key值返回
   * @return array $list            秒杀商品信息
   */
  public function seckillData($ids,$key=''){
      $time = NOW_TIME;
      $map['item_id'] = array('in',$ids);
      $map['stime'] = array('elt',$time);
      $map['etime'] = array('gt',$time);
      $map['item_stock'] = array('gt',0);
      $list = M('seckill_item')->where($map)->field('id',true)->select();
      if($list && !empty($key)){
          $list = array_column($list, null , $key);
      }
      return $list;
  }
  
  /**
   * 获取商品的秒杀信息
   * @version 2015101509
   * @author Justin <justin@jipu.com>
   */
  function getSeckill($item_id = 0){
    if($item_id){
      $where = array(
        'status' => 1
      );
      $where[] = "FIND_IN_SET('{$item_id}', item_ids)";
      $seckill_lists = M('Seckill')->field('name, item_ids, start_time, expire_time')->where($where)->select();
      return $seckill_lists[0];
    }
  }
  
}
