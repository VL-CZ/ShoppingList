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
            </tr>
        </table>
    </div>

    <table>
        <?php
        foreach ($items as $item)
        {
            echo("<tr><td>$item->name</td><td>$item->amount</td></tr>");
        }
        ?>
    </table>

    <h3>Add item</h3>
    <form method="post" action="?action=Items/Item">
        <input type="text" name="name"/>
        <input type="number" name="amount"/>
        <input type="submit" value="Submit"/>
    </form>
</body>
</html>