<?php
/**
 * Panada Installation Check
 *
 * Checking all minimum requirements
 *
 * @since	Version 0.4.1
 * @author	Mulia Arifandy Nasution <https://github.com/mul14>
 */

define('INDEX_FILE', basename(__FILE__));
define('GEAR', '../panada/');
define('DS', DIRECTORY_SEPARATOR);
define('THISPATH', dirname(__FILE__));
require_once THISPATH . DS . 'config/main.php';
//require_once THISPATH . DS . '../panada' . DS . 'variable' . DS . 'version.php';
?>
<!DOCTYPE html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Panada Installation Check</title>
    <link rel="stylesheet" href="assets/css/main.css" type="text/css" media="screen" />
    <style type="text/css">
    .pass {
        color: #191;
        font-weight: bold;
    }
    .pass:before {
        content: '✔ ';
    }
    .fail {
        color: #911;
        font-weight: bold;
    }
    .fail:before {
        content: '✘ ';
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    tr:nth-child(odd) {
        background: #dfdfdf;
    }
    tr:nth-child(even) {
        background: #fcfcfc;
    }
    td {
        padding: 4px 8px;
        font-we
    }
    td { 
        width: 25%; 
    }
    td+td{ 
        width: 65%;
    }
    td+td+td{ 
        width: 10%;
    }
    .monospace {
        font: 14px Consolas,Courier New,Verdana;
    }
    .box {
        font-size: 16px;
        text-align: center;
        background: #191;
        padding: 8px 0;
        color: #fff;
        text-shadow: 1px 1px 0 #333;
    }
    </style>

</head>
<body>
    <h1 class="logo"><img alt="Logo" src="assets/img/logo.png" /></h1> 
    <h1>Installation Check</h1>
    <h2>Minimum Requirements</h2>
    <?php $failed = FALSE ?>

    <table>
        <tr>
            <td>
                PHP Version
            </td>
            <td>
                <?php echo PHP_VERSION ?>
            </td>
            <td>
                <?php if (version_compare(PHP_VERSION, '5.3.0', '>=')): ?>
                    <span class="pass">PASS</span>
                <?php else: $failed = TRUE ?>
                    <span class="fail">FAIL</span>
                <?php endif ?>
            </td>
        </tr>
        
        <tr>
            <td>
                System Directory
            </td>
            
            <?php if (is_dir(THISPATH . DS . '../panada') AND is_file(THISPATH . DS . '../panada' . DS . 'Gear.php')): ?>
                <td><span class="monospace"><?php echo THISPATH . DS . 'panada' ?></span></td>
                <td><span class="pass">PASS</span></td>
            <?php else: $failed = TRUE ?>
                <td>&nbsp;</td>
                <td><span class="fail">FAIL</span></td>
            <?php endif ?>
            
        </tr>
        
        <tr>
            <td>
                Application Directory
            </td>
            <?php if (is_dir(THISPATH . DS) AND is_file(THISPATH . DS . 'config' . DS . 'main.php')): ?>
                <td><span class="monospace"><?php echo THISPATH . DS ?></td>
                <td><span class="pass">PASS</span></td>
            <?php else: $failed = TRUE ?>
                <td>&nbsp;</td>
                <td><span class="fail">FAIL</span></td>
            <?php endif ?>
        </tr>
    </table>
	
    <?php if ($failed === TRUE): ?>
        <p class="box fail">Panada may not work correctly with your environment</p>
    <?php else: ?>
        <p class="box pass">Your environment passed all requirements</p>
    <?php endif ?>
    
    <h2>Optional</h2>
    
    <h3>Database</h3>
    
    <table>
        <tr>
            <td>
                MySQL
            </td>
            <?php if (function_exists('mysql_connect')): ?>
                <td><?php echo mysql_get_client_info() ?></td>
                <td><span class="pass">PASS</span></td>
            <?php else: ?>
                <td>&nbsp;</td>
                <td><span class="fail">FAIL</span></td>
            <?php endif ?>
        </tr>
        
        <tr>
            <td>
                PostgreSQL
            </td>
            <?php if (function_exists('pg_connect')): ?>
                <td>&nbsp;</td>
                <td><span class="pass">PASS</span></td>
            <?php else: ?>
                <td>&nbsp;</td>
                <td><span class="fail">FAIL</span></td>
            <?php endif ?>
        </tr>
        
        <tr>
            <td>SQLite</td>
            <?php if (function_exists('sqlite_open')): ?>
                <td><?php echo sqlite_libversion(); ?></td>
                <td><span class="pass">PASS</span></td>
            <?php else: ?>
                <td>&nbsp;</td>
                <td><span class="fail">FAIL</span></td>
            <?php endif ?>
        </tr>
        
        <tr>
            <td>MongoDB</td>
            <?php if (class_exists('Mongo')): ?>
                <td>&nbsp;</td>
                <td><span class="pass">PASS</span></td>
            <?php else: ?>
                <td>&nbsp;</td>
                <td><span class="fail">FAIL</span></td>
            <?php endif ?>
        </tr>
    </table>
    
    <h3>Cache</h3>
    
    <table>
        <tr>
            <td>APC</td>
            <?php if (extension_loaded('apc')): ?>
                <td>&nbsp;</td>
                <td><span class="pass">PASS</span></td>
            <?php else: ?>
                <td>&nbsp;</td>
                <td><span class="fail">FAIL</span></td>
            <?php endif ?>
        </tr>
        
        <tr>
            <td>Memcache</td>
            <?php if (extension_loaded('memcache')): ?>
                <td>&nbsp;</td>
                <td><span class="pass">PASS</span></td>
            <?php else: ?>
                <td>&nbsp;</td>
                <td><span class="fail">FAIL</span></td>
            <?php endif ?>
        </tr>
        
        <tr>
            <td>Memcached</td>
            <?php if (extension_loaded('memcached')): ?>
                <td>&nbsp;</td>
                <td><span class="pass">PASS</span></td>
            <?php else: ?>
                <td>&nbsp;</td>
                <td><span class="fail">FAIL</span></td>
            <?php endif ?>
        </tr>
    </table>
</body>
</html>
