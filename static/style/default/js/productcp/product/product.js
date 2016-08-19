"use srtict";
/**
 * 绑定方法
 */
//绑定省市区
region.init($(".select_region"));
//绑定select2
$("select").select2();
//富文本绑定初始化
KindEditor.ready(function(K) {
    window.editor = K.create('.ke', {
        uploadJson: '/imageuc/kindeditor/upload?', //填写异步url /imageuc/kindeditor/upload?
        resizeType: 1,
        width: '100%',
        height: '300px',
        allowPreviewEmoticons: true,
        allowImageUpload: true,
        afterBlur: function () {
            this.sync();
          }
    });
});

//绑定上下架时间
$(".form_datetime").datetimepicker({
    language: 'zh',
    weekStart: 1,
    showMeridian: 1,
    autoclose: true,
    isRTL: Metronic.isRTL(),
    format: "yyyy-mm-dd hh:ii",
    pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
});

/**
 * 产品分类相关
 */
var prodCLass = function() {
    var pordId = null;
    //编辑页面获取默认的val值
    if ($("#pordId").length > 0) pordId = $("#pordId").val();
    $.ajax({
            url: 'ajax?op=catelist', //填写异步url ajax?op=catelist
            type: 'POST',
            dataType: 'json',
            data: {
            	product_id: pordId
            }
        }).done(function(data) {
            //加载树
            $('#prodClassTree').jstree({
                'plugins': ["checkbox", "types"],
                'core': {
                    "themes": {
                        "responsive": false
                    },
                    'data': data
                }
            }).on('changed.jstree', function(e, data) {
                var arrId = new Array(),
                    addText = new Array();
                for (var i = 0; i < data.selected.length; i++) {
                    var node = data.instance.get_node(data.selected[i]);
                    if (data.instance.is_leaf(node)) {
                        var obj = Object();
                        arrId.push(node.id);
                        obj['id'] = node.id;
                        obj['text'] = node.text;
                        addText.push(obj);
                    }
                }
                _show(addText); //添加到显示框
                $("#prodClass").val(arrId); //添加到input-value
            });
        })
        //添加商品到显示框
    function _show(arr) {
        var box = $("#prodClassBox"),
            str = '';
        arr.forEach(function(v, i) {
            str += '<div class="pord_class">' + v.text + ' <i class="fa fa-times" onclick=\'_del("' + v.id + '")\'></i></div>';
        })
        box.html(str);
    }
}();
//删除商品
function _del(id) {
    $.jstree.reference('#prodClassTree').deselect_node(id);
}
/**
 * 选择类型获取套餐
 */
var getTypeOption = function() {
    var typeSelect = $("#typeSelect");
    //编辑页面获取类型
    if (typeSelect.val() != '') {
        $.post('ajax?op=addspecval', { //填写异步url  ajax?op=addspecval
            type: $("#typeSelect").val()
        }, function(data) {
            if (data.errno == 0) {
                showType(data.option,'get');
            }
        }, 'json');
    }
    //选择select获取数据
    typeSelect.on('change', function() {
        var type = $(this).val();
        if (type !== '') {
            $.post('ajax?op=addspecval', { //填写异步url ajax?op=addspecval
                type: type
            }, function(data) {
                if (data.errno == 0) {
                    showType(data.option);
                }
            }, 'json');
        }
    });
    //渲染勾选数据到页面
    function showType(data,type) {
        var typebox = $("#typesCheck"),
            str = '';
        if(data==null){
        	typebox.html('');
        	return false;
        }
        data.forEach(function(v, i) {
            var j = i + 1;
            str += '<div class="form-group"><label class="col-md-2 control-label table_title" data-name=' + v.name + '>' + v.name + ':</label>';
            str += '<div class="col-md-8"><div class="types_check_box table_items_' + i + '"><div class="icheck-inline">';
            v.value.forEach(function(d) {
                str += '<label><input type="checkbox" class="icheck" data-info=' + d.value + '|' + d.id + '> ' + d.value + ' </label>';
            });
            str += '</div></div></div></div>';
        });
        typebox.html(str);
        $('.icheck').iCheck({
            cursor: true,
            checkboxClass: 'icheckbox_square-grey',
        });
        if(type!='get') $(".spec_class_box ul,.spec_table_box").html("");
    }
}();
/**
 * 行程规格相关方法
 */
var spec = function() {
    var specTable = $(".spec_table_box"), //用来存放行程规格表格的容器
        specUl = $(".spec_class_box ul"); //用来存放行程时间标签的容器
    //添加一个规格
    $(".spec_add_class").click(function() {
        var tableTitle = $(".table_title"), //表格标题dom
            titleArr = [], //保存包体的数组
            opArr = [], //保存勾选的check信息
            colArr = [], //需要合并的列
            allCheck = true, //是否每一行都已经有勾选项
            colIndex = 0;
        if (tableTitle.length > 0) {
            tableTitle.each(function(i) {
                colArr.push(colIndex);
                colIndex++;
                titleArr.push($(this).data('name'));
                var items = "table_items_" + i,
                    itemsInfo = []; //一行勾选的信息
                $("." + items + " input[type=checkbox]:checked").each(function() {
                    itemsInfo.push($(this).data('info'));
                })
                opArr.push(itemsInfo);
                if (itemsInfo.join() == "") {
                    allCheck = false;
                }
            });
        }
        if (allCheck) {
            var num = 0; //初始化每一个规格的序号
            specTable.children("div").each(function(k, v) {
                var dI = $(v).data("tableid");
                if (num < dI) num = dI;
            });
            num++; //取出当前最大序号+1
            var timeStr = specLi(num); //按钮DOM
            specUl.append(timeStr);
            //绑定时间框
            new specOnTime($('.time_change'));
            var tab = $('<div class="tab-pane fade" id="tab' + num + '" data-tableid="' + num + '"></div>');
            specTable.append(tab);
            var table = $('<table class="table table-bordered"></table>');
            tab.append(table);
            var thead = '<thead><tr role="row">';
            titleArr.forEach(function(title) {
                thead += '<th width="100px">' + title + '</th>';
            })
            thead += '<th width="100px">货号</th><th width="200px">产品名</th><th width="100px">售价</th><th width="100px">成本价</th><th width="100px">市场价</th><th width="100px">儿童价</th><th width="100px">库存</th></tr></thead>';
            table.append(thead);
            var tbody = '<tbody>';
            var newOp = handleArr(opArr); //获取处理完的信息
            if(typeof(newOp) !=='undefined'){
              newOp.forEach(function(op, opi) {
                  var tdArr = op.split(',');
                  tbody += '<tr>';
                  tdArr.forEach(function(td, tdi) {
                      var tdInfoArr = td.split('|'),
                          tdi = tdi + 1;
                      tbody += '<td><input type="hidden" name="item_s[' + num + '][row][' + opi + '][spec_' + tdi + ']" value="' + tdInfoArr[1] + '"/><span>' + tdInfoArr[0] + '</span></td>';
                  })
                  tbody += '<td><input type="text" class="form-control input-sm" placeholder="货号" name="item_s[' + num + '][row][' + opi + '][fn]"></td>';
                  tbody += '<td><input type="text" class="form-control input-sm" placeholder="产品名" name="item_s[' + num + '][row][' + opi + '][item_name]"></td>';
                  tbody += '<td><input type="text" class="form-control input-sm" placeholder="售价" name="item_s[' + num + '][row][' + opi + '][price]"></td>';
                  tbody += '<td><input type="text" class="form-control input-sm" placeholder="成本价" name="item_s[' + num + '][row][' + opi + '][cost_price]"></td>';
                  tbody += '<td><input type="text" class="form-control input-sm" placeholder="市场价" name="item_s[' + num + '][row][' + opi + '][mktprice]"></td>';
                  tbody += '<td><input type="text" class="form-control input-sm" placeholder="儿童价" name="item_s[' + num + '][row][' + opi + '][child_price]"></td>';
                  tbody += '<td><input type="text" class="form-control input-sm" placeholder="库存" name="item_s[' + num + '][row][' + opi + '][stock]"></td>';
                  tbody += '</tr>';
              })
            }else{
              tbody += '<tr>';
              tbody += '<td><input type="text" class="form-control input-sm" placeholder="货号" name="item_s[' + num + '][row][0][fn]"></td>';
              tbody += '<td><input type="text" class="form-control input-sm" placeholder="产品名" name="item_s[' + num + '][row][0][item_name]"></td>';
              tbody += '<td><input type="text" class="form-control input-sm" placeholder="售价" name="item_s[' + num + '][row][0][price]"></td>';
              tbody += '<td><input type="text" class="form-control input-sm" placeholder="成本价" name="item_s[' + num + '][row][0][cost_price]"></td>';
              tbody += '<td><input type="text" class="form-control input-sm" placeholder="市场价" name="item_s[' + num + '][row][0][mktprice]"></td>';
              tbody += '<td><input type="text" class="form-control input-sm" placeholder="儿童价" name="item_s[' + num + '][row][0][child_price]"></td>';
              tbody += '<td><input type="text" class="form-control input-sm" placeholder="库存" name="item_s[' + num + '][row][0][stock]"></td>';
              tbody += '</tr>';
            }
            tbody += '</tbody>';
            table.append(tbody);
            //结束创建Table表
            colArr.pop(); //删除数组中最后一项
            //合并单元格
            $(table).mergeCell(colArr);
        } else {
            alert("还有选项没有勾选");
        }
    });
    //返回规格时间标签
    function specLi(num) {
        var timeStr = '';
        timeStr += '<li class="spec_class">';
        timeStr += '<a href="#tab' + num + '" data-toggle="tab" aria-expanded="true">';
        timeStr += '<span class="time' + num + '">请选择时间</span>';
        timeStr += '<button type="button" class="btn btn-default btn-xs" onclick=\'specDel(this,"' + num + '")\'><i class="fa fa-times"></i> 删除</button>';
        timeStr += '<button type="button" class="btn btn-default btn-xs time_change"><i class="fa fa-pencil"></i> 选择时间</button><input type="hidden" name="item_s[' + num + '][time]" value="">';
        timeStr += '</a></li>';
        return timeStr;
    };
    //处理获取的信息
    function handleArr(opArr) {
        var len = opArr.length;
        if (len >= 2) {
            var arr1 = opArr[0],
                arr2 = opArr[1],
                len1 = arr1.length,
                len2 = arr2.length,
                newlen = len1 * len2,
                temp = new Array(newlen),
                index = 0;
            for (var i = 0; i < len1; i++) {
                for (var j = 0; j < len2; j++) {
                    temp[index] = arr1[i] + "," + arr2[j];
                    index++;
                }
            }
            var newArray = new Array(len - 1);
            newArray[0] = temp;
            if (len > 2) {
                var _count = 1;
                for (var i = 2; i < len; i++) {
                    newArray[_count] = opArr[i];
                    _count++;
                }
            }
            return handleArr(newArray);
        } else {
            return opArr[0];
        }
    }
    //对table添加一个方法对表格进行合并操作
    $.fn.mergeCell = function(options) {
        return this.each(function() {
            var cols = options;
            for (var i = 0; i < cols.length; i++) {
                mergeCell($(this), cols[i]);
            }
            dispose($(this));
        });
    };

    function mergeCell($table, colIndex) {
        $table.data('col-content', ''); // 存放单元格内容
        $table.data('col-rowspan', 1); // 存放计算的rowspan值 默认为1
        $table.data('col-td', $()); // 存放发现的第一个与前一行比较结果不同td, 默认一个"空"的jquery对象
        $table.data('trNum', $('tbody tr', $table).length); // 要处理表格的总行数, 用于最后一行做特殊处理时进行判断之用
        $('tbody tr', $table).each(function(index) {
            var $td = $('td:eq(' + colIndex + ')', this), //当前TD
                tdHtml = $td.find('span').html(), //当前TD里面span的值
                prevTdHtml = $td.prev().find('span').html(); //td上一个td里的值
            if (typeof(prevTdHtml) == 'undefined') {
                var currentContent = tdHtml;
            } else {
                var currentContent = tdHtml + prevTdHtml;
            }
            if ($table.data('col-content') == '') {
                $table.data('col-content', currentContent);
                $table.data('col-td', $td);
            } else {
                if ($table.data('col-content') == currentContent) {
                    var rowspan = $table.data('col-rowspan') + 1;
                    $table.data('col-rowspan', rowspan);
                    $td.hide();
                    if (++index == $table.data('trNum'))
                        $table.data('col-td').attr('rowspan', $table.data('col-rowspan'));
                } else {
                    if ($table.data('col-rowspan') != 1) {
                        $table.data('col-td').attr('rowspan', $table.data('col-rowspan'));
                    }
                    $table.data('col-td', $td);
                    $table.data('col-content', currentContent);
                    $table.data('col-rowspan', 1);
                }
            }
        });
    }
    // 清理内存
    function dispose($table) {
        $table.removeData();
    }

    //编辑时处理表格
    if($(".spec_class").length>0){
      var cTable=$("table.table-bordered");
      cTable.each(function(j,t){
        var len=$(t).data('mc'),
            colArr=[];
        for(var i=0;i<len-1;i++){
            colArr.push(i);
        }
        $(t).mergeCell(colArr);
      })
    }

    //复制规格
    $(".spec_copy_class").click(function() {
            var spec_class = $(".spec_class_box li"),
                i = 0;
            if (spec_class.length == 0) {
                alert("请先新增一个规格才可以复制。");
                return false;
            }
            spec_class.each(function(j, v) {
                if ($(v).hasClass("active")) {
                    i = j;
                }
            })
            var num = 0; //规格的长度
            //比较当前I的值
            specTable.children("div").each(function(k, v) {
                var dI = $(v).data("tableid");
                if (num < dI) num = dI;
            });
            num++;
            var timeStr = specLi(num), //规格的html内容
                tableHtml = $(".spec_table_box .tab-pane").eq(i).html(); //第一个表格的html内容
            specUl.append(timeStr);
            //绑定时间框
            new specOnTime($('.time_change'));
            specTable.append('<div class="tab-pane fade" id="tab' + num + '" data-tableid="' + num + '">' + tableHtml.replace(/item_s[\[][0-9][\]]/g, 'item_s[' + num + ']') + '</div>');
        })
        //给value赋值，用于复制规格
    specTable.on('keyup', 'input', function() {
            $(this).attr('value', $(this).val());
        }).on('change', 'select', function() {
            var i = $(this).get(0).selectedIndex,
                option = $(this).find('option');
            option.removeAttr("selected", "selected");
            option.eq(i).attr("selected", "selected");
        })
        //绑定动态生成的时间
    function specOnTime(dom) {
        dom.daterangepicker({
            language: 'en',
            singleDatePicker: true,
            opens: 'right'
        }).on('apply.daterangepicker', function(e, picker) {
            e.stopPropagation();
            var time = picker.startDate.format('YYYY-MM-DD'), //获取时间
                dome = $(this).prev().prev(), //dom按钮
                inV = $(this).next(), //时间input
                timeI = $(".spec_class input"); //已有的时间选项
            if (timeI.length > 0) {
                var flag = true;
                //对比当前已经添加的时间规格
                timeI.each(function(k, v) {
                    var dTime = $(v).val();
                    if (dTime == time) {
                        alert("该时间已添加");
                        flag = false;
                        return false;
                    }
                });
                if (flag) {
                    dome.html(time);
                    inV.val(time);
                }
            }
            cGoTime(time);
        })
    }
    specOnTime($('.time_change'));

}();
//删除规格
function specDel(e, id) {
    $('#tab' + id + '').remove();
    $(e).parents("li").remove();
    var time = $(e).parents("li").find("input").val();
    cGoTime(time);
}
/*
 *出发时间选择
 */
var goTime = $("#goTime"); //出发日期
goTime.datetimepicker({
    language: 'zh',
    autoclose: true,
    minView: "month",
    format: "yyyy-mm-dd",
    pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
}).on('hide', function(e) {
    var changeTime = goTime.val(); //选择时间
    cGoTime(changeTime);
});
//出发日期限制
function cGoTime(changeTime) {
    var cTime = stamp(changeTime), //选择时间
        haveTime = $(".spec_class input"), //已有时间
        hArr = [];
    if (haveTime.val()) {
        //添加到数组
        haveTime.each(function(i, v) {
            var hTime = stamp($(v).val());
            if (!isNaN(hTime)) {
                hArr.push(hTime);
            }

        });
        //取出最小值
        var minTime = Math.min.apply(null, hArr);
        goTime.val(rtime(minTime));
    }
}
//返回时间戳
function stamp(date) {
    date = date.substring(0, 19);
    date = date.replace(/-/g, '/');
    var timestamp = new Date(date).getTime();
    return timestamp;
}
//返回日期
function rtime(timestamp) {
    var d = new Date(timestamp * 1); //根据时间戳生成的时间对象
    var date = (d.getFullYear()) + "-" +
        (d.getMonth() + 1 < 10 ? '0' + (d.getMonth() + 1) : d.getMonth() + 1) + "-" +
        (d.getDate() < 10 ? '0' + (d.getDate()) : d.getDate());
    return date;
}
/*
	验证表单
*/
$("form").validate({
    errorElement: 'span',
    errorClass: 'help-block help-block-error',
    onkeyup: false,
    highlight: function(element) {
        $(element).parents('.form-group').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).parents('.form-group').removeClass('has-error');
    },
    success: function(label) {
        label.parents('.form-group').removeClass('has-error');
    },
    rules: {
        product_name: 'required',
        sn: {
            required: true,
            noZw: true,
            remote: {
                url: "ajax?op=authsn", //填写异步url ajax?op=authsn
                type: "post",
                dataType: "json",
                data: {
                    id: $("#pordId").val()
                }
            }
        },
        mktprice: {
            required: true,
            number: true
        },
        price: {
            required: true,
            number: true
        },
        cost_price: {
            required: true,
            number: true
        },
        stock: {
            required: true,
            number: true
        },
        supplier_id:'required',
        tourism_type:'required',
        brief: 'required',
        up_time: 'required',
        down_time: 'required',
        item_s: 'required',

    },
    messages: {
        sn: {
            remote: "货号不能重复",
        }
    }
});
var errorMsg=$(".error_msg");
$("form").submit(function(e){
    var specClass=$(".spec_class_box input[name*=time]");
    if(specClass.length==0){
      alert("请先添加规格");
      return false;
    }
    var isUp=false;//上架时间是否小于规格时间
    var upTime=$("input[name='up_time']").val();
    var downTime=$("input[name='down_time']").val();
    $.each(specClass,function(i,t){
      if(upTime<$(t).val()){
        isUp=true;
      }
    })
    if(!isUp){
      alert("上架时间必须小于规格时间中一个即可");
      return false;
    }
    if(downTime<upTime){
      alert("上架时间必须大于下架时间");
      return false;
    }

		e.preventDefault();

    $.post($(this).attr("action"),$(this).serialize(),function(data){
        if(data.flag=='error'){
          var str='<div class="alert alert-danger">';
    			str+='<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
    			str+='<strong>错误！</strong> '+data.msg;
    			str+='</div>';
        }else{
          var str='<div class="alert alert-success">';
    			str+='<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
    			str+='<strong>保存成功！</strong> '+data.msg;
    			str+='</div>';
        }
        errorMsg.html(str);
    },'json');
});
