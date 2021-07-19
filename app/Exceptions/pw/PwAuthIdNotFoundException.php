<?php
declare(strict_types = 1);

namespace App\Exceptions\pw;

/**
 * 認証エラー用Exception
 * - 発生条件 : IDに合致するユーザー情報が見つからなかった
 */
class PwAuthIdNotFoundException extends PwAuthenticationException
{
}
