<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employees;
use App\Penalty;

class PenaltyController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $penalty = Penalty::paginate(5);

        return view('system-mgmt/penalty/index', ['penalty' => $penalty]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('system-mgmt/penalty/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateInput($request);
         Penalty::create([
            'name' => $request['name'],
            'code' => $request['code'],
            'salary' => $request['salary']
        ]);

        return redirect()->intended('system-management/penalty');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $penalty = Penalty::find($id);
        // Redirect to division list if updating division wasn't existed
        if ($penalty == null || count($penalty) == 0) {
            return redirect()->intended('/system-management/penalty');
        }

        return view('system-mgmt/penalty/edit', ['penalty' => $penalty]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $penalty= Penalty::findOrFail($id);
        $this->validateInput($request);
        $input = [
            'name' => $request['name'],
            'code' => $request['code'],
             'salary' => $request['salary']
        ];
        Penalty::where('id', $id)
            ->update($input);
        
        return redirect()->intended('system-management/penalty');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Penalty::where('id', $id)->delete();
         return redirect()->intended('system-management/penalty');
    }

    /**
     * Search division from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $constraints = [
            'name' => $request['name'],
            'code' => $request['code'],
             'salary' => $request['salary']
            ];

       $divisions = $this->doSearchingQuery($constraints);
       return view('system-mgmt/penalty/index', ['penalty' => $penalty, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = Penalty::query();
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {
            if ($constraint != null) {
                $query = $query->where( $fields[$index], 'like', '%'.$constraint.'%');
            }

            $index++;
        }
        return $query->paginate(10);
    }
    private function validateInput($request) {
        $this->validate($request, [
        'name' => 'required|max:60|unique:penalty',
        'code' => 'required|max:60|unique:penalty',
        'salary' => 'required|max:60|unique:penalty'
    ]);
    }
}

