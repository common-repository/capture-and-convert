<?php
if( ! class_exists('\Ctct\SplClassLoader') )
  require_once('SplClassLoader.php');

// Load the Ctct namespace
$loader = new \Ctct\SplClassLoader('Ctct', dirname(__DIR__));
$loader->register();
