<?php
declare(strict_types=1);


namespace Sheep\Command;

use pocketmine\command\Command as PMCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use Sheep\Command\Problem\PMProblem;

class PMCommandProxy extends PMCommand {
	private $command;

	public function __construct(Command $command) {
		$this->command = $command;
		parent::__construct($command->getName(), $command->getDescription(), $command->getUsage());
	}
}
