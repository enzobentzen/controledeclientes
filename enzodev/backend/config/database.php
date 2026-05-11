<?php

class Database
{

    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "class";
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed");
        }
    }

    public function registerUsers($fname, $lname, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = $this->conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        $sql->bind_param("ssss", $fname, $lname, $email, $hashedPassword);

        if ($sql->execute()) {
            echo "User registered successfully";
        } else {
            echo "Not registered";
        }

        $sql->close();
    }

    public function listUsers($search)
    {
        $Users = $this->conn->prepare("SELECT first_name, last_name FROM users WHERE first_name LIKE ? OR last_name LIKE ?");
        $Users->bind_param("ss", $search, $search);
        $Users->execute();
        return $Users;
    }

    public function searchName($search)
    {
        $Users = $this->conn->prepare("SELECT first_name, last_name FROM users WHERE first_name LIKE ? OR last_name LIKE ?");
        $Users->bind_param("ss", $search, $search);
        $Users->execute();
        $result = $Users->get_result();
        return $result;
    }

    public function showUsers()
    {
        $Users = $this->conn->query("SELECT id, first_name, last_name FROM users");
        $list = [];
        while ($row = mysqli_fetch_assoc($Users)) {
            $list[] = $row;
        }
        return $list;
    }

    public function deleteUser($id)
    {
        $userid = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $userid->bind_param("i", $id);

        if ($userid->execute()) {
            echo "User deleted successfully";
        } else {
            echo "Not deleted";
        }
    }

    public function getUser($id)
    {
        $data = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $data->bind_param("i", $id);

        if ($data->execute()) {
            $result = $data->get_result();
            $result = $result->fetch_assoc();
            return $result;
        }
    }

    public function getUserByEmail($email)
    {
        $data = $this->conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $data->bind_param("s", $email);
        $data->execute();

        $result = $data->get_result();

        if ($result->num_rows < 1) {
            return false;
        }

        return $result->fetch_assoc();
    }

    public function updateUser($fname, $lname, $id, $password = null)
    {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $data = $this->conn->prepare("UPDATE users SET first_name = ?, last_name = ?, password = ? WHERE id = ?");
            $data->bind_param("sssi", $fname, $lname, $hashedPassword, $id);
        } else {
            $data = $this->conn->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE id = ?");
            $data->bind_param("ssi", $fname, $lname, $id);
        }

        if ($data->execute()) {
            echo "User Updated successfully";
        } else {
            echo "Failed to update user";
        }

        $data->close();
    }

    public function loginUser($email, $pass)
    {
        $getUser = $this->getUserByEmail($email);

        if (!$getUser) {
            return false;
        }

        return password_verify($pass, $getUser['password']);
    }

    public function uploadImage($image, $id)
    {
        $data = $this->conn->prepare("INSERT INTO uploads (path, user_id) VALUES (?, ?)");
        $data->bind_param("si", $image, $id);

        if ($data->execute()) {
            echo "Image uploaded successfully";
        } else {
            echo "There was an error uploading your image";
        }
    }

    public function listImages($id)
    {
        $data = $this->conn->prepare("SELECT path FROM uploads WHERE user_id = ?");
        $data->bind_param("i", $id);
        $data->execute();
        $result = $data->get_result();
        return $result;
    }

    public function registerClient($fname, $lname, $email, $phone, $user_id)
    {
        $sql = $this->conn->prepare("INSERT INTO clients (first_name, last_name, email, phone, user_id) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("ssssi", $fname, $lname, $email, $phone, $user_id);

        if ($sql->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function showClients($user_id)
    {
        $data = $this->conn->prepare("SELECT * FROM clients WHERE user_id = ?");
        $data->bind_param("i", $user_id);
        $data->execute();
        $result = $data->get_result();
        $list = [];
        while ($row = $result->fetch_assoc()) {
            $list[] = $row;
        }
        return $list;
    }

    public function getClient($id)
    {
        $data = $this->conn->prepare("SELECT * FROM clients WHERE id = ?");
        $data->bind_param("i", $id);
        $data->execute();
        $result = $data->get_result();
        return $result->fetch_assoc();
    }

    public function getClientByEmail($email, $user_id)
    {
        $data = $this->conn->prepare("SELECT * FROM clients WHERE email = ? AND user_id = ? LIMIT 1");
        $data->bind_param("si", $email, $user_id);
        $data->execute();
        $result = $data->get_result();
        return $result->num_rows > 0;
    }

    public function updateClient($fname, $lname, $email, $phone, $id)
    {
        $data = $this->conn->prepare("UPDATE clients SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE id = ?");
        $data->bind_param("ssssi", $fname, $lname, $email, $phone, $id);

        if ($data->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteClient($id)
    {
        $data = $this->conn->prepare("DELETE FROM clients WHERE id = ?");
        $data->bind_param("i", $id);

        if ($data->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
