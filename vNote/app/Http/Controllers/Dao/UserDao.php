<?php
/**
 * Created by PhpStorm.
 * User: 61990
 * Date: 2017/11/16
 * Time: 15:20
 */

namespace App\Http\Controllers\Dao;
use Illuminate\Support\Facades\DB;

class UserDao
{
    function login($email,$password){

        if($email==""||$password==""){
            return [0,"用户名和密码不能为空"];
        }
        try {
            $user = DB::table('user')->where('email',$email)->get(); //piao zhun xie fa
            if (!$user->count()){
                return [0,"用户名不存在"];
            }else{
                $pw = get_object_vars($user[0])['password'];
                if ($pw == $password) {
                    $nickname =  get_object_vars($user[0])['nickname'];
                    session(['nickname' => $nickname]);
                    return [1,"登录成功"];
                } else {
                    return [0,"用户名或密码错误"];
                }
            }
        }catch (Exception $e){
            return  [0,"用户名或密码错误"];
        }
    }

    function register($email,$nickname,$password,$password2){
        if($email==""||$nickname==""||$password==""||$password2==""){
            return [0,"注册信息不能为空"];
        }
        if($password!=$password2){
            return [0,"两次密码不统一"];
        }
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if ( !preg_match( $pattern, $email ) )
        {
            return [0,"您输入的电子邮件地址不合法"];
        }
        try {
            $user = DB::table('user')->where('email',$email)->get(); //piao zhun xie fa
            if ($user->count()){
                return [0,"用户名已经存在"];
            }else{
                DB::table('user')->insert(
                    ['email' => $email,'password' => $password, 'nickname' => $nickname,  'scores' => 0]
                );
                return [1,"用户创建成功！"];
            }
        }catch (Exception $e){
            return  [0,"注册失败，请重试"];
        }
    }
    public function getName($userID){
        $user = DB::table('user')->where('email',$userID)->get();
        return $user;
    }

    public function managerLogin($email, $password)
    {
        $manager = DB::table('manager')->where('manager',$email)->get(); //piao zhun xie fa
        if (!$manager->count()){
            return false;
        }else{
            $pw = get_object_vars($manager[0])['password'];
            if ($pw == $password) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getAllUser()
    {
        return DB::table('user')->get();
    }

    /**
     * @return mixed
     */
    public function deleteUser($userID)
    {
        DB::table('user')->where([
            ['email', '=', $userID]
        ])->delete();
        DB::table('note')->where([
            ['creator', '=', $userID]
        ])->delete();
        DB::table('notebook')->where([
            ['creator', '=', $userID]
        ])->delete();
        return true;
    }

    /**
     * @param $email
     * @param $nickname
     * @param $password
     * @return bool
     */
    public function updateUser($email, $nickname, $password)
    {
        DB::table("user")->where([
            ['email', '=',$email],
        ])->update([
            'nickname' => $nickname,
            'password' => $password
        ]);
        return true;
    }

    public function getUserMessage($email)
    {
        return   DB::table("user")->where([
            ['email', '=',$email],
        ])->get();
    }

}