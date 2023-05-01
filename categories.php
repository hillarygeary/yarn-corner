<?php

/*

Hello! Just as a quick note, this code is pretty poorly documented, that's something
I'm aware of and I'm working on updating it in my free time!
Just know that documentation in my more recent programs is much more thorough and clean!

*/

function categories(){
    $conn = mysqli_connect('localhost', 'root', '', 'geary') or die('bad db conn');
    $query = "SELECT * FROM `categories`";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_array($result)) {

        echo "<form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type='text' value='$row['cat_id'] readonly hidden>
                <input type='submit'" . $row['cat_name'] . "<span>(6)</span></a></li>";

    }

}