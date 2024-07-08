<?php

class Database
{
    private $host;
    private $username;
    private $password;
    private $db;

    function __construct()
    {
        $os = php_uname('s'); // Get the operating system name
        
        // Set credentials based on the operating system
        if (strpos($os, 'Linux') !== false) { // Check if running on Ubuntu (Linux)
            $this->host = "localhost";
            $this->username = "checklist";
            $this->password = "ap:sU98b-~P4>{u+!<6E_c";
            $this->db = "ubuntu_database";
        } elseif (strpos($os, 'Windows') !== false) { // Check if running on Windows
            $this->host = "localhost";
            $this->username = "root";
            $this->password = "";
            $this->db = "windows_database";
        } else {
            // Default credentials if the OS cannot be determined
            $this->host = "localhost";
            $this->username = "root";
            $this->password = "";
            $this->db = "checklist_db";
        }
    }

	function connect()
	{
		$connection = mysqli_connect($this->host,$this->username,$this->password,$this->db);
		return $connection;
	}

	function read($query)
	{
		$conn = $this->connect();
		$result = mysqli_query($conn, $query);

		if(!$result)
		{
			return false;
		}else
		{
			$data = false;
			while($row = mysqli_fetch_assoc($result)) 
			{
				$data[] = $row;
			}

			return $data;
		}
	}

	function save($query)
	{
		$conn = $this->connect();
		$result = mysqli_query($conn, $query);

		if(!$result)
		{
			return false;
		}else
		{
			return true;
		}
	}

}
