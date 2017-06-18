;(function () {
    var $body = $('body');
    var $chosens = $('.chosen');

    if ($chosens.length > 0) {
        $chosens.chosen({
            allow_single_deselect: true,
            no_results_text: "没有找到"
        });
    }

    $body.on('click', '.reset', function(e) {
        var _this = $(this);

        if (confirm('确定要重置密码？')) {
            var url = _this.data('url');

            $.get(url, function (json) {
                if(json.success){
                    iSuccess(json.msg);
                }else{
                    iError(json.msg);
                }
            }, 'json');
        }
    });

    $body.on('click', '.delete', function(e) {
        var _this = $(this);

        if (confirm('确定要删除？')) {
            var url = _this.data('url');

            $.get(url, function (json) {
                if(json.success){
                    iSuccess(json.msg, function() {
                        if (json.redirect) {
                            location.href = json.redirect;
                        }
                    });
                }else{
                    iError(json.msg);
                }
            }, 'json');
        }
    });
})();