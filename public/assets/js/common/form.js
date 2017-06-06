;(function() {
    $('#ajax_form').submit(function(e) {
        var _this = $(this);

        var url = _this.attr('action');
        var data = _this.serialize();

        $.post(url, data, function(json) {
            if(json.success){
                iSuccess(json.msg, function() {
                    if (json.data.next) {
                        location.href = json.data.next;
                    }
                });
            }else{
                iError(json.msg, function() {
                    if (json.data.next) {
                        location.href = json.data.next;
                    }
                });
            }
        }, 'json');

        return false;
    });
})();