<?php
    header('Access-Control-Allow-Origin: *');
    session_start();
    require_once('connection.php');
    require_once("twitteroauth.php");

    function retrieve_tweets($twitteruser, $id)
    {
        $notweets          = 1000; 
        $consumerkey       = ""; 
        $consumersecret    = ""; 
        $accesstoken       = ""; 
        $accesstokensecret = ""; 

        function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
          $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
          return $connection;
        }

        $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
        $tweets     = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);

        global $pdo;

        try{
            foreach ($tweets as $tweet)
            {
                $date = date_create_from_format('U', strtotime($tweet->created_at));
                $date_tweet = date_format($date, 'Y-m-d H:i:s');
                $query = $pdo->prepare("INSERT IGNORE INTO tweets(tweet_text, tweet_date, tweet_twitterId) VALUES (?, ?, ?) ");
                $query->bindValue(1, $tweet->text);
                $query->bindValue(2, $date_tweet);               
                $query->bindValue(3, $tweet->id_str);   
                $query->execute();

                $lastTweetId = $pdo->lastInsertId();

                $query = $pdo->prepare("SELECT * FROM user_tweets WHERE user_id = ? AND tweet_id = ?");
                $query->bindValue(1, $id);                 
                $query->bindValue(2, $lastTweetId);
                $query->execute();  

                if($query->rowCount() == 0)
                {
                    $query = $pdo->prepare("INSERT INTO user_tweets(user_id, tweet_id) VALUES (?, ?) ");
                    $query->bindValue(1, $id);                 
                    $query->bindValue(2, $lastTweetId);
                    $query->execute();            
                }
            }


        }catch(PDOException $e){
            return 'Exception Handled';
        }
        
    }


    function login($email, $password)
    {
        global $pdo;

        try{
            $query = $pdo->prepare("SELECT * FROM user WHERE email = ? AND password = ?");
            $query->bindValue(1, $email);
            $query->bindValue(2, $password);
            $query->execute();

            $num     = $query->rowCount();
            $result  = $query->fetchAll();

            if($num == 1)
            {  
                $_SESSION['logged_in'] = true;
                $_SESSION['user']      = $result[0];
                $result[0]["response"] = "true";

                retrieve_tweets($result[0]['twitter_account'], $result[0]['id']);

                return $result[0];
            }

            else
            {
                $_SESSION['logged_in'] = false;
                $result["response"] = "false";
                return $result;
            }

        }catch(PDOException $e){
            return 'loginIncorrect';
        }
    }


    if(isset($_POST['loginFormEmail']))
    {
        $email    = $_POST['loginFormEmail'];
        $password = $_POST['loginFormPassword'];
        $auth     = login($email, $password);

        echo json_encode($auth);
    }  

    function get_tweets($id)
    {
        global $pdo;

        try{
            $query = $pdo->prepare("SELECT * FROM user_tweets INNER JOIN tweets ON user_tweets.tweet_id = tweets.id WHERE user_tweets.user_id = ? ORDER BY tweet_date DESC");
            $query->bindValue(1, $id);
            $query->execute();

            $num     = $query->rowCount();
            $result  = $query->fetchAll();

            if($num > 0) 
               echo json_encode($result);

            else
                echo "Empty";


        }catch(PDOException $e){
            return 'error';
        }    
    }

    function get_passwords($id)
    {
        global $pdo;

        try{
            $query = $pdo->prepare("SELECT * FROM user_passwords INNER JOIN passwords ON user_passwords.password_id = passwords.id WHERE user_passwords.user_id = ? ");
            $query->bindValue(1, $id);
            $query->execute();

            $num     = $query->rowCount();
            $result  = $query->fetchAll();

            if($num > 0)
               echo json_encode($result);

            else
                echo "Empty";
        

        }catch(PDOException $e){
            return 'error';
        }    
    }

    if(isset($_GET['app']))
    {
        if($_GET['app'] == "twitter")
            get_tweets($_GET['id']);
       
        else if($_GET['app'] == "passwords")
            get_passwords($_GET['id']);
       
        //if($_POST['app'] == "texts")
           // get_texts();
    } 

    if(isset($_GET['logout']))
    {
        session_destroy();
    }  

    if(isset($_POST['createAccount']))
    {
        $firstName  = $_POST['nameProfile'];
        $middleName = $_POST['middleNameProfile'];
        $lastName   = $_POST['lastNameProfile'];
        $email      = $_POST['emailProfile'];
        $twitter    = $_POST['twitterProfile'];
        $password   = $_POST['password1Profile'];

        global $pdo;

        try{
            $query = $pdo->prepare("INSERT INTO user(first_name, middle_name, last_name, email, password, twitter_account) VALUES (?, ?, ?, ?, ?, ?) ");
            $query->bindValue(1, $firstName);
            $query->bindValue(2, $middleName);               
            $query->bindValue(3, $lastName);  
            $query->bindValue(4, $email);
            $query->bindValue(5, $password);               
            $query->bindValue(6, $twitter);  
            $query->execute();

            echo "true";        

        }catch(PDOException $e){
            return 'Exception Handled';
        }       
    }

    if(isset($_POST['updateAccount']))
    {
        $firstName  = $_POST['nameProfile'];
        $middleName = $_POST['middleNameProfile'];
        $lastName   = $_POST['lastNameProfile'];
        $email      = $_POST['emailProfile'];
        $twitter    = $_POST['twitterProfile'];
        $password   = $_POST['password1Profile'];
        $id         = $_POST['id'];

        global $pdo;

        try{

            $query = $pdo->prepare("UPDATE user SET first_name = ?, middle_name = ?, last_name = ?, email = ?, password = ?, twitter_account = ? WHERE id = ? ");
            $query->bindValue(1, $firstName);
            $query->bindValue(2, $middleName);               
            $query->bindValue(3, $lastName);  
            $query->bindValue(4, $email);
            $query->bindValue(5, $password);               
            $query->bindValue(6, $twitter);  
            $query->bindValue(7, $id);  
            $query->execute();

            echo "true";        

        }catch(PDOException $e){
            return 'Exception Handled';
        }     
    }

    if(isset($_POST['websitePassword']))
    {
        $website  = $_POST['websitePassword'];
        $login    = $_POST['loginPassword'];
        $password = $_POST['passwordPassword'];
        $id       = $_POST['id'];

        global $pdo;

        try{
            $query = $pdo->prepare("INSERT INTO passwords(password_website, password_login, password_pass) VALUES (?, ?, ?) ");
            $query->bindValue(1, $website);
            $query->bindValue(2, $login);               
            $query->bindValue(3, $password);   
            $query->execute();

            $lastPassId = $pdo->lastInsertId();

            $query = $pdo->prepare("INSERT INTO user_passwords(password_id, user_id) VALUES (?, ?) ");
            $query->bindValue(1, $lastPassId);
            $query->bindValue(2, $id);                 
            $query->execute();    

            echo "true";  

        }catch(PDOException $e){
            echo 'Exception Handled';
        }       
    }
?>