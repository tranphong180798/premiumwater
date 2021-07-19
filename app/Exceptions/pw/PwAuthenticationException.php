<?php
declare(strict_types = 1);

namespace App\Exceptions\pw;

use Exception;
use Throwable;

/**
 * 認証エラー用Exceptionの基底クラス
 */
class PwAuthenticationException extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct("", 0, $previous);
    }
}
