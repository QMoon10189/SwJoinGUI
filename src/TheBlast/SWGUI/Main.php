<?php

namespace TheBlast\SWGUI;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

	public function onEnable(){
		$this->getLogger()->info("Sw Join Gui Enabled made by TheBlast");
		if(!InvMenuHandler::isRegistered()){
			InvMenuHandler::register($this);
		}
		$command = new PluginCommand("swgui", $this);
		$command->setDescription("SwGui command");
		$this->getServer()->getCommandMap()->register("sw", $command);
	}

	public function onDisable(){
		$this->getLogger()->info("Sw Join Gui disabled made by TheBlast");
	}

	public function onCommand(CommandSender $player, Command $cmd, string $label, array $args) : bool{
		switch($cmd->getName()){
			case "swgui":
				if(!$player instanceof Player){
					$player->sendMessage("Youve been transfered");
					return true;
				}
				$this->tgui($player);
				break;
		}
		return true;
	}

	public function swgui(Player $player){
		$menu = InvMenu::create(InvMenu::TYPE_CHEST);
		$menu->readOnly();
		$menu->setListener(\Closure::fromCallable([$this, "GUIListener"]));
		$menu->setName("Skywars Games");
		$menu->send($player);
		$inv = $menu->getInventory();
		$feather = Item::get(Item::FEATHER)->setCustomName("Graveyard");
		$stone = Item::get(Item::STONE)->setCustomName("Coven");
		$inv->setItem(1, $feather);
		$inv->setItem(2, $stone);
	}

	public function GUIListener(InvMenuTransaction $action) : InvMenuTransactionResult{
		$itemClicked = $action->getOut();
		$player = $action->getPlayer();
		if($itemClicked->getId() == 288){
            $action->getAction()->getInventory()->onClose($player);
            $player->sendMessage("Skywars");
            \pocketmine\Server::getInstance()->dispatchCommand($player, "say no gamemodes available yet");
            return $action->discard();
}
		if($itemClicked->getId() == 1){
            $action->getAction()->getInventory()->onClose($player);
            $player->sendMessage("Skywars");
            \pocketmine\Server::getInstance()->dispatchCommand($player, "sw join Graveyard");
            return $action->discard();
}
		return $action->discard();
	}
}
