;(function () {
    var $multiple = $('#multiple');

    $multiple.multiSelect({
        selectableHeader: '<input type="text" id="search_selectable" class="search-input form-control" autocomplete="off"><div class="custom-header"><span class="label label-default">未分配路由</span></div>',
        selectionHeader: '<input type="text" id="search_selection" class="search-input form-control" autocomplete="off"><div class="custom-header"><span class="label label-success">已分配路由</span></div>',
        selectableFooter: '<button type="button" id="select_all" class="btn btn-success">全选</button>',
        selectionFooter: '<button type="button" id="deselect_all" class="btn btn-danger">全不选</button>',
        afterInit: function (ms) {
            var that = this,
                $selectableSearch = that.$container.find('#search_selectable'),
                $selectionSearch = that.$container.find('#search_selection'),
                selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString).on('keydown', function (e) {
                    if (e.which == 40) {
                        that.$selectableUl.focus();
                        return false;
                    }
                });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString).on('keydown', function (e) {
                    if (e.which == 40) {
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function () {
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function () {
            this.qs1.cache();
            this.qs2.cache();
        }
    });

    $('#select_all').click(function () {
        $multiple.multiSelect('select_all');
        return false;
    });

    $('#deselect_all').click(function () {
        $multiple.multiSelect('deselect_all');
        return false;
    });
})();