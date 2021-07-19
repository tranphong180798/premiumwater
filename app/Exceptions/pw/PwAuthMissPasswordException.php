<?php
declare(strict_types = 1);

namespace App\Exceptions\pw;

/**
 * 認証エラー用Exception
 * - 発生条件 : パスワードが一致しない
 */
class PwAuthMissPasswordException extends PwAuthenticationException
{
}
