<?php
require_once __DIR__ . '/db_connect.php';
$response = array();//array for JSON object
if(isset($_POST['command_key']))//if command has been received with this key
{	$db = new DB_CONNECT();
	$command=strtolower($_POST['command_key']); //convert the instruction into lower case
	$command_query=mysql_query("SELECT Commands,action_id FROM smart_home_commands");
	while($row=mysql_fetch_array($command_query)) //loop through the entire table
	{
		$sql_command=strtolower($row["Commands"]); //convert the commands in sql to lower case
		$sql_command_id=$row["action_id"]; //id of the command
		$id=$sql_command_id;

		if(strpos($command,$sql_command)!==FALSE || strpos($sql_command,$command)!==FALSE) //command found
		{
			if(preg_match('/on/',$command))//if 'on' is found in the command
			{
				$update_instruction=mysql_query("UPDATE smart_home_commands SET state='true',time_stamp=CURTIME() WHERE action_id='$id'");
			}
			elseif(preg_match('/off/',$command))//if 'off' is found in the command
			{
				$update_instruction=mysql_query("UPDATE smart_home_commands SET state='false',time_stamp=CURTIME() WHERE action_id='$id'");
			}
			else
			{
				$update_instruction=mysql_query("UPDATE smart_home_commands SET state='true',time_stamp=CURTIME() WHERE action_id='$id'");
			}
		}
	}
        $response["message"] = "successfully updated.";
        echo json_encode($response); //return JSON string to the android device to complete the action
}
?>
