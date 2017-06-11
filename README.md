# PHP Sokoban game

This classic game was created as a hobby project with the aim of making a solver
algorithm against it.

![Level 1 screen shot](https://image.ibb.co/n9ZpyF/Workspace_1_056.png)

## Requirements
 - Unix OS - The only currently available rendering driver is for Unix console.
 - PHP-CLI 5.4+
 - PHP Composer utility installed.

## Setup

Checkout the repository and run ```composer install``` in the root directory.

## Playing the game.

```sh
# Play the default level 1.
php play.php 

# Play explicit level, from the ones available.
php play.php 1

```

## Controlls

The player is controlled with the arrow keys. Reversing moves are is through
the ```r``` key.

## Levels

The game levels are plain text files, located under the ```levels``` directory.
They should be named in the format [level-number].txt. For example: ```1.txt```.
Level files are following this [format](http://sokobano.de/wiki/index.php?title=Level_format#Level).

Current levels were taken from the following public repository:
 - https://github.com/leoliu/sokoban

### Replays / Solutions

The engine can replay solutions stored in the [standard format](http://sokobano.de/wiki/index.php?title=Level_format#Solution).

Successfully completed levels have replays stored for them.

Replay files are stored in the directory ```replays```. Replay file names
should follow this convention "level-1-day-time.txt". The string `level` is a
static prefix. Character `-` is used as separator. The value `1` in the example
means the level that the solution is associated with.

```sh
php replay.php replays/level-1-sample.txt
```
