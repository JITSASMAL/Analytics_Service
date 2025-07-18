<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Table</title>
    <link rel="stylesheet" href="record.css">
</head>
<body>
    <form action="" method="post">
        <div>
            <table border="1">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Location</th>
                        <th>Quentity</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require "dbconnect.php";

                    if (mysqli_connect_error()) {
                        echo mysqli_connect_error();
                        exit;
                    }

                    $db = mysqli_select_db($conn, "final_project");
                    $query = "SELECT * FROM sales";
                    $query_runn = mysqli_query($conn, $query);

                    while ($row = $query_runn->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['age']; ?></td>
                            <td><?php echo $row['sex']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td><?php echo $row['location']; ?></td>
                            <td><?php echo $row['quentity']; ?></td>
                            <td><?php echo $row['payment']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </form>
</body>
</html>
