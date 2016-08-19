"use srtict";
var orderDetails = function() {
    return {
        init: function() {
            var orderId = $("#orderId").val();
            bootbox.setDefaults("locale", "zh_CN");
            /**
             * 提交快递信息
             */
            var addExModal = $("#addExpress");
            $("#expressSub").click(function() {
                var company = addExModal.find(".company").val(),
                    num = addExModal.find(".num").val();
                $.post("/ordercp/order/ajax?op=shipping", {
                    id: orderId,
                    company_no: company,
                    shipping_no: num
                }, function(data) {
                    if (data.errno == 0) {
                        addExModal.modal('hide');
                        window.location.reload();
                    } else {
                        alert('保存失败');
                    }
                }, 'json');
            });
            /**
             * 提交优惠信息
             */
            var discountModal = $("#addDiscount");
            $("#addDis").click(function() {
                var amount = discountModal.find(".discount").val();
                $.post("/ordercp/order/discount", {
                    id: orderId,
                    amount: amount
                }, function(data) {
                    if (data.errno == 0) {
                    	discountModal.modal('hide');
                        window.location.reload();
                    } else {
                        alert(data.errmsg);
                    }
                }, 'json');
            });
            /**
             * 查看快递信息
             */
            var checkExModal = $("#checkExpress");
            $(".checkExpress").click(function() {
                var id = $(this).parents("tr").data("id");
                $.post("../success.php", {
                    orderId: orderId,
                    exModalId: id
                }, function(data) {
                    if (data.errno == 1) {
                        checkExModal.find('.modal-body').html(data.str); //快递进度信息
                        checkExModal.modal('show');
                    } else {
                        alert('获取失败');
                    }
                }, 'json');
            });
            /**
             * 确认
             */
            $('.right').click(function() {
                bootbox.confirm("如果已经和供应商确认有尾单，点击确认按钮，订单会改变为待出行状态?", function(result) {
                    if (result) {
                        $.post("/ordercp/order/ajax?op=confirm_order", {
                            id: orderId
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
                bootbox.confirm("确认要给此订单退款？", function(result) {
                    if (result) {
                        $.post("/ordercp/order/ajax?op=refund", {
                            id: orderId
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
            /**
             * 提交备注
             */
            var rModal = $('#remarks');
            rModal.find('.sub').click(function() {
                var textarea = rModal.find('textarea').val();
                $.post("/ordercp/order/ajax?op=editremarks", {
                    id: orderId,
                    remarks: textarea
                }, function(data) {
                    if (data.errno == 0) {
                        rModal.modal('hide');
                        window.location.reload();
                    } else {
                        alert('保存失败');
                    }
                }, 'json');
            });
            /**
             * 查看合同
             */
            var conModal = $("#con");
            $(".check_con").click(function() {
                var id = $(this).parents("tr").data("id");
                $.post("success.php", {
                    orderId: orderId,
                    conId: id
                }, function(data) {
                    if (data.errno == 0) {
                        conModal.find('.modal-body').html(data.admin_memo); //合同内容
                        conModal.modal('show');
                    } else {
                        alert('获取失败');
                    }
                }, 'json');
            });
        }
    };
}();
orderDetails.init();
