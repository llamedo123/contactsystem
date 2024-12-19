<?php 
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['contact_name'], $_POST['email_add'], $_POST['phone_number'])) {
        $contactId = $_POST['tbl_contact_id'];
        $contactName = $_POST['contact_name'];
        $emailAdd = $_POST['email_add'];
        $phoneNumber = $_POST['phone_number'];

        try {
            $stmt = $conn->prepare("UPDATE tbl_contact SET name = :name, email = :email, phone = :phone WHERE tbl_contact_id = :tbl_contact_id");

            $stmt->bindParam(":tbl_contact_id", $contactId, PDO::PARAM_INT);
            $stmt->bindParam(":name", $contactName, PDO::PARAM_STR);
            $stmt->bindParam(":email", $emailAdd, PDO::PARAM_STR);
            $stmt->bindParam(":phone", $phoneNumber, PDO::PARAM_STR);

            $stmt->execute();
            echo "
                <script>
                    alert('Contact Update Successfully');
                    window.location.href = 'http://localhost/contactsystem/';
                </script>
            ";

        } catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    } else {
        echo "
            <script>
                alert('Please fill in all fields!');
                window.location.href = 'http://localhost/contactsystem/';
            </script>
        ";
    }
}
?>
