<?php

namespace App\Http\Controllers;

use App\Exceptions\pw\PwSystemErrror;
use App\Exceptions\pw\PwAuthIdNotFoundException;
use App\Exceptions\pw\PwAuthLockUserException;
use App\Exceptions\pw\PwAuthMissPasswordException;
use App\Exceptions\pw\PwAuthUnUsableException;
use App\Http\Requests\LoginFormRequest;
use App\Models\Customer;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Validator;

class CustomerController extends Controller
{
    private array $customer = [];

    public function registration(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|',
            'c_password' => 'required|same:password'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 202);
        }

        $allData = $request->all();
        $allData['password'] = bcrypt($allData['password']);

        $user = User::create($allData);
        $resArr = [];
        $resArr['token'] = $user->createToken('api-application')->accessToken;
        $resArr['name'] = $user->name;
        return response()->json($resArr, 200);
    }

    public function login(LoginFormRequest $request)
    {
        $status = null;
        $code = null;
        $empl = null;
        try {
            $authenticatedAccount = $this->authenticate($request->email, $request->password, $request->case);

            if ($authenticatedAccount) {

                return response()->json([
                    'result' => __('statusPw.E00900001001'),
                    'returnValue' => [
                        "return_code" => __('returnCode.Login_Success_(Other_authentication)_00-01-000007'),
                        "emplex_customer_no" => $authenticatedAccount['customerID']
                    ]
                ]);

            } else {
                $status = __('statusPw.E00900001002');
                $code = __('returnCode.Login_Authentication_Error_00-01-000004');
            }
        } catch (PwAuthLockUserException $e) {
            $status = __('statusPw.E00900001002');
            $code = __('returnCode.Account_Lock_Error_00-01-000003');
        } catch (PwAuthIdNotFoundException | PwAuthMissPasswordException $e) {
            $status = __('statusPw.E00900001002');
            $code = __('returnCode.Login_Authentication_Error_00-01-000004');
        } catch (PwSystemErrror $e) {
            $status = __('statusPw.E00900001003');
            $code = __('returnCode.System_Error_00-01-000001');
        }

        return response()->json([
            'result' => $status,
            'returnValue' => [
                "return_code" => $code,
                "emplex_customer_no" => null
            ]
        ]);

    }

    private function responOnAuthorized($authenticatedAccount)
    {


    }

    public function authenticate(string $email, string $password, string $case): array
    {

        $customer = new Customer();

        switch ($case) {
            case 1:
            case 2:
            case 3:
                $this->customer[] = $customer->init();
                break;
            case 4:
                $this->customer[] = $customer->init1();
                break;
            case 5:
                throw new PwSystemErrror();
        }

        $checkLockUser = $this->checkLockUser($email);

        $account = $this->customer[0];
        if ($checkLockUser) {
            throw new PwAuthLockUserException();
        }

        if (!Hash::check($password, $account->getPassword())) {

            throw new PwAuthMissPasswordException();
        }

        return $account->toArray();
    }

    public function checkLockUser($email): ?bool
    {
        foreach ($this->customer as $cus) {
            if ($email === $cus->getEmail() && $cus->getCountLoginFail() >= 5) {
                return true;
            }
        }
        return false;
    }


}
