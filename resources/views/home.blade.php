<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menu</title>
    <link rel="stylesheet" href="{{ asset ('bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset ('bootstrap/icons/bootstrap-icons.min.css') }}">
    <style>
        .task-link:hover {
            background-color: #e2e3e5;
        }
    </style>
</head>
<body>
    <div class="container-xl py-5">
        <div class="row">
            <div class="col-3 bg-light p-3 rounded-4">
                <h4 class="pb-2 fw-bold d-flex justify-content-between">Menu<i class="bi bi-list"></i></h4>
                <input class="form-control mr-sm-2 bg-light" type="text" placeholder="Search" aria-label="Search">
                <div>
                    <div class="fw-bolder pt-4 small text-muted">TASKS</div>
                    <div class="task-link py-2 rounded-3"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-chevron-double-right mx-2"></i>Calendar</a></div>
                    <div class="task-link py-2 rounded-3"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-list-ul mx-2"></i>Calendar</a></div>
                    <div class="task-link py-2 rounded-3"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-calendar-week-fill mx-2"></i>Calendar</a></div>
                    <div class="task-link py-2 rounded-3"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi bi-sticky-fill mx-2"></i>Calendar</a></div>
                </div>
                <hr>
                <div>
                    <div class="fw-bolder small text-muted">LISTS</div>
                    <div class="task-link py-2 rounded-3 d-flex justify-content-between"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-square-fill text-danger mx-2"></i>Personal</a><i class="bi bi-3-square text-secondary"></i></div>
                    <div class="task-link py-2 rounded-3 d-flex justify-content-between"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-square-fill text-info mx-2"></i>Personal</a><i class="bi bi-6-square text-secondary"></i></div>
                    <div class="task-link py-2 rounded-3 d-flex justify-content-between"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-square-fill text-warning mx-2"></i>Personal</a><i class="bi bi-3-square text-secondary"></i></div>
                    <a href="#" style="text-decoration: none" class="text-muted ps-2"><span class="h4">+</span> Add New List</a>
                </div>
                <hr>
                <div class="pb-5 mb-5">
                    <div class="fw-bolder small text-muted mb-2">TAGS</div>
                    <div class="d-flex gap-2">
                        <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold">Tag 1</div>
                        <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold">Tag 2</div>
                        <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold">+ Add Tag</div>
                    </div>
                </div>
                <a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-sliders2"></i>Settings</a>
            </div>
        </div>
    </div>
</body>
</html>
