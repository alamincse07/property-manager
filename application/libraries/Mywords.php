<?php
/**
 * Numbers_Words
 *
 * PHP version 4
 *
 * Copyright (c) 1997-2006 The PHP Group
 *
 * This source file is subject to version 3.01 of the PHP license,
 * that is bundled with this package in the file LICENSE, and is
 * available at through the world-wide-web at
 * http://www.php.net/license/3_01.txt
 * If you did not receive a copy of the PHP license and are unable to
 * obtain it through the world-wide-web, please send a note to
 * license@php.net so we can mail you a copy immediately.
 *
 * Authors: Piotr Klaban <makler@man.torun.pl>
 *
 * @category Numbers
 * @package  Numbers_Words
 * @author   Piotr Klaban <makler@man.torun.pl>
 * @license  PHP 3.01 http://www.php.net/license/3_01.txt
 * @version  SVN: $Id$
 * @link     http://pear.php.net/package/Numbers_Words
 */

// {{{ Numbers_Words

/**
 * The Numbers_Words class provides method to convert arabic numerals to words.
 *
 * @category Numbers
 * @package  Numbers_Words
 * @author   Piotr Klaban <makler@man.torun.pl>
 * @license  PHP 3.01 http://www.php.net/license/3_01.txt
 * @link     http://pear.php.net/package/Numbers_Words
 * @since    PHP 4.2.3
 * @access   public
 */
class Mywords
{
    function __construct($class = NULL)
    {
        // include path for Zend Framework
        // alter it accordingly if you have put the 'Zend' folder elsewhere
        ini_set('include_path',
        ini_get('include_path') . PATH_SEPARATOR . APPPATH . '/libraries');

        if ($class)
        {
            require_once (string) $class . EXT;
            log_message('debug', "Words Class $class Loaded");
        }
        else
        {
            log_message('debug', "Words Class Initialized");
        }
    }

    function load($class)
    {
        require_once (string) $class . EXT;
        log_message('debug', "Words Class $class Loaded");
    }
}

// }}}
