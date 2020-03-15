
/**
 * Populates task table with records from database
 * @param data
 */
function populateTaskTable(data) {

    // remove old rows
    $('#taskListBody').find('tr').each(function () {
        this.remove();
    });

    $.each(data, function (i, item) {
        let tr = parseInt(item.completed) == 0 ? $('<tr>') : $('<tr class="table-success">');
        tr.append(
            $('<th scope="col">').text(item.id),
            $('<td>').text(item.userName),
            $('<td>').text(item.email),
            $('<td>').text(item.body),
            $('<td>').text(parseInt(item.edited) == 0 ? "" : "[Admin]"),
            $('<td>').text(parseInt(item.completed) == 0 ? "In progress" : "Done")
        ).appendTo('#taskListBody');
        tr.dblclick(showEditForm)
    });
}

/**
 * Set sorting when clicked on table column
 * @param field
 */
function toggleSort(field) {

    let elm = $(`#${field}`);
    let colSort = elm.attr('sort');

    if (colSort === "") {
        setColumnSorting(elm, "asc");
    } else if (colSort === "asc") {
        setColumnSorting(elm, "desc");
    } else if (colSort === "desc") {
        setColumnSorting(elm, "clear");
    }
}

/**
 * Set sorting for given column
 */
function setColumnSorting(colElm, colSort) {
    let name = colElm[0].firstElementChild.innerHTML.replace("↑", "").replace("↓", "");
    if (colSort === "asc") {
        name += " ↓";
    }
    if (colSort === "desc") {
        name += " ↑";
    }
    colElm.attr('sort', colSort);
    colElm[0].firstElementChild.innerHTML = name.trim();
}

/**
 * Prepares sorting for loading data
 */
function getSorting() {

    let sortValues = [];
    let elm;

    elm = $("#id");
    if (elm.attr("sort") !== undefined && elm.attr("sort") !== "") {
        sortValues.push(`id:${elm.attr("sort")}`);
    }
    elm = $("#userName");
    if (elm.attr("sort") !== undefined && elm.attr("sort") !== "") {
        sortValues.push(`userName:${elm.attr("sort")}`);
    }
    elm = $("#email");
    if (elm.attr("sort") !== undefined && elm.attr("sort") !== "") {
        sortValues.push(`email:${elm.attr("sort")}`);
    }
    elm = $("#completed");
    if (elm.attr("sort") !== undefined && elm.attr("sort") !== "") {
        sortValues.push(`completed:${elm.attr("sort")}`);
    }
    return sortValues.join(',');
}

/**
 * Apply current sorting over columns
 */
function applySorting(sortString) {

    setColumnSorting($(`#id`), "");
    setColumnSorting($(`#userName`), "");
    setColumnSorting($(`#email`), "");
    setColumnSorting($(`#completed`), "");

    if (sortString == undefined || sortString == "") {
        return;
    }

    let strArr = sortString.includes(",") ? sortString.split(',') : [sortString];
    strArr.forEach(function (value) {
        if (value.includes("clear")) return;
        let values = value.split(':');
        setColumnSorting($(`#${values[0]}`), values[1]);
    });
}