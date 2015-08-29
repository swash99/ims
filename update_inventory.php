<?php

include 'sql_common.php';

function get_inventory($category_id, $date) {
    global $conn;
    connect_to_db();

    $sql = 'SELECT T2.item_id AS id, T2.item_name AS name, T2.item_unit AS unit, IFNULL(T1.amount, "-") AS amount FROM
            (SELECT * from Inventory
            WHERE Inventory.date = "' .$date. '") AS T1
            RIGHT JOIN
            (SELECT Item.id AS item_id, Item.name AS item_name, Item.unit AS item_unit from Item
            INNER JOIN Category ON Item.category_id = Category.id
            WHERE Category.id = ' .$category_id. ') AS T2  ON T2.item_id = T1.item_id';

    $result = $conn->query($sql); 
    if ($result == False) {
        echo "<br> Query failed <br>";
    }

    echo '<a href="index.php"> Home </a><br><br>';
    echo '<br><form action="update_inventory.php" method="post">
    <input type="hidden" name="category_id" value="' .$category_id. '">
    <input type="hidden" name="func_name" value="get_inventory">
    <input type="date" name="date" value="' .$date. '">
    <input type="submit"> </form><br>';

    echo "<table border=\"1px solid black\">";
    echo "<th>Item</th>";
    echo "<th>Unit</th>";
    echo "<th>Amount</th>";

    $i = 0;
    $inventory_items = [];

    while ($row = $result->fetch_assoc()) {
        $inventory_items[$i] = $row["id"];

        echo '<form action="update_inventory.php" method="post">
              <tr><td>' . $row["name"]. '</td>
                  <td>' . $row["unit"]. '</td>
                  <td>' . $row["amount"]. '</td>
                  <td><input type="number" min="0" name="values[]"</td></tr>';
        ++$i;
    }
    echo '</table><br>
    <input type="hidden" name="func_name" value="update_inventory">
    <input type="hidden" name="date" value="' .$date. '">
    <input type="hidden" name="category_id" value="' .$category_id. '">
    <input type="hidden" name="inventory_items" value=' .serialize($inventory_items). '/>
    <input type="submit" value="Update"> </form><br>';
}

function update_inventory($category_id, $date, $items, $values) {
    global $conn;
    connect_to_db();

    for ($i = 0; $i < count($items); $i++) {
        if ($values[$i] == null) {
            continue;
        }

        $sql = 'INSERT INTO Inventory (`date`, `item_id`, `amount`)
                VALUES ("' .$date. '", ' .$items[$i]. ', ' .$values[$i]. ')
                ON DUPLICATE KEY UPDATE 
                date=VALUES(date), item_id = VALUES(item_id), amount = VALUES(amount)';

        $result = $conn->query($sql); 
        if ($result == False) {
            echo "<br> Query failed <br>";
        }
    }

    get_inventory($category_id, $date);
}


if (strcmp($_POST['func_name'], 'get_inventory') == 0) {
    get_inventory($_POST['category_id'], $_POST['date']);
} else if(strcmp($_POST['func_name'], 'update_inventory') == 0) {
    $items = unserialize($_POST['inventory_items']);
    $values = $_POST['values'];
    $date = $_POST['date'];
    $category_id = $_POST['category_id'];
    update_inventory($category_id, $date, $items, $values);
} 
?>