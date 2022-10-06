<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Inquiry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Database\QueryException;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Inquiry::with(['createdByUser', 'user'])->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('preferredDate', function ($row) {
                    return $row->tourFrom . '-' . $row->tourEnd;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'Expired') {
                        return '<span class="badge bg-danger">Expired</span>';
                    } elseif ($row->status == 'In Process') {
                        return '<span class="badge bg-primary">Pending</span>';
                    } elseif ($row->status == 'Confirm') {
                        return '<span class="badge bg-default">Complete</span>';
                    } else {
                        return '<span class="badge bg-warning">New</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return view('inquiries.actions', ['row' => $row]);
                })
                ->rawColumns(['action', 'preferredDate', 'status'])
                ->make(true);
        }
        return view('inquiries.inquiries');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::latest()->get();
        $cities = City::where('is_active', 'Y')->where('show_in_app', '0')->get();
        return view('inquiries.add_new_inquiry', ['users' => $users, 'cities' => $cities]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'contactNo' => 'required|string|max:255',
            'cityId' => 'required|integer',
            'adults' => 'required|integer',
        ]);

        // $input  = $request->all();
        // if (isset(Auth::user()->id)) {
        //     $logginedUserInquiry = Auth::user();
        //     if ($logginedUserInquiry->defaultAssigneeInquiries) {
        //         $assignedTo = $logginedUserInquiry->defaultAssigneeInquiries;
        //     } else {
        //         $assignedTo = $logginedUserInquiry->id;
        //     }

        //     $createdBy = $logginedUserInquiry->id;
        //     $inquiryFrom = $logginedUserInquiry->id;

        //     /* if($logginedUserInquiry->userable_type=="Admin")
        //     {
        //         $processedByName = $request->processedByName;
        //     }*/
        // } else if ($request->operator > 0) {
        //     $operator = User::find($request->operator);
        //     if ($operator->defaultAssigneeInquiries) {
        //         $assignedTo = $operator->defaultAssigneeInquiries;
        //         $createdBy = $operator->id;
        //     } else {
        //         $assignedTo = $operator->id;
        //         $createdBy = $operator->id;
        //     }
        //     $inquiryFrom = $operator->id;
        // } else {

        //     $admin = User::where("userable_type", "Admin")->first();
        //     if ($admin->defaultAssigneeInquiries) {
        //         $assignedTo = $admin->defaultAssigneeInquiries;
        //         $createdBy = $admin->defaultAssigneeInquiries;
        //     } else {
        //         $defaultUserInquiry = env('DEFAULT_USER_INQUIRY');
        //         $assignedTo = User::where("username", $defaultUserInquiry)->first()->id;
        //         $createdBy = $admin->id;
        //     }
        //     $status = "New";
        //     $inquiryFrom = $adminInfo->id;
        // }
        $assignedTo = $request->processedByName;
        $createdBy = \Auth::user()->id;

        $inquiryFrom = \Auth::user()->id;
        $arrCitiesToVisit = $request->cities;
        // dd($arrCitiesToVisit);
        /* $inputCities = json_decode($request->citiesToVisit);
        $arrCitiesToVisit = array();
        $totalCities = count($inputCities);

        for($i=0;$i<$totalCities;$i++)
        {
            $arrCitiesToVisit[] = $inputCities[$i]->id;
        }*/

        $arrOtherAreas = $request->otherAreas; //array();
        /* if($request->otherAreas)
        {
            $otherAreas = json_decode($request->otherAreas);
            $totalOAreas = count($otherAreas);
            for($i=0;$i<$totalOAreas;$i++)
            {
                $arrOtherAreas[] = $otherAreas[$i]->value;
            }
        }  */

        // dd(json_encode(array_values($arrCitiesToVisit)));
        $input['inquiryFrom'] = $inquiryFrom;
        $input['assignedTo'] = $assignedTo;
        $input['createdBy'] = $createdBy;
        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $input['contactNo'] = $request->contactNo;
        $input['cityId'] = $request->cityId;
        $input['citiesToVisit'] = json_encode($arrCitiesToVisit);
        $input['otherAreas'] = json_encode($arrOtherAreas);
        $tourFrom = Carbon::createFromDate($request->tourFrom);
        $input['tourFrom'] = $tourFrom->format("Y-m-d H:i:s");
        $tourEnd = Carbon::createFromDate($request->tourTo);
        $input['tourEnd'] = $tourEnd->format('Y-m-d H:i:s');
        $input['adults'] = $request->adults;
        $input['children'] = $request->children;
        $requiredServicesUser = array();

        // for ($s = 0; $s < count($servicesAll); $s++) {
        //     $requiredServicesUser[$servicesAll[$s]] = false;
        // }
        // if ($request->requiredServices) {

        //     $userRequireds = $request->requiredServices;
        //     $ur = count($userRequireds);
        //     for ($i = 0; $i < $ur; $i++) {
        //         $requiredServicesUser[$userRequireds[$i]] = true;
        //     }
        // }

        if ($request->hotel || $request->hotel == 'on') {
            $requiredServicesUser['hotel'] = true;
        } else {
            $requiredServicesUser['hotel'] = false;
        }


        if ($request->meal || $request->meal == 'on') {
            $requiredServicesUser['meal'] = true;
        } else {
            $requiredServicesUser['meal'] = false;
        }

        if ($request->transport || $request->transport == 'on') {
            $requiredServicesUser['transport'] = true;
        } else {
            $requiredServicesUser['transport'] = false;
        }

        if ($request->activities || $request->activities == 'on') {
            $requiredServicesUser['activities'] = true;
        } else {
            $requiredServicesUser['activities'] = false;
        }

        $input['requiredServices'] = json_encode($requiredServicesUser);
        $input['specialRequest'] = $request->specialRequest;
        $input['source'] = $request->source;
        $input['reason'] = $request->reason;
        $input['status'] = isset($status) ? $status : "New";
        $input['staffRemarks'] = $request->staffRemarks;
        // dd($input);
        // if ($request->has("processedByName") && $request->processedByName > 0) {
        //     $inquiry->assignedTo = $request->processedByName;
        // if ($inquiry->assignedTo != $logginedUserInquiry->id) {
        //     $inquiry->status = "Pending";
        // } else {
        //     $inquiry->status = "New";
        // }
        // }
        // dd($input);
        $inquiry = Inquiry::create($input);
        // $this->sendSMSAlert($inquiry);

        return redirect()->route('inquiries.index')
            ->with('success', 'Inquiry created successfully.');
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
        $inquiry = Inquiry::findOrFail($id);
        // dd($inquiry->toArray());
        $users = User::latest()->get();
        $cities = City::where('is_active', 'Y')->where('show_in_app', '0')->get();
        return view('inquiries.edit_inquiry', ['inquiry' => $inquiry, 'users' => $users, 'cities' => $cities]);
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
        $request->validate([
            'name' => 'required|string|max:255',
            'contactNo' => 'required|string|max:255',
            'cityId' => 'required|integer',
            'adults' => 'required|integer',
            'requiredServices' => 'required|text'
        ]);
        $inquiry = Inquiry::findOrFail($id);

        $inquiry->update($request->all());

        return redirect()->route('inquiries.index')
            ->with('success', 'Inquiry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        try {
            return $inquiry->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    private function sendSMSAlert($inquiry)
    {

        /*
        * -------------------------------------------------------------
        * send sms to operator/user :: service used: sendpk.com
        * ---------------------------------------------------------------
        */
        $username = "923139367626"; // Account Username
        $password = "Wonderweal-99"; // Account Password
        $sender = "26144";
        $adminNumbers = array("923005681728", "923105660406", "923075758907", "923329488482");
        $mobiles = array();

        //$mobile[] = "923075758907";
        $logginedUser = Auth::user();
        if (isset($logginedUser->userable_type) && $logginedUser->userable_type == "Admin") {
            $mobiles[] = $logginedUser->phone;
            $mobiles = array_merge($adminNumbers, $mobiles);
        } else if (isset($logginedUser->userable_type) && $logginedUser->userable_type == "Operator") {
            $mobiles[] = $logginedUser->userable->contactNumber;
        } else {
            $mobiles = array_merge($adminNumbers, $mobiles);
        }

        if ($totalMobiles = count($mobiles)) {
            $url = "https://sendpk.com/api/sms.php?username=" . $username . "&password=" . $password . "";
            $message = "A customer " . $inquiry->name . " has submitted an inquiry. Please review and respond to that inquiry.";
            for ($i = 0; $i < $totalMobiles; $i++) {
                $post = "sender=" . urlencode($sender) . "&mobile=" . urlencode($mobiles[$i]) . "&message=" . urlencode($message) . "";
                $ch = curl_init();

                $timeout = 30; // set to zero for no timeout
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $result = curl_exec($ch);
            }
        }
    }
}
