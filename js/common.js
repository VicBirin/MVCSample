/**
 * Event handlers
 */
$(document).ready(function () {

    $('#id').on('click', function () {
        toggleSort('id');
        loadTaskList(currentPage);
    });
    $('#userName').on('click', function () {
        toggleSort('userName');
        loadTaskList(currentPage);
    });
    $('#email').on('click', function () {
        toggleSort('email');
        loadTaskList(currentPage);
    });
    $('#completed').on('click', function () {
        toggleSort('completed');
        loadTaskList(currentPage);
    });

    $("#newTaskModalForm").on('submit',function(e){
        e.preventDefault();
    });

    $("#loginForm").on('submit',function(e){
        e.preventDefault();
    });

    $(`#firstPage`).on('click', function (sender) {
        if(!sender.target.className.includes('disabled')) {
            moveToFirst();
        }
    });
    $(`#prevPage`).on('click', function (sender) {
        if(!sender.target.className.includes('disabled')) {
            moveBack();
        }
    });
    $(`#nextPage`).on('click', function (sender) {
        if(!sender.target.className.includes('disabled')) {
            moveNext();
        }
    });
    $(`#lastPage`).on('click', function (sender) {
        if(!sender.target.className.includes('disabled')) {
            moveToLast();
        }
    });

    $('#taskAlert').on('click', function(){
        $("#taskAlert")[0].hidden = true;
    });

    $('#taskUpdateAlert').on('click', function(){
        $("#taskUpdateAlert")[0].hidden = true;
    });

    $('#loginAlert').on('click', function(){
        $("#loginAlert")[0].hidden = true;
    });

    loadCurrentUser();
    loadTaskList();
});
