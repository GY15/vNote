<?php
/**
 * Created by PhpStorm.
 * User: 61990
 * Date: 2017/11/19
 * Time: 17:06
 */

namespace App\Http\Controllers\Dao;

use Illuminate\Support\Facades\DB;


class NoteDao
{
    private $tablename = 'note';

    public function getAllNote($userID)
    {
        $notes = DB::table($this->tablename)->where([
            ['creator', '=', $userID],
        ])->orderBy('createtime', 'desc')->get();
        if (sizeof($notes->toArray()) == 0) {
            return null;
        }
        return $notes;
    }

    public function getNoteOfBook($userID, $bookID)
    {
        $notes = DB::table($this->tablename)->where([
            ['bookid', '=', $bookID],
            ['creator', '=', $userID]
        ])->orderBy('createtime', 'desc')->get();
        if (sizeof($notes->toArray()) == 0) {
            $this->createNote($userID, $bookID);
            return ($this->getNoteOfBook($userID, $bookID));
        }
        return $notes;
    }

    public function getNoteOfTagOrTitle($userID, $bookID, $tag)
    {
        $notes = "";
        if ($bookID == 0) {
            $notes = DB::table($this->tablename)->where([
                    ['tag', 'like', '%' . $tag . '%'],
                    ['creator', '=', $userID]
                ]
            )->orWhere([
                ['title', 'like', '%' . $tag . '%'],
                ['creator', '=', $userID]
            ])->orderBy('createtime', 'desc')->get();
        } else {
            $notes = DB::table($this->tablename)->where([
                ['tag', 'like', '%' . $tag . '%'],
                ['bookid', '=', $bookID],
                ['creator', '=', $userID]
            ])->orWhere([
                ['title', 'like', '%' . $tag . '%'],
                ['bookid', '=', $bookID],
                ['creator', '=', $userID]
            ])->orderBy('createtime', 'desc')->get();
        }

        if (sizeof($notes->toArray()) == 0) {
            return null;
        }
        return $notes;
    }

    public function getNoteOfId($userID, $noteid)
    {
        $notes = DB::table($this->tablename)->where([
            ['creator', '=', $userID],
            ['noteid', '=', $noteid],
        ])->get();
        return $notes;
    }

    /**
     * 用于创建笔记
     */
    public function createNote($userID, $bookID)
    {
        $max = DB::table($this->tablename)->orderBy('noteid', 'desc')->first();
        $id = get_object_vars($max)['noteid']; //递增id
        $id += 1;
        DB::table($this->tablename)->insert([
            'noteid' => $id,
            'bookid' => $bookID,
            'title' => '新建笔记',
            'note' => '',
            'creator' => $userID,
            'createtime' => time(),
            'visible' => '0',
        ]);
        return $id;
    }

    /**
     * 用于更新笔记
     */
    public function updateNote($noteID, $bookID, $tag, $title, $note, $visible, $user)
    {
        if ($bookID != 0) {
            DB::table($this->tablename)->where([
                ['noteid', '=', $noteID],
                ['creator', '=', $user]
            ])->update([
                'bookid' => $bookID,
            ]);
        }
        $oldnote = get_object_vars($this->getNoteOfId($user, $noteID)[0]);
        if ($oldnote['tag'] == $tag && $oldnote['note'] == $note && $oldnote['visible'] == $visible && $oldnote['title'] == $title) {
            return true;
        }
        DB::table($this->tablename)->where([
            ['noteid', '=', $noteID],
            ['creator', '=', $user]
        ])->update([
            'title' => $title,
            'tag' => $tag,
            'note' => $note,
            'createtime' => time(),
            'visible' => $visible,
        ]);
        return true;
    }

    /**
     * 用于删除笔记
     */
    public function removeNote( $userID,$noteID)
    {
        $result = DB::table($this->tablename)->where([
            ['creator', '=', $userID],
            ['noteid', '=', $noteID]
        ])->delete();
        return $result;
    }

    /**
     * 用于删除笔记本的笔记
     */
    public function removeOneNotebook($userid, $bookID)
    {
        $result = DB::table($this->tablename)->where([
            ['bookid', '=', $bookID],
            ['creator', '=', $userid]
        ])->delete();
        return $result;
    }

    public function getCommunity($message){
       $notes="";
        if($message==""||$message==null){
            $notes = DB::table($this->tablename)->where([
                ['visible', '=', 1],
            ])
                ->inRandomOrder()
                ->take(10)
                ->get();
        }else{
            $notes = DB::table($this->tablename)->where([
                ['visible', '=', 1],
                ['tag', 'like', '%' . $message . '%']
            ])->orWhere([
                ['visible', '=', 1],
                ['title', 'like', '%' . $message . '%']
            ]) ->inRandomOrder()
                ->take(10)
                ->get();
        }

        if (sizeof($notes->toArray()) == 0) {
           return null;
        }
        return $notes;
    }
    public function getTop10(){

        $notes = DB::table($this->tablename)
            ->where([
                ['visible', '=', 1],
            ])
                ->orderBy('score', 'desc')
                ->take(10)
                ->get();
        if (sizeof($notes->toArray()) == 0) {
            return null;
        }
        return $notes;
    }

    public function likeNote($noteid)
    {
        $note = DB::table($this->tablename)->where([
            ['noteid', '=', $noteid],
        ])->get();
        $score = get_object_vars($note[0])['score'];
        DB::table($this->tablename)->where([
            ['noteid', '=', $noteid],
        ])->update([
            'score' => $score+1
        ]);
        return true;
    }
}