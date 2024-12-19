<?php
include('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['contact_name'], $_POST['email_add'], $_POST['phone_number'], $_POST['country_code'])) {
        $contactName = $_POST['contact_name'];
        $emailAdd = $_POST['email_add'];
        $phoneNumber = $_POST['phone_number'];
        $countryCode = $_POST['country_code'];

        // Format the full phone number
        $formattedPhoneNumber = $countryCode . ' ' . preg_replace('/(\d{4})(\d{3})(\d{3})/', '$1 $2 $3', $phoneNumber);

        try {
            $stmt = $conn->prepare("INSERT INTO tbl_contact (name, email, phone) VALUES (:name, :email, :phone)");

            $stmt->bindParam(":name", $contactName, PDO::PARAM_STR);
            $stmt->bindParam(":email", $emailAdd, PDO::PARAM_STR);
            $stmt->bindParam(":phone", $formattedPhoneNumber, PDO::PARAM_STR);

            $stmt->execute();

            echo "
                <script>
                    alert('Contact Added Successfully');
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
