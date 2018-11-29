<?php
#------------------------------------------------------------
class Posting{
    private $mysqli_host = "localhost";
    private $mysqli_user = "Jared";
    private $mysqli_pass = "291996";
    private $mysqli_database = "hidden_post";
    protected $connect_db;
    protected $id=NULL;

    public function __construct () {
        $this->connect_db = new mysqli ($this->mysqli_host,
                                  $this->mysqli_user,
                                  $this->mysqli_pass,
                                  $this->mysqli_database);
            if (!$this->connect_db) {
                print_r("<hr>Error: wrong connection");
                exit();
            }
    }

    public function inputData () {
        if ($_POST["add"]) {
            if ($_POST["nick"] && $_POST["msg"] !== "") {
                $query = "INSERT INTO messages (id, nickname, msg) VALUES (?,?,?)";
                $stmt = $this->connect_db->prepare($query);
                $nick = $_POST["nick"];
                $msg = $_POST["msg"];
                $stmt->bind_param("sss", $this->id, $nick, $msg);
                $stmt->execute();
                if ($stmt->affected_rows !== 0) {
                    $stmt->close();
                }else{
                    $stmt->connection_error();
                    echo "<hr>Error: wrong mysqli script.";
                }
            }
        }
    }

    public function outputData () {
        $query = "SELECT id, nickname, msg FROM messages";
            if ($stmt = $this->connect_db->prepare($query)) {
            $stmt->execute();
            }
            $stmt->bind_result($id_out, $nick_out, $msg_out);
            while ($stmt->fetch()) {
                printf("<br> %i %s %s\n", $id_out, $nick_out, $msg_out);
                echo "<br>";
            }
            $stmt->close();

    }

    public function __destruct () {
        if ($this->connect_db) {
            $this->connect_db->close();
        }
        if ($this->connect_db == true) {
            exit();
        }
    }
}