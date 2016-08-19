"use srtict";
var orderList = function() {
    return {
        init: function() {
            bootbox.setDefaults("locale", "zh_CN");
            /**
             * 显示用户备注
             */
            $(".userNote a").click(function() {
                var info = $(this).data("info"),
                    modal = $('#userNote');
                modal.find('.modal-body').html(info);
                modal.modal('show');
            });
            /**
             * 显示后台备注
             */
            var rModal = $('#remarks');
            $(".remarks").click(function() {
                var id = $(this).parents("tr").data("id");
                $.post("/ordercp/order/ajax?op=remarks", {
                    id: id
                }, function(data) {
                    if (data.errno == 0) {
                        rModal.find('input').val(data.id);
                        rModal.find('textarea').val(data.admin_memo);
                        rModal.modal('show');
                    } else {
                        alert('获取失败');
                    }
                }, 'json');
            });
            /**
             * 提交备注
             */
            rModal.find('.sub').click(function() {
                var data = rModal.find('form').serialize();
                $.post("/ordercp/order/ajax?op=editremarks", data, function(data) {
                    if (data.errno == 0) {
                        rModal.modal('hide');
                    } else {
                        alert('保存失败');
                    }
                }, 'json');
            });
            /**
             * 确认
             */
            $('.right').click(function() {
                var id = $(this).parents("tr").data("id");
                bootbox.confirm("如果已经和供应商确认有尾单，点击确认按钮，订单会改变为待出行状态?", function(result) {
                    if (result) {
                        $.post("/ordercp/order/ajax?op=confirm_order", {
                            id: id
                        }, function(data) {
                            if (data.errno == 0) {
                                window.location.reload();
                            } else {
                                alert('确认失败！');
                            }
                        }, 'json');
                    }
                });
            });
            /**
             * 退款
             */
            $(".refund").click(function() {
                var id = $(this).parents("tr").data("id");
                bootbox.confirm("确认要给此订单退款？", function(result) {
                    if (result) {
                        $.post("/ordercp/order/ajax?op=refund", {
                            id: id
                        }, function(data) {
                            if (data.errno == 0) {
                                window.location.reload();
                            } else if ( data.errno == 2)
                            {
                            	window.open(data.url);
                            }
                            else{
                                alert('退款失败！');
                            }
                        }, 'json');
                    }
                });
            });
        }
    };
}();
orderList.init();
