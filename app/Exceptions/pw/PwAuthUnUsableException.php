<?php
declare(strict_types = 1);

namespace App\Exceptions\pw;

/**
 * 認証エラー用Exception
 * - 発生条件 : 利用権限がない
 */
class PwAuthUnUsableException extends PwAuthenticationException
{
}
