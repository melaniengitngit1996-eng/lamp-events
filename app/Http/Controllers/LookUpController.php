<?php

namespace App\Http\Controllers;

use App\Enums\RegistrationType;
use App\Imports\LookUpImport;
use App\Models\LookUp;
use App\Models\Booking;
use App\Models\Registration;
use App\Models\Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class LookUpController extends Controller
{
    /**
     * Methods to bypass authentication.
     * Methods: Show
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'index', 'validation', 'store', 'upload', 'upload_view', 'edit']]);
    }

    /**
     * Return all lookup data
     *
     * @param  String $awtaNumber
     * @return \App\Models\LookUp
     */
    public function index(Event $event, Request $request)
    {
        $search = json_decode($request->search);

        $lookUp = LookUp::with(['registrations' => function ($query) use ($event) {
            $query->where('event_id', $event->id);
        }]);

        if ($search->registration_status != '') {
            if ($search->registration_status == 1) {
                $lookUp = $lookUp->whereHas('registrations', function ($query) use ($event) {
                    $query->where('event_id', $event->id);
                });
            } else {
                $lookUp = $lookUp->whereDoesntHave('registrations', function ($query) use ($event) {
                    $query->where('event_id', $event->id);
                });
            }
        }

        if ($search->local_church) {
            $lookUp = $lookUp->where('local_church', $search->local_church);
        }

        if ($search->keyword) {
            $lookUp = $lookUp->where('fullname', 'LIKE', "%$search->keyword%")
                ->orWhere('lamp_id', 'LIKE', "%$search->keyword%");
        }

        $lookUp = $lookUp->paginate(10);

        return $lookUp;
    }

    /**
     * Checking if record exists in the lookup data
     * Checking by Last Name & Local Church
     *
     * @param  Request $request
     * @return \App\Models\LookUp
     */
    public function validation(Event $event, Request $request)
    {
        $lookUp = LookUp::with(['registrations' => function ($query) use ($event) {
            $query->where('event_id', $event->id);
        }]);

        if ($request->lastname) {
            $lookUp = $lookUp->where('lastname', 'LIKE', "%$request->lastname%");
        }

        if ($request->localChurch) {
            $lookUp = $lookUp->where('local_church', $request->localChurch);
        }

        if ($lookUp->count() === 0) {
            return response()->json(['error' => 'Data not found. Please reach out to your local coordinator.'], 500);
        }


        return $lookUp->orderBy('firstname', 'ASC')->get();
    }

    /**
     * Return delegate record.
     *
     * @param  String $awtaNumber
     * @return \App\Models\LookUp
     */
    public function show(Event $event, $awtaNumber)
    {
        $lookUp = LookUp::where('lamp_id', $awtaNumber)->first();

        if (!$lookUp) {
            return response()->json(['error' => 'Data not found. Please reach out to your local coordinator.'], 404);
        }

        $isRegistered = Registration::where('event_id', $event->id)->where('uuid', $lookUp->lamp_id)->first();

        if ($isRegistered) {
            return response()->json(['error' => 'Sorry, this LAMP ID number is already registered.'], 500);
        }

        return $lookUp;
    }

    /**
     * Bulk upload via excel
     *
     * @param  Request $request
     * @return String
     */
    public function upload(Request $request)
    {
        request()->validate([
            'lookup' => 'required|mimes:xlx,xls,xlsx|max:2048'
        ]);

        FacadesExcel::import(new LookUpImport, $request->file('lookup'));

        return back()->with('massage', 'User Imported Successfully');
    }

    /**
     * Upload via excel view
     *
     * @return View
     */
    public function upload_view()
    {
        return view('lookup.upload');
    }

    /**
     * Edit lookup data view
     *
     * @return View
     */
    public function edit(Event $event, $awtaNumber)
    {
        return view('lookup.edit', [
            'lookup' => LookUp::where('lamp_id', $awtaNumber)->first(),
            'event' => $event
        ]);
    }

    /**
     * Update lookup data
     *
     * @param  String $awtaNumber
     * @param  Request $request
     * @return View
     */
    public function update(Event $event, $awtaNumber, Request $request)
    {
        $lookup = LookUp::where('lamp_id', $awtaNumber)->first();

        $lookup->update([
            'email' => $request->email,
            'firstname' => $request->firstName,
            'lastname' => $request->lastName,
            'fullname' => $request->firstName . ' ' . $request->lastName,
            'facebook_name' => $request->facebookName,
            'local_church' => $request->localChurch,
            'country' => $request->country,
            'category' => $request->category,
            'can_book_days' => $request->canBookDays,
            'cluster_group' => $request->clusterGroup,
            'birthdate' => $request->birthdate
        ]);

        $registration = Registration::where('uuid', $awtaNumber)->first();

        if ($registration) { // if has registration
            $registration->update([
                'email' => $request->email,
                'firstname' => $request->firstName,
                'lastname' => $request->lastName,
                'fullname' => $request->firstName . ' ' . $request->lastName,
                'facebook_name' => $request->facebookName,
                'local_church' => $request->localChurch,
                'country' => $request->country,
                'category' => $request->category,
                'can_book_days' => $request->canBookDays,
                'cluster_group' => $request->clusterGroup
            ]);

            // if has booking
            $registration->bookings()->update([
                'local_church' => $request->localChurch,
            ]);
        }

        return view('lookup.edit', [
            'lookup' => $lookup,
            'event' => $event
        ]);
    }

    /**
     * Create lookup view
     *
     * @return View
     */
    public function create(Event $event)
    {
        return view('lookup.create', [
            'event' => $event
        ]);
    }

    /**
     * Store lookup data
     *
     * @param  String $awtaNumber
     * @param  Request $request
     * @return View
     */
    public function store(Request $request)
    {
        $lookUp = LookUp::where('lamp_id', $request->lampIDNumber)->first();

        if ($lookUp) {
            return response()->json(['error' => 'LAMP ID number already exists.'], 422);
        }

        $lookUp = LookUp::where('fullname', $request->firstName . ' ' . $request->lastName)->first();

        if ($lookUp) {
            return response()->json(['error' => 'Data already exists.'], 422);
        }

        $lookUp = LookUp::create([
            'lamp_id' => $request->lampIDNumber,
            'email' => $request->email,
            'firstname' => $request->firstName,
            'lastname' => $request->lastName,
            'fullname' => $request->firstName . ' ' . $request->lastName,
            'facebook_name' => $request->facebookName,
            'registration_type' => RegistrationType::Member,
            'local_church' => $request->localChurch,
            'country' => $request->country,
            'category' => $request->category,
            'can_book_days' => $request->canBookDays,
        ]);

        return view('lookup.create', [
            'lookup' => $lookUp
        ]);
    }
}
