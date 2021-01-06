<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping List</title>
    <script src="../scripts/script.js"></script>
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

            <?php
            foreach ($items as $item)
            {
                ?>

                <tr>
                    <td><?= htmlspecialchars($item->name) ?></td>
                    <td><?= htmlspecialchars($item->amount) ?></td>
                    <td>
                        <button class="deleteItemButton" data-id="<?= htmlspecialchars($item->id) ?>">DELETE</button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>

    <h3>Add item</h3>
    <form method="post" action="?action=Items/Item">
        <input type="text" name="name"/>
        <input type="number" name="amount"/>
        <input type="submit" value="Submit"/>
    </form>
</body>
</html>