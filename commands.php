<?php
use GetOpt\GetOpt;
use GetOpt\Option;

$getOpt = new GetOpt();

$getOpt->addOptions([
	Option::create('?', 'help', GetOpt::NO_ARGUMENT)
		->setDescription('Show this help and quit'),
	Option::create('o', 'output', GetOpt::REQUIRED_ARGUMENT)
]);

$getOpt->addCommands([
	\GetOpt\Command::create('token', 'Twitter\\Token::getToken'),
	\GetOpt\Command::create('profile', 'BotsIL\\Profile::get', [
		\GetOpt\Option::create('n', 'name', \GetOpt\GetOpt::REQUIRED_ARGUMENT),
	]),
	\GetOpt\Command::create('favs', 'BotsIL\\Profile::favs', [
		\GetOpt\Option::create('n', 'name', \GetOpt\GetOpt::REQUIRED_ARGUMENT),
	]),
	\GetOpt\Command::create('usage', 'Twitter\\AppStatus::process', []),
]);

// process arguments and catch user errors
try {
	try {
		$getOpt->process();
	} catch (Exception $exception) {
		// catch missing exceptions if help is requested
		if (!$getOpt->getOption('help')) {
			throw $exception;
		}
	}
} catch (Exception $exception) {
	file_put_contents('php://stderr', $exception->getMessage() . PHP_EOL);
	echo PHP_EOL . $getOpt->getHelpText();
	exit;
}

// show help and quit
$command = $getOpt->getCommand();
if (!$command || $getOpt->getOption('help')) {
	echo $getOpt->getHelpText();
	exit;
}