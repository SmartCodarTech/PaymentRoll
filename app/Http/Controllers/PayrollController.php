<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use App\Payroll;
use App\Civilian;
use App\Allowance;
use App\Tax;

class PayrollController extends Controller
{
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
        $civilian = Civilian::findOrFail($id);
        //return view('payroll.payroll')->with('employee',$employee);
         return view('payroll-mgmt/index', ['civilians' => $civilians]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tax = Tax::all();
        $allowances = Allowance::all();
        $civilian = Civilian::findOrFail($id);
        return view('payroll-mgmt/index', ['civilians' => $civilians]);
        //return view('payroll.create')->with('employee',$employee);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'hours'=> 'required',
            'rate'=>'required',
            'over_time' => 'required|bool'
        ]);
        
        $payroll = Payroll::create([
            'hours' => $request->hours,
            'rate' => $request->rate,
            'over_time' => $request->over_time,
            'civilian_id' => $id
        ]);
        
        $payroll->grossPay();
        $payroll->save();
        
        Session::flash('success', 'Payroll Created');
        return redirect()->route('payrolls.show',['id'=>$id]);
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
         $payroll = Payroll::findOrFail($id);
         return view('payroll.edit')->with('payroll',$payroll);
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
         $this->validate($request,[
            'hours'=> 'required',
            'rate'=>'required',
            'over_time' => 'required|bool'
        ]);
        
        $payroll = Payroll::findOrFail($id);
        $payroll->hours = $request->hours;
        $payroll->rate= $request->rate;
        $payroll->over_time = $request->over_time;
        $payroll->save();       
        
        $payroll->grossPay();
        $payroll->save();
        
        Session::flash('success', 'Payroll Updated ready for download');
        return redirect()->route('payrolls.show',['id'=>$payroll->employee_id]);    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $payroll=Payroll::findOrFail($id);
        $payroll->delete();
        
        Session::flash('success','Payroll Deleted');
        return redirect()->back();
    }
    public function bin(){
        $payrolls=Payroll::onlyTrashed()->get();
        return view('payroll.bin')->with('payrolls', $payrolls);
    }
    
    public function restore($id){
        $payroll=Payroll::withTrashed()->where('id', $id)->first();
        $payroll->restore();
        
        Session::flash('success', 'payroll row is restored.');
        return redirect()->route('employees.index');
    }
    
    public function kill($id){
        $payroll=Payroll::withTrashed()->where('id', $id)->first();     
        $payroll->forceDelete();
        
        Session::flash('success', 'payroll permanently destroyed.');
        return redirect()->route('civilian.index');
    }

    }
}
