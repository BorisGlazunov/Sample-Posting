<?php

declare (strict_types=1);

/**
 * Posting Model
 * 
 * @autor ...
 */
class Posting {
    /** @var string */
    private $host = "";
    /** @var string */
    private $user = "";
    /** @var string */
    private $pass = "";
    /** @var string */
    private $database = "";
    /** @var mysqli */
    protected $connect_db;
    /** @var int|null */
    protected $id = NULL;
    /** @var int|null */
    protected $id_parsed = 0;

    public function __construct (string $host, string $user, string $pass, string $database) {
        try {
            $this->connect_db = new mysqli (
                $this->host,
                $this->user,
                $this->pass,
                $this->database);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        
    }

    public function inputData (): void {
        if ($_POST["add"]) {
            if ($_POST["nick"] && $_POST["msg"] !== "") {
                $query = "INSERT INTO messages (id, nickname, msg) VALUES (?,?,?)";
                $stmt = $this->connect_db->prepare($query);
                    if (isset ($_POST["nick"]) && isset ($_POST["msg"])) {
                        $nick = $_POST["nick"];
                        $msg = $_POST["msg"];
                    }
                $stmt->bind_param ("sss", $this->id, $nick, $msg);
                $stmt->execute ();
                if ($stmt->affected_rows !== 0) {
                    $stmt->close ();
                }
            }
        }
    }
    
    public function getMessages (): bool {
        if ($stmt = $this->connect_db->prepare("SELECT id, nickname, msg FROM messages")) {
            $stmt->execute();
        }
        $stmt->bind_result ($id_out, $nick_out, $msg_out);

        return $stmt;
    }

    public function outputPagination (): string {
        $output = "";
        $messages = $this->getMessages ();
        
        while ($messages->fetch ()) {
            for ($id_cnt = 0; $id_cnt <= $id_out; $id_cnt++) {
                if ($id_cnt == $id_out) {
                    $this->id_parsed++;#валидное количество #ID в базе, не выводит лишние страницы
                }
            }
        }

        if ($this->id_parsed) {
            $this->amnt_pages = $this->id_parsed / 9;
        }

        $output .= "<form action='' method='post' class='SelectDecoration'><select name='id_page'>";

        for ($cnt=0; $cnt<=$this->amnt_pages; $cnt++) {
            $output .= "<option value=".$cnt.">$cnt</option>";
        }
        
        $output .= "</select><input type='submit' name='change_page' class='PageButtonDecoration' value='Chose the page'></form>";

        return $output;
    }

    public function outputData (): void {
        if (isset($change_page)) { 
            $change_page = $_POST["change_page"];
        }
        if (isset($_POST["id_page"])) {
            $id_page = (int) $_POST["id_page"];
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