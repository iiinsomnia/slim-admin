;(function() {
    //搜索
    var $searchForm = $('#search_form');
    var $searchSubmit = $('#search_submit');
    //数据
    var $totalCount = $('#totalCount');
    var $dataBody = $('#data_body');
    //分页
    var $ajaxPagination = $('#ajax_pagination');
    var $firstPage = $('#firstPage');
    var $prevPage = $('#prevPage');
    var $nextPage = $('#nextPage');
    var $lastPage = $('#lastPage');
    var $curPage = $('#curPage');
    var $pageNum = $('#pageNum');
    var $totalPage = $('#totalPage');
    var $skipBtn = $('#skipBtn');

    var curPage = 1;
    var lastPage = parseInt($totalPage.data('num'));
    var pageUrl = $dataBody.data('url');
    var query = {};

	$searchSubmit.click(function(e) {
		/* Act on the event */
		loading_start();

		if ($searchForm) {
			formData = $searchForm.serializeArray();
			formData.forEach(function(ele, index, arr) {
				query[ele.name] = ele.value;
			});
		}

		$.post(pageUrl, {"page": 1, "query": query}, function(json){
			loading_end();

			if(json.success){
				if(json.data.totalPage > 1){
					$firstPage.removeClass('allowed').addClass('disabled');
					$prevPage.removeClass('allowed').addClass('disabled');
					$nextPage.removeClass('disabled').addClass('allowed');
					$lastPage.removeClass('disabled').addClass('allowed');
					$ajaxPagination.show();
				}else{
					$ajaxPagination.hide();
				}

				curPage = 1;
				lastPage = json.data.totalPage;
				$totalCount.text(json.data.totalCount);
				$curPage.text(1);
				$totalPage.text(json.data.totalPage);

				$dataBody.html(json.data.html);
			}else{
				alert_error(json.msg);
			}
		}, 'json');
	});

	$firstPage.click(function(e) {
		/* Act on the event */
		if(curPage > 1){
			loading_start();

			curPage = 1;
			$curPage.text(curPage);

			$.post(pageUrl, {"page": curPage, "query": query}, function(json){
				loading_end();

				if(json.success){
					$firstPage.removeClass('allowed').addClass('disabled');
					$prevPage.removeClass('allowed').addClass('disabled');
					$nextPage.removeClass('disabled').addClass('allowed');
					$lastPage.removeClass('disabled').addClass('allowed');

					$dataBody.html(json.data.html);
				}else{
					alert_error(json.msg);
				}
			}, 'json');
		}

		return false;
	});

	$prevPage.click(function(e) {
		/* Act on the event */
		if(curPage > 1){
			loading_start();

			curPage--;
			$curPage.text(curPage);

			$.post(pageUrl, {"page": curPage, "query": query}, function(json){
				loading_end();

				if (json.success) {
					if(curPage == 1){
						$firstPage.removeClass('allowed').addClass('disabled');
						$prevPage.removeClass('allowed').addClass('disabled');
					}
					$nextPage.removeClass('disabled').addClass('allowed');
					$lastPage.removeClass('disabled').addClass('allowed');

					$dataBody.html(json.data.html);
				}else{
					alert_error(json.msg);
				}
			}, 'json');
		}

		return false;
	});

	$nextPage.click(function(e) {
		/* Act on the event */
		if(curPage < lastPage){
			loading_start();

			curPage++;
			$curPage.text(curPage);

			$.post(pageUrl, {"page": curPage, "query": query}, function(json){
				loading_end();

				if(json.success){
					$firstPage.removeClass('disabled').addClass('allowed');
					$prevPage.removeClass('disabled').addClass('allowed');
					if(curPage == lastPage){
						$nextPage.removeClass('allowed').addClass('disabled');
						$lastPage.removeClass('allowed').addClass('disabled');
					}

					$dataBody.html(json.data.html);
				}else{
					alert_error(json.msg);
				}
			}, 'json');
		}

		return false;
	});

	$lastPage.click(function(e) {
		/* Act on the event */
		if(curPage < lastPage){
			loading_start();

			curPage = lastPage;
			$curPage.text(curPage);

			$.post(pageUrl, {"page": curPage, "query": query}, function(json){
				loading_end();

				if(json.success){
					$firstPage.removeClass('disabled').addClass('allowed');
					$prevPage.removeClass('disabled').addClass('allowed');
					$nextPage.removeClass('allowed').addClass('disabled');
					$lastPage.removeClass('allowed').addClass('disabled');

					$dataBody.html(json.data.html);
				}else{
					alert_error(json.msg);
				}
			}, 'json');
		}

		return false;
	});

	$skipBtn.click(function(e) {
		/* Act on the event */
		var pageNum = $pageNum.val();

		if(pageNum.trim() == '' || isNaN(pageNum)){
			alert_error('请输入页码');
			return false;
		}

		if(pageNum < 1){
			alert_error('页码太小了');
			return false;
		}

		if(pageNum > lastPage){
			alert_error('页码太大了');
			return false;
		}

		loading_start();

		curPage = pageNum;
		$curPage.text(curPage);

		$.post(pageUrl, {"page": curPage, "query": query}, function(json){
			loading_end();

			if(json.success){
				if(curPage == 1){
					$firstPage.removeClass('allowed').addClass('disabled');
					$prevPage.removeClass('allowed').addClass('disabled');
					$nextPage.removeClass('disabled').addClass('allowed');
					$lastPage.removeClass('disabled').addClass('allowed');
				}else if(curPage == lastPage){
					$firstPage.removeClass('disabled').addClass('allowed');
					$prevPage.removeClass('disabled').addClass('allowed');
					$nextPage.removeClass('allowed').addClass('disabled');
					$lastPage.removeClass('allowed').addClass('disabled');
				}else{
					$firstPage.removeClass('disabled').addClass('allowed');
					$prevPage.removeClass('disabled').addClass('allowed');
					$nextPage.removeClass('disabled').addClass('allowed');
					$lastPage.removeClass('disabled').addClass('allowed');
				}

				$dataBody.html(json.data.html);
			}else{
				alert_error(json.msg);
			}
		}, 'json');
	});
})();