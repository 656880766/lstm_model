<?php

namespace App\Http\Controllers;

use App\Models\Locations;
use App\Models\Note_Average;
use App\Models\Reserve;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteAverageController extends Controller
{
    /**
     * enregistrer les notes.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function makeNote(Request $request)
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'location_id' => 'required|numeric',

        ]);
        $note = $request->input('note');

        if (count(User::where('id', $request->user_id)->get()) != 0 && count(Locations::where('id', $request->location_id)->get()) != 0) {
            if (count(Note_Average::where('user_id', $request->user_id)->where('location_id', $request->location_id)->get()) == 0) {
                if ($note >= 0 && $note <= 10) {
                    // $moyenne = Note_Average::select('note')->where('location_id', $request->location_id)->get('note');
                    // dd($moyenne);

                    DB::insert('insert into note_average (user_id,location_id,note) values(?,?,?)', [$request->user_id, $request->location_id, $note]);
                    $yves = DB::select('select avg(note) as moyenne from note_average where location_id =?', [$request->location_id]);
                    $note_globale = (int)$yves[0]->moyenne;
                    DB::update("update locations set note_average = $note_globale where id = ?", [$request->location_id]);
                    return response()->json([
                        'message' => "your attribute $note/10 thank you "
                    ], 403);
                    // $moyenne = count(Note_Average::select('note')->where('location_id', $request->location_id));

                    // dd($moyenne);
                } else {
                    return response()->json([
                        'message' => 'this note is not valide'
                    ], 403);
                }
            } else {
                return response()->json([
                    'message' => 'you have already put a note for this location'
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'incorrect  user id or location id'
            ], 403);
        }
    }
}
