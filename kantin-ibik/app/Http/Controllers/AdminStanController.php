<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\AdminStan;
use App\Models\Stand;
use App\Models\FoodDrink;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminStanController extends Controller {
    public function updateProfile(Request $request) {
        $user = Auth::user();
        $admin = AdminStan::where('user_id', $user->id)->first();
        $stand = Stand::find($admin->stand_id);

        $stand->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json(['message' => 'Profile updated']);
    }

    public function confirmOrder(Request $request) {
        $order = Order::find($request->order_id);
        $order->update(['status' => $request->status]);
        return response()->json(['message' => 'Order status updated']);
    }

    public function viewMonthlyOrders() {
        $orders = Order::whereMonth('order_date', Carbon::now()->month)
            ->with('items')->get();
        return response()->json($orders);
    }

    public function viewMonthlyRevenue() {
        $revenue = Order::whereMonth('order_date', Carbon::now()->month)
            ->sum('total_price');
        return response()->json(['total_revenue' => $revenue]);
    }
}