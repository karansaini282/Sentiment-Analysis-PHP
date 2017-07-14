<?php
session_start();
include 'conn.php';
$user_id=$_SESSION['user_id'];
$topic_id=$_POST['topic_id'];
$msg_content=$_POST['msg_content'];
$sql="INSERT INTO messages (topic_id,user_id,msg_content) VALUES (".$topic_id.",".$user_id.",'".$msg_content."')";
$conn->query($sql);
?>
  	<ul class="nav nav-pills">
    <?php
	$sql="SELECT t.topic_id,t.topic_name,count(*) as count from messages m inner join topics t on m.topic_id=t.topic_id group by m.topic_id;";
	$res=$conn->query($sql);
	$j=0;
	while($row=mysqli_fetch_assoc($res))
	{
	?>
    <li<?php if($j==0){echo " class='active'";}?>><a data-toggle="pill" href="#topic<?php echo $row['topic_id'];?>"><?php echo $row['topic_name'];?><span class="badge"><?php echo $row['count'];?></span></a></li>
	<?php
	$j++;
	}
	?>
	</ul>
	<div class="tab-content">
	<?php 
	$sql="SELECT * FROM topics;";
	$res=$conn->query($sql);
	$i=0;
	while($row=mysqli_fetch_assoc($res))
	{
	?>
	<div id="topic<?php echo $row['topic_id'];?>" class="tab-pane<?php if($i==0){echo " in active";}?>">
	  <h3><?php echo $row['topic_name']?></h3>
	  <div class="panel-group">	  	  
      <?php
	  $sql2="SELECT messages.msg_content,users.user_name FROM messages inner join users on messages.user_id=users.user_id WHERE messages.topic_id=".$row['topic_id'].";";
	  $res2=$conn->query($sql2);
	  while($row2=mysqli_fetch_assoc($res2))
	  {
	  ?>
	<div class="panel panel-info">
      <div class="panel-heading"><?php echo $row2['user_name'];?></div>
      <div class="panel-body"><?php echo $row2['msg_content'];?></div>
    </div>
	  <?php 
	  }
	  ?>
    </div>
	</div>
	<?php
	$i++;
	}
	?>
  </div>