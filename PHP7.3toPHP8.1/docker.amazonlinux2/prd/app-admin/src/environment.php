<?php

declare(strict_types=1);

// CakePHPのバージョンでファイルを使い分ける
// CakePHP2: operation_mode.php
// CakePHP4: enviroenment.php

use Cake\Core\Configure;

/**
 * サーバ環境(development/staging/private/production)
 */
Configure::write('Environment', 'production');
