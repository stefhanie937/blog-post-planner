<?php
// config/db.php
// This file connects your website to the MySQL database.
// Every other file will use this connection.

function getConnection(): \mysqli {
    $host     = 'host.docker.internal';  // reach Windows MySQL from Docker
    $user     = 'root';                  // default MySQL user
    $password = '';                      
    $database = 'blog_planner';          // the database you created in Navicat

    $conn = new \mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die('<p style="color:red;font-family:sans-serif;padding:2rem;">
            ❌ Database connection failed: ' . $conn->connect_error . '<br>
            Make sure MySQL is running in XAMPP!
        </p>');
    }

    return $conn;
}