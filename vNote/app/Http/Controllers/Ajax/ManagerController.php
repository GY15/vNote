<?php
/**
 * Created by PhpStorm.
 * User: 61990
 * Date: 2017/12/8
 * Time: 11:37
 */

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Dao\NoteBookDao;
use App\Http\Controllers\Dao\NoteDao;
use App\Http\Controllers\Dao\UserDao;
use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ManagerController extends Controller{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUser (Request $request){
        $userDao = new UserDao();
        $users = $userDao->getAllUser();
        $res= "";
        foreach ($users as $user){
            $email = get_object_vars($user)['email'];
            $nickname = get_object_vars($user)['nickname'];
            $password=get_object_vars($user)['password'];
            $string = "
                    <div class=\"row user\">
                        <div class=\"email col-md-4\">".$email."</div>
                        <div class=\"nickname col-md-4\">".$nickname."</div>
                        <div class='password' style='display: none'>".$password."</div>
                        <div class=\"col-md-1 col-md-offset-1\">
                            <button class=\"edit button button-royal button-circle button-tiny\" style=\"\"><i
                                        class=\"icon-edit\"></i></button>
                        </div>
                        <div class=\"col-md-1\">
                            <button class=\"delete button button-caution button-circle button-tiny\" style=\"\"><i
                                        class=\"icon-remove \"></i></button>
                        </div>
                    </div>     ";
            $res =$res.$string;
        }
        return  response()->json(array(
            'success' => 1,
            'users'=> $res
        ));
    }
    public function deleteUser (Request $request){
        $userDao = new UserDao();
        $email = $request->input("email");
        $user = $userDao->deleteUser($email);
        return  response()->json(array(
            'success' => 1,
        ));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modifyUser (Request $request){
        $userDao = new UserDao();
        $email = $request->input("email");
        $nickname = $request->input("nickname");
        $password = $request->input("password");
        $user = $userDao->updateUser($email,$nickname,$password);
        return  response()->json(array(
            'success' => 1,
        ));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserMessage (Request $request){
        $userDao = new UserDao();
        $email = session('user');

        $user = $userDao->getUserMessage($email);

        $email = get_object_vars($user[0])['email'];
        $nickname = get_object_vars($user[0])['nickname'];
        $password=get_object_vars($user[0])['password'];

        return  response()->json(array(
            'success' => 1,
            'email' => $email,
            'nickname'=> $nickname,
            'password'=>$password
        ));
    }


}