<?php

declare(strict_types=1);

/**
 *Posting Model
 *
 *@autor Yoda.
 */

class Posting {

    /** @var object */
    protected $messages;
    /** @var string */
    protected $output = "";

    /** @var object */
    protected $connect_db;
    /** @var string */
    protected $id = NULL;
    /** @var string */
    protected $id_parsed = 0;

    /** @var string */
    protected $id_out = "";
    /** @var string */
    protected $nick_out = "";
    /** @var string */
    protected $msg_out = "";

    /** @var string */
    protected $nick;
    /** @var string */
    protected $msg;
    /** @var string */
    protected $query;

    /** @var integer */
    protected $id_page = 0;

    /** @var string */
    protected $output_form = "";
    /** @var string */
    protected $usr_log;
    /** @var string */
    protected $usr_pass;
    /** @var string */
    protected $check_log;
    /** @var string */
    protected $check_pass;
    /** @var array */
    protected $outputUsrData;

    /** @var string */
    protected $checkNewUsrLog;
    /** @var string */
    protected $checkNewUsrToken;
    /** @var string */
    private $token;

    public function __construct (string $host, string $user, string $pass, string $database) {
        
        try {
            $this->connect_db = new mysqli ($host,
                                            $user,
                                            $pass,
                                            $database);
        } catch (Exception $e) {
            exit ($e->getMessage ()."\n");
        }
    }#workable (don't touch)

    public function inputDatabase () {
        if ($_POST['add'] !== NULL) {
            if ($_POST['nick'] !== NULL && $_POST['msg'] !== NULL) {
                $this->msg = strip_tags ($_POST['msg']);
                $this->nick = strip_tags ($_POST['nick']);

                if ($this->nick !== "" && $this->nick !== NULL && $this->msg !== "" && $this->msg !== NULL) {
                    $this->query = "INSERT INTO messages (id, nickname, msg) VALUES (?,?,?)";
                    $stmt = $this->connect_db->prepare ($this->query);
                    $stmt->bind_param ("sss", $this->id, $this->nick, $this->msg);
                    $stmt->execute ();
                }

            if ($stmt->affected_rows !== 0 && $stmt->affected_rows !== NULL) {
                $stmt->close ();
            }
            }
        }
    }#workable (don't touch)
    
    public function getMess () {
        $this->query = "SELECT id, nickname, msg FROM messages";

        $stmt = $this->connect_db->prepare ($this->query);

        if (is_object ($stmt)) {
            $stmt->execute ();
        }

        $stmt->bind_result ($this->id_out, $this->nick_out, $this->msg_out);

        return $stmt;
    }#workable (don't touch)

    public function outputPugination (): string {
        $this->messages = $this->getMess ();

        while ($this->messages->fetch ()) {
            for ($id_cnt=0; $id_cnt<=$this->id_out; $id_cnt++) {
                if ($id_cnt == $this->id_out){
                    $this->id_parsed++;
                }
            }
        }
        
        if (isset ($this->id_parsed)) {
            $this->amnt_pages = $this->id_parsed/9;
        }

        $this->output .= "<form action='' method='post' class='SelectDecoration'><select name='id_page'>";
        
            for ($cnt=0; $cnt<=$this->amnt_pages; $cnt++) {
                $this->output .= "<option value=".$cnt.">$cnt</option>";
            }

        $this->output .= "</select><input type='submit' name='change_page' class='PageButtonDecoration' value='Chose the page'></form>";

        return $this->output;
    }#workable (don't touch)

    public function outputDatabase () {

        if ($_POST['change_page']) {
            $this->id_page = (int) $_POST["id_page"];
            $mess_since = (int) $this->id_page*9;
        } else {
            $mess_since = (int) 0;
        }

        $this->query = "SELECT id, nickname, msg FROM messages ORDER BY id DESC LIMIT $mess_since, 9";
        $stmt = $this->connect_db->prepare ($this->query);
        $stmt->execute ();

        if (is_object ($stmt)) {
            $stmt->bind_result ($this->id_out, $this->nick_out, $this->msg_out);
        }
        print_r ('<form method="post" action="">');
        while ($stmt->fetch ()) {
            if ($this->nick_out == "Jared" or $this->nick_out == "jared") {
                printf ("<hr>%i %s %s\n", $this->id_out, "<button class='JaredNickDecoration'>".$this->nick_out."</button>", "<button class='JaredTextDecoration'>".$this->msg_out."</button>".'<form method="post" action=""><input type="submit" title="Delete?" name="x_button" value='."$this->id_out".' class="X_button">');
            } else {
                printf ("<hr>%i %s %s\n", $this->id_out, "<button class='NickDecoration'>".$this->nick_out."</button>", "<button class='TextMessageDecoration'>".$this->msg_out."</button>".'<input type="submit" title="Delete?" name="x_button" value='."$this->id_out".' class="X_button">');
            }
        }
        print_r ('</form>');

        if (isset ($_POST["x_button"])) {
            $this->deleteMessage ();
        }

        $stmt->close ();
    }#workable (don't touch)

    private function deleteMessage (): void {
        $this->getUsrData ();

        $this->query = 'DELETE FROM messages WHERE id="'.$_POST['x_button'].'"'.'AND nickname LIKE"'.$this->outputUsrData[1].'"';

        $stmt = $this->connect_db->prepare ($this->query);
        
        if (is_object ($stmt)) {
            $stmt->execute ();
        } 
        
    }

    private function checkNewUsr () {
        if (isset ($_POST['usr_log']) && isset ($_POST['usr_pass'])) {
            $this->usr_log = strip_tags ($_POST['usr_log']);
            $this->usr_pass = strip_tags ($_POST['usr_pass']);

            if ($this->usr_log !== NULL && $this->usr_pass !== NULL) {
                $this->query = 'SELECT token FROM usrs WHERE log LIKE "'.$this->usr_log.'" AND pass LIKE "'.$this->usr_pass.'"';
                $stmt = $this->connect_db->prepare ($this->query);
                $stmt->execute ();
                if (is_object ($stmt)) {
                    $stmt->bind_result ($this->checkNewUsrToken);
                    $stmt->fetch ();

                    if (isset ($this->checkNewUsrToken)) {
                        return $this->checkNewUsrToken;
                    } else {
                        return false;
                    }
                }
            }
        }
    }

    private function createNewUsr (string $usr_log, string $usr_pass): void {
        if ($usr_log !== NULL && $usr_pass !== NULL) {
            $this->usr_log = strip_tags ($usr_log);
            $this->usr_pass = strip_tags ($usr_pass);
            $this->token = password_hash ($this->usr_pass, PASSWORD_DEFAULT);

            if (isset ($this->token)) {
                $this->query = 'INSERT INTO usrs VALUES (?,?,?,?)';
                $stmt = $this->connect_db->prepare ($this->query);
                $stmt->bind_param ("ssss", $this->id, $this->usr_log, $this->usr_pass, $this->token);
                $stmt->execute ();
            } else {
                exit ("We can't create new account for you. Sorry.");
            }
        }
    }

    private function deleteAccount () {
        $this->getUsrData ();

        if (isset ($_POST['dropProfile'])) {
            $this->query = 'DELETE FROM usrs WHERE log LIKE "'.$this->outputUsrData[1].'" AND pass LIKE"'.$this->outputUsrData[2].'"';
            $stmt = $this->connect_db->prepare ($this->query);
            $stmt->execute ();

            if (is_object ($stmt)) {
                $stmt->close ();
            }
        }
    }

    public function mainNewUsr () {
        $this->token = $this->checkNewUsr ();
        $this->deleteAccount ();

        if ($this->token !== false) {

            if (password_verify ($this->usr_pass, $this->token)) {
                return;
            } else {
                exit ("Try again, can't login.");
            }

        } else {
            $this->createNewUsr ($_POST['usr_log'], $_POST['usr_pass']);
        }
    }
    
    public function setCookie (): string {#1 установка сессии 
        if (isset ($_POST['usr_log']) && isset ($_POST['usr_pass'])) {
            $this->usr_log = strip_tags ($_POST['usr_log']);
            $this->usr_pass = strip_tags ($_POST['usr_pass']);
            
            setcookie ("usr_cookie_log", $this->usr_log, time () + 20);
            setcookie ("usr_cookie_pass", $this->usr_pass, time () + 20);

            if (isset ($_COOKIE["usr_cookie_log"]) && isset ($_COOKIE["usr_cookie_pass"])) {
                return '[Cookie were already sat.]';
            } else {
                return '<a href="index.php" style="color:#CCCCCC">[ Go to home page ]</a>';
            }

        } else {
            return '[Please, enter login and password.]';
        }
    }

    public function logOut () {
        if (isset ($_POST['logOutProfile'])) {
            unset ($_COOKIE['usr_cookie_log']);
            unset ($_COOKIE['usr_cookie_pass']);
            setcookie ("usr_cookie_log", $this->usr_log, time () - 2000);
            setcookie ("usr_cookie_pass", $this->usr_pass, time () - 2000);

            if (empty ($_COOKIE['usr_cookie_log']) && empty ($_COOKIE['usr_cookie_pass'])) {
                exit ("You are log out.");
            }
        }
        echo var_dump ($_COOKIE['usr_cookie_pass']);
    }

    public function getUsrData (): array {#2
        if (isset ($_COOKIE['usr_cookie_log']) && isset ($_COOKIE['usr_cookie_pass'])) {
            $usr_log_cookie = $_COOKIE['usr_cookie_log'];
            $usr_pass_cookie = $_COOKIE['usr_cookie_pass'];
            $this->query = 'SELECT log, pass FROM usrs WHERE log LIKE "'."$usr_log_cookie".'"'.' AND pass LIKE "'."$usr_pass_cookie".'"';

            $stmt = $this->connect_db->prepare ($this->query);

            if (is_object ($stmt)) {
                $stmt->execute ();
            }

            $stmt->bind_result ($this->check_log, $this->check_pass);
            $stmt->fetch ();

            if (is_object ($stmt)) {
                return $this->outputUsrData = [1 => $this->check_log, 2 => $this->check_pass];
            } else {
                return ["1" => "[ Sorry, can't get usr data. ]"];
            }
        } else {
            return ["1" => "[ Sorry, can't find you info into the database. ]"];
        }
    }#workable (don't touch)

    public function checkCookie (): bool {#3
        $this->getUsrData ();

        if ($_COOKIE['usr_cookie_log'] == $this->outputUsrData[1] && $_COOKIE['usr_cookie_pass'] == $this->outputUsrData[2] && $_COOKIE['usr_cookie_log'] !== NULL && $_COOKIE['usr_cookie_pass'] !== NULL) {
            return true;
        } else {
            return false;
        }
    }#workable (don't touch)

    public function outputAccess () {#4
        if ($this->checkCookie () === true) {
            $this->inputDatabase ();
            print_r ($this->outputPugination ());
            $this->outputDatabase ();
        } else {
            print_r ($this->setCookie ());
        }
    }

    public function outputMainForm (): string {#5
        if ($this->checkCookie() === true) {
            $this->getUsrData ();

            $this->output_form = '
                <form action="index.php" method="post" class="MainForm">
                <h2 align="center"><pre>Sending Protected Messages</pre></h2>
                <input type="text" name="nick" id="MainForm_username" value='.$this->outputUsrData[1].' placeholder='.$this->outputUsrData[1].'><br />
                <textarea name="msg" id="MainForm_message" placeholder="message"></textarea><br />
                <input type="submit" value="Forward" name="add" id="MainForm_submit">
                <input type="reset" value="Сancel" id="MainForm_reset">
                <input type="submit" value="Drop Profile" name="dropProfile" id="DropProfile">
                <input type="submit" value="Log Out" name="logOutProfile" id="logOutProfile">
                </form>
            ';
            return $this->output_form;
        } else {
            $this->output_form .= '
                <form action="index.php" method="post" class="MainForm">
                <h2 align="center"><pre>Please LogIn</pre></h2>
                <input type="login" name="usr_log" id="MainForm_username">
                <input type="password" name="usr_pass" id="MainForm_username_pass">
                <input type="submit" name="Continue" value="Continue" id="MainForm_submit">
                <input type="reset" value="Reset" id="MainForm_reset">
                </form>
            ';
            return $this->output_form;
        }
    }

    public function __destruct () {
        if ($this->connect_db) {
            $this->connect_db->close ();
        }
        if ($this->connect_db == true) {
            exit ();
        }
    }
}#workable (don't touch)