<?php
include("database.php");
class Query extends Database
{
    public function insertQ($table, $insertData)
    {
        array_pop($insertData);
        // print_r($insertData);die;
        $image = '';
        if (isset($insertData['password'])) { //If password is found it will be encrypted
            $insertData['password'] = md5($insertData['password']);
        }
        $keys = implode(',', array_keys($insertData));
        $values = "'" . implode("','", array_values($insertData)) . "'";
        if (!empty($_FILES)) {
            // print_r($_FILES);die;
            $image = $_FILES['main_img']['name'];
            $path = "./uploads";
            $tmpname = $_FILES['main_img']['tmp_name'];
            move_uploaded_file($tmpname, $path . "/" . $image);
            $keys .= ',main_img';
            $values .= ",'$image'";

        }
        $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        // print_r($sql);
        // die();
        $this->conn->query($sql);
    }

    public function selectAllQ($table)
    {
        $sql = "SELECT * FROM $table";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }
    public function selectSpecCol($table, $col_name)
    {
        $sql = "SELECT $col_name FROM $table";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }
    public function login($table, $email, $password)
    {
        // $password = md5($password);
        $sql = "SELECT * FROM $table WHERE email='$email' && password='$password'";
        $res = $this->conn->query($sql);
        if ($res->num_rows == 1) {
            $userdata = mysqli_fetch_assoc($res);
            return $userdata;
        } else {
            return false;
        }
    }

    public function duplicateEntry($table, $key, $value)
    {
        $sql = "SELECT * FROM $table WHERE $key = '$value'";
        $res = $this->conn->query($sql);
        if ($res->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function selectAlltypeQ($table, $key, $value)
    {
        $sql = "SELECT * FROM $table WHERE $key='$value'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }
    public function selectAlltypeInDirectionQ($table, $order, $dir = "DESC")
    {
        $sql = "SELECT * FROM $table ORDER BY $order $dir";
        // echo ($sql);die();
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }
    public function getSpecDataQ($table, $col_name, $pk, $id)
    {
        $sql = "SELECT $col_name FROM $table WHERE $pk='$id'";
        $result = mysqli_fetch_assoc($this->conn->query($sql));
        return $result;
    }


    public function deleteQ($table, $pk, $id)
    {
        //Remove the files if present
        // $img = "SELECT image FROM $table WHERE $pk=$id";
        // $imgname = $this->conn->query($img);
        // unlink("/staic/uploads/$imgname");
        $sql = "DELETE FROM $table WHERE $pk = '$id'";
        $this->conn->query($sql);
    }
    // for update query
    public function getRecordById($table, $pk, $id)
    {
        $sql = "SELECT * FROM $table WHERE $pk='$id'";
        $res = $this->conn->query($sql);
        $record = mysqli_fetch_assoc($res);
        return $record;
    }
    public function updateQ($table, $updateData, $pk, $id)
    {   
        foreach ($updateData as $key => $value) {
            $updateData[$key] = "{$key} = '{$value}'";
        }
        $updatesql = implode(",", $updateData);
        $sql = "UPDATE $table SET $updatesql WHERE $pk ='$id'";
        $this->conn->query($sql);
    }
    public function executeQ($sql)
    {
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function numQ($table)
    {
        $sql = "SELECT * FROM $table";
        $rows = $this->conn->query($sql);
        return $rows->num_rows;
    }
    public function lastIndex($table,$pk){
        $sql = "SELECT MAX($pk) as max FROM $table";
        $res = $this->conn->query($sql);
        $value = mysqli_fetch_assoc($res);
        return $value['max'];
    }
}
