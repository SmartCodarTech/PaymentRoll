<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Bank;

class BankController extends Controller
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
        $banks = Bank::paginate(5);

        return view('system-mgmt/bank/index', ['banks' => $banks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('system-mgmt/bank/create');
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
         Bank::create([
            'name' => $request['name'],
            'branch' => $request['branch'],
            'code' => $request['code']
        ]);

        return redirect()->intended('system-management/bank');
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
        $bank = Bank::find($id);
        // Redirect to division list if updating division wasn't existed
        if ($bank == null || count($bank) == 0) {
            return redirect()->intended('/system-management/bank');
        }

        return view('system-mgmt/bank/edit', ['bank' => $bank]);
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
        $bank = Bank::findOrFail($id);
        $this->validateInput($request);
        $input = [
            'name' => $request['name'],
            'branch' => $request['branch'],
             'code' => $request['code']
        ];
        Bank::where('id', $id)
            ->update($input);
        
        return redirect()->intended('system-management/bank');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Bank::where('id', $id)->delete();
         return redirect()->intended('system-management/bank');
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
            'branch' => $request['branch'],
             'code' => $request['code']
            ];

       $divisions = $this->doSearchingQuery($constraints);
       return view('system-mgmt/bank/index', ['banks' => $banks, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = Bank::query();
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
        'name' => 'required|max:60',
        'branch' => 'required|max:60',
        'code' => 'required|max:60|unique:bank'
    ]);
    }
}
