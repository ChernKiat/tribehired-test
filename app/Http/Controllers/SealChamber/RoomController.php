<?php

namespace App\Http\Controllers\SealChamber;

use App\Http\Controllers\Controller;
use App\Models\SealChamber\Room;

class RoomController extends Controller
{
    public function __construct()
    {
    }

    public function join()
    {
        $tracking = OrderTrackingNo::with(['order', 'scan', 'details'])->where('order_tracking_no', $request->tracking_no)->first();
        if (!empty($tracking)) {
            $order = $tracking->order;
            $scan = $tracking->scan;
            $type = ScanComponent::ORDER_TRACKING_NO;
        } else {
            $order = Order::with(['scan'])->where('order_tracking_no', $request->tracking_no)->first();
            if (!empty($order)) {
                $scan = $order->scan;
                $type = ScanComponent::ORDER;
            } else {
                $dhl = DHLLog::with(['order.scan'])->where('code', $request->tracking_no)->first();
                if (!empty($dhl)) {
                    $order = $dhl->order;
                    $scan = $order->scan;
                    $type = ScanComponent::DHL;
                } else {
                    return json_encode(array('status' => 'error', 'message' => 'Tracking No. ' . $request->tracking_no . " doesn't exist"));
                }
            }
        }

        if (!is_null($scan)) {
            if (!is_null($order->scan->collected_at)) {
                return json_encode(array('status' => 'error', 'message' => 'Tracking No: ' . $request->tracking_no . "<br/>Batch No: " . $scan->collected_at->getTimestamp() . "<br/>Picked up at: " . $scan->collected_at->addHours(8)));
            } elseif (!is_null($scan->processed_at)) {
                return json_encode(array('status' => 'error', 'message' => 'Tracking No: ' . $request->tracking_no . "<br/>Processed at: " . $scan->processed_at->addHours(8)));
            } elseif (!is_null($scan->cancelled_at)) {
                return json_encode(array('status' => 'error', 'message' => 'Tracking No: ' . $request->tracking_no . "<br/>Cancelled at: " . $scan->cancelled_at->addHours(8)));
            } else {
                return json_encode(array('status' => 'error', 'message' => 'Tracking No: ' . $request->tracking_no . "<br/>Scanned at: " . $scan->scanned_at->addHours(8)));
    }
}
