<?php
require 'config.php';
require 'functions.php';

//if (login_check($mysqli) != true){   $url = "Location: /index.php?s=e403";header($url);} 
//if (chk_rights($mysqli,OP_OP) != true){$url = "Location: /index.php?s=e403";header($url);}
/* 
 * The MIT License
 *
 * Copyright 2018 Arne Hude.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
$error_msg = '';

if (isset($_POST['groupname'], $_POST['rank'], $_POST['ppg'])) {
    $groupname = filter_input(INPUT_POST, 'groupname', FILTER_SANITIZE_STRING);
    $rank = filter_input(INPUT_POST, 'rank', FILTER_SANITIZE_NUMBER_INT);
    $ppg = filter_input(INPUT_POST, 'ppg', FILTER_SANITIZE_NUMBER_INT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $prep_stmt = "SELECT RID FROM ranks WHERE name  = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('s', $groupname);
        $stmt->execute();
        $stmt->store_result(); 
        if ($stmt->num_rows >= 1) {
            $error_msg .= 'There is already a Group with the name '. $groupname;
        }
    } else {
        $error_msg .= 'Database error';
    }    
    if (empty($error_msg)) {
        if ($insert_stmt = $mysqli->prepare(
                "INSERT INTO ranks "
                . "(name, rank, pricepergramm, description) "
                . "VALUES (?, ?, ?, ?)")) {
             
            $insert_stmt->bind_param('siis', $groupname, $rank, $ppg, $description);
            if (!$insert_stmt->execute()) {
                header('Location: /index.php?s=manageop&err=Registration failure: INSERT');
            }
        }
        echo 'ok';
        header('Location: /index.php?s=manageop&success=newgroup');
    }
    else{
        $url = "Location: /index.php?s=manageop&err=$error_msg";
        header($url);
    }
}