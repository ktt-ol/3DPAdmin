<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$_MYSQLI['server'] = "server.server";
$_MYSQLI['username'] = 'user';
$_MYSQLI['database'] = 'database';
$_MYSQLI['password'] = "p455w0rd";
$_MYSQLI['port'] = 3306;


/*
 * Database Structure 
 * 
 * Userbase: 
 *  UID,        INT 5
 *  UN,         VARCHAR 30
 *  PW (enc),   VARCHAR 128   
 *  salt,       VARCHAR 128
 *  active,     INT 1
 *  status,     INT 1
 *  opedby,     VARCHAR 30
 *  created,    DATETIME
 *  lastprint   DATETIME
 * 
 * Login_attempts:
 *  UID,        INT 5
 *  time        DATETIME
 * 
 * History: 
 *  PID,        INT 5
 *  username,   VARCHAR 30
 *  operator,   VARCHAR 30
 *  weight,     INT 5
 *  date-time,  DATETIME
 *  description TEXT 255
 * 
 * Ranks: 
 *  RID,            INT 5
 *  name,           VARCHAR 30
 *  rank,           INT 5
 *  pricepergramm   INT 5
 * 
 * Printer:
 *  PrID,       INT 3
 *  owner,      VARCHAR 30
 *  descripton, VARCHAR 255
 *  status,     INT 1
 *  nozzle,     FLOAT 
 *  defects     TEXT
 *  
 */

define("MYSQLI_HOST", $_MYSQLI['server']);
define("MYSQLI_USER", $_MYSQLI['username']);
define("MYSQLI_BASE", $_MYSQLI['database']);
define("MYSQLI_PASS", $_MYSQLI['password']);
define("MYSQLI_PORT", $_MYSQLI['port']);

define("ADMIN", 10000);
define("OP_OP", 1000);
define("OP", 100);
define("MEMBER", 10);
define("EXTERN", 0);
define("OUTLOOGED", 0);

$mysqli = new mysqli(MYSQLI_HOST, MYSQLI_USER, MYSQLI_PASS, MYSQLI_BASE);
