
/**
 * Configures page pagination
 * @param data
 */
function configurePaging(pagination) {
    currentPage = pagination.page;
    totalPages = pagination.totalPages;

    if (totalPages > 1) {
        // add navigation pages
        let currElm = $('#prevPage');
        for (let i = 1; i <= totalPages; i++) {
            if ($(`#page${i}`).length) {
                $(`#page${i}`).attr('class', 'page-item');
                continue;
            }

            let newElm = $(`<li class="page-item" id="page${i}"><a class="page-link">${i}</a></li>`);
            currElm.after(newElm);
            currElm = newElm;
            newElm.on('click', function () {
                loadTaskList(i);
            });
        }
    }

    // set current page
    $(`#page${currentPage}`).attr('class', 'page-item active');

    // set first and last pages
    if (currentPage == 1) {
        $(`#firstPage`).attr('class', 'page-item disabled');
        $(`#prevPage`).attr('class', 'page-item disabled');
    }
    if (totalPages > 1 &&  currentPage < totalPages) {
        $(`#nextPage`).attr('class', 'page-item');
        $(`#lastPage`).attr('class', 'page-item');
    }
    if (currentPage == totalPages) {
        $(`#nextPage`).attr('class', 'page-item disabled');
        $(`#lastPage`).attr('class', 'page-item disabled');
    }
    if(currentPage > 1) {
        $(`#firstPage`).attr('class', 'page-item');
        $(`#prevPage`).attr('class', 'page-item');
    }
}

/**
 * handles click on next page button
 */
function moveNext() {
    if (currentPage == totalPages) return;
    loadTaskList(++currentPage);
}

/**
 * handles click on back button
 */
function moveBack() {
    if (currentPage == 1) return;
    loadTaskList(--currentPage);
}

/**
 * handles click on move to first page button
 */
function moveToFirst() {
    loadTaskList(1);
}

/**
 * handles click on move to last page button
 */
function moveToLast() {
    loadTaskList(totalPages);
}