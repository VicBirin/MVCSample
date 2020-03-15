/**
 * Configures and displays task edit form
 * @param obj
 */
function showEditForm(obj) {

    let id = obj.delegateTarget.cells[0].innerText;
    let userName = obj.delegateTarget.cells[1].innerText;
    let email = obj.delegateTarget.cells[2].innerText;
    let body = obj.delegateTarget.cells[3].innerText;
    let status = obj.delegateTarget.cells[5].innerText;

    $("#formTaskId")[0].value = id;
    $("#newTaskModalTitle")[0].innerText = `Edit task #${id}`;
    $("#formUsername")[0].value = userName;
    $("#formEmail")[0].value = email;
    $("#formTaskBody")[0].value = body;
    $("#formSubmit")[0].innerHTML = "Edit task";
    $("#newTaskModalForm")[0].onsubmit = updateTask;
    $("#formCompleted")[0].checked = status === 'Done';
    $("#formCompletedArea")[0].hidden = false;

    if (sessionStorage.getItem("user") != 'admin') {
        $("#formTaskId")[0].readOnly = true;
        $("#newTaskModalTitle")[0].readOnly = true;
        $("#formUsername")[0].readOnly = true;
        $("#formEmail")[0].readOnly = true;
        $("#formTaskBody")[0].readOnly = true;
        $("#formSubmit")[0].disabled = true;
        $("#newTaskModalForm")[0].onsubmit = null;
        $("#formCompleted")[0].disabled = true;
        $("#formCompletedArea")[0].readOnly = true;
    }
    else{
        // re-enable controls
        $("#formTaskId")[0].readOnly = false;
        $("#newTaskModalTitle")[0].readOnly = false;
        $("#formUsername")[0].readOnly = false;
        $("#formEmail")[0].readOnly = false;
        $("#formTaskBody")[0].readOnly = false;
        $("#formSubmit")[0].disabled = false;
        $("#formCompleted")[0].disabled = false;
        $("#formCompletedArea")[0].readOnly = false;
    }

    $("#newTaskModal").modal();

}

/**
 * Configures login form
 */
function prepareLoginForm() {
    $("#loginUser")[0].required = sessionStorage.getItem('user') == null;
    $("#loginPassword")[0].required = sessionStorage.getItem('user') == null;
    $("#loginUserRow")[0].hidden = sessionStorage.getItem('user') != null;
    $("#loginPasswordRow")[0].hidden = sessionStorage.getItem('user') != null;
    $("#loginSigninRow")[0].hidden = sessionStorage.getItem('user') != null;
    $("#loginSignoutRow")[0].hidden = sessionStorage.getItem('user') == null;
    $("#loginForm")[0].onsubmit = sessionStorage.getItem('user') == null ? performLogin : performLogout;
}

/**
 * Reset task creation form to original state
 */
function resetTaskForm() {
    $("#newTaskModal").modal('hide');

    $("#formTaskId")[0].value = "";
    $("#newTaskModalTitle")[0].innerText = `New task`;
    $("#formUsername")[0].value = "";
    $("#formEmail")[0].value = "";
    $("#formTaskBody")[0].value = "";
    $("#formCompleted")[0].checked = false;

    $("#formCompletedArea")[0].hidden = true;
    $("#formSubmit")[0].innerHTML = "Create task";
    $("#newTaskModalForm")[0].onsubmit = createTask;

    $("#formTaskId")[0].readOnly = false;
    $("#newTaskModalTitle")[0].readOnly = false;
    $("#formUsername")[0].readOnly = false;
    $("#formEmail")[0].readOnly = false;
    $("#formTaskBody")[0].readOnly = false;
    $("#formSubmit")[0].disabled = false;
    $("#formCompleted")[0].disabled = false;
    $("#formCompletedArea")[0].readOnly = false;
}