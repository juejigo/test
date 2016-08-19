"use srtict";
var orderList = function() {
    return {
        init: function() {
            bootbox.setDefaults("locale", "zh_CN");
            /**
             * 确认
             */
            $('.right').click(function() {
                var id = $(this).parents("tr").data("id");
                bootbox.confirm("如果已经和供应商确认有尾单，点击确认按钮，订单会改变为已完成状态?", function(result) {
                    if (result) {
                        $.post("/onecp/order/ajax?op=confirm", {
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
        }
    };
}();
orderList.init();
