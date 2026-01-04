<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Auth Login</title>
</head>

<body>
    <h1>Login</h1>
    <?php if (!empty($authentication_error)): ?>
        <div style="color: red;">
            <?php echo htmlspecialchars($authentication_error); ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="/form-auth/check-login">
        <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
        <?php if (!empty($redirect_to)): ?>
            <input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($redirect_to); ?>">
        <?php endif; ?>
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</body>

</html>