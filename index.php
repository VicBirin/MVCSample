<?
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/bootstrap-reboot.min.css">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>

    <!-- custom JS -->
    <script src="js/config.js"></script>
    <script src="js/formFunctions.js"></script>
    <script src="js/tableFunctions.js"></script>
    <script src="js/pagingFunctions.js"></script>
    <script src="js/apiFunctions.js"></script>
    <script src="js/common.js"></script>

    <title>Sample PHP MVC application(powered by bootstrap)</title>
</head>
<body>

<!-- navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Author @ Birin Victor</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>
<!-- /navbar -->

<!-- content table -->
<div class="container mt-5">
    <div class="alert alert-secondary alert-dismissible fade show" role="alert" id="taskInfo">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
        <strong>Info tip!</strong> Double click row to edit task. You must be logged in to edit.
    </div>
    <div class="alert alert-success alert-dismissible fade show" role="alert" hidden id="taskAlert">
        <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
        <strong>Success!</strong> New task has been added successfully.
    </div>
    <div class="alert alert-success alert-dismissible fade show" role="alert" hidden id="taskUpdateAlert">
        <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
        <strong>Success!</strong> Task has been updated successfully.
    </div>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" hidden id="loginAlert">
        <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
        <strong>Error!</strong> Incorrect credentials.
    </div>
    <div class="row">
        <div class="col-sm">
            <h1>Task list</h1>
        </div>
        <div class="text-center">
            <a href="" class="btn btn-outline-danger btn-rounded mb-4" data-toggle="modal" data-target="#newTaskModal"
               onclick="resetTaskForm()">Add new task</a>
        </div>
        <div class="text-center mx-2">
            <a href="" class="btn btn-primary btn-rounded mb-4" data-toggle="modal" data-target="#loginModal"
               onclick="prepareLoginForm()" id="loginLabel">Sign in</a>
        </div>
    </div>
    <table class="table table-hover" id="taskList">
        <thead>
        <tr>
            <th scope="col" id="id" sort="" style="width: 5%"><a href="#">#</a></th>
            <th scope="col" id="userName" sort="" style="width: 10%"><a href="#">User</a></th>
            <th scope="col" id="email" sort="" style="width: 20%"><a href="#">Email</a></th>
            <th scope="col" id="body" sort="" style="width: 45%">Task</th>
            <th scope="col" id="edited" sort="" style="width:  10%"></th>
            <th scope="col" id="completed" sort="" style="width:  10%"><a href="#">Status</a></th>
        </tr>
        </thead>
        <tbody id="taskListBody">

        </tbody>
    </table>

    <!-- pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination pagination-circle pg-blue justify-content-end" id="paginationElm">
            <li class="page-item disabled" id="firstPage"><a class="page-link">First</a></li>
            <li class="page-item disabled" id="prevPage">
                <a class="page-link" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li class="page-item" id="nextPage" disabled="">
                <a class="page-link" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
            <li class="page-item" id="lastPage" disabled=""><a class="page-link">Last</a></li>
        </ul>
    </nav>
    <!-- pagination -->

</div>
<!-- /content table -->

<form onsubmit="createTask()" id="newTaskModalForm" method="post">
    <div class="modal fade" id="newTaskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold" id="newTaskModalTitle">New task</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">

                    <input type="hidden" id="formTaskId" value="">

                    <!-- user name-->
                    <div class="form-group">
                        <i class="fas fa-envelope prefix grey-text"></i>
                        <label data-error="wrong" data-success="right" for="defaultForm-email">User name</label>
                        <input type="text" id="formUsername" class="form-control validate" required>
                    </div>

                    <div class="form-group">
                        <i class="fas fa-envelope prefix grey-text"></i>
                        <label data-error="wrong" data-success="right" for="defaultForm-email">User email</label>
                        <input type="email" id="formEmail" class="form-control validate" placeholder="name@example.com"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Task text</label>
                        <textarea class="form-control" id="formTaskBody" rows="3" required></textarea>
                    </div>

                    <div class="form-group" hidden="hidden" id="formCompletedArea">
                        <label class="form-check-label"><input type="checkbox" id="formCompleted"> Completed</label>
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-outline-danger" id="formSubmit">Create task</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- login form -->
<form id="loginForm" method="post" onsubmit="performLogin()">
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold" id="newTaskModalTitle">Sign in</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        <div class="form-group row" id="loginUserRow">
                            <label for="loginEmail" class="col-sm-2 col-form-label">User</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="loginUser" id="loginUser"
                                       placeholder="User name" required>
                            </div>
                        </div>
                        <div class="form-group row" id="loginPasswordRow">
                            <label for="loginPassword" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="loginPassword" id="loginPassword"
                                       placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-group row" id="loginSigninRow">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" id="loginSubmit" class="btn btn-primary">Sign in</button>
                            </div>
                        </div>
                        <div class="form-group row" id="loginSignoutRow" hidden>
                            <div class="col-sm-10 offset-sm-4">
                                <button type="submit" id="loginSignout" class="btn btn-primary">Sign out</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

</body>
</html>