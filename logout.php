<?php
    session_start();
    
    if (isset($_COOKIE[session_name()])) {  
        setcookie(session_name(), '', time()-42000, '/');   
    }
    //kill session
    session_destroy();
    //send to index.php with added entity on the url to show logout
    header('Location: index.php?logout=true');
?>