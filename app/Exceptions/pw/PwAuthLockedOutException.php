<?php
declare(strict_types = 1);

namespace App\Exceptions\pw;

use Throwable;

/**
 * 認証エラー用Exception
 *
 * - 発生条件 : ロックアウトしている
 */
class PwAuthLockedOutException extends PwAuthenticationException
{
    // true:既にロックアウトしている、false:今回の認証でロックアウトした
    private bool $isAlredyLockedOut = false;
    // ログインに失敗した数
    private int $loginFailTimes;
    // ロックアウト発生時のメール送信に成功したかどうか
    private bool $isSuccessfullySendMail = true;

    /**
     * コンストラクタ
     *
     * @param bool $isAlredyLockedOut ロックアウト発生タイミング
     * @param bool $isSuccessfullySendMail ロックアウト発生時のメール送信に成功したかどうか
     * @param int $loginFailTimes　ログインに失敗した数
     * @param Throwable $previous
     */
    public function __construct(bool $isAlredyLockedOut, bool $isSuccessfullySendMail = true, int $loginFailTimes = 0, Throwable $previous = null)
    {
        parent::__construct($previous);

        $this->isAlredyLockedOut = $isAlredyLockedOut;
        $this->isSuccessfullySendMail = $isSuccessfullySendMail;
        $this->loginFailTimes = $loginFailTimes;
    }

    /**
     * ロックアウトがいつ発生したかを判定します
     *
     * @return bool ture:既にロックアウトしている、false:今回の認証でロックアウトした
     */
    public function isAlredyLockedOut(): bool
    {
        return $this->isAlredyLockedOut;
    }

    /**
     * ロックアウト発生時のメール送信に成功したかどうかを判定します
     *
     * @return bool ture:メール送信に成功した、false:メール送信に失敗した
     */
    public function isSuccessfullySendMail(): bool
    {
        return $this->isSuccessfullySendMail;
    }

    /**
     * ログインに失敗した数を取得します
     *
     * @return int ログイン失敗数
     */
    public function getLoginFailTimes(): bool
    {
        return $this->loginFailTimes;
    }
}
