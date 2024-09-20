<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>
    <?php if (isset($error)) echo '<p style="color:red;">' . $error . '</p>'; ?>
    <?php echo form_open('user/reset_password'); ?>
        <p>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </p>
        <p>
            <input type="submit" value="Reset Password">
        </p>
    <?php echo form_close(); ?>
    <a href="<?php echo site_url('user/login'); ?>">Login</a>
</body>
</html>
