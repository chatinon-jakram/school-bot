<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- [ 1. ตั้งค่าเชื่อมต่อ ] ---
$gas_url = "https://script.google.com/macros/s/AKfycbx5ue2dzjSFqCJ6gN-XJJOtL9j3ICMuifD5A6YDegj2oFsRRZrtzGrahPzNnVYEgxyZ/exec"; 
$webhook_url = "https://discord.com/api/webhooks/1501520381826043946/TIa1l2i3REl96ZStVCpKi5xveJER2jowCGJHyQX_7NySc5jYk80pZUClFjrEpJP7N9Vd";

// --- [ 2. ส่วนของหน้าจอ UI ] ---
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>NR Bot Control Panel</title>";
echo "<style>
    body { font-family: sans-serif; padding: 20px; line-height: 1.6; background: #f4f4f9; }
    .btn { padding: 10px 20px; text-decoration: none; color: white; border-radius: 5px; margin-right: 10px; display: inline-block; font-weight: bold; border: none; cursor: pointer; }
    .btn-blue { background: #3498db; }
    .btn-red { background: #e74c3c; }
    .btn-green { background: #2ecc71; }
    .log-box { background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 5px; margin-top: 20px; overflow-x: auto; }
    hr { margin: 20px 0; border: 0; border-top: 1px solid #ccc; }
</style></head><body>";

echo "<h1>🚀 NR Bot Control Panel</h1>";
echo "<a href='?action=debug' class='btn btn-blue'>▶️ บังคับรัน (ยิงเข้า Discord ทันที)</a>";
echo "<a href='?action=check_error' class='btn btn-red'>🔍 เช็ค Error / การเชื่อมต่อ</a>";
echo "<a href='?action=view_log' class='btn btn-green'>📑 ดู Log จาก Google Sheets</a>";
echo "<hr>";

// --- [ 3. ฟังก์ชันจัดการคำสั่ง ] ---
function google_db($action, $key, $val = "") {
    global $gas_url;
    $ch = curl_init($gas_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["action" => $action, "key" => $key, "val" => $val]));
    $res = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    return $error ? "Error: $error" : trim($res);
}

$action = $_GET['action'] ?? '';

// --- [ 4. ตรรกะการทำงานตามปุ่ม ] ---
echo "<div class='log-box'>";

if ($action == 'debug' || $action == '') {
    $is_debug = ($action == 'debug');
    echo "<h3>" . ($is_debug ? "Mode: Forced Debug" : "Mode: Normal Scan") . "</h3>";
    
    // ใส่รายชื่อ RSS (ย่อไว้เพื่อให้โค้ดไม่ยาวเกินไป เอิร์กเอาตัวเต็มมาใส่ได้ครับ)
    $rss_sources = [
        "งานประชาสัมพันธ์โรงเรียนนางรอง" => "https://rss.app/feeds/1TQl9fs4RGwFQO5u.xml",
        "โรงเรียนนางรอง" => "https://rss.app/feeds/y2raHbpZnAJfIN0p.xml"
        // ... เพิ่มเพจอื่นๆ ตามเดิมได้เลยครับ
    ];

    foreach ($rss_sources as $name => $url) {
        $key = substr(md5($url), 0, 8);
        $rss = @simplexml_load_file($url);
        if (!$rss) { echo "❌ $name: อ่าน RSS ไม่ได้\n"; continue; }

        $guid = (string)$rss->channel->item[0]->guid;
        $last_guid = google_db("get", $key);

        if ($is_debug || ($last_guid && $guid !== $last_guid)) {
            // ส่ง Discord
            $payload = json_encode([
                "username" => $name,
                "content" => "📌 **แจ้งเตือนข่าวใหม่!**\n" . (string)$rss->channel->item[0]->link
            ]);
            $ch = curl_init($webhook_url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            google_db("set", $key, $guid);
            echo "✅ $name: ส่งเข้า Discord แล้ว (HTTP $http_code)\n";
        } else {
            echo "⚪ $name: ข่าวยังเหมือนเดิม (จำรหัสแล้ว)\n";
        }
    }

} elseif ($action == 'check_error') {
    echo "<h3>🔍 Connection Check</h3>";
    $test_sheet = google_db("get", "test_connection");
    echo "Google Sheets Connection: " . ($test_sheet ? "✅ OK ($test_sheet)" : "❌ Failed") . "\n";
    
    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "Discord Webhook: " . ($http_code != 404 ? "✅ OK (Status $http_code)" : "❌ Webhook URL Incorrect") . "\n";

} elseif ($action == 'view_log') {
    echo "<h3>📑 Latest Log Data</h3>";
    echo "ระบบกำลังดึงข้อมูลรหัสข่าวที่บันทึกไว้ล่าสุดจาก Google Sheets...\n";
    // ในที่นี้เราจะดึงรหัสของเพจแรกมาโชว์เป็นตัวอย่าง
    $sample = google_db("get", substr(md5("https://rss.app/feeds/1TQl9fs4RGwFQO5u.xml"), 0, 8));
    echo "รหัสข่าวล่าสุดที่บันทึก (เพจ ปชส.): " . ($sample ?: "ไม่พบข้อมูล");
}

echo "</div><p>Last Update: " . date("H:i:s") . "</p></body></html>";
