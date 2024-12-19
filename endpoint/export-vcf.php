<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get contact details from POST request
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Generate VCF content
    $vcfContent = "BEGIN:VCARD\n";
    $vcfContent .= "VERSION:3.0\n";
    $vcfContent .= "FN:" . $name . "\n";
    $vcfContent .= "TEL;TYPE=HOME,VOICE:" . $phone . "\n";
    $vcfContent .= "EMAIL:" . $email . "\n";
    $vcfContent .= "END:VCARD\n";

    // Define the file name
    $fileName = 'contact_' . $id . '.vcf';

    // Set the headers to force download
    header('Content-Type: text/vcard');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Content-Length: ' . strlen($vcfContent));

    // Output the VCF content
    echo $vcfContent;

    // Stop further execution (optional, ensures the script doesn't continue)
    exit;
}
?>
