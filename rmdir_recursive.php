function rmdir_recursive($dir, $verbose=false) {


    $test_lock = false;


    //check.1
    if (substr($dir, -1) == "/") $dir = substr_replace($dir, "", -1);  //remo last slash char
    if ($dir == "" || !$dir) return false;


    //check.2
    $dir = str_replace($_SERVER["DOCUMENT_ROOT"], "", $dir);

    if ($dir == "/") return false;
    if ($dir == "//") return false;
    if ($dir == ".") return false;
    if ($dir == "..") return false;
    if ($dir == "www") return false;
    if ($dir == "/www") return false;


    //check.3
    $dir = $_SERVER["DOCUMENT_ROOT"].$dir;
    $root_dir_arr = scandir($_SERVER["DOCUMENT_ROOT"]);
    foreach($root_dir_arr as $root_file) {
        if ($verbose > 1) echo $dir. " : ".$_SERVER["DOCUMENT_ROOT"]."/".$root_file."\n";
        if ($dir == $_SERVER["DOCUMENT_ROOT"]."/".$root_file) return false;
    }



    //Real Remove

    if ($verbose) echo "\n[dir : ".$dir."]\n\n";

    if (is_file($dir) && !$test_lock) unlink($dir);

    if (is_dir($dir)) {
    
        $tmp = scandir($dir);
        foreach ($tmp as $key => $file) {
    
            if ($file == "." || $file == "..") continue;
    
            $dir_file = $dir."/".$file;
            
            if (is_dir($dir_file)) {
                rmdir_recursive($dir_file, $verbose);
            }
    
            if (is_file($dir_file)) {
                if ($verbose) echo "unlink ".$dir_file."\n";
                if (!$test_lock) unlink($dir_file);
            }
    
            if ($verbose) ob_flush();
    
        }
    
    
        if ($verbose) echo "rmdir ".$dir."\n";
        if (!$test_lock) rmdir($dir);
    
    }

    if ($verbose) ob_flush();


}
