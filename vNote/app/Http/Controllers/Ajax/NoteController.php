<?php
/**
 * Created by PhpStorm.
 * User: 61990
 * Date: 2017/11/18
 * Time: 23:33
 */

namespace App\Http\Controllers\Ajax;


use App\Http\Controllers\Dao\NoteBookDao;
use App\Http\Controllers\Dao\NoteDao;
use App\Http\Controllers\Dao\UserDao;
use App\Http\Requests;

use App\Http\Controllers\Controller;



use Illuminate\Http\Request;

use Illuminate\Http\Response;




class NoteController extends Controller {
    /**
     * 通过笔记本id 得到所有note
     */
    public function getAllNote(Request $request)
    {
        $bookid = $request->input("bookid");
        $noteDao = new NoteDao();
        $userID = session('user');
        $notes = "";
        if($bookid==0) {
                $notes = $noteDao->getAllNote($userID);
        }else{
                $notes = $noteDao->getNoteOfBook($userID,$bookid);
         }
        if ($notes!=null) {
            $res =$this->parseNotes($notes);
            $firstNote= get_object_vars($notes[0]);
            return response()->json(array(
                'success' => 1,
                'notes' => $res,
                'noteid' => $firstNote['noteid'],
            ));
        } else {
            $noteBookDao = new NoteBookDao();
            $bookid = $noteBookDao->createNoteBook($userID);
            $noteDao->createNote($userID,$bookid);
            return $this->getAllNote($request);
        }
    }
    /**
     * 用于通过Tag得到笔记
     */
    public function getNoteOfTag(Request $request)
    {
        $bookid = $request->input("bookid");
        $tag = $request->input("tag");
        $noteDao = new NoteDao();
        $userID = session('user');
        $notes = $noteDao->getNoteOfTagOrTitle($userID,$bookid,$tag);
        $res = $this->parseNotes($notes);
        return response()->json(array(
            'success' => 1,
            'notes' => $res,
        ));
    }

    /**
     * 将从数据库拿来的notes文本化
     */
    private function parseNotes($notes){
        $res = "";
        if ($notes!=null) {
            foreach ($notes as $note){
                $title=get_object_vars($note)['title'];
                $content=get_object_vars($note)['note'];
                $noteid=get_object_vars($note)['noteid'];
                $time =time()-get_object_vars($note)['createtime'];
                if ($time<300){
                    $time = "刚刚";
                }else if($time<3600){
                    $time = (int)($time/60);
                    $time = $time."分钟前";
                }else if($time<60*60*24){
                    $time = (int)($time/60/60);
                    $time = $time."小时前";
                }else{
                    $time = (int)($time/60/60/24);
                    $time = $time."天前";
                }
                $note ="<div class=\"noteSummary\">
                        <div class=\"row\" style=\"height: 25px\">
                            <p class='noteidSummary' style='display:none;'>".$noteid."</p>
                            <div class=\"titleSummary col-md-7 col-md-offset-1\">".$title."</div>
                            <div class=\"col-md-1\">
                                <button class=\"shareSummary button button-primary button-circle button-tiny\"
                                        style=\"display: none\"><i
                                            class=\"icon-group \"></i></button>
                            </div>
                            <div class=\"col-md-1\">
                                <button class=\"deleteSummary  button button-caution button-circle button-tiny\"
                                        style=\"display: none\"><i
                                            class=\"icon-remove \"></i></button>
                            </div>
                        </div>
                        <div class=\"row\">
                            <div class=\"timeSummary col-md-offset-1 col-md-5\">".$time."</div>
                        </div>
                        <div class=\"row\">
                            <div class=\"contentSummary col-md-offset-1 col-md-10\"><p>
                                    ".$content."</p>
                            </div>
                        </div>
                    </div>";
                $res =$res.$note;
            }
        }
        return $res;
    }
    /**
     * 用于创建笔记
     */
    public function addNewNote(Request $request)
    {
        $noteDao = new NoteDao();
        $userID = session('user');
        $noteBookDao = new NoteBookDao();
        $noteBooks = $noteBookDao->getAllNotebook($userID);
        $book = get_object_vars($noteBooks[0])['bookid'];

        $id= $noteDao->createNote($userID,$book);

        $bookSelect ="";
        foreach ($noteBooks as $book){
            $bookid=get_object_vars($book)['bookid'];
            $title=get_object_vars($book)['title'];
            $bookSelect = $bookSelect."<option value=\"".$bookid."\">".$title."</option>";
        }
        return response()->json(array(
            'success' => 1,
            'noteid' => $id,
            'noteTitle' => "新建笔记",
            'books' => $bookSelect,
            ));
    }
    /**
     * 用于创建笔记
     */
    public function deleteNote(Request $request)
    {

        $noteDao = new NoteDao();
        $userID = session('user');
        $noteid = $request->input("noteid");
        $res = $noteDao->removeNote($userID,$noteid);
        return response()->json(array(
            'success' => 1,
        ));
    }
    /**
     * h获取指定笔记的详细内容
     */
    public function getOneNote(Request $request)
    {
        $noteDao = new NoteDao();
        $userID = session('user');
        $noteid = $request->input("noteid");
        $notes= $noteDao->getNoteOfId($userID,$noteid);
        $note = get_object_vars($notes[0]);

        $bookDao = new NoteBookDao();
        $firstBook = $bookDao->getBookOfId( $note['bookid']);
        $bookName = get_object_vars($firstBook[0])['title'];
        return response()->json(array(
            'success' => 1,
            'noteid' => $note['noteid'],
            'noteTitle' => $note['title'],
            'bookname' => $bookName,
            'content' => $note['note'],
            'visible' => $note['visible'],
            'tag' => $note['tag'],
        ));
    }
    /**
     * 更新笔记的内容
     */
    public function updateNote(Request $request)
    {
        $noteDao = new NoteDao();
        $userID = session('user');
        $noteid = $request->input("noteid");
        $title = $request->input("title");
        $tag = $request->input("tag");
        $note = $request->input("note");
        $bookid = $request->input("bookid");
        $state = $request->input("state");

        $res= $noteDao->updateNote($noteid,$bookid,$tag,$title,$note,$state,$userID);

        return response()->json(array(
            'success' => 1,
        ));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCommunity(Request $request)
    {
        $noteDao = new NoteDao();
        $message = $request->input("message");
        $res = $this->parseCommunity($noteDao->getCommunity($message));
        if($res!=null) {
            return response()->json(array(
                'success' => 1,
                'notes'=>$res
            ));
        }else{
            return response()->json(array(
                'success' => 0,
            ));
        }
    }

    public function searchTop(Request $request)
    {
        $noteDao = new NoteDao();
        $res = $this->parseCommunity($noteDao->getTop10());
        if($res!=null) {
            return response()->json(array(
                'success' => 1,
                'notes'=>$res
            ));
        }else{
            return response()->json(array(
                'success' => 0,
            ));
        }
    }

    /**
     * @param $notes
     * @return string
     */
    private function parseCommunity($notes){
        $res="";
        if($notes==null){
            return null;
        }
        $userDao = new UserDao();
        foreach ($notes as $note){
            $title=get_object_vars($note)['title'];
            $content=get_object_vars($note)['note'];
            $noteid=get_object_vars($note)['noteid'];
            $creator=get_object_vars($note)['creator'];
            $user = $userDao->getName($creator);
            $name = get_object_vars($user[0])['nickname'];
            $score = get_object_vars($note)['score'];
            $time =time()-get_object_vars($note)['createtime'];
            if ($time<300){
                $time = "刚刚";
            }else if($time<3600){
                $time = (int)($time/60);
                $time = $time."分钟前";
            }else if($time<60*60*24){
                $time = (int)($time/60/60);
                $time = $time."小时前";
            }else{
                $time = (int)($time/60/60/24);
                $time = $time."天前";
            }
            $note ="        <div class=\"noteCommunity\">
                                <div class=\"row\" style=\"height: 25px\">
                                    <div class=\"noteidCommunity\" style=\"display: none\">".$noteid."</div>
                                    <div class=\"titleCommunity col-md-4 col-md-offset-1\">".$title."</div>
                                    <div class=\"writerCommunity col-md-2\">作者：<span class=\"writer\">".$name."</span></div>
                                    <div class=\"col-md-1 open1 col-md-offset-1\">
                                        <button class=\"openDetail button button-primary button-circle button-tiny\"
                                        ><i
                                                    class=\" icon-resize-vertical \"></i></button>
                                    </div>
                                    <div class=\"col-md-1 close1 col-md-offset-1\" style=\"display: none\">
                                        <button class=\"closeDetail button button-royal button-circle button-tiny\"
                                        ><i class=\" icon-retweet \"></i></button>
                                    </div>
                                    <div class=\"col-md-1\" style=\"margin-left: 20px\">
                                        <button class=\"likeNote  button button-caution button-circle button-tiny\"
                                        ><i
                                                    class=\"icon-heart \"></i></button>
                                    </div>
                                </div>
                                <div class=\"row\">
                                    <div class=\"timeCommunity col-md-offset-1 col-md-2\">".$time."</div>
                                    <div class=\"scoreCommunity col-md-offset-6 col-md-2\">点赞数：<span
                                                class=\"score\">".$score."</span></div>
                                </div>
                                <div class=\"row\">
                                    <div class=\"contentCommunity max_line col-md-offset-1 col-md-10\">
                                        <div class=\"summernote\" style=\"z-index: 1000\">
                                        ".$content."    </div>
                                    </div>
                                </div>
                            </div>";
            $res =$res.$note;
        }
        return $res;
    }
    /**
     * 赞一个笔记
     */
    public function likeNote(Request $request)
    {
        $noteDao = new NoteDao();
        $noteid = $request->input("noteid");
        $noteDao->likeNote($noteid);
        return response()->json(array(
            'success' => 1,
        ));
    }

}