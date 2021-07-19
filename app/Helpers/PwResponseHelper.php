<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Lib\Common\Type\TypeList;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Lib\Business\Common\Models\PwValidationErrors;

/**
 * レスポンス情報を生成するためのヘルパークラス
 *
 * @author Yoko Mimura
 */
class PwResponseHelper
{
    /**
     * 正常終了時のレスポンス
     *
     * - HTTPStatus : 200
     * - 画面情報の返却により利用可能
     *
     * @param  ?array $htmlHead HTML <head> タグ用の情報
     * @param  ?array $headers ヘッダ情報
     * @param  ?array $footers フッタ情報
     * @param  ?array $contents データ以外のコンテンツ
     * @param  ?array $data データ
     * @param  ?array $statuses ステータスリスト
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function responseOnSuccessful(?array $htmlHead,
                                                ?array $headers,
                                                ?array $footers,
                                                ?array $contents,
                                                ?array $data,
                                                ?array $statuses = null
                                                ): Response {
        //レスポンスデータ
        $responseData = [];

        // HTML <head>タグ用の情報設定
        if (isset($htmlHead)) {
            $responseData['htmlhead'] = $htmlHead;
        }

        // ヘッダ情報設定
        if (isset($headers)) {
            $responseData['headers'] = $headers;
        }

        // フッタ情報設定
        if (isset($footers)) {
            $responseData['footers'] = $footers;
        }

        // 画面権限情報設定
        if (isset($contents)) {
            $responseData['contents'] = $contents;
        }

        // コンテンツデータ設定
        if (is_null($data)) {
            // null の場合、空配列を設定
            $data = [];
        }
        $responseData['contents']['data'] = $data;

        // ステータス設定
        if (isset($statuses)) {
            $responseData['contents']['statuses'] = $statuses;
        }

        Log::debug('responseData = ', $responseData);

        // JSON化し、レスポンス返却
        return response()->json($responseData);
    }

    /**
     * バリデーションエラー
     *
     * - HTTPStatus : 422
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function responseOnValidationErrorsFromException(ValidationException $exception)
    {
        return response()->json([
            'contents' => [
                'errors' => $exception->errors()
            ]
        ], 422);
    }

    /**
     * バリデーションエラー
     *
     * - HTTPStatus : 422
     *
     * @param  PwValidationErrors  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function responseOnValidationErrors(PwValidationErrors $errors)
    {
        return response()->json([
            'contents' => [
                'errors' => $errors->toArray()
            ]
        ], 422);
    }

    /**
     * ビジネス系処理エラー
     *
     * @param int $statusCode HTTPStatus
     * @param string $errCode エラーコード
     * @param string $errDescription エラー詳細. null可
     * @param string $errMessage エラーメッセージ. null可
     * @param mixed $optional 任意情報. null可
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function responseOnBusinessError(
        string  $errCode,
        string  $errDescription = null,
        string  $errMessage = null,
        $optional = null
    ) {
        return PwResponseHelper::responseOnError(
                                        400,
                                        $errCode,
                                        $errDescription,
                                        $errMessage,
                                        $optional
                                    );
    }

    /**
     * ビジネス系処理エラー
     *
     * @param int $statusCode HTTPStatus
     * @param PwResponseStatus $status エラー情報
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function responseOnBusinessErrorFromStatus(PwResponseStatus $status)
    {
        return PwResponseHelper::responseOnError(
                                        400,
                                        $status->getCode(),
                                        $status->getDescription(),
                                        $status->getMessage(),
                                        $status->getOptional()
                                    );
    }

    /**
     * 処理エラー
     *
     * @param int $statusCode HTTPStatus
     * @param string $errCode エラーコード
     * @param string $errDescription エラー詳細. null可
     * @param string $errMessage エラーメッセージ. null可
     * @param mixed $optional 任意情報. null可
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function responseOnError(
        int     $statusCode,
        string  $errCode,
        string  $errDescription = null,
        string  $errMessage = null,
        $optional = null
    ) {
        $error = PwResponseStatus::createErrorStatus(   $errCode,
                                                        $errDescription,
                                                        $errMessage,
                                                        $optional,
                                                    );

        $errors = new TypeList(new PwResponseStatus, []);
        $errors->add($error);

        return PwResponseHelper::responseOnErrors($statusCode, $errors);
    }

    /**
     * 処理エラー
     *
     * @param int $statusCode HTTPStatus
     * @param TypeList<PwResponseStatus> $errors エラー情報リスト
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function responseOnErrors(int $statusCode, TypeList $errors)
    {
        return response()->json([
            'errors' => $errors->toArray()
        ], $statusCode);
    }
}
