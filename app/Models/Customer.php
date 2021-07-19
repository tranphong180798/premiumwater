<?php


namespace App\Models;


use Cassandra\Custom;

class Customer
{
    private int $customerID;
    private string $name;
    private string $email;
    private string $email_verified_at;
    private string $password;
    private int $point;
    private bool $isLocked;
    private string $created_at;
    private string $updated_at;
    private int $countLoginFail;

    /**
     * Customer constructor.
     */
    public function __construct()
    {

    }


    public function init(): Customer
    {
        $customer = new Customer();
        $customer->customerID = 1001234567;
        $customer->name = 'Prof. Karli Weimann';
        $customer->email = 'premium1@gmail.com';
        $customer->email_verified_at = '2021-06-26T08:54:34.000000Z';
        $customer->point = 20;
        $customer->isLocked = false;
        $customer->countLoginFail = 0;
        $customer->password = '$2y$12$6c2tFiMrWuWGr3Aqllo5G.PNEREXuOUCo9WJ0kQUW8rLqYcndAKKO';
        $customer->created_at = '2021-06-26T08:54:35.000000Z';
        $customer->updated_at = '2021-06-26T08:54:56.000000Z';
        return $customer;
    }

    public function init1(): Customer
    {
        $customer = new Customer();
        $customer->customerID = 1001234567;
        $customer->name = 'Prof. Karli Weimann';
        $customer->email = 'premium1@gmail.com';
        $customer->email_verified_at = '2021-06-26T08:54:34.000000Z';
        $customer->point = 20;
        $customer->isLocked = false;
        $customer->countLoginFail = 5;
        $customer->password = '$2y$10$gVh9ymXjdOXRtuQcLH/x0erCDalMCWf9haI3RBw1McJrhsljQycJm';
        $customer->created_at = '2021-06-26T08:54:35.000000Z';
        $customer->updated_at = '2021-06-26T08:54:56.000000Z';
        return $customer;
    }

    public function init2(): Customer
    {
        $customer = new Customer;
        $this->customerID = 1001234567;
        $this->name = 'Prof. Karli Weimann';
        $this->email = 'premium1@gmail.com';
        $this->email_verified_at = '2021-06-26T08:54:34.000000Z';
        $this->point = 20;
        $this->isLocked = false;
        $this->countLoginFail = 0;
        $this->password = '$2y$10$gVh9ymXjdOXRtuQcLH/x0erCDalMCWf9haI3RBw1McJrhsljQycJm';
        $this->created_at = '2021-06-26T08:54:35.000000Z';
        $this->updated_at = '2021-06-26T08:54:56.000000Z';
        return $this;
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->isLocked;
    }

    /**
     * @return int
     */
    public function getCountLoginFail(): int
    {
        return $this->countLoginFail;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getEmailVerifiedAt(): string
    {
        return $this->email_verified_at;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


    /**
     * @return int
     */
    public function getPoint(): int
    {
        return $this->point;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function toArray(): array
    {
        $target = get_object_vars($this);
        return $this->toArrayFromModel($target);
    }

    protected function toArrayFromModel(array $property, array $unset = []): array
    {
        // 不要な要素を除外
        foreach ($unset as $value) {
            unset($property[$value]);
        }
        // モデルクラスを配列に変換
        return $this->toArrayRecursive($property);
    }

    private function toArrayRecursive($property)
    {
        // 配列の場合は中身を再帰的に配列に変換
        if (is_array($property)) {
            $array = [];
            foreach ($property as $key => $value) {

                $array[$key] = $this->toArrayRecursive($value);
            }
            return $array;
        }

        // 配列変換対象がクラスの場合
        if (is_object($property)) {
            return $property->toArray();
        }

        // プリミティブ型の場合
        if (is_null($property)) {
            // null の場合、空文字を設定
            $property = "";
        }
        return $property;
    }
}
