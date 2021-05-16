<?php

/**
 * Script will check if your server can run QALAR
 */

function extension_check($extensions)
{
    $fail = '';
    $pass = '';

    if (version_compare(phpversion(), '7.1.3', '<')) {
        $fail .= '<li>You need<strong> PHP 7.1.3+</strong></li>';
    } else {
        $pass .='<li>You have<strong> PHP '.phpversion().'</strong></li>';
    }

    foreach ($extensions as $extension) {
        if (!extension_loaded($extension)) {
            $fail .= '<li> You are missing the <strong>'.$extension.'</strong> extension</li>';
        } else {
            $pass .= '<li>You have the <strong>'.$extension.'</strong> extension</li>';
        }
    }

    if ($fail) {
        echo '<p><strong>Your server does not meet the following requirements in order to install QALAR.</strong>';
        echo '<br>The following requirements failed, please contact your hosting provider in order to receive assistance with meeting the system requirements for QALAR:';
        echo '<ul>'.$fail.'</ul></p>';
        echo 'The following requirements were successfully met:';
        echo '<ul>'.$pass.'</ul>';
    } else {
        echo '<p><strong>Congratulations!</strong> Your server meets the requirements for QALAR.</p>';
        echo '<ul>'.$pass.'</ul>';
    }
}

extension_check(array(
    'BCMath',
    'Ctype',
    'JSON',
    'Mbstring',
    'OpenSSL',
    'PDO',
    'Tokenizer',
    'XML',
    'curl',
    'dom',
    'gd',
    'hash',
    'iconv',
    'mcrypt',
    'pcre',
));
