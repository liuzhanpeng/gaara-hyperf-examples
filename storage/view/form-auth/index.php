<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Auth</title>
</head>

<body>
    <h1>Form Auth Example</h1>
    <div>
        Username: <?php echo htmlspecialchars($username); ?>
    </div>
    <form id="logout-form" method="POST" action="/form-auth/logout" style="display: inline;">
        <a href="#" id="lnk-logout">Logout</a>
    </form>

    <script>
        document.getElementById('lnk-logout').addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                document.getElementById('logout-form').submit();
            }
        });
    </script>
</body>

</html>