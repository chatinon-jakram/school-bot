<?php
/**
 * NR RSS BOT - Version: Anti-Spam & Full Media
 * แก้ไขปัญหาบอตเด้งรัว และเพิ่มรูปภาพ/ลิงก์ให้กลับมาครบ
 */

error_reporting(E_ERROR | E_PARSE);

// --- [ 1. CONFIGURATION ] ---
// เอิร์กเอา URL ใหม่ที่ได้จากข้อ 1 มาวางทับตรงนี้ครับ
$gas_url = "https://script.google.com/macros/s/AKfycbx5ue2dzjSFqCJ6gN-XJJOtL9j3ICMuifD5A6YDegj2oFsRRZrtzGrahPzNnVYEgxyZ/exec"; 
$webhook_url = "https://discord.com/api/webhooks/1501520381826043946/TIa1l2i3REl96ZStVCpKi5xveJER2jowCGJHyQX_7NySc5jYk80pZUClFjrEpJP7N9Vd";

$action = isset($_GET['action']) ? $_GET['action'] : null;
$is_cron = ($action === null); 

// --- [ 2. CORE FUNCTIONS ] ---
function google_db($act, $k, $v = "") {
    global $gas_url;
    $post_data = json_encode(["action" => $act, "key" => $k, "val" => $v]);
    $ch = curl_init($gas_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $res = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // ถ้า Google ไม่ตอบกลับ 200 หรือค่าว่าง ให้ถือว่าตาย
    if ($http_code != 200 || empty($res)) return "DB_ERROR";
    return trim($res);
}

// --- [ 3. RSS SOURCES (11 แหล่ง) ] ---
$rss_sources = [
    "งานประชาสัมพันธ์โรงเรียนนางรอง" => ["rss" => "https://rss.app/feeds/1TQl9fs4RGwFQO5u.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/324269728_743603630069476_228333852253818672_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=53a332&oh=00_Af68PNKCTkk6MHN-q2yZ6qH0cO8WR188tpti4eFbXSTzZQ&oe=6A009594", "color" => "4ebc00"],
    "โรงเรียนนางรอง" => ["rss" => "https://rss.app/feeds/y2raHbpZnAJfIN0p.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/648873944_26574300548828725_2833658263750313445_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=53a332&oh=00_Af7yXti2unkb8AfMJUGQDRKv9NJyI29t4_660aoZZsk5Pg&oe=6A00A79B", "color" => "e28b00"]
    // ... แหล่งข่าวอื่นๆ ผมตัดออกเพื่อความสั้นตอนก๊อป แต่ในไฟล์จริงเอิร์กใส่ให้ครบนะ
];

// --- [ 4. EXECUTION LOGIC ] ---
if ($is_cron || $action == 'run') {
    if (!$is_cron) echo "<h1>Scanning...</h1><pre>";
    
    foreach ($rss_sources as $name => $info) {
        $key = substr(md5($info['rss']), 0, 8);
        $rss = @simplexml_load_file($info['rss']);
        if (!$rss) continue;

        $item = $rss->channel->item[0];
        $guid = (string)$item->guid;

        // ตรวจสอบกับ Database
        $last_guid = google_db("get", $key);

        // --- จุดสำคัญ: ถ้า Database ตาย ห้ามส่ง Discord เด็ดขาด ---
        if ($last_guid == "DB_ERROR") {
            if (!$is_cron) echo "• $name: ❌ DATABASE FAIL (Skipped to prevent spam)\n";
            continue; 
        }

        if ($guid === $last_guid) {
            if (!$is_cron) echo "• $name: ข่าวเดิม\n";
            continue;
        }

        // ดึงรูปภาพจาก Media Namespace
        $image_url = "";
        $ns = $rss->getNamespaces(true);
        if (isset($ns['media'])) {
            $media = $item->children($ns['media']);
            if (isset($media->content)) $image_url = (string)$media->content->attributes()->url;
        }

        // ส่ง Discord แบบ Full Media
        $payload = [
            "username" => $name,
            "avatar_url" => $info['avatar'],
            "embeds" => [[
                "title" => "📌 " . (string)$item->title,
                "url" => (string)$item->link,
                "description" => mb_strimwidth(strip_tags((string)$item->description), 0, 300, "..."),
                "color" => hexdec($info['color']),
                "image" => !empty($image_url) ? ["url" => $image_url] : null,
                "footer" => ["text" => "M.3/5 | " . date("H:i")]
            ]]
        ];

        $ch = curl_init($webhook_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_exec($ch);
        curl_close($ch);

        // บันทึกลง Database
        google_db("set", $key, $guid);
        if (!$is_cron) echo "• $name: ✅ ส่งข่าวใหม่สำเร็จ!\n";
        usleep(500000);
    }
} else {
    // หน้า UI หลัก
    echo "<h1>ยินดีต้อนรับครับเอิร์ก</h1><p>กรุณาใช้ ?action=view เพื่อดูเมนูควบคุม</p>";
    echo "<p>ระบบป้องกันสแปม: <b>ทำงาน</b> (ถ้าฐานข้อมูลล่ม บอตจะหยุดส่งอัตโนมัติ)</p>";
}
