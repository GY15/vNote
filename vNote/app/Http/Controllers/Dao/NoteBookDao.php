<?php
/**
 * Created by PhpStorm.
 * User: 61990
 * Date: 2017/11/19
 * Time: 17:06
 */

namespace App\Http\Controllers\Dao;

use Illuminate\Support\Facades\DB;


class NoteBookDao
{
    private $tablename = 'notebook';

    public function getAllNotebook($userID)
    {
        $notebooks = DB::table($this->tablename)->where([
            ['creator', '=', $userID],
        ])->orderBy('createtime', 'desc')->get();;

        if (sizeof($notebooks->toArray()) == 0) {
            $this->createNoteBook($userID);
            return $this->getAllNotebook($userID);
        }
        return $notebooks;
    }

    public function getBookOfId($bookid)
    {
        $notebook = DB::table($this->tablename)->where([
            ['bookid', '=', $bookid],
        ])->get();

        if (sizeof($notebook->toArray()) == 0) {
            return null;
        }
        return $notebook;
    }

    public function getBookOfTagOrTitle($userID, $tag)
    {
        $notebooks = DB::table($this->tablename)->where([
                ['tag', 'like', '%' . $tag . '%'],
                ['creator', '=', $userID]
            ]
        )->orWhere([
            ['title', 'like', '%' . $tag . '%'],
            ['creator', '=', $userID]
        ])->get();
        if (sizeof($notebooks->toArray()) == 0) {
            return null;
        }
        return $notebooks;
    }


    /**
     * 用于创建笔记
     */
    public function createNoteBook($userID)
    {
        $max = DB::table($this->tablename)->orderBy('bookid', 'desc')->first();
        $id = get_object_vars($max)['bookid']; //递增id
        DB::table($this->tablename)->insert([
            'bookid' => $id + 1,
            'title' => '新建笔记本',
            'creator' => $userID,
            'createtime' => time() * 1000,
        ]);
        return $id + 1;
    }

    /**
     * 用于更新笔记
     */
    public function updateNoteBook($bookid, $tag, $title, $userID)
    {
        DB::table($this->tablename)->where([
            ['bookid', '=', $bookid],
            ['creator', '=', $userID],
        ])->update([
            'tag' => $tag,
            'title' => $title,
        ]);
        return true;
    }

    /**
     * 用于删除笔记本中的笔记
     */
    public function removeNoteBook($userID, $bookID)
    {
        $result = DB::table($this->tablename)->where([
            ['creator', '=', $userID],
            ['bookid', '=', $bookID]
        ])->delete();
        return $result;
    }
}