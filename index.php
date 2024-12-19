<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Manager with Export to VCF</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Data Table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

    <!-- Style CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 40px 30px 40px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: rgba(0, 0, 0, 0.3) 0 5px 15px;
            width: 60%;
            height: 700px;
            position: absolute;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            border-bottom: 1px solid;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header-container > h3 {
            font-weight: 500;
        }

        .tasks-container {
            position: relative;
            width: 100%;
        }

        .action-button {
            display: flex;
            justify-content: center;
        }
        
        .action-button > button {
            width: 25px;
            height: 25px;
            font-size: 17px;
            display: flex !important;
            justify-content: center;
            align-items: center;
            margin: 0px 2px;
        }

        .dataTables_wrapper .dataTables_info {
            position: absolute !important;
            bottom: 20px !important;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="container">
            <div class="header-container">
                <h3>Contact Management System</h3>
                <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addcontactModal">
                    Add Contact
                </button>
            </div>

            <div class="tasks-container">
                <table class="table table-striped table-hover table-sm" id="contactTable">
                    <thead>
                        <tr>
                            <th scope="col">Contact ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Email</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            include('./conn/conn.php');

                            $stmt = $conn->prepare("SELECT * FROM tbl_contact");
                            $stmt->execute();

                            $result = $stmt->fetchAll();

                            foreach ($result as $row) {
                                $contactId = $row['tbl_contact_id'];
                                $contactName = $row['name'];
                                $phoneNumber = $row['phone'];
                                $emailAdd = $row['email'];
                                ?>

                                <tr>
                                    <th><?= $contactId ?></th>
                                    <td id="contactName-<?= $contactId ?>"><?= $contactName ?></td>
                                    <td id="phoneNumber-<?= $contactId ?>"><?= $phoneNumber ?></td>
                                    <td id="emailAdd-<?= $contactId ?>"><?= $emailAdd ?></td>
                                    <td>
                                        <div class="action-button">
                                            <button class="btn btn- secondary" onclick="updateContact(<?= $contactId ?>)">&#128393;</button>
                                            <button class="btn btn-danger" onclick="deleteContact(<?= $contactId ?>)">X</button>
                                            <button class="btn btn-success" onclick="exportVcf(<?= $contactId ?>)">&#xF759</button>
                                        </div>
                                    </td>
                                </tr>

                                <?php                                                                                                                       
                            }                                                                                                                   
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->                                              
    <div class="modal fade" id="addcontactModal" tabindex="-1" aria-labelledby="addcontact" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content mt-5">
                <div class="modal-header">
                    <h5 class="modal-title" id="addcontact">Add Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
    <form action="./endpoint/add-contact.php" method="POST">
        <div class="mb-3">
            <label for="contactName" class="form-label">Contact Name:</label>
            <input type="text" class="form-control" id="contactName" name="contact_name" required>
        </div>
        <div class="mb-3">
            <label for="countryCode" class="form-label">Country Code:</label>
            <select class="form-select" id="countryCode" name="country_code" required>
                <option value="+63">+63 (Philippines)</option>
                <option value="+1">+1 (USA)</option>
                <option value="+44">+44 (UK)</option>
                <option value="+91">+91 (India)</option>
                <!-- Add other country codes as needed -->
            </select>
        </div>
        <div class="mb-3">
            <label for="phoneNumber" class="form-label">Phone Number:</label>
            <input type="text" class="form-control" id="phoneNumber" name="phone_number" required placeholder="e.g., 9491641256">
        </div>
        <div class="mb-3">
            <label for="emailAdd" class="form-label">Email Address:</label>
            <input type="email" class="form-control" id="emailAdd" name="email_add" required>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-dark">Add</button>
        </div>
    </form>
</div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updatecontactModal" tabindex="-1" aria-labelledby="updatecontact" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content mt-5">
                <div class="modal-header">  
                    <h5 class="modal-title" id="updatecontact">Update Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
    <form action="./endpoint/add-contact.php" method="POST">
        <div class="mb-3">
            <label for="contactName" class="form-label">Contact Name:</label>
            <input type="text" class="form-control" id="contactName" name="contact_name" required>
        </div>
        <div class="mb-3">
            <label for="countryCode" class="form-label">Country Code:</label>
            <select class="form-select" id="countryCode" name="country_code" required>
                <option value="+63">+63 (Philippines)</option>
                <option value="+1">+1 (USA)</option>
                <option value="+44">+44 (UK)</option>
                <option value="+91">+91 (India)</option>
                <!-- Add other country codes as needed -->
            </select>
        </div>
        <div class="mb-3">
            <label for="phoneNumber" class="form-label">Phone Number:</label>
            <input type="text" class="form-control" id="phoneNumber" name="phone_number" required placeholder="e.g., 9491641256">
        </div>
        <div class="mb-3">
            <label for="emailAdd" class="form-label">Email Address:</label>
            <input type="email" class="form-control" id="emailAdd" name="email_add" required>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-dark">Add</button>
        </div>
    </form>
</div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Data Table -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <script>
        $(document).ready( function () {
            $('#contactTable').DataTable();
        });
        
        function updateContact(id) {
            $("#updatecontactModal").modal("show");

            let updatecontactName = $("#contactName-" + id).text();
            let updateEmailAdd = $("#emailAdd-" + id).text();
            let updatePhoneNumber = $("#phoneNumber-" + id).number();

            $("#updatecontactId").val(id);
            $("#updatecontactName").val(updatecontactName);
            $("#updateEmailAdd").val(updateEmailAdd);
            $("#updatePhoneNumber").val(updatePhoneNumber);

        }

        function deleteContact(id) {
            if (confirm("Do you want to delete this contact?")) {
                window.location = "./endpoint/delete-contact.php?contact=" + id;
            }
        }

        function exportVcf(id) {
            let contactName = $("#contactName-" + id).text();
            let phoneNumber = $("#phoneNumber-" + id).text();
            let emailAdd = $("#emailAdd-" + id).text();

            // Prepare the data to be sent to the server
            let contactData = {
                id: id,
                name: contactName,
                phone: phoneNumber,
                email: emailAdd
            };

            // Create a form element to submit the data
            let form = $('<form></form>').attr({
                method: 'POST',
                action: './endpoint/export-vcf.php'
            });

            // Add the contact data to the form
            $.each(contactData, function(key, value) {
                form.append($('<input></input>').attr({
                    type: 'hidden',
                    name: key,
                    value: value
                }));
            });

            // Append the form to the body and submit it
            form.appendTo('body').submit();
        }


    </script>
</body>
</html>
