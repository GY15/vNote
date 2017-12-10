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
use App\Http\Requests;

use App\Http\Controllers\Controller;



use Illuminate\Http\Request;

use Illuminate\Http\Response;




class NoteBookController extends Controller {

    public function getAllBook(Request $request)
    {
        $noteBookDao = new NoteBookDao();
        $userID = session('user');
        $books = $noteBookDao->getAllNotebook($userID);
        if ($books!=null) {
            $res =$this->parseBooks($books);
            return response()->json(array(
                'success' => 1,
                'books' => $res,
            ));
        } else {
           $noteBookDao->createNoteBook($userID);
           return $this->getAllBook();
        }
    }
    public function addNewBook(Request $request)
    {
        $noteBookDao = new NoteBookDao();
        $userID = session('user');
        $bookid= $noteBookDao->createNoteBook($userID);
        $noteDao = new NoteDao();
        $noteDao->createNote($userID,$bookid);
        return response()->json(array(
            'success' => 1,
            ));
    }
    public function deleteBook(Request $request)
    {
        $noteDao = new NoteDao();
        $noteBookDao = new NoteBookDao();
        $userID = session('user');
        $bookid = $request->input("bookid");
        $res1 = $noteBookDao->removeNoteBook($userID,$bookid);
        $res2 = $noteDao->removeOneNotebook($userID,$bookid);

        return response()->json(array(
            'success' => 1,
        ));
    }
    public function updateBook(Request $request)
    {
        $noteBookDao = new NoteBookDao();
        $userID = session('user');
        $bookid = $request->input("bookid");
        $title = $request->input("title");
        $tag = $request->input("tag");

        $res= $noteBookDao->updateNoteBook($bookid,$tag,$title,$userID);

        return response()->json(array(
            'success' => 1,
        ));
    }
    public function getBookOfTag(Request $request)
    {
        $tag = $request->input("tag");
        $noteBookDao = new NoteBookDao();
        $userID = session('user');
        $books = $noteBookDao->getBookOfTagOrTitle($userID,$tag);
        $res = $this->parseBooks($books);
        return response()->json(array(
            'success' => 1,
            'books' => $res,
        ));
    }
    private function parseBooks($books){
        $res ='';
        if ($books!=null) {
            foreach ($books as $book) {
                $title = get_object_vars($book)['title'];
                $tag = get_object_vars($book)['tag'];
                $bookid = get_object_vars($book)['bookid'];
                $thebook =
                    "<div class=\"bookSummary\">
                        <div class='bookid' style='display: none'>" . $bookid . "</div>
                        <div class=\"row\" style=\"height: 25px\">
                            <div class=\"bookTitleSummary col-md-7 col-md-offset-1\">" . $title . "</div>
                            <div class=\"col-md-1\">
                                <button class=\"editbook button button-royal button-circle button-tiny\" style=\"\"><i
                                            class=\"icon-edit\"></i></button>
                            </div>
                            <div class=\"col-md-1\">
                                <button class=\"deletebook button button-caution button-circle button-tiny\" style=\"\"><i
                                            class=\"icon-remove \"></i></button>
                            </div>
                        </div>

                        <div class=\" row col-md-offset-2\">
                            <label class=\"\" style=\"margin-top: 0px;font-size: 13px;color:slategrey\"><i
                                            class=\"icon-tags\"></i></label>
                            <p class=\"bookTag\" style=\"display:inline\">" . $tag . "</p>
                                          
                        </div>
                    </div>";
                $res = $res . $thebook;
            }
        }
        return $res;
    }

}