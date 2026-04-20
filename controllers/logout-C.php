<?php
session_start();
session_destroy();

header("Location: /M-Motors/public/index.php?page=login");
exit;