<?php

/*
 * ___  ___               _ _____            __ _         
 * |  \/  |              (_)  __ \          / _| |        
 * | .  . | __ _ ______ _ _| /  \/_ __ __ _| |_| |_ _   _ 
 * | |\/| |/ _` |_  / _` | | |   | '__/ _` |  _| __| | | |
 * | |  | | (_| |/ / (_| | | \__/\ | | (_| | | | |_| |_| |
 * \_|  |_/\__,_/___\__,_|_|\____/_|  \__,_|_|  \__|\__, |
 *                                                   __/ |
 *                                                  |___/
 * Copyright (C) 2017-2018 @MazaiCrafty (https://twitter.com/MazaiCrafty)
 *
 * This program is free plugin.
 */

namespace jp\mazaicrafty\pmmp\CheckPing;

# Base
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;

# Commands
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecuter;
use pocketmine\command\ConsoleCommandSender;

# Utils
use pocketmine\utils\TextFormat as COLOR;
use pocketmine\utils\Config;

class main extends PluginBase{

    const PREFIX = "§a[§bCheckPing§a]§r ";
    const VERSION = "1.1.0";

    public function onEnable(): void{
        $this->allRegisterEvents();
        $this->enableMessage();
    }

    public function enableMessage(): void{
        Server::getInstance()->getLogger()->info(self::PREFIX . COLOR::YELLOW . "is Enabling!");
        Server::getInstance()->getLogger()->info(self::PREFIX . COLOR::AQUA . "Version " . COLOR::GREEN . self::VERSION);
        Server::getInstance()->getLogger()->info(self::PREFIX . COLOR::GRAY . "https://github.com/MazaiCrafty");
        Server::getInstance()->getLogger()->info(self::PREFIX . COLOR::WHITE . "Thank you for observing the specified license." . COLOR::BLUE . " by @MazaiCrafty");
    }
    
    public function allRegisterEvents(): void{
        if(!file_exists($this->getDataFolder())){
            mkdir($this->getDataFolder(), 0755, true);
        }
        $dir = $this->getDataFolder();
        $this->config = new Config($dir . "Config.yml", Config::YAML, array(
            "message" => "%PLAYER%'s §aPing %PING%ms"
        ));
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
        switch ($cmd->getName()){
            case "ping":
            if (isset($args[0])){
                $name = $args[0];
                $player = $this->getServer()->getPlayer($name);
                $name = $player->getName();
                if ($player === null){
                    $sender->sendMessage("${name}はいない");
                    return true;
                }
                $ping = $player->getPing();
                $str = $this->config->get("message");
                $setPing = str_replace("%PING%", $ping, $str);
                $message = str_replace("%PLAYER%", $name, $setPing);
                $sender->sendMessage($message);
                return true;
            }
            else{
                $ping = $sender->getPing();
                $name = $sender->getName();
                $str = $this->config->get("message");
                $setPing = str_replace("%PING%", $ping, $str);
                $message = str_replace("%PLAYER%", $name, $setPing);
                $sender->sendMessage($message);
                return true;        
            }
        }
        return true;
    }
}
