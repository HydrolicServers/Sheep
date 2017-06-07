<?php
declare(strict_types = 1);


namespace Sheep;


use Sheep\Async\PMAsyncHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Sheep\Command\CommandManager;
use Sheep\Command\PMCommandProxy;
use Sheep\Source\SourceManager;
use Sheep\Utils\Lockfile;

class SheepPlugin extends PluginBase {
	/** @var Sheep */
	private $api;
	/** @var Config */
	private $cache;
	/** @var SourceManager */
	private $sourceManager;
	/** @var CommandManager */
	private $commandManager;

	public function onEnable() {
		include_once("../../vendor/autoload.php");
		define("Sheep\\PLUGIN_PATH", constant("pocketmine\\PLUGIN_PATH"));

		$asyncHandler = new PMAsyncHandler($this->getServer()->getScheduler());
		$this->api = Sheep::getInstance();
		$this->api->init($asyncHandler, new Lockfile());
		$this->sourceManager = $this->api->getSourceManager();
		$this->commandManager = new CommandManager();

		foreach($this->commandManager->getAll() as $command) {
			$this->getServer()->getCommandMap()->register("sheep", new PMCommandProxy($command));
		}
	}

	public function getSourceManager() {
		return $this->sourceManager;
	}

	public function getGitRevision() {
		$ref = @file_get_contents($this->getFile() . DIRECTORY_SEPARATOR . ".git/HEAD");
		if(!$ref) return "unknown";
		$rev = trim(@file_get_contents($this->getFile() . ".git/" . trim(explode(" ", $ref)[1])));
		return $rev ?: "unknown";
	}

	public function getCache() {
		return $this->cache;
	}

	public function onDisable() {
		//$this->getCache()->save();
	}
}