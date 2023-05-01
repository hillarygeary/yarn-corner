<?php

/*

Hello! Just as a quick note, this code is pretty poorly documented, that's something
I'm aware of and I'm working on updating it in my free time!
Just know that documentation in my more recent programs is much more thorough and clean!

*/

function showpages($page, $id){

    $_SESSION['login'] = False;

    if(isset($_POST['login'])) {
        $_SESSION['login'] = True;
    }
    $login = $_SESSION['login'];

    $masterlist = ['Home' => 'index.php', 'My Profile and Blogs' => "bloglist.php?userdispl=$id", 'Admin' => 'admin.php'];

    if(!isset($_SESSION['username'])) {
        $pagelist = ['Home' => 0];
        $loggedin = False;
    } else {
        if($_SESSION['level'] == 1) {
            $pagelist = ['Home' => 0, 'My Profile and Blogs' => 0];
        } else {
            $pagelist = ['Home' => 0, 'My Profile and Blogs' => 0, 'Admin' => 0];
        }
        $loggedin = True;
    }
    if($page != 'Log In' && $page != 'Blog' && $login) { $pagelist[$page] = 1; }

    echo "<nav id='colorlib-main-menu' role='navigation'><ul>";
    
    foreach($pagelist as $key => $value) {
        if($value == 0) {
            echo "<li ><a href='$masterlist[$key]'>$key</a></li>";
        } else {
            echo "<li class='colorlib-active'><a href='$masterlist[$key]'>$key</a></li>";
        }
    }
    if(!$loggedin) {
        echo "<li><a href='login.php'>Log In</a></li>";
    } else {
        echo "<li><a href='logout.php'>Log Out</a></li>";
    }
    echo "</ul></nav>";

    if($login) { login(); }

}