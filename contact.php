<?php
// CONFIGURE THESE:
$to = "tomal@malwareremoval.us"; // replace with your email
$subject = "New Contact Message from Portfolio Site";
$recaptcha_secret = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe"; // replace with your secret key

// 1. Verify reCAPTCHA
if (isset($_POST['g-recaptcha-response'])) {
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $response = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response"
    );
    $response_keys = json_decode($response, true);

    if (!$response_keys["success"]) {
        die("reCAPTCHA verification failed. Please try again.");
    }
} else {
    die("reCAPTCHA not submitted.");
}

// 2. Sanitize and validate form fields
$name = htmlspecialchars(trim($_POST['name']));
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$message = htmlspecialchars(trim($_POST['message']));

if (!$name || !$email || !$message) {
    die("Invalid input. Please fill out all required fields correctly.");
}

// 3. Handle file upload (optional)
$attachment_path = '';
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $filename = basename($_FILES['attachment']['name']);
    $target_file = $upload_dir . uniqid() . "_" . $filename;
    
    $file_type = mime_content_type($_FILES['attachment']['tmp_name']);
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf', 'text/plain'];

    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
            $attachment_path = $target_file;
        } else {
            die("Failed to upload attachment.");
        }
    } else {
        die("Invalid file type.");
    }
}

// 4. Prepare and send email
$headers = "From: $name <$email>\r\n";
$body = "Name: $name\nEmail: $email\nMessage:\n$message\n";

if ($attachment_path) {
    // Send with PHPMailer (recommended) if you want file attachments
    // Otherwise just mention the file path
    $body .= "\nAttachment: " . $attachment_path;
}

if (mail($to, $subject, $body, $headers)) {
    echo "Message sent successfully & if you have any issues contac direct fiverr, me all deal fiverr rules :https://www.fiverr.com/websecurity2025  !";
} else {
    echo "Failed to send email.";
}
?>
