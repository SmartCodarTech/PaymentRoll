<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Debit;
use App\Employees;

class DebitController extends Controller
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
        $debit = Debit::paginate(5);

        return view('system-mgmt/debit/index', ['debit' => $debit]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('system-mgmt/debit/create');
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
         Debit::create([
            'name' => $request['name'],
            'code' => $request['code'],
            'salary' => $request['salary']
        ]);

        return redirect()->intended('system-management/debit');
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
        $debit = Debit::find($id);
        // Redirect to division list if updating division wasn't existed
        if ($debit == null || count($debit) == 0) {
            return redirect()->intended('/system-management/debit');
        }

        return view('system-mgmt/debit/edit', ['debit' => $credit]);
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
        $debit= Debit::findOrFail($id);
        $this->validateInput($request);
        $input = [
            'name' => $request['name'],
            'code' => $request['code'],
             'salary' => $request['salary']
        ];
        Credit::where('id', $id)
            ->update($input);
        
        return redirect()->intended('system-management/debit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Debit::where('id', $id)->delete();
         return redirect()->intended('system-management/debit');
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
       return view('system-mgmt/debit/index', ['debits' => $debits, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = Debit::query();
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
        'name' => 'required|max:60|unique:credit',
        'code' => 'required|max:60|unique:credit',
        'salary' => 'required|max:60|unique:credit'
    ]);
    }
}

