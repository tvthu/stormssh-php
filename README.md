<h1 align="center">PHP Storm SSH</h1>

<p align="center">PHP Storm SSH tool</p>

<p align="center">
    <img alt="Preview" src="/art/preview.png">
	<p align="center">
		<a href="//packagist.org/packages/tvthu/stormssh-php"><img alt="Latest Stable Version" src="https://poser.pugx.org/tvthu/stormssh-php/v"></a>
		<a href="//packagist.org/packages/tvthu/stormssh-php"><img alt="License" src="https://poser.pugx.org/tvthu/stormssh-php/license"></a>
	</p>
</p>

## Instal

This CLI application is a small game written in PHP and is installed using [Composer](https://getcomposer.org):

```
composer global require tvthu/stormssh-php
```

Make sure the `~/.composer/vendor/bin` directory is in your system's `PATH`.

<details>
<summary>Show me how</summary>

If it's not already there, add the following line to your Bash configuration file (usually `~/.bash_profile`, `~/.bashrc`, `~/.zshrc`, etc.):

```
export PATH=~/.composer/vendor/bin:$PATH
```

If the file doesn't exist, create it.

Run the following command on the file you've just updated for the change to take effect:

```
source ~/.bash_profile
```
</details>

## Use

All you need to do is call the `play` command to start the game:

```
storm play
```

## Update

```
composer global update tvthu/stormssh-php
```

## Delete

```
composer global remove tvthu/stormssh-php
```
