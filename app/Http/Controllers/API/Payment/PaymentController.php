<?php

namespace App\Http\Controllers\API\Payment;

use App\Http\Controllers\Controller;
use App\Models\Premium_status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function handlePayOSWebhook(Request $request)
    {
        // Log::info("Webhook từ PayOS", $request->all());

        // Dữ liệu webhook trả về
        $data = $request->input('data', []);
        $orderCode = $data['orderCode'] ?? null;
        $status = $data['code'] ?? null;
        $description = $data['description'] ?? ''; // Lấy description từ webhook

        // Kiểm tra nếu description bị null
        if (!$description) {
            Log::error("Lỗi: Description từ webhook bị null", ['data' => $data]);
            return response()->json(["error" => "Description is missing"], 400);
        }

        // Log description nhận được
        // Log::info("Description từ webhook:", ['description' => $description]);

        // Trích xuất user_id từ description
        $user_id = null;
        if (preg_match('/UserID\s+(\d+)/', $description, $matches)) {
            $user_id = $matches[1];
        }

        // Log kiểm tra giá trị status và user_id
        // Log::info("Status và UserID", ['status' => $status, 'user_id' => $user_id]);

        if ($status === "00" && $user_id) { // "00" là code thanh toán thành công từ PayOS
            $user = User::find($user_id);
            if ($user) {
                $premium_status = Premium_status::where('user_id', $user->id)->first();
                if (!$premium_status) {
                    Premium_status::create([
                        'user_id' => $user_id,
                        'premium_id' => 1,
                        'expired_at' => Carbon::today()->addDays(30),
                    ]);
                } else {
                    $premium_status->update([
                        'expired_at' => Carbon::parse($premium_status->expired_at)->addDays(30),
                    ]);
                }
                // Log::info("Người dùng {$user->id} đã được cập nhật trạng thái premium.");
            } else {
                Log::warning("Không tìm thấy user với ID: $user_id");
            }
        } else {
            Log::warning("Điều kiện cập nhật premium không thỏa mãn. Status: $status, UserID: $user_id");
        }

        return response()->json(["message" => "Webhook nhận thành công"], 200);
    }
}
