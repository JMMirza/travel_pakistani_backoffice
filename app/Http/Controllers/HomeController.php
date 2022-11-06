<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $user = Auth::user();
        $user = User::with('userable.staff.user')->where('id', $user->id)->first();

        $user_ids = [];
        $data = $user->userable->staff;

        foreach ($data as $key => $s) {
            $user_ids[] = $s->user->id;
        }

        // dd($user_ids);

        echo $sql = "
        
            SELECT
                u.id,
                COUNT(q.id) AS quotation_count,
                q.status,
                qs.label
            FROM
                quotations AS q
            JOIN users AS u
            ON
                q.userId = u.id
            RIGHT JOIN quotation_statuses AS qs
            ON
                q.status = qs.id
            WHERE u.id IN (" . implode(',', $user_ids) . ")
            GROUP BY
                u.id,
                q.status,
                qs.label
        ";

        $data = \DB::select($sql);

        dd($data);

        return view('home');
    }
}
