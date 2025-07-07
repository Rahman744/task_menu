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
                <input class="form-control bg-light" type="text" placeholder="Search" aria-label="Search">
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
                    <div class="task-link py-2 rounded-3 d-flex justify-content-between"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-square-fill text-danger mx-2"></i>Personal</a><i class="bi bi-3-square text-secondary pe-2"></i></div>
                    <div class="task-link py-2 rounded-3 d-flex justify-content-between"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-square-fill text-info mx-2"></i>Personal</a><i class="bi bi-6-square text-secondary pe-2"></i></div>
                    <div class="task-link py-2 rounded-3 d-flex justify-content-between"><a href="#" style="text-decoration: none" class="text-secondary"><i class="bi bi-square-fill text-warning mx-2"></i>Personal</a><i class="bi bi-3-square text-secondary pe-2"></i></div>
                    <a href="#" style="text-decoration: none" class="text-muted ps-2"><span class="h4">+</span> Add New List</a>
                </div>
                <hr>
                <div class="pb-5 mb-5">
                    <div class="fw-bolder small text-muted mb-2">TAGS</div>
                    <div class="d-flex gap-2">
                        <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small"><a href="#" style="text-decoration: none" class="text-dark">Tag 1</a></div>
                        <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small"><a href="#" style="text-decoration: none" class="text-dark">Tag 2</a></div>
                        <div class="px-3 py-1 bg-body-secondary rounded-3 fw-semibold small"><a href="#" style="text-decoration: none" class="text-dark">+ Add Tag</a></div>
                    </div>
                </div>
                <div class="task-link py-2 rounded-3 h6"><a href="#" style="text-decoration: none; font-size: 17px" class="text-secondary"><i class="bi bi-sliders2 mx-2"></i>Settings</a></div>
                <div class="task-link py-2 rounded-3 h6"><a href="#" style="text-decoration: none; font-size: 17px" class="text-secondary"><i class="bi bi-box-arrow-right mx-2"></i>Sign out</a></div>
            </div>
            <div class="col-6 ps-4 pe-4">
                <div class="d-flex align-items-center mb-3">
                    <h1 class="me-5 fw-bold">Today</h1>
                    <span class="h3 border rounded px-2 py-1 fw-semibold">5</span>
                </div>
                <a href="#" style="text-decoration: none" class="text-secondary border rounded-2 form-control ps-3"><span class="h4 mx-2">+</span> Add New List</a>
                <div class="d-flex justify-content-between pt-3 ">
                    <div class="form-check ps-5 fw-semibold">
                        <input class="form-check-input" type="checkbox" id="">
                        <label class="ps-2">Research content ideas</label>
                    </div>
                    <i class="bi bi-chevron-right h5 pe-4"></i>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <div class="form-check ps-5 fw-semibold">
                        <input class="form-check-input" type="checkbox" id="">
                        <label class="ps-2">Create a database of quest authors</label>
                    </div>
                    <i class="bi bi-chevron-right h5 pe-4"></i>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <div class="form-check ps-5 fw-semibold">
                        <input class="form-check-input" type="checkbox" id="">
                        <label class="ps-2">Renew driver's license</label>
                    </div>
                    <i class="bi bi-chevron-right h5 pe-4"></i>
                </div>
                <div class="d-flex gap-3 ps-5">
                    <a href="#" style="text-decoration: none" class="text-black"><i class="bi bi-calendar-x-fill mx-2"></i><input type="date" class="small border"></a>|
                    <a href="#" style="text-decoration: none" class="text-black "><i class="bi bi-1-square mx-2"></i>Subtasks</a>|
                    <a href="#" style="text-decoration: none" class="text-black"><i class="bi bi-square-fill text-danger mx-2"></i>Personal</a>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <div class="form-check ps-5 fw-semibold">
                        <input class="form-check-input" type="checkbox" id="">
                        <label class="ps-2">Consult accountant</label>
                    </div>
                    <i class="bi bi-chevron-right h5 pe-4"></i>
                </div>
                <div class="d-flex gap-3 ps-5">
                    <a href="#" style="text-decoration: none" class="text-black"><i class="bi bi-square-fill text-warning mx-2"></i>Personal</a>|
                    <a href="#" style="text-decoration: none" class="text-black "><i class="bi bi-3-square mx-2"></i>Subtasks</a>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <div class="form-check ps-5 fw-semibold">
                        <input class="form-check-input" type="checkbox" id="">
                        <label class="ps-2">Print business card</label>
                    </div>
                    <i class="bi bi-chevron-right h5 pe-4"></i>
                </div>
            </div>
            <div class="col-3 bg-light p-3 rounded-4">
                <h4 class="pb-2 fw-bold">Task:</h4>
                <input type="text" placeholder="Renew driver's license" class="form-control bg-light">
                <br>
                <textarea name="" id="" cols="10" rows="4" class="form-control bg-light" placeholder="Description"></textarea>
                <div class="d-flex align-items-between gap-5 pt-4">
                    <div class="h6 pt-2 fw-semibold">List</div>
                    <form action="" class="small ps-4 ms-3">
                        <select name="" id="" class="py-1 px-2 bg-light border rounded">
                            <option value="personal">Personal</option>
                            <option value="personal">Developer</option>
                            <option value="personal">Buhgalter</option>
                        </select>
                    </form>
                </div>
                <div class="d-flex align-items-between gap-5 py-3">
                    <div class="h6 pt-2 fw-semibold">Due date</div>
                    <input type="date" class="bg-light border rounded small ps-2">
                </div>
                <div class="d-flex align-items-between pt-1 gap-5">
                    <div class="h6 pt-1 fw-semibold">Tags</div>
                    <div class="ps-4 ms-2">
                        <a href="" style="text-decoration: none" class="text-black p-1 bg-body-secondary border rounded-3 small px-2">Tag 1</a>
                        <a href="" style="text-decoration: none" class="text-black p-1 bg-body-secondary border rounded-3 small px-2">+ Add Tag</a>
                    </div>
                </div>
                <h4 class="pt-4 fw-bold">Subtasks:</h4>
                <div class="ps-2 fw-semibold ">
                    <a href="" style="text-decoration: none" class="text-muted"><span class="h5 text-muted mx-3">+</span> Add New Subtask</a>
                </div>
                <hr>
                <div class="form-check ps-5 fw-semibold text-muted pb-5 mb-5">
                    <input class="form-check-input" type="checkbox" id= "">
                    <label class="ps-2">Subtask</label>
                </div>
                <br>
                <br>
                <div class="pt-5">
                    <button class="btn btn-light border fw-semibold px-4 mx-2">Delete Task</button>
                    <button class="btn btn-warning border fw-semibold px-3">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
