<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CT_Custom
 */
$theme = new Coalition();
$themeData = $theme->getThemeData();
$logo = $theme::maybeNullOrEmpty($themeData, "logo")
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
    <title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
    <meta content="" name="<?php bloginfo('description'); ?>">
	<?php wp_head(); ?>
</head>
<style>
    :root {
        --colorOrange: <?= $theme::maybeNullOrEmpty($themeData, "main-color") ?>;
    }
</style>
<body <?php body_class(); ?>>
<header>
    <div class="band">
        <div class="band-left">CALL US NOW <span><?= $theme::maybeNullOrEmpty($themeData, "phone") ?></span></div>
        <div class="band-right">
            <a href="">LOGIN</a>
            <a href="">SIGN UP</a>
        </div>
    </div>
    <nav>
        <div class="logo">
	        <?php
            if(is_numeric($logo)){
	            echo wp_get_attachment_image($logo, "full");
            }else{
                $fallbackLogo = $theme::maybeNullOrEmpty($themeData, "fallbackLogo");
                if(str_contains($fallbackLogo, "|" )){
                    $split = explode("|", $fallbackLogo);
	                ?>
                    <a href="<?= site_url() ?>""><?= $split[0] ?><span><?= $split[1] ?></span></a>
	                <?php
                }else{
	                ?>
                    <a href="<?= site_url() ?>"><?= $fallbackLogo ?></a>
	                <?php
                }
            }
            ?>

        </div>
        <div class="menu-burger">
            <i class="fa fa-bars"></i>
        </div>
        <div class="desktop-menu">
	        <?php  echo $theme->showMenu() ?>
            <!--<ul>
                <li>
                    <a href="">TITLE 1</a>
                </li>
                <li>
                    <a href="">TITLE 2</a>
                    <ul class="dropdown">
                        <li>
                            <a href="">SUBMENU 1 </a>
                        </li>
                        <li>
                            <a href=""
                            >SUBMENU 2
                                <ul class="sub-dropdown">
                                    <li><a href="">SUBMENU 1</a></li>
                                    <li><a href="">SUBMENU 2</a></li>
                                    <li><a href="">SUBMENU 3</a></li>
                                </ul>
                            </a>
                        </li>
                        <li><a href="">SUBMENU 3</a></li>
                    </ul>
                </li>
                <li>
                    <a href="">TITLE 3</a>
                </li>
                <li>
                    <a href="">TITLE 4</a>
                </li>
                <li>
                    <a href="">TITLE 5</a>
                </li>
                <li>
                    <a href="">TITLE 6</a>
                </li>
            </ul>-->
        </div>
    </nav>
    <ul class="phone-menu">
        <div>
            <span><i class="fa fa-times"></i></span>
        </div>
        <!--<li>
            <a href="">TITLE 1</a>
        </li>
        <li>
            <a href="">TITLE 2</a>
        </li>
        <li>
            <a href="">TITLE 3</a>
        </li>
        <li>
            <a href="">TITLE 4</a>
        </li>
        <li>
            <a href="">TITLE 5</a>
        </li>
        <li>
            <a href="">TITLE 6</a>
        </li>-->
    </ul>
</header>
<section>
      <span class="path">
        <a href="">Home</a>
        <a href="">/ who are we</a>
        <a href="">/ Contact</a>
      </span>