<?php
session_start(); 
include 'conn.php';
$user_id=$_SESSION['user_id'];
$sql="SELECT * from users WHERE user_id=".$user_id.";";
$res=$conn->query($sql);
$user=mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Sentiment/member</title>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>
  <link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
  <script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>
  <script src="amcharts/amcharts.js"></script>
  <script src="amcharts/serial.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand"><span style='color:lightblue'>Sentiment Analysis</span></a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a>Member</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
		<li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $user['user_name'];?><span class="caret"></span></a>
        <ul class="dropdown-menu">
			<li><a href="logout.php"><span style='color:red'><span class="glyphicon glyphicon-log-out"></span> Log Out</span></a></li>
        </ul>
      </li>         
    </ul>
  </div>
</nav>
<div class='container'>
  <div class='row'>
  
  <div class='col-sm-3'>
		<ul class="nav nav-pills nav-justified">
			<li class="active"><a data-toggle="pill" href="#form1">Add Topic</a></li>
			<li><a data-toggle="pill" href="#form2">Add Message</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane in active" id="form1">
				<form role='form' method='POST'>
				  <div class='form-group'>
					<label for='username'>Topic Name: </label>
					<input type='text' class='form-control' id='1_topic_name'>
				  </div>
				  <div class='form-group'>
					<button type="button" class="btn btn-success" onclick='addTopic()'>Submit</button>
				  </div>
				</form>
			</div>
			<div class="tab-pane" id="form2">
				<form role='form' method='POST'>
				  <div class='form-group'>
					<label for='2_topic_name'>Topic Name: </label>
					<select class='form-control' id='2_topic_name' name='2_topic_name'>
					<?php 
					$sql="SELECT * from topics;";
					$res=$conn->query($sql);
					while($row=mysqli_fetch_assoc($res))
					{
					?>
					<option value='<?php echo $row['topic_id'];?>'><?php echo $row['topic_name'];?></option>
					<?php 
					}
					?>
					</select>
				  </div>
				  <div class='form-group'>
					<label for='2_msg_content'>Message: </label>
					<textarea rows="5" class='form-control' id='2_msg_content' name='2_msg_content'></textarea>
				  </div>
				  <div class='form-group'>
					<button type="button" class="btn btn-success" onclick='addMessage()'>Submit</button>
				  </div>
				</form>
			</div>
		</div>
  </div>
  
    <div class='col-sm-4'>
  		<form role='form' method='POST'>
		  <div class='form-group'>
			<label for='3_topic_name'>Topic Name: </label>
			<select class='form-control' id='3_topic_name' name='3_topic_name'>
			<?php 
			$sql="SELECT * from topics;";
			$res=$conn->query($sql);
			while($row=mysqli_fetch_assoc($res))
			{
			?>
			<option value='<?php echo $row['topic_id'];?>'><?php echo $row['topic_name'];?></option>
			<?php 
			}
			?>
			</select>
		  </div>
		  <div class='form-group'>
			<button type="button" class="btn btn-success" onclick='getSentiment()'>Find Sentiment</button>
		  </div>
		</form>
		<div id='result_table' style="width: 320px; height: 400px;">
		</div>
  </div>
  
  <div class='col-sm-5'>
  <div id='message_table'>
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
  </div>
  </div>
  
  </div>
</div>
</body>
</html>
<script src="jquery.min.js"></script>
<script src="//algorithmia.com/v1/clients/js/algorithmia-0.2.0.js" type="text/javascript"></script>
<script>
function addTopic()
{
	topic_name=document.getElementById('1_topic_name').value;	
	$.ajax({
		type: "POST",
		url: "addTopic.php",
		data: {
				topic_name:topic_name
			  }, 
		cache: false,
		success: function(data){
		option=document.createElement('option');
		option.setAttribute('value',data.topic_id);
		option.innerText=topic_name;
		select=document.getElementById('2_topic_name');
		select.appendChild(option);
		}
	});
}
function addMessage()
{
	topic_id=document.getElementById('2_topic_name').value;
	msg_content=document.getElementById('2_msg_content').value;	
	$.ajax({
		type: "POST",
		url: "addMessage.php",
		data: {
				topic_id:topic_id,
				msg_content:msg_content
			  }, 
		cache: false,
		success: function(data){
		document.getElementById('message_table').innerHTML=data;
		}
	});
}
function getSentiment()
{
	select_topic=document.getElementById('3_topic_name');
	topic_name=select_topic.options[select_topic.selectedIndex].text;
	topic_id2=document.getElementById('3_topic_name').value;
	div=document.getElementById('topic'+topic_id2);
	panel_group=div.getElementsByClassName("panel-group")[0];
	panel_info=panel_group.getElementsByClassName("panel panel-info");
	input=[]
	for(i=0;i<panel_info.length;i++)
	{
		panel_body=panel_info[i].getElementsByClassName('panel-body')[0];
		input.push({'document':panel_body.innerText});
	}
	Algorithmia.client("simmGxxYvPWLVHflZ5kXNebMxNx1")
           .algo("algo://nlp/SentimentAnalysis/1.0.3")
           .pipe(input)
           .then(function(output) {
             console.log(output);
			sum=0
			n=0
			average=0
			for(i=0;i<output.result.length;i++)
			{
				output.result[i].sentiment=Math.round(((output.result[i].sentiment*100)+100)/2)
				output.result[i].field=i;
				sum+=output.result[i].sentiment;
				n++;
			}
			if(n>0)
			{
				average=Math.round(sum/n);
			}
			output.result.push({"document":"Average","sentiment":average,"field":n,"color":"#2A0CD0"});
			for(i=0;i<output.result.length-1;i++)
			{
				if(output.result[i].sentiment>average)
				{
					output.result[i].color="#04D215";
				}
				else if(output.result[i].sentiment<average)
				{
					output.result[i].color="#FF0F00";
				}
				else
				{
					output.result[i].color="#F8FF01";
				}
			}
			console.log(output.result);
			document.getElementById('result_table').innerHTML="";
			AmCharts.makeChart( "result_table", {
			  "titles": [
			  {
			  "text": "Topic Analysis: "+topic_name,
			  "size": 15
		      }
		      ],
			  "type": "serial",
			  "dataProvider": output.result,
			  "categoryField": "field",
			  "angle": 15,
			  "depth3D": 15,			  
			  "valueAxes": [
			    {
				"position": "left",
				"title": "Sentiment"
				},
				{
				"position": "bottom",
				"title": "Message"
				}],			  
			  "graphs": [ {
				"valueField": "sentiment",
				"type": "column",			
			    "fillAlphas": 1,
				"lineAlpha": 0.1,
				"fillColorsField": "color",
				"lineColorField": "color",
				"balloonText": "[[document]]: <b>[[sentiment]]</b>"
			  } ]
			} );
           });
}
</script>