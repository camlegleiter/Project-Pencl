<?php

function removeStudentFromClass($id, $classid)
{
	$id = mysql_real_escape_string($id);
	$classid = mysql_real_escape_string($classid);
	
	//Remove user from class and all his/her notepads from the class
	$deleteduser = mysql_query("DELETE FROM classmates WHERE userid='$id' AND classid='$classid'");
	$removed = mysql_affected_rows() != 0;
	
	$classnotepads = mysql_query("SELECT notebookid FROM classbooks WHERE classid='$classid'");
	while ($row = mysql_fetch_assoc($classnotepads))
	{
		$notebook = mysql_query("SELECT userid FROM notebooks WHERE id='".$row['notebookid']."'");
		$nrow = mysql_fetch_assoc($notebook);
		mysql_free_result($notebook);
		
		if ($nrow)
		{
			if ($nrow['userid'] == $id)
				mysql_query("DELETE FROM classbooks WHERE notebookid='".$row['notebookid']."' AND classid='$classid'");
		}
	}
	mysql_free_result($classnotepads);
	
	return $removed;
}

function removeNotepadFromClass($id, $classid)
{
	$id = mysql_real_escape_string($id);
	$classid = mysql_real_escape_string($classid);
	
	mysql_query("DELETE FROM classbooks WHERE notebookid='$id' AND classid='$classid'");

	return mysql_affected_rows() != 0;
}

?>