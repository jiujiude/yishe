/**
 * 获取分类信息插件  
 * @author Max.Yu <max@winhu.com>
 * @version 2015041517
 * @example 
 * $('#item_type_div').loadtype({
 type: 'area',  //【商品分类】itemCategory | 【地区】area
 name1: 'prov',
 name2: 'city',
 name3: 'area',
 value1: 230000,
 value2: 230700,
 value3: 230703
 });
 **/
(function(factory){
  if(typeof define === 'function'){
    // 如果define已被定义，模块化代码
    define('zepto.loadtype', ['zepto'], function(require, exports, moudles){
      factory(require('zepto')); // 初始化插件
      return Zepto; // 返回Zepto
    });
  }else{
    // 如果define没有被定义，正常执行Zepto
    factory(Zepto);
  }
}(function(){
  var LoadType = function(element, option, data){
    this.element = element;
    //合并参数
    for(var i in option){
      this.defaults[i] = option[i];
    }
    this.options = this.defaults;
    //console.log(data);
    this.init(data);
  };

  LoadType.prototype = {
    //定义默认参数
    defaults: {
      type: 'itemCategory',
      name1: 'cid_1',
      name2: 'cid_2',
      name3: 'cid_3',
      value1: '',
      value2: '',
      value3: '',
      changed: function(){
      }
    },
    
    init: function(data){
      var options = this.options;
      var rand = Math.random() * 1e17;
      var select_default = '<option value="">请选择</option>';
      if(options.type=='Area')select_default = '<option value="">选择地区</option>';
      var html1 = html2 = html3 = select_default;
      if(data[0]){
        for(var i = 0; i < data[0].length; i++){
          html1 += '<option value="' + data[0][i].id + '">' + data[0][i].name + '</option>';
        }
      }
      var init_html = '';
      init_html = '<select name="' + options.name1 + '" class="' + options.name1 + '" style="background: #fff;" id="n1_' + rand + '">' + html1 + '</select>\r\n';
      init_html += '<select name="' + options.name2 + '" class="' + options.name2 + '"  style="background: #fff;" id="n2_' + rand + '" style="display:none;">' + html2 + '</select>\r\n';
      init_html += '<select name="' + options.name3 + '" class="' + options.name3 + '"  style="background: #fff;"  id="n3_' + rand + '" style="display:none;">' + html3 + '</select>\r\n';
      this.element.html(init_html);
      var changed = function(){
        if(typeof options.changed === 'function'){
          options.changed($('#n1_' + rand).val(), $('#n2_' + rand).val(), $('#n3_' + rand).val());
        }
      };
      $('#n1_' + rand).change(function(){
        var value = $(this).val() === '' ? NaN : $(this).val() * 1;
        $('#n2_' + rand).html(select_default).hide();
        $('#n3_' + rand).html(select_default).hide();
        if(value !== NaN){
          if(typeof data[value] !== 'undefined'){
            html2 = select_default;
            for(var i = 0; i < data[value].length; i++){
              html2 += '<option value="' + data[value][i].id + '">' + data[value][i].name + '</option>';
            }
            $('#n2_' + rand).html(html2).show();
          }
        }
        changed();
      });
      $('#n2_' + rand).change(function(){
        var value = $(this).val() === '' ? NaN : $(this).val() * 1;
        $('#n3_' + rand).html(select_default).hide();
        if(value !== NaN){
          if(typeof data[value] !== 'undefined'){
            html3 = select_default;
            for(var i = 0; i < data[value].length; i++){
              html3 += '<option value="' + data[value][i].id + '">' + data[value][i].name + '</option>';
            }
            $('#n3_' + rand).html(html3).show();
          }
        }
        changed();
      });
      $('#n3_' + rand).change(function(){
        changed();
      });
      if(options.value1 !== ''){
        $('#n1_' + rand + ' option[value="' + options.value1 + '"]').attr('selected', true);
        $('#n1_' + rand).change();
      }
      if(options.value2 !== ''){
        $('#n2_' + rand + ' option[value="' + options.value2 + '"]').attr('selected', true);
        $('#n2_' + rand).change();
      }
      if(options.value3 !== ''){
        $('#n3_' + rand + ' option[value="' + options.value3 + '"]').attr('selected', true);
        $('#n3_' + rand).change();
      }
    }
  };

  // ====================================================
  Zepto.extend(Zepto.fn, {
    Loadtype: function(option){
      var optionType = $.type(option);
      var $this;
      if(optionType == 'object' || optionType == 'undefined'){
        $.each(this, function(){
          $this = $(this);
         //处理插件逻辑
          try{
            var type_data = eval(option.type);
            new LoadType($this, option, type_data);
          }catch(e){
            $.get($.U('/Api/getTypeData', 'type=' + option.type), function(res){
              eval(res);
              new LoadType($this, option, eval(option.type));
            });
          }
        });
      }
    }
  });
}));