<?php
    include "sql_common.php";
    session_start();
    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    }
    if (isset($_POST["message"])) {
        set_new_conversation($_SESSION["username"], $_POST["recipient"], $_POST["title"], $_POST["message"], gmdate("Y-m-d H:i:s"), $_POST["attached"]);
        header("Location: received_messages.php" );
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div>
        <form action="compose_messages.php" method="post">
            <select name="recipient" id="recipient">
                <?php $result = get_users(); ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php if ($row["username"] != $_SESSION["username"]): ?>
                        <option value="<?php echo $row["username"] ?>"><?php echo $row["username"] ?></option>
                    <?php endif ?>
                <?php endwhile ?>
            </select><br/>
            <input type="text" name="title" placeholder="Title"><br/>
            <textarea name="message" id="" cols="30" rows="10" placeholder="Message" required></textarea><br/>
            <input type="submit" value="Send" class="button">
            <?php if (isset($_POST["new_print_data"])): ?>
                <label for="attached">Attachment</label>
                <input type="hidden" name="attached" id="attached" value='<?php  echo $_POST["new_print_data"] ?>'>
            <?php endif ?>
        </form>
    </div>
</body>
</html>