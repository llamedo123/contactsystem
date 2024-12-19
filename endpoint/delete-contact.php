<?php
include ('../conn/conn.php');

if (isset($_GET['contact'])) {
    $contact = $_GET['contact'];

    try {

        $query = "DELETE FROM tbl_contact WHERE tbl_contact_id = '$contact'";

        $stmt = $conn->prepare($query);

        $query_execute = $stmt->execute();

        if ($query_execute) {
            echo "
                <script>
                    alert('Contact Deleted Successfully');
                    window.location.href = 'http://localhost/contactsystem/';
                </script>
            ";
        } else {
            header("Location: http://localhost/contactsystem/");
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>