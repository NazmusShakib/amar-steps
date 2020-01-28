<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ActivityLogController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activityLogs = ActivityLog::with('user')->select(
            'id', 'activity')->where('user_id', Auth::id())
            ->orderBy('created_at', 'DESC')->paginate(10);

        return response()->json($activityLogs, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity' => 'required',
            'notes' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->only(['activity', 'notes']);
        $input['user_id'] = Auth::id();
        $activityLog = ActivityLog::create($input);

        return $this->sendResponse($activityLog, 'Activity has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $activityLog = ActivityLog::with('user')->find($id);

        if (is_null($activityLog)) {
            return $this->sendError('Activity not found.');
        }

        return $this->sendResponse($activityLog, 'Activity retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'activity' => 'required',
            'notes' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $activityLog = ActivityLog::find($id);
        $activityLog->update([
            'activity' => $request->activity,
            'notes' => $request->notes,
        ]);

        return $this->sendResponse($activityLog, 'Activity has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            ActivityLog::findOrFail($id)->delete();
            return $this->sendResponse([], 'Activity has been deleted successfully.');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), '', 422);
        }
    }

}
