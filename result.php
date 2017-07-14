<?php
if(isset($_POST['result']))
{
$result=$_POST['result'];
?>
  <table class="table table-bordered th, .table-bordered td { border: 1px solid #ddd!important }">
    <thead>
      <tr>
        <th>Message</th>
		<th>Sentiment</th>
      </tr>
    </thead>
    <tbody id='message_table_body'>
<?php
$sentiment=0;
$n=0;
foreach($result as $row)
{
	$sentiment+=$row["sentiment"];
	$n++;
?>
      <tr>
        <td><?php echo $row["document"]; ?></td>
        <td><?php echo $row["sentiment"]; ?></td>        
      </tr>
<?php
}
if($n>0)
{	
?>
      <tr>
        <td><?php echo "Average"; ?></td>
        <td><?php echo round($sentiment/(float)$n,4); ?></td>        
      </tr>
<?php	
}
}
?>
    </tbody>
  </table>