<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <h2>Shopping list</h2>
    <div>
        <table>
            <tr>
                <th>Item</th>
                <th>Amount</th>
                <th></th>
                <th></th>
            </tr>
        </table>
    </div>

    <?php
    $i = 10;
    echo("Hello world" . $i);

    ?>

    <h3>Add item</h3>
    <form method="post" action=".">
        <input type="text" name="item"/>
        <input type="number" name="amount"/>
        <input type="submit" value="Submit"/>
    </form>
</body>
</html>