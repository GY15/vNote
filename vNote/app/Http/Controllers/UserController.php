<?php
/**
 * Created by PhpStorm.
 * User: 61990
 * Date: 2017/11/15
 * Time: 19:28
 */

namespace App\Http\Controllers;
use App\Http\Controllers\Dao\NoteBookDao;
use App\Http\Controllers\Dao\NoteDao;
use App\Http\Controllers\Dao\UserDao;
use Illuminate\Http\Request;
use app\Member;
use function MongoDB\BSON\toJSON;
use PhpParser\Node\Expr\Array_;

class UserController extends Controller {
    protected $layout = "layouts.home";
    public function login(Request $request)
    {
        $request->flash();
        $email = $request->input("login_email");
        $password = $request->input("login_password");
        $userDao = new UserDao();
        $res = $userDao->login($email,$password);
        if($res[0]==0){
            $isManager = $userDao->managerLogin($email,$password);
            if($isManager){
                return view('manager');
            }else {
                return view('home', ['errorMessage' => $res[1], 'type' => '0']);
            }
        }else {
            session(['user' => $email]);
//            删除session $value = $request->session()->pull('key', 'default');$request->session()->forget('key');
            return view('myNote');
        }
    }


    public function register(Request $request){
        $request->flash();
        $email = $request->input("reg_email");
        $nickname = $request->input("reg_nickname");
        $password = $request->input("reg_password");
        $password2 = $request->input("reg_password2");
        $userDao = new UserDao;
        $res = $userDao->register($email,$nickname,$password,$password2);
        if($res[0]==1){
            $noteBookDao = new NoteBookDao();
            $noteBookDao->createNoteBook($email);
            return view('home',['errorMessage'=>$res[1],'type'=>'0']);
        }else{
            return view('home',['errorMessage'=>$res[1],'type'=>'1']);
        }
    }
    /**
     * 存储新的博客文章
     *
     * @param  Request  $request
     * @return Response
     */

    public function show($id)
    {
        return view('profile', ['user' => User::findOrFail($id)]);
    }

    public function getAllUser (Request $request){
        return  response()->json(array(
            'success' => 1,
        ));
    }
}