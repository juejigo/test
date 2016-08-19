"use srtict";
var airfare = new Object(),
    addBox = $("#addFly"),
    editBox = $("#editFly");
var pordId = $("#prodId").val(); //商品ID
/*
	绑定事件控件
*/
var timeOn = $('#addFly .reservationtime').daterangepicker({
    timePicker: true,
    timePickerIncrement: 1,
    opens: 'left',
    startDate: moment().subtract('days', 29),
    endDate: moment(),
    timePicker12Hour: false
});
timeOn.on('apply.daterangepicker', function(ev, picker) {
    $('#addFly .reservationtime').val(picker.startDate.format('YYYY-MM-DD HH:mm') + ' 至 ' + picker.endDate.format('YYYY-MM-DD HH:mm'));
    $('#addFly .start_time').val(picker.startDate.format('YYYY-MM-DD HH:mm'));
    $('#addFly .end_time').val(picker.endDate.format('YYYY-MM-DD HH:mm'));
});


var airfare = new Object();
/*
	添加航班
*/
airfare.add = function() {
        var type = $("#addFly .type").val(), //往返类型
						prodTime = $("#addFly .prod_time").val(), //商品日期
						startTime = $("#addFly .start_time").val(), //起飞时间
            endTime = $("#addFly .end_time").val(), //降落时间
            goAddr = $("#addFly .go_address").val(), //出发城市
            goAirport = $("#addFly .go_airport").val(), //出发机场
            backAddr = $("#addFly .back_address").val(), //到达城市
            backAirport = $("#addFly .back_airport").val(), //到达机场
            company = $("#addFly .company").val(), //航空公司
            flight = $("#addFly .flight").val(), //航班
            seat = $("#addFly .seat").val(), //舱位
            price = $("#addFly .price").val(); //价格
        if (!type) {
            alert("请选择类型");
            return false;
        }
        $.ajax({
            url: 'ajax?op=addticket',
            type: 'POST',
            dataType: 'json',
            data: {
				product_id: prodTime,
                type: type,
                go_time: startTime,
                return_time: endTime,
                go_area: goAddr,
                go_airport: goAirport,
                return_area: backAddr,
                return_airport: backAirport,
                company: company,
                flight: flight,
                berths: seat,
                price: price
            }
        }).done(function(data) {
            if (data.errno == 0) {
                addBox.modal("hide").on('hidden.bs.modal', function(e) {
                    window.location.reload();
                })
            }
        })
    }
    /*
    	编辑加载航班
    */
$(".edit").click(function() {
        var _this = $(this),
            id = _this.parents("tr").data("airfareid"),
            box = $("#editFly .modal-body");
        $.ajax({
            url: 'ajax?op=editticket',
            type: 'POST',
            dataType: 'json',
            data: {
                id: id,
                prod_id:$("#prodId").val()
            }
        }).done(function(data) {
            if (data.errno == 0) {
                box.html(data.html);
            }
        })
        editBox.modal('show').on('shown.bs.modal', function() {
            $('#editFly .reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 1,
                opens: 'left',
                startDate: moment().subtract('days', 29),
                endDate: moment(),
                timePicker12Hour: false
            }).on('apply.daterangepicker', function(ev, picker) {
                $('#editFly .reservationtime').val(picker.startDate.format('YYYY-MM-DD HH:mm') + ' 至 ' + picker.endDate.format('YYYY-MM-DD HH:mm'));
                $('#editFly .start_time').val(picker.startDate.format('YYYY-MM-DD HH:mm'));
                $('#editFly .end_time').val(picker.endDate.format('YYYY-MM-DD HH:mm'));
            });
        });

    })
    /*
    	编辑航班
    */
airfare.edit = function() {
        var flyId = $("#editFly #flyId").val(), //航班ID
            type = $("#editFly .type").val(), //往返类型
						prodTime = $("#editFly .prod_time").val(), //商品日期
            startTime = $("#editFly .start_time").val(), //起飞时间
            endTime = $("#editFly .end_time").val(), //降落时间
            goAddr = $("#editFly .go_address").val(), //出发城市
            goAirport = $("#editFly .go_airport").val(), //出发机场
            backAddr = $("#editFly .back_address").val(), //到达城市
            backAirport = $("#editFly .back_airport").val(), //到达机场
            company = $("#editFly .company").val(), //航空公司
            flight = $("#editFly .flight").val(), //航班
            seat = $("#editFly .seat").val(), //舱位
            price = $("#editFly .price").val(); //价格
        if (!type) {
            alert("请选择类型");
            return false;
        }
        $.ajax({
            url: 'ajax?op=deitvalticket',
            type: 'POST',
            dataType: 'json',
            data: {
                id: flyId,
                product_id: prodTime,
                type: type,
                go_time: startTime,
                return_time: endTime,
                go_area: goAddr,
                go_airport: goAirport,
                return_area: backAddr,
                return_airport: backAirport,
                company: company,
                flight: flight,
                berths: seat,
                price: price
            }
        }).done(function(data) {
            if (data.errno == 0) {
                editBox.modal("hide").on('hidden.bs.modal', function(e) {
                    window.location.reload();
                })
            }
        })
    }
    /*
    	删除航班
    */
$(".del").click(function() {
    var _this = $(this).parents("tr"),
        id = _this.data("airfareid");
    $.ajax({
        url: 'ajax?op=deleteticket',
        type: 'POST',
        dataType: 'json',
        data: {
            id: id
        }
    }).done(function(data) {
        if (data.errno == 0) {
            _this.animate({
                opacity: 0
            }, 800, function() {
                _this.remove();
            })
        }
    })
})
