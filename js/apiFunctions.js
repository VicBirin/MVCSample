

/**
 * Loads current user from server
 */
function loadCurrentUser() {

    let url = `${apiUrl}user-get`;
    $.ajax({
        type: "GET",
        url: url,
        datatype: "application/json",
        success: function (response) {
            if (response.user != undefined && response.user != null) {
                $("#loginLabel")[0].innerText = response.user;
                sessionStorage.setItem("user", response.user);
            } else {
                $("#loginLabel")[0].innerText = "Sign in";
                sessionStorage.removeItem("user");
            }
        }
    });
}

/**
 * Load task list from the server
 */
function loadTaskList(page) {

    if (page == undefined) {
        page = -1;
    }
    let url = buildUrl(page);

    $.ajax({
        type: "GET",
        url: url,
        datatype: "application/json",
        success: function (response) {
            populateTaskTable(response.data);
            configurePaging(response.pagination);
            applySorting(response.pagination.sort);
        }
    });

}

/**
 * Builds url to request data from server
 * @returns {string}
 */
function buildUrl(page) {
    let sortString = getSorting();
    let url;
    if (sortString != undefined && sortString != "") {
        url = `${apiUrl}task-list/${page}/${perPage}/${sortString}`
    } else {
        url = `${apiUrl}task-list/${page}/${perPage}`
    }
    return url;
}

/**
 * Creates new task
 */
function createTask() {

    let data = [];
    data['userName'] = $("#formUsername")[0].value;
    data['email'] = $("#formEmail")[0].value;
    data['body'] = $("#formTaskBody")[0].value;
    data['completed'] = 0;

    let url = `${apiUrl}task-add`;

    $.ajax({
        type: "POST",
        url: url,
        data: {userName: data['userName'], email: data['email'], body: data['body'], completed: data['completed']},
        datatype: "application/json",
        success: function (response) {
            $("#taskAlert")[0].hidden = false;
            loadTaskList(-1);
        }
    });

    // hide form
    $("#newTaskModal").modal('hide');
}

/**
 * Updates existing task
 */
function updateTask() {

    let data = [];
    data['id'] = $("#formTaskId")[0].value
    data['userName'] = $("#formUsername")[0].value;
    data['email'] = $("#formEmail")[0].value;
    data['body'] = $("#formTaskBody")[0].value;
    data['completed'] = $("#formCompleted")[0].checked;

    let url = `${apiUrl}task-edit`;

    $.ajax({
        type: "POST",
        url: url,
        data: {
            id: data['id'],
            userName: data['userName'],
            email: data['email'],
            body: data['body'],
            completed: data['completed']
        },
        datatype: "application/json",
        success: function (response) {
            $("#taskUpdateAlert")[0].hidden = false;
            loadTaskList(-1);
            resetTaskForm();
        },
        error: function (response){
            $("#loginAlert")[0].hidden = false;
        }
    });

    $("#newTaskModal").modal('hide');
}

/**
 * Sign in procedure
 */
function performLogin() {

    let url = `${apiUrl}user-login`;
    let user = $("#loginUser")[0].value;
    let pass = $("#loginPassword")[0].value;

    $.ajax({
        type: "POST",
        url: url,
        data: {user: user, password: pass},
        datatype: "application/json",
        success: function (response) {
            if (response != undefined && response.data != null) {
                $("#loginLabel")[0].innerText = response.data;
                sessionStorage.setItem("user", response.data);
            } else {
                $("#loginAlert")[0].hidden = false;
                sessionStorage.removeItem("user");
            }
        }
    });

    $("#loginModal").modal('hide');
}

/**
 * Performs log out
 */
function performLogout() {

    let url = `${apiUrl}user-logout`;
    $.ajax({
        type: "POST",
        url: url,
        datatype: "application/json",
        success: function (response) {
            if (response) {
                loadCurrentUser();
                prepareLoginForm();
            }
        }
    });
    $("#loginModal").modal('hide');
}

