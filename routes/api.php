<?php

use App\Http\Controllers\CustomerController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['throttle:5,1'])->group(function () {
    // Route::post('/regist', [CustomerController::class, 'registration']);
    Route::post('/login', [CustomerController::class, 'login']);
});
Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [CustomerController::class, 'logout']);
});
Route::prefix('premium')->group(function () {
//    Route::post('user', function (Request $request) {
//        $user = User::where('email', '=', $request->input('email'))->first();
//        return $user && Hash::check($request->input('password'), $user->password)
//           ? $user->makeVisible('password')->makeHidden('id')->toArray()
//           : ['error'];
//    });

    Route::post('user', [CustomerController::class, 'login']);

    Route::prefix('csv')->group(function () {
        Route::get('animal', function () {
            Storage::exists('csv') ?: Storage::makeDirectory('csv');
            $link = 'https://my.api.mockaroo.com/animal.json?key=cda27560';
            $filename = 'animal_' . uniqid() . '.csv';
            Http::withOptions([
                'sink' => storage_path('app/csv/' . $filename),
            ])->get($link);

            return response()->download(storage_path('app/csv/' . $filename));
        });
    });
});

Route::prefix('ws')->group(function () {
    Route::post('login', function (Request $request) {
        $objResult = loginWsDataDummy($request);
        return response()->json($objResult);
    });
    Route::post('usePoint', function (Request $request) {
        $objResult = useWsDataDummy($request);
        return response()->json($objResult);
    });
});

Route::prefix('foru')->group(function () {
    Route::post('login', function (Request $request) {
        $objResult = loginForUDataDummy($request);
        return response()->json($objResult);
    });
});

function loginWsDataDummy($request)
{
    //  2.Account bình thường
    //   huyentrang202107010820/trang1234
    //  3.Account có contract_status = 1 (contract_status.column_name = csts_tm_bc_before_contact)
    //   huyentrang202107010823/trang1234
    //  4.Account có contract_status = 4 (contract_status.column_name =csts_on_on_ongoing)
    //   huyentrang202107010824/trang1234
    //  5.Account bị lock vì nhập sai 5 lần
    //   huyentrang202107010750/trang1234

    $objReturnValue = new stdClass();
    $objReturnValue->return_code = 1;
    $objReturnValue->emplex_customer_no = null;

    $objResult = new stdClass();
    $objResult->result = 'NG';
    $objResult->returnValue = $objReturnValue;

    if ($request->has('login_id') && $request->has('password')) {
        $loginId = $request->login_id;
        $password = $request->password;

        if ($password !== 'trang1234') {
            $objResult->result = 'OK';
            $objReturnValue->return_code = 14;
            return $objResult;
        }

        switch ($loginId) {
            case 'huyentrang202107010820':
            case 'huyentrang202107010823':
            case 'huyentrang202107010824':
                $objResult->result = 'OK';
                $objReturnValue->return_code = 21;
                $objReturnValue->emplex_customer_no = substr($loginId, 10, strlen($loginId));
                break;
            case 'huyentrang202107010750':
                $objResult->result = 'OK';
                $objReturnValue->return_code = 12;
                break;
            default:
                $objResult->result = 'OK';
                $objReturnValue->return_code = 14;
                break;
        }
    }

    return $objResult;
}


function loginForUDataDummy($request)
{
    // 1.Account email
    //  huyen_trang@lampart-vn.com/trang1234

    $objReturnValue = new stdClass();
    $objReturnValue->return_code = 1;
    $objReturnValue->emplex_customer_no = 0;

    $objResult = new stdClass();
    $objResult->result = 'NG';
    $objResult->returnValue = $objReturnValue;

    if ($request->has('login_id') && $request->has('password')) {
        $loginId = $request->login_id;
        $password = $request->password;

        if ($password !== 'trang1234') {
            $objResult->result = 'OK';
            $objReturnValue->return_code = 14;
            return $objResult;
        }

        switch ($loginId) {
            case 'huyen_trang@lampart-vn.com':
                $objResult->result = 'OK';
                $objReturnValue->return_code = 22;
                $objReturnValue->emplex_customer_no = 1234567891;
                break;
            default:
                $objResult->result = 'OK';
                $objReturnValue->return_code = 14;
                break;
        }
    }

    return $objResult;
}


function useWsDataDummy($request)
{

    $objectErrorDetail = new stdClass();
    $objectErrorDetail->diUsePoint = 'E900130';

    $objectError = new stdClass();
    $objectError->dserrdetail = $objectErrorDetail;

    $objReturnValue = new stdClass();
    $objReturnValue->dsretcd = 2;
    $objReturnValue->dserror = $objectError;

    if ($request->usepoint !== 0) {
        $dilockid = $request->dilockid;
        $sales = $request->sales;
        $usepoint = $request->usepoint;

        if ($dilockid === 'ECS11111') {
            //point=10
            $point = 10;

            if ($usepoint > 10) {
                $objReturnValue->dsretcd = 12;
            } elseif ($usepoint == 10) {
                $objReturnValue->dsretcd = 14;
            } else {
                if ($sales !== 'cardNoUse') {
                    $objReturnValue->dsretcd = 13;
                } else {
                    $objReturnValue->dsretcd = 0;
                    $objReturnValue->dserror = null;
                }
                return $objReturnValue;
            }
        } else {
            $objReturnValue->dsretcd = 11;
        }
        $objectErrorDetail->diUsePoint = 'E900131';
    }
    return $objReturnValue;

}
