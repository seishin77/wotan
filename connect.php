<?php
session_start();
require_once 'core/system.php';

core::init();

users::login();

core::redirect();
exit();