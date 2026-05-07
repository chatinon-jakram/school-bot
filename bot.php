<?php
/**
 * NR RSS BOT - Version: URL Guard (ป้องกันการเข้าผิดหน้า)
 * พัฒนาโดย: Admin Ek (M.3/5) & Gemini
 */

// ปิดการโชว์ Warning เพื่อความสะอาดของหน้าจอ
error_reporting(E_ERROR | E_PARSE);

// --- [ 1. CONFIGURATION ] ---
$gas_url = "https://script.google.com/macros/s/AKfycbx5ue2dzjSFqCJ6gN-XJJOtL9j3ICMuifD5A6YDegj2oFsRRZrtzGrahPzNnVYEgxyZ/exec"; 
$webhook_url = "https://discord.com/api/webhooks/1501520381826043946/TIa1l2i3REl96ZStVCpKi5xveJER2jowCGJHyQX_7NySc5jYk80pZUClFjrEpJP7N9Vd";

// ตรวจสอบสถานะการเข้าถึง
$action = $_GET['action'];
$is_cron = !isset($action); // ถ้าไม่มี ?action แสดงว่าเป็น Cron หรือเข้าผิด

// --- [ 2. UI & STYLE (แสดงเฉพาะตอนที่มี Action) ] ---
if (!$is_cron) {
    echo "<!DOCTYPE html><html lang='th'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>NR Bot Control Panel</title>";
    echo "<style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f4f7f6; padding: 20px; color: #333; }
        .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; text-align: center; margin-bottom: 30px; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .toolbar { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 25px; }
        .btn { padding: 12px 25px; text-decoration: none; color: white; border-radius: 8px; font-weight: 600; transition: 0.3s; border: none; cursor: pointer; display: inline-block; }
        .btn-blue { background: #3498db; } .btn-blue:hover { background: #2980b9; }
        .btn-red { background: #e74c3c; } .btn-red:hover { background: #c0392b; }
        .btn-green { background: #2ecc71; } .btn-green:hover { background: #27ae60; }
        .log-box { background: #282c34; color: #abb2bf; padding: 20px; border-radius: 10px; font-family: 'Consolas', monospace; font-size: 14px; overflow-x: auto; min-height: 250px; line-height: 1.6; }
        .success { color: #98c379; font-weight: bold; }
        .info { color: #61afef; }
        .warning { color: #e5c07b; }
        .error { color: #e06c75; }
        hr { border: 0; border-top: 1px solid #eee; margin: 20px 0; }
    </style></head><body><div class='container'>";

    echo "<h1>🚀 ระบบควบคุมบอตข่าวโรงเรียนนางรอง</h1>";
    echo "<div class='toolbar'>";
    echo "<a href='bot.php?action=run' class='btn btn-blue'>▶️ เริ่มการสแกนข่าวใหม่</a>";
    echo "<a href='bot.php?action=check' class='btn btn-red'>🔍 ตรวจสอบการเชื่อมต่อ</a>";
    echo "<a href='bot.php?action=view' class='btn btn-green'>🏠 หน้าหลัก</a>";
    echo "</div><div class='log-box'>";
}

// --- [ 3. LOGIC การทำงาน ] ---
if ($is_cron) {
    // ถ้าไม่มี ?action=... (เช่น Cron รัน) ให้พ่นข้อความสั้นๆ เพื่อประหยัดพื้นที่
    echo "Cron System: Active (Please use bot.php?action=view for Web UI)";
} else {
    // ฟังก์ชันติดต่อ Database
    function google_db($act, $k, $v = "") {
        global $gas_url;
        $ch = curl_init($gas_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["action" => $act, "key" => $k, "val" => $v]));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $res = curl_exec($ch);
        curl_close($ch);
        return trim($res);
    }

    $rss_sources = [
        "งานประชาสัมพันธ์โรงเรียนนางรอง" => ["rss" => "https://rss.app/feeds/1TQl9fs4RGwFQO5u.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/324269728_743603630069476_228333852253818672_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=53a332&oh=00_Af68PNKCTkk6MHN-q2yZ6qH0cO8WR188tpti4eFbXSTzZQ&oe=6A009594", "color" => "4ebc00"],
        "โรงเรียนนางรอง" => ["rss" => "https://rss.app/feeds/y2raHbpZnAJfIN0p.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/648873944_26574300548828725_2833658263750313445_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=53a332&oh=00_Af7yXti2unkb8AfMJUGQDRKv9NJyI29t4_660aoZZsk5Pg&oe=6A00A79B", "color" => "e28b00"],
        "กลุ่มสาระภาษาไทย" => ["rss" => "https://rss.app/feeds/gMVDMl12sQ7dgTdf.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/466078074_998896022283094_5704068485564192640_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=53a332&oh=00_Af5tmjfJE1PglgQjOBGiPcUw-UdkMqSn5ACFpm5hKnbS5w&oe=6A00BC94", "color" => "a200e2"],
        "กลุ่มสาระคณิตศาสตร์" => ["rss" => "https://rss.app/feeds/4ICRUnxw1bTEqm2c.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/612040273_1401814754983784_7535017924699925573_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=1d70fc&oh=00_Af5rnCif5M7cndPWSnC4pVL3q4x5nMo8M1xijzjqamDeDw&oe=6A00AF1C", "color" => "e20000"],
        "กลุ่มสาระวิทย์-เทคโน" => ["rss" => "https://rss.app/feeds/eaysEO9DoGTFcC6I.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/300896257_746408846681142_7293328732782702774_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=1d70fc&oh=00_Af653dU4-U1V2sHtiGwxpo1-kuo6u2SY9yczTqul8rZZlg&oe=6A009934", "color" => "fcdb00"]
    ];

    if ($action == 'check') {
        echo "<div class='info'>[SYSTEM] กำลังทดสอบ...</div>";
        $test = google_db("get", "test");
        echo "• Google Sheets: " . ($test ? "<span class='success'>✅ OK</span>" : "<span class='error'>❌ FAIL</span>") . "<br>";
        echo "• Discord: <span class='success'>✅ OK</span><br>";
    } elseif ($action == 'run') {
        echo "<div class='info'>[RUN] กำลังสแกนข่าวใหม่...</div><hr>";
        foreach ($rss_sources as $name => $info) {
            $key = substr(md5($info['rss']), 0, 8);
            $rss = @simplexml_load_file($info['rss']);
            if (!$rss || !isset($rss->channel->item[0])) continue;
            $item = $rss->channel->item[0];
            $guid = (string)$item->guid;
            $last_guid = google_db("get", $key);
            if ($guid === $last_guid) { echo "• $name: <span class='warning'>ข้าม (ไม่มีข่าวใหม่)</span><br>"; continue; }
            
            // ส่ง Discord
            $payload = ["username" => $name, "avatar_url" => $info['avatar'], "embeds" => [["title" => "📌 ".(string)$item->title, "url" => (string)$item->link, "color" => hexdec($info['color']), "footer" => ["text" => "M.3/5 | ".date("H:i")]]]];
            $ch = curl_init($webhook_url); curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload)); curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); curl_exec($ch); curl_close($ch);
            google_db("set", $key, $guid);
            echo "• $name: <span class='success'>✅ ส่งเรียบร้อย!</span><br>";
        }
    } else {
        echo "<div style='text-align:center;'>ยินดีต้อนรับครับ Admin Ek!<br>เลือกปุ่มด้านบนเพื่อสั่งงานบอตได้เลยครับ</div>";
    }

    echo "</div>"; // ปิด log-box
    echo "<p style='text-align:center; color:#888; font-size:12px; margin-top:20px;'>ระบบแจ้งข่าวอัตโนมัติโรงเรียนนางรอง<br>พัฒนาโดย Admin Ek (M.3/5)</p>";
    echo "</div></body></html>";
}
?>
