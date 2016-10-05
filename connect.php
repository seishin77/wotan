<?php
require_once 'core/system.php';
core::init();

users::login();

core::redirect();
exit();