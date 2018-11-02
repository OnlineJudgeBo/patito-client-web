<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo system("ssh root@localhost");
echo system("sudo -u root -S /sbin/reboot < secret");
