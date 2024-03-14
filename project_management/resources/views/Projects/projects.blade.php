<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Project/Task Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="<INTEGRITY>" crossorigin="anonymous">
    {{-- <style>
        .list-group-item {
            border: none;
        }
    </style> --}}
</head>
<body>
    <div class="container">

        <div class="row">
            <div class="col-md-8 offset-2">
                <h1 class="mt-5 mb-5">Project/Task Management System</h1>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-success mb-3 text-right" data-bs-toggle="modal" data-bs-target="#addProjectModal">Add New</button>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                </div>
                <!-- Table -->
                <table class="table table-bordered border-primary">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col">Project Code</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="projectTableBody">
                        <!-- Table rows will be populated dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Project Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectModalLabel">Add New Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addProjectForm">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row task">
                                <div class="form-group mb-3">
                                    <label for="project_code">Project Code:</label>
                                    <input type="text" class="form-control" name="projectCode" id="projectCode" value="{{ $rand }}">
                                    <div id="projectCode_error" class="invalid-feedback"></div>
                                    {{-- @error('projectCode')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>

                                <div class="form-group mb-3">
                                    <label for="project_name">Project Name:</label>
                                    <input type="text" class="form-control" name="projectName" id="projectName" value="{{ old('projectName') }}">
                                    <div id="projectName_error" class="invalid-feedback"></div>
                                    {{-- @error('projectName')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>

                                <div class="form-group">
                                    <label>Tasks:</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="container-fluid" id="tasks">
                            <div class="row task">
                            <div class="col-md-5">
                                <input type="text" class="form-control mb-2" name="tasks[0][task_name]" placeholder="Task Name">
                            </div>
                            <div class="col-md-5 ml-auto">
                                <input type="text" class="form-control mb-2" name="tasks[0][task_hours]" placeholder="Task Hours">
                            </div>
                            <div class="col-md-2 ml-auto">
                                <button type="button" class="btn btn-primary mb-3" id="addTask">+</button>
                            </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Project Modal -->
    <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProjectForm">
                    <div class="modal-body">
                        <input type="hidden" name="project_id" id="edit_project_id">
                        <div class="mb-3">
                            <label for="edit_project_code" class="form-label">Project Code</label>
                            <input type="text" class="form-control" id="edit_project_code" name="project_code">
                            <div id="project_code_error" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_project_name" class="form-label">Project Name</label>
                            <input type="text" class="form-control" id="edit_project_name" name="project_name">
                            <div id="project_name_error" class="invalid-feedback"></div>
                        </div>
                        <hr>
                        <h6>Tasks</h6>
                        <div class="container-fluid" id="etasks">
                            <!-- Tasks will be populated here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('addTask').addEventListener('click', function() {
            var taskContainer = document.getElementById('tasks');
            var index = taskContainer.querySelectorAll('.task').length;
            var newTask = document.createElement('div');
            newTask.classList.add('row', 'task');
            newTask.innerHTML = `

                <div class="col-md-5">
                            <input type="text" class="form-control mb-2" name="tasks[${index}][task_name]" placeholder="Task Name">
                          </div>
                          <div class="col-md-5 ml-auto">
                            <input type="text" class="form-control mb-2" name="tasks[${index}][task_hours]" placeholder="Task Hours">
                          </div>
                          <div class="col-md-2 ml-auto">
                            <button type="button" class="btn btn-danger btn-remove-task">-</button>
                          </div>

                `;
            taskContainer.appendChild(newTask);
        });

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('btn-remove-task')) {
                event.target.closest('.task').remove();
            }
        });

        $(document).ready(function() {
            $('#addProjectForm').submit(function(e) {
                e.preventDefault(); // Prevent form submission

                // Collect form data
                var projectCode = $('#projectCode').val();
                var projectName = $('#projectName').val();


                var formData = $(this).serialize(); // Serialize form data
                var token = "{{ csrf_token() }}"; // Get CSRF token value

                // Append CSRF token to form data
                formData += '&_token=' + token;
                // AJAX request
                $.ajax({
                    type: 'POST',
                    url: '/projects', // Assuming your route for ProjectController@create is '/projects'
                    data: formData,
                    success: function(response) {
                        // Handle success response
                        if(response.code == 200){
                            $('#addProjectModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                timer: 2000, // Set the timeout for 2 seconds (2000 milliseconds)
                                showConfirmButton: false, // Hide the "OK" button
                                didClose: function() {
                                    // Reload the page after 2 seconds
                                    setTimeout(function() {
                                        location.reload();
                                    }, 0);
                                }
                            });
                            // location.reload();
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                timer: 2000, // Set the timeout for 2 seconds (2000 milliseconds)
                                showConfirmButton: false // Hide the "OK" button
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        // Optionally, display an error message to the user
                        var errors = xhr.responseJSON.errors;
                        console.error(errors);
                        $.each(errors, function(key, value) {
                            var keyArr = key.split(".");
                            var newKey = keyArr[0]+"["+keyArr[1]+"]["+keyArr[2]+"]";
                            console.log(newKey);
                            $('input[name="' + newKey + '"]').addClass('is-invalid'); // Add Bootstrap class for displaying validation error
                            $('input[name="' + newKey + '"]').next('.invalid-feedback').html(value); // Display the error message
                            $('input[name="' + key + '"]').addClass('is-invalid');
                            $('#'+ key + '_error').html(value);
                            $('input[name="' + newKey + '"]').on('input', function() {
                                $(this).removeClass('is-invalid');
                            });

                            $('#'+key).on('input', function() {
                                $(this).removeClass('is-invalid');
                            });
                        });
                    }
                });
            });

            // Remove validation styling when the user starts typing in the projectName field
            // $('#projectName').on('input', function() {
            //     $(this).removeClass('is-invalid');
            // });
        });
    </script>

<script>
    $(document).ready(function() {
        // Initial population of table rows
        populateTableRows();

        // Function to populate table rows
        function populateTableRows() {
            var projects = {!! json_encode($projects) !!}; // Assuming $projects is passed from the server side
            var tableBody = $('#projectTableBody');
            tableBody.empty(); // Clear existing rows

            projects.forEach(function(project) {
                var rowHtml = `
                    <tr>
                        <td>${project.project_code}</td>
                        <td>
                            <strong>${project.project_name}</strong><br>
                            <ul class="list-group list-group-flush">
                                ${project.tasks.map(task => `<li class="list-group-item">${task.task_name} - ${task.task_hours} Hours</li>`).join('')}
                            </ul>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editModal${project.id}"  onclick="updateProject(${project.id})">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal${project.id}" onclick="deleteProject(${project.id})">Delete</button>
                        </td>
                    </tr>`;
                tableBody.append(rowHtml);
            });
        }

        // Search functionality
        $('#searchInput').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            var rows = $('#projectTableBody').find('tr');

            rows.each(function() {
                var projectCode = $(this).find('td:first-child').text().toLowerCase();
                var projectName = $(this).find('td:nth-child(2)').text().toLowerCase();
                if (projectCode.includes(searchText) || projectName.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>

<script>
    function deleteProject(id){
        var projectId = id;
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get CSRF token
                    var token = $('meta[name="csrf-token"]').attr('content');

                    // User confirmed, send AJAX request to delete project
                    $.ajax({
                        type: 'DELETE',
                        url: '/projects/' + projectId,
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        success: function(response){
                            if(response.code == 200){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    timer: 2000, // Set the timeout for 2 seconds (2000 milliseconds)
                                    showConfirmButton: false, // Hide the "OK" button
                                    didClose: function() {
                                        // Reload the page after 2 seconds
                                        setTimeout(function() {
                                            location.reload();
                                        }, 0);
                                    }
                                });
                            }else{
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                    timer: 2000, // Set the timeout for 2 seconds (2000 milliseconds)
                                    showConfirmButton: false // Hide the "OK" button
                                });
                            }

                            // Optionally, you can update the table UI after deletion
                        },
                        error: function(xhr, status, error){
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
    }

</script>

<script>
    function updateProject(id){
        var projectId = id;
        // Fetch project details using AJAX and populate the edit modal
        $.ajax({
            type: 'GET',
            url: '/projects/' + projectId, // Update the URL to fetch project details
            success: function(response){
                $('#edit_project_id').val(response.id);
                $('#edit_project_code').val(response.project_code);
                $('#edit_project_name').val(response.project_name);

                // Populate tasks in modal
                var tasksHtml = '';
                tasksHtml += '<button type="button" class="btn btn-primary mb-3" id="addeTask">+</button>';

                var index = 0;
                response.tasks.forEach(function(task){
                    tasksHtml += '<div class="row etask">';
                    tasksHtml += '<div class="col-md-5">';
                    tasksHtml += '<input type="text" class="form-control mb-3" name="tasks[' + index + '][task_name]" value="' + task.task_name + '" placeholder="Task Name">';
                    tasksHtml += '</div>';
                    tasksHtml += '<div class="col-md-5">';
                    tasksHtml += '<input type="text" class="form-control mb-3" name="tasks[' + index + '][task_hours]" value="' + task.task_hours + '" placeholder="Task Hours"></div>';
                    tasksHtml += '<div class="col-md-2 ml-auto"><button type="button" class="btn btn-danger btn-remove_etask" onclick="deleteTask('+task.id+')">-</button>'
                    tasksHtml += '<input type="hidden" name="tasks[' + index + '][id]" value="'+task.id+'" />';
                    tasksHtml += '</div>';
                    tasksHtml += '</div>';
                    index++;
                });

                $('#etasks').html(tasksHtml);

                // Show the edit modal
                $('#editProjectModal').modal('show');

                document.getElementById('addeTask').addEventListener('click', function() {
                    var taskContainer = document.getElementById('etasks');
                    var index = taskContainer.querySelectorAll('.etask').length;
                    var newTask = document.createElement('div');
                    newTask.classList.add('row', 'etask');
                    newTask.innerHTML = `

                        <div class="col-md-5">
                        <input type="text" class="form-control mb-2" name="tasks[${index}][task_name]" placeholder="Task Name">
                        </div>
                        <div class="col-md-5 ml-auto">
                        <input type="text" class="form-control mb-2" name="tasks[${index}][task_hours]" placeholder="Task Hours">
                        </div>
                        <div class="col-md-2 ml-auto">
                        <button type="button" class="btn btn-danger btn-remove-etask">-</button>
                        <input type="hidden" name="tasks[${index}][id]" value="" />
                        </div>
                        `;
                    taskContainer.appendChild(newTask);
                });

                document.addEventListener('click', function(event) {
                    if (event.target.classList.contains('btn-remove-etask')) {
                        event.target.closest('.etask').remove();
                    }
                });
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
            }
        });
    }


    $(document).ready(function(){
        $('#editProjectForm').submit(function(e){
            e.preventDefault();

            var projectId = $('#edit_project_id').val();
            var formData = $(this).serialize();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'PUT',
                url: '/projects/' + projectId,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response){
                    if(response.code == 200){
                        $('#editProjectModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 2000, // Set the timeout for 2 seconds (2000 milliseconds)
                            showConfirmButton: false, // Hide the "OK" button
                            didClose: function() {
                                // Reload the page after 2 seconds
                                setTimeout(function() {
                                    location.reload();
                                }, 0);
                            }
                        });
                        // location.reload();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            timer: 2000, // Set the timeout for 2 seconds (2000 milliseconds)
                            showConfirmButton: false // Hide the "OK" button
                        });
                    }


                    // Optionally, you can update the UI or close the modal
                    console.log(response);
                },
                error: function(xhr, status, error){
                    var errors = xhr.responseJSON.errors;
                    console.error(errors);
                    $.each(errors, function(key, value) {
                        var keyArr = key.split(".");
                        var newKey = keyArr[0]+"["+keyArr[1]+"]["+keyArr[2]+"]";
                        console.log(key);
                        $('input[name="' + newKey + '"]').addClass('is-invalid'); // Add Bootstrap class for displaying validation error
                        $('input[name="' + newKey + '"]').next('.invalid-feedback').html(value); // Display the error message
                        $('input[name="' + key + '"]').addClass('is-invalid');
                            $('#'+ key + '_error').html(value);
                        $('input[name="' + newKey + '"]').on('input', function() {
                            $(this).removeClass('is-invalid');
                        });
                    });
                    // Swal.fire({
                    //     icon: 'error',
                    //     title: 'Error',
                    //     text: xhr.responseText,
                    //     timer: 10000, // Set the timeout for 2 seconds (2000 milliseconds)
                    //     showConfirmButton: false // Hide the "OK" button
                    // });
                }
            });
        });
    });


</script>


</body>
</html>
