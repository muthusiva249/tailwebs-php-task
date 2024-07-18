<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../includes/db_connect.php';

$query = "SELECT * FROM students";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Portal | Home</title>
    <!-- Include jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Toastr CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- css  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/home.css">

</head>
<body>
    <div class="header">
        <div class="header-container">
            <a href="#" class="logo">tailwebs.</a>
            <div class="header-right">
                <a href="home.php">Home</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>Student List</h2>

        <table>
            <tr  style="border-bottom: 2px inset">
                <th class="table-heading">Name</th>
                <th class="table-heading">Subject</th>
                <th class="table-heading">Marks</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr  style="border-bottom: 2px inset" id="student-<?= $row['id'] ?>">
            <td><span class="view"><?= htmlspecialchars($row['name']) ?></span><input type="text" class="edit" id="et_name_<?= $row['id'] ?>" value="<?= htmlspecialchars($row['name']) ?>" style="display:none;"></td>
            <td><span class="view"><?= htmlspecialchars($row['subject']) ?></span><input type="text" class="edit" id="et_subject_<?= $row['id'] ?>" value="<?= htmlspecialchars($row['subject']) ?>" style="display:none;"></td>
            <td><span class="view"><?= htmlspecialchars($row['marks']) ?></span><input type="number" class="edit" id="et_marks_<?= $row['id'] ?>" value="<?= htmlspecialchars($row['marks']) ?>" style="display:none;"></td>
            <td>
                <button class="view" onclick="editStudent(<?= $row['id'] ?>)">Edit</button>
                <!-- <button class="edit" id="<?= $row['id'] ?>"; name="<?=$row['name']?>"; subject="<?=$row['subject']?>"; marks="<?=$row['marks']?>";  style="display:none;">Save</button> -->
                <button class="edit" id="save_edit_<?=$row['id']?>" data-id="<?= $row['id'] ?>" data-name="<?= $row['name'] ?>" data-subject="<?= $row['subject'] ?>" data-marks="<?= $row['marks'] ?>" style="display:none;">Save</button>

                <button onclick="deleteStudent(<?= $row['id'] ?>)">Delete</button>
            </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <!-- ##Add student -->
    </div>
    
    <div style="width: 80%; margin-top: 20px;">
        <button onclick="showAddStudentModal()">Add Student</button>
    </div>

    <!-- ##Add student modal -->
    <div id="addStudentModal" class="modal" style="display:none">
        <div class="modal-content">
            <h3 style="text-align: center;">Add New Student</h3>
            <form>
                <label for="name">Name:</label>
                <div class="inputWithIcon">
                    <input type="text" placeholder="Name" id="name" name="name" required>
                    <i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i>
                </div>
                <label for="subject">Subject:</label>
                <div class="inputWithIcon">
                    <input type="text" placeholder="Subject" id="subject" name="subject" required>
                    <i class="fa fa-book fa-lg fa-fw" aria-hidden="true"></i>
                </div>
                <label for="marks">Marks:</label>
                <div class="inputWithIcon">
                    <input type="number" placeholder="Marks" id="marks" name="marks" required>
                    <i class="fa fa-bookmark fa-lg fa-fw" aria-hidden="true"></i>
                </div>
                <div style="text-align: center;">
                    <input type="button" class="add_student btn" value="Add" >
                    <button onclick="hideAddStudentModal()" class="btn cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function showAddStudentModal() {
        document.getElementById('addStudentModal').style.display = 'block';
    }

    function hideAddStudentModal() {
        document.getElementById('addStudentModal').style.display = 'none';
    }

    function editStudent(id) {
        // Implementing edit functionality
        const row = document.getElementById(`student-${id}`);
        const viewElements = row.querySelectorAll('.view');
        const editElements = row.querySelectorAll('.edit');
        viewElements.forEach(element => element.style.display = 'none');
        editElements.forEach(element => element.style.display = 'inline');

        // console.log(id)
        $(document).ready(function() {
            $("#save_edit_"+ id).click(function() {
                var button = $(this);
                var id = button.data('id');
                var name    = document.getElementById('et_name_'+ id).value;
                var subject = document.getElementById('et_subject_'+ id).value;
                var marks   = document.getElementById('et_marks_'+ id).value;
    
                console.log(id);
                console.log(name);
                console.log(subject);
                console.log(marks);
    
                if (id != '') {
                    $.ajax({
                        url: 'update_student.php',
                        type: 'POST',
                        data: {
                            id: id,
                            name: name,
                            subject: subject,
                            marks: marks,
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(name + " record updated.");
                                setTimeout(function() {
                                    location.reload();
                                }, 3000);
                            } else {
                                // console.error("Error: " + response.error);
                                toastr.error('Error updating record');
                            }
                            // location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX error: ", error);
                            // toastr.error('Error updating record');
                        }
                    });
                }
            });
        });
    }

    // ##delete record
    function deleteStudent(id) {
    if (confirm("Are you sure you want to delete this student?")) {
        $.ajax({
            url: 'delete_student.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                if (response.success) {
                    toastr.success('Student record deleted.');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                } else {
                    // console.error("Error: " + response.error);
                    toastr.error('Error deleting record');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error: ", error);
                toastr.error('Error deleting record');
            }
        });
    }
    }

    // ## toster alert
    window.onload = function()
    {
        if (localStorage.getItem('loginSuccess') === 'true') {
            toastr.success('Login successful');
            localStorage.removeItem('loginSuccess');
        }
    };

    // ## Add student action
    $(".add_student").click(function(){
    var name    = document.getElementById('name').value;
    var subject = document.getElementById('subject').value;
    var marks   = document.getElementById('marks').value;
        // console.log(name);
        // console.log(subject);
        // console.log(marks);
        if(name == "" || subject== "" || marks == "" )
        {
            toastr.error('Please enter the deatils in all fields.');
        }
        var url = "add_student.php";
        // console.log(url);
        if(name != "" && subject != "" && marks != "" )
        {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    name: name,
                    subject: subject,
                    marks: marks,
                },
                success: function(data) {

                    if (data === "New student record inserted.") {
                        toastr.success(data);
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } 
                    else if(data === 'Updated '+name+' record.'){
                        toastr.success(data);
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    }
                    else {
                        console.log(data);
                        toastr.error('error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("adding details failed:", error);
                    toastr.error('Adding student details failed');
                }

            });
        }
    });

    </script>
</body>
</html>
