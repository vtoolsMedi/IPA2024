<?php
/**
 * Created by PhpStorm.
 * User: peto
 * Date: 30.11.23
 * Time: 14:08
 */

// Bsp Wert -> 'landingModule/v001/view/eventInfos/eventInfos.html.twig' => 1
//$overwrittenTwigIncludePaths = ['baseModule/v001/view/layout/navigation.html.twig' => 1];
$overwrittenTwigIncludePaths = [];
$overwrittenTwigIncludePaths = ['contactModule/v001/view/registerForm.html.twig' => 1,
                                'landingModule/v001/view/landing.html.twig' => 1,
                                'landingModule/v001/view/slider/slider.html.twig' => 1,
                                'landingModule/v001/view/slider/slider.css.twig' => 1,
                                'baseModule/v001/view/layout/base.html.twig' => 1,
                                'landingModule/v001/view/eventInfos/eventInfos.html.twig' => 1,
                                'baseModule/v001/view/mail/mailTemplate.html.twig' => 1];
//$overwrittenTwigIncludePaths = ['contactModule/v001/model/form/ContactForm.php' => 1];
define('OVERWRITTEN_TWIG_INCLUDE_PATHS', $overwrittenTwigIncludePaths);


// muss ein Array sein, wenn es nicht gebraucht wird, dann mit null initialisieren.
$entityPaths = ['src/appl/module/modules/contactModule/v001/model/entity/'];
//$entityPaths = [];
define('ENTITY_PATHS', $entityPaths);
