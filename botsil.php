#!/usr/bin/php
<?php

use \BotsIL\CLI;

require __DIR__ . DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

try {
	$dotenv = new Dotenv\Dotenv(__DIR__ . DIRECTORY_SEPARATOR);
	$dotenv->load();
	$dotenv->required(['CONSUMER_KEY', 'CONSUMER_SECRET']);
} catch (Exception $e) {
	CLI::critical("Error in .env file: ".$e->getMessage());
	die();
}

require_once __DIR__.DIRECTORY_SEPARATOR."commands.php";
if (file_exists(__DIR__.DIRECTORY_SEPARATOR."botsil-commands.php"))
	require_once __DIR__.DIRECTORY_SEPARATOR."botsil-commands.php";

$logo=<<<LOGO
.______     ______   .___________.    _______.        __   __      
|   _  \   /  __  \  |           |   /       |       |  | |  |     
|  |_)  | |  |  |  | `---|  |----`  |   (----` ______|  | |  |     
|   _  <  |  |  |  |     |  |        \   \    |______|  | |  |     
|  |_)  | |  `--'  |     |  |    .----)   |          |  | |  `----.
|______/   \______/      |__|    |_______/           |__| |_______|
                                                                   
LOGO;

CLI::out($logo, CLI::C_LIGHTPURPLE);

call_user_func($command->getHandler(), $getOpt);