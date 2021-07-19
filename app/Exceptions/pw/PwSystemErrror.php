<?php
declare(strict_types=1);

namespace App\Exceptions\pw;

/**
 * 認証エラー用Exception
 * - 発生条件 : IDに合致するユーザー情報が複数見つかった
 */
class PwSystemErrror extends PwAuthenticationException
{
}
