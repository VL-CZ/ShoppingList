<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping List</title>
    <link rel="stylesheet" href="../styles/style.css" type="text/css"/>
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
                <th></th>
                <th></th>
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
                        <button class="moveItemUpButton" data-id="<?= htmlspecialchars($item->id) ?>">MOVE UP</button>
                    </td>
                    <td>
                        <button class="moveItemDownButton" data-id="<?= htmlspecialchars($item->id) ?>">MOVE DOWN
                        </button>
                    </td>
                    <td>
                        <button class="editItemButton" data-id="<?= htmlspecialchars($item->id) ?>">UPDATE</button>
                    </td>
                    <td>
                        <button class="deleteItemButton" data-id="<?= htmlspecialchars($item->id) ?>">DELETE</button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>

    <div id="editItemForm" class="hidden">
        <h3>Editing item</h3>
        <form method="post" action="?action=Items/ItemUpdate">
            <input type="hidden" name="id" id="editItemId"/>
            <input type="number" name="newAmount"/>
            <input type="submit" value="Submit"/>
        </form>
        <button id="cancelEditButton">Cancel</button>
    </div>

    <h3>Add item</h3>
    <form method="post" action="?action=Items/Item">
        <input type="text" name="name" list="itemsDatalist"/>
        <datalist id="itemsDatalist">
            <?php
            foreach ($items as $item)
            {
            ?>

                <option value="<?= htmlspecialchars($item->name) ?>">

                <?php
                }
                ?>
        </datalist>
        <input type="number" name="amount"/>
        <input type="submit" value="Submit"/>
    </form>
</body>
</html>