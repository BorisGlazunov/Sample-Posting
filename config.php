<?php
declare(strict_types=1);
#------------------------------------------------------------
class Posting {
    private $mysqli_host = "localhost";
    private $mysqli_user = "Jared";
    private $mysqli_pass = "291996";
    private $mysqli_database = "hidden_post";
    /*private $mysqli_host = "localhost";
    private $mysqli_user = "id7534529_jared";
    private $mysqli_pass = "291996jared";
    private $mysqli_database = "id7534529_hidden_post";*/
    protected $connect_db;
    protected $id = NULL;
    protected $id_parsed = 0;
    private $query_outputPagination = "SELECT id, nickname, msg FROM messages";

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
                    if (isset($_POST["nick"]) && isset($_POST["msg"])) {
                        $nick = $_POST["nick"];
                        $msg = $_POST["msg"];
                    }
                $stmt->bind_param("sss", $this->id, $nick, $msg);
                $stmt->execute();
                if ($stmt->affected_rows !== 0) {
                    $stmt->close();
                }
            }
        }
    }

    public function outputPagination () {
        # query запрос
        #$query = "SELECT id, nickname, msg FROM messages";
        #
            if ($stmt = $this->connect_db->prepare($this->query_outputPagination)) {
                $stmt->execute();
            }
            $stmt->bind_result($id_out, $nick_out, $msg_out);
                while ($stmt->fetch()) {
                    for ($id_cnt=0; $id_cnt<=$id_out; $id_cnt++) {
                        #COUNTER
                        if ($id_cnt == $id_out){
                            $this->id_parsed++;#валидное количество #ID в базе, не выводит лишние страницы
                        }
                    }
                }
                if (isset($this->id_parsed)) {
                    $this->amnt_pages = $this->id_parsed/9;
                }
                    echo "<form action='' method='post' class='SelectDecoration'><select name='id_page'>";
                for ($cnt=0; $cnt<=$this->amnt_pages; $cnt++) {
                    echo "<option value=".$cnt.">$cnt</option>";
                }
                    echo "</select><input type='submit' name='change_page' class='PageButtonDecoration' value='Chose the page'></form>";
    }

    public function outputData () {
        if (isset($change_page)) { 
            $change_page = $_POST["change_page"];
        }
        if (isset($_POST["id_page"])) {
            $id_page = $_POST["id_page"];
            $mess_since = $id_page*9;
            $mess_to = $mess_since-9;
        }else{
            $id_page = 0;
            $mess_since = $id_page*9;
            $mess_to = $mess_since-9;
        }
        # query запрос
        $query = "SELECT id, nickname, msg FROM messages ORDER BY id DESC LIMIT $mess_since, 9";
        #
        if ($stmt = $this->connect_db->prepare($query)) {
            $stmt->execute();
        }
        if ($stmt !== false) {
            $stmt->bind_result($id_out, $nick_out, $msg_out);
        }
        while ($stmt->fetch()) {
            if ($nick_out == "Jared" or $nick_out == "jared") {
                printf("<hr>%i %s %s\n", $id_out, "<button class='JaredNickDecoration'>".$nick_out."</button>", "<button class='JaredTextDecoration'>".$msg_out."</button>");
            }else{
                printf("<hr>%i %s %s\n", $id_out, "<button class='NickDecoration'>".$nick_out."</button>", "<button class='TextMessageDecoration'>".$msg_out."</button>");
            }
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