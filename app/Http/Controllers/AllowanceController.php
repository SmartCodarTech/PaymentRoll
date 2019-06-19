<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator; 
use App\Allowance;
use App\Civilian;


class AllowanceController extends Controller
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
          $bonuses = Allowance::paginate(5);

        return view('system-mgmt/allowance/index', ['bonuses' => $bonuses]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         
        return view('system-mgmt/allowance/create');
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
         Allowance::create([

            'allowance_type' => $request['allowance_type'],
            'amount' => $request['amount'],
            'allowance_date' => $request['allowance_date'],
            'staff_type'=>$request['staff_type']
            
        ]);

         
         

        return redirect()->intended('system-management/allowance');
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
        $bonuses = Allowance::find($id);
        // Redirect to division list if updating division wasn't existed
       
         //return view('system-mgmt/allowance/edit', ['bonuses'=>Allowance::find($id)]);
        return view('system-mgmt/allowance/edit', ['bonuses' => $bonuses]);
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

         $bonuses=Allowance::findOrFail($id);
        $this->validate($request,[
            'staff_type' => 'required',
            'allowance_type' => 'required',
            'amount' => 'required',
            'allowance_date' => 'required',
            
        ]);
                
        $bonuses->allowance_type = $request->allowance_type;
        $bonuses->amount = $request->amount;
        
        $bonuses->allowance_date = $request->allowance_date; 
        $bonuses->staff_type = $request->staff_type;     
        $bonuses->save();
        
        $request->session()->flash('status', 'Allowance created');
         return redirect()->intended('system-management/allowance');
        //return redirect()->route('employees.index');
    }


       /** $bonuses = Allowance::findOrFail($id);
        $this->validateInput($request);
        $input = [
            'allowance_type' => $request['allowance_type'],
            'amount' => $request['amount'],
            'allowance_date' => $request['allowance_date'],
            'staff_type'=>$request['staff_type']
        ];
        Allowance::where('id', $id)
            ->update($input);
        
        return redirect()->intended('system-management/allowance');
    }**/

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Allowance::where('id', $id)->delete();
         return redirect()->intended('system-management/allowance');
    }

    /**
     * Search division from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $constraints = [
            'allowance_type' => $request['allowance_type'],
            'amount' => $request['amount'],
            'allowance_date' => $request['allowance_date'],
            'staff_type'=>$request['staff_type']
                    ];

       $divisions = $this->doSearchingQuery($constraints);
       return view('system-mgmt/allowance/index', ['bonuses' => $bonuses, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = Allowance::query();
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
            'allowance_type' => 'required|max:60',
            'amount' => 'required|max:60',
            'allowance_date' => 'required|max:60',
         
            'staff_type' => 'required'
           
        
    ]);
    }
}
