<?php
require_once 'core/system.php';
core::init();

core::init();

users::logout();

core::redirect();
exit();