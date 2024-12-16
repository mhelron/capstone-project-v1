<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class FoodTasteControllerAdmin extends Controller
{
    protected $database, $foodtaste, $reservations;
    public function __construct(Database $database){
        $this->database = $database;
        $this->foodtaste = 'foodtaste';
    }
    public function index(){
        $foodtaste = $this->database->getReference('foodtaste')->getValue();
        $foodtaste = is_array($foodtaste) ? $foodtaste : [];
        $isExpanded = session()->get('sidebar_is_expanded', true);

        return view('admin.foodtaste.index', compact('foodtaste', 'isExpanded'));
    }

    public function setSchedule(Request $request, $id)
    {
        try {
            $request->validate([
                'set_date' => 'required|date',
                'set_time' => 'required',
            ]);

            $this->database->getReference($this->foodtaste . '/' . $id)->update([
                'set_date' => $request->set_date,
                'set_time' => $request->set_time,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return redirect()->back()->with('status', 'Schedule has been set successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to set schedule: ' . $e->getMessage());
        }
    }
}
