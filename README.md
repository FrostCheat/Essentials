<p align="center">
  <img src="https://img.shields.io/badge/plugin-Essentials-blueviolet?style=for-the-badge">
  <br><br>
  <a href="https://paypal.me/FrostCheatMC?country.x=CO&locale.x=es_XC">
    <img src="https://img.shields.io/badge/donate-paypal-ff69b4?style=for-the-badge&logo=paypal">
  </a>
  <a href="https://discord.gg/k8X7CG2kFv">
    <img src="https://img.shields.io/discord/1384337463971020911?style=for-the-badge&logo=discord&logoColor=white&logoSize=12&color=blue">  
  </a>
  <a href="https://poggit.pmmp.io/ci/FrostCheat/Essentials/Essentials">
    <img src="https://poggit.pmmp.io/ci.shield/FrostCheat/Essentials/Essentials?style=for-the-badge">
  </a>
  <a href="https://poggit.pmmp.io/p/Essentials">
    <img src="https://poggit.pmmp.io/shield.downloads/Essentials?style=for-the-badge">
  </a>
</p>

# 🛠 Essentials

**Essentials** is a powerful all-in-one PocketMine-MP plugin that provides essential commands, moderation tools, utilities, and developer events for Minecraft Bedrock servers.

---

## ✨ Features

- ✅ Full teleportation system (`/tpa`, `/back`, `/tpahere`, etc.)
- 💬 Private messaging with `/tell` and `/reply`
- 🔄 Gamemode and fly toggles
- 🧑‍⚕️ Heal, feed, milk and godmode commands
- 🔧 Item repairing system (`/fix hand` / `/fix all`)
- 🔍 Ping, near, vanish, and nick customization
- 🔥 Burn, clear inventory, and broadcast support
- 🌲 Tree spawning command
- 🔐 Extensive permissions per command and target
- 🧩 Events API for developers to hook into actions
- 🧼 Clean, modular, and extensible code structure

---

## 🔧 Installation

1. Download the `.phar` file from [Poggit](https://poggit.pmmp.io/p/Essentials)
2. Place it into your `/plugins` folder.
3. Restart the server.
4. You're ready to go!

---

## 📜 Commands, Permissions & Descriptions

| Command                 | Permission                                 | Description                                 |
|-------------------------|---------------------------------------------|---------------------------------------------|
| `/help`                | `essentials.command.help`                  | Shows available commands                    |
| `/list`                | `essentials.command.list`                  | Lists online players                        |
| `/reload`              | `essentials.command.reload`                | Reloads plugin data                         |
| `/save`                | `essentials.command.save`                  | Saves the world manually                    |
| `/back`                | `essentials.command.back`                  | Teleports to the previous location          |
| `/broadcast`           | `essentials.command.broadcast`             | Sends a global server message               |
| `/burn`                | `essentials.command.burn`                  | Sets you on fire                            |
| `/burn <player>`       | `essentials.command.burn.other`            | Sets other player on fire                   |
| `/clear`               | `essentials.command.clear`                 | Clears your inventory                       |
| `/clear <player>`      | `essentials.command.clear.other`           | Clears inventory of another player          |
| `/feed`                | `essentials.command.feed`                  | Restores hunger                             |
| `/feed <player>`       | `essentials.command.feed.other`            | Feeds another player                        |
| `/fix`                 | `essentials.command.fix`                   | Base fix command                            |
| `/fix all`             | `essentials.command.fix.all`               | Repairs all inventory items                 |
| `/fix all <player>`    | `essentials.command.fix.all.other`         | Repairs all items of another player         |
| `/fix hand`            | `essentials.command.fix.hand`              | Repairs item in hand                        |
| `/fix hand <player>`   | `essentials.command.fix.hand.other`        | Repairs hand item of another player         |
| `/fly`                 | `essentials.command.fly`                   | Toggles flight                              |
| `/fly <player>`        | `essentials.command.fly.other`             | Toggles flight for other player             |
| `/gamemode`            | `essentials.command.gamemode`              | Changes your gamemode                       |
| `/gamemode <player>`   | `essentials.command.gamemode.other`        | Changes gamemode for other player           |
| `/god`                 | `essentials.command.god`                   | Enables god mode                            |
| `/god <player>`        | `essentials.command.god.other`             | Enables god mode for other player           |
| `/heal`                | `essentials.command.heal`                  | Heals yourself                              |
| `/heal <player>`       | `essentials.command.heal.other`            | Heals another player                        |
| `/milk`                | `essentials.command.milk`                  | Removes potion effects                      |
| `/milk <player>`       | `essentials.command.milk.other`            | Removes effects from another player         |
| `/near`                | `essentials.command.near`                  | Shows nearby players                        |
| `/nick`                | `essentials.command.nick`                  | Changes your nickname                       |
| `/nick <player>`       | `essentials.command.nick.other`            | Changes another player’s nickname           |
| `/nick reset`          | `essentials.command.nick.reset`            | Resets your nickname                        |
| `/nick reset <player>` | `essentials.command.nick.reset.other`      | Resets another player’s nickname            |
| `/ping`                | `essentials.command.ping`                  | Displays your ping                          |
| `/ping <player>`       | `essentials.command.ping.other`            | Shows ping of another player                |
| `/reply <message>`               | `essentials.command.reply`                 | Replies to the last private message         |
| `/tell <player>`       | `essentials.command.tell`                  | Sends a private message                     |
| `/tpa <player>`        | `essentials.command.tpa`                   | Sends teleport request                      |
| `/tpahere <player>`    | `essentials.command.tpahere`               | Sends teleport-here request                 |
| `/tpaccept`            | `essentials.command.tpaccept`              | Accepts teleport request                    |
| `/tpdeny`              | `essentials.command.tpdeny`                | Denies teleport request                     |
| `/tree`                | `essentials.command.tree`                  | Spawns a tree near you                      |
| `/vanish`              | `essentials.command.vanish`                | Makes you invisible                         |
| `/vanish <player>`     | `essentials.command.vanish.other`          | Vanishes another player                     |

---

## 🧷 Other Permissions

| Permission                  | Description                                      |
|-----------------------------|--------------------------------------------------|
| `essentials.command.back.bypass`     | Allows bypassing teleport-back cooldown        |
| `essentials.command.feed.bypass`     | Allows bypassing feed cooldown               |
| `essentials.command.heal.bypass`     | Allows bypassing heal cooldown               |
| `essentials.command.milk.bypass`     | Allows bypassing milk (cleanse) cooldown     |
| `essentials.tell.see`                | Allows staff to see all private messages         |
| `essentials.vanish.see`              | Allows staff to see vanished players             |

---

## 🧑‍💻 Developer Events

These custom events allow developers to extend or react to Essentials' actions:

- `PlayerBackEvent`
- `PlayerBurnEvent`
- `PlayerChangeFlyEvent`
- `PlayerChangeGodEvent`
- `PlayerChangeNickEvent`
- `PlayerChangeVanishEvent`
- `PlayerClearInventoryEvent`
- `PlayerFeedEvent`
- `PlayerFixEvent`
- `PlayerHealEvent`
- `PlayerMilkEvent`
- `PlayerPrivateMessageEvent`
- `ServerBroadcastEvent`
- `SpawnTreeEvent`
- `TeleportRequestEvent`
- `TeleportRespondEvent`

Register them just like any standard PocketMine event.

---

## ☕ Support & Donate

If this plugin has helped you, consider supporting its development:

> 💖 [Donate via PayPal](https://paypal.me/FrostCheatMC?country.x=CO&locale.x=es_XC)

Your support is greatly appreciated!

---

<p align="center"><b>Made with 💙 by FrostCheatMC</b></p>