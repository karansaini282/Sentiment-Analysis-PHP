<?php
session_start(); 
include 'conn.php';
$user_id=$_SESSION['user_id'];
$topic_name=$_POST['topic_name'];
$sql="INSERT INTO topics (topic_name) VALUES ('".$topic_name."')";
$conn->query($sql);
$topic_id=$conn->insert_id;
header('Content-Type: application/json');
echo json_encode(array('topic_id' => $topic_id));
?>