//페이지네이션
function renderPagination(tableId, totalCnt, perPage, pageSize, current, changePage){
	let $pagingHtml = $('#' + tableId).parent(); //append시킬 부모 요소

	if($($pagingHtml).find('.pagination').length > 0){
		$('.pagination').remove();
	}

	const pageCount = parseInt((totalCnt - 1) / perPage + 1); // 전체 페이지 수
	const pageBlock = parseInt(pageCount / pageSize); // 생성될 페이지 블록 수

	let pages = [];
	let curBlockNum = parseInt((current - 1) / pageSize);

	if(totalCnt > 0){
		let start = curBlockNum * pageSize;
		let end = pageCount >= start + pageSize - 1 ? start + pageSize - 1 : pageCount - 1;
		for(let i = start; i <= end; i++){
			pages.push(i);
		}
	}
	let html = '<div class="pagination">';

	if(current !== 1){
		html += '<a href="#" id="first">처음</a>';
		html += '<a href="#" id="prev">이전</a>';
	}
	if(pages.length > 0){
		for(let i=0; i<pages.length; i++){
			html += "<a href='#' id=" + (pages[i] + 1) + ">" + (pages[i] + 1) + "</a>";
		}
	}
	if(pageCount > 1 && current !== pageCount){
		html += '<a href="#" id="next">다음글</a>';
		html += '<a href="#" id="last">마지막</a>';
	}
	html += '</div>';
	$($pagingHtml).append(html);

	$(".pagination a").css("color", "black");
	$(".pagination a#" + current).css({"text-decoration": "none", "font-weight": "bold"});

	$(".pagination a").on("click",function(){

		let $item = $(this);
		let $id = $item.attr("id");
		let selected = $item.text();

		if($id === "next") selected = Number(current) + 1;
		else if($id === "prev") selected = Number(current) - 1;
		else if($id === "last") selected = Number(pageCount);
		else selected = Number(selected);

		if(selected === current) return;
		changePage(selected);
		renderPagination(tableId, totalCnt, perPage, selected, changePage);
	});
}