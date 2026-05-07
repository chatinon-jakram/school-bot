<?php
/**
 * NR RSS BOT - Version: The Ultimate Freedom (ร.ศ. 124)
 * FIXED: UI Navigation, Database Connection, and Anti-Spam
 */

// ปิดการโชว์ Warning
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set("Asia/Bangkok");

// --- [ 1. CONFIGURATION ] ---
$gas_url = "https://script.google.com/macros/s/AKfycbXXtpKmLqEY83CpbgEpE5pyN5_ATjP4E0bhhwc3QKB2f9spFeS5CxgiSyJNNf-Z14A/exec"; 
$webhook_url = "https://discord.com/api/webhooks/1501520381826043946/TIa1l2i3REl96ZStVCpKi5xveJER2jowCGJHyQX_7NySc5jYk80pZUClFjrEpJP7N9Vd";

$action = isset($_GET['action']) ? $_GET['action'] : 'cron'; 

// --- [ 2. CORE FUNCTIONS ] ---
function google_db($act, $k, $v = "") {
    global $gas_url;
    $post_data = json_encode(["action" => $act, "key" => $k, "val" => $v]);
    $ch = curl_init($gas_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 25);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $res = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code != 200 || empty($res)) return "DATABASE_CONNECT_FAIL";
    return trim($res);
}

// --- [ 3. ALL RSS SOURCES (11 แหล่ง) ] ---
$rss_sources = [
    "งานประชาสัมพันธ์โรงเรียนนางรอง" => ["rss" => "https://rss.app/feeds/1TQl9fs4RGwFQO5u.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/324269728_743603630069476_228333852253818672_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=53a332&oh=00_Af68PNKCTkk6MHN-q2yZ6qH0cO8WR188tpti4eFbXSTzZQ&oe=6A009594", "color" => "4ebc00"],
    "โรงเรียนนางรอง" => ["rss" => "https://rss.app/feeds/y2raHbpZnAJfIN0p.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/648873944_26574300548828725_2833658263750313445_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=53a332&oh=00_Af7yXti2unkb8AfMJUGQDRKv9NJyI29t4_660aoZZsk5Pg&oe=6A00A79B", "color" => "e28b00"],
    "กลุ่มสาระการเรียนรู้ภาษาไทย" => ["rss" => "https://rss.app/feeds/gMVDMl12sQ7dgTdf.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/466078074_998896022283094_5704068485564192640_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=53a332&oh=00_Af5tmjfJE1PglgQjOBGiPcUw-UdkMqSn5ACFpm5hKnbS5w&oe=6A00BC94", "color" => "a200e2"],
    "กลุ่มสาระการเรียนรู้คณิตศาสตร์" => ["rss" => "https://rss.app/feeds/4ICRUnxw1bTEqm2c.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/612040273_1401814754983784_7535017924699925573_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=1d70fc&oh=00_Af5rnCif5M7cndPWSnC4pVL3q4x5nMo8M1xijzjqamDeDw&oe=6A00AF1C", "color" => "e20000"],
    "กลุ่มสาระการเรียนรู้วิทยาศาสตร์และเทคโนโลยี" => ["rss" => "https://rss.app/feeds/eaysEO9DoGTFcC6I.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/300896257_746408846681142_7293328732782702774_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=1d70fc&oh=00_Af653dU4-U1V2sHtiGwxpo1-kuo6u2SY9yczTqul8rZZlg&oe=6A009934", "color" => "fcdb00"],
    "กลุ่มสาระการเรียนรู้สังคมศึกษาฯ" => ["rss" => "https://rss.app/feeds/p3XziBCnjAbksa3a.xml", "avatar" => "https://cdn-icons-png.flaticon.com/512/3534/3534033.png", "color" => "00b0fc"],
    "กลุ่มสาระการเรียนรู้สุขศึกษาและพลศึกษา" => ["rss" => "https://rss.app/feeds/9Gev9MaiKdlz1rb5.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/452480471_122102462126421014_6871839481956866531_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=53a332&oh=00_Af6TZrnPBczjm4xZvwnYDxp7_k7KYmG65IfDIxmwWfs_iQ&oe=6A00AE60", "color" => "fc00eb"],
    "กลุ่มสาระการเรียนรู้ศิลปะ" => ["rss" => "https://rss.app/feeds/2QpDynw9Qm7XRbtm.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/481299726_1129102305678515_2278776288030225200_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=53a332&oh=00_Af7cMS4DgBTSZmdxPStAzsa5tRE894ScZO5F3pO7Uqxb7A&oe=6A00B39B", "color" => "3200fc"],
    "กลุ่มสาระการเรียนรู้การงานอาชีพ" => ["rss" => "https://rss.app/feeds/2FqXov6HBHJNs7Rv.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/471164713_1233258884423055_869006746446843372_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=53a332&oh=00_Af4s29tUJRh6Ivi2Lv8mEX2dHjpYGUnrkxP9UnfQHrtvVg&oe=6A00A0C0", "color" => "fc8600"],
    "กลุ่มสาระการเรียนรู้ภาษาต่างประเทศ" => ["rss" => "https://rss.app/feeds/dwsJ9ElT7Rf8O8KV.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-1/353797149_3193673687444746_8123120628808621095_n.jpg?stp=dst-jpg_s200x200_tt6&_nc_cat=106&ccb=1-7&_nc_sid=2d3e12&oh=00_Af7SF4xuFv1Am8zldzAkMfmFLNTOFfwIlLrkL5oNl2MP2Q&oe=6A00A5AE", "color" => "fc0071"],
    "คณะกรรมการสภานักเรียนโรงเรียนนางรอง" => ["rss" => "https://rss.app/feeds/cdeTCXnSfyaJguza.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/307475364_478915944282703_3356226624056089045_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=1d70fc&oh=00_Af766loH9oWJedrQSAzajyrz0bYHFycopqUSM2bqnYFs0A&oe=6A00AE08", "color" => "ffffff"]
];

// --- [ 4. WEB INTERFACE ] ---
if ($action === 'view' || $action === 'run' || $action === 'check') {
    echo "<!DOCTYPE html><html lang='th'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>NR Bot Control Panel</title>";
    echo "<style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f0f2f5; padding: 20px; color: #1c1e21; }
        .container { max-width: 800px; margin: auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #1877f2; text-align: center; font-size: 28px; margin-bottom: 30px; }
        .toolbar { display: flex; gap: 15px; justify-content: center; margin-bottom: 30px; }
        .btn { padding: 12px 28px; text-decoration: none; color: white; border-radius: 10px; font-weight: bold; transition: 0.3s; cursor: pointer; border: none; font-size: 16px; display: inline-block; }
        .btn-blue { background: #1877f2; } .btn-blue:hover { background: #166fe5; }
        .btn-red { background: #fa3e3e; } .btn-red:hover { background: #e03535; }
        .log-box { background: #1c1e21; color: #e4e6eb; padding: 25px; border-radius: 12px; font-family: 'Consolas', monospace; font-size: 14px; min-height: 150px; line-height: 1.7; border-left: 5px solid #1877f2; }
        .success { color: #45bd62; } .warning { color: #f7b928; } .error { color: #f3425f; }
        .footer { text-align: center; color: #65676b; font-size: 13px; margin-top: 30px; line-height: 1.6; }
    </style></head><body><div class='container'><h1>🚀 ระบบควบคุมบอตโรงเรียนนางรอง (M.3/5)</h1><div class='toolbar'>";
    echo "<a href='bot.php?action=run' class='btn btn-blue'>▶️ เริ่มสแกนข่าว</a> ";
    echo "<a href='bot.php?action=check' class='btn btn-red'>🔍 เช็คสถานะฐานข้อมูล</a>";
    echo "</div><div class='log-box'>";

    if ($action === 'check') {
        $test = google_db("get", "test_connection");
        echo "Google Sheets Status: " . ($test !== "DATABASE_CONNECT_FAIL" ? "<span class='success'>✅ OK ($test)</span>" : "<span class='error'>❌ FAIL (โปรดเช็ค URL GAS)</span>");
    } elseif ($action === 'run') {
        echo "<b>กำลังเริ่มสแกน...</b><br>";
        process_bot(false);
    } else {
        echo "ยินดีต้อนรับครับเอิร์ก พักผ่อนก่อนนะ เดี๋ยวระบบจัดการต่อเองครับ<br>สถานะระบบป้องกันสแปม: <span class='success'>ทำงาน</span>";
    }

    echo "</div><div class='footer'>บอตได้รับอิสระ ไม่เป็นทาสการสแปมตามพระราชบัญญัติเลิกทาส ร.ศ. 124 🇹🇭<br>พัฒนาด้วย ❤️ โดย Admin Ek (Chatinon Jakram)</div></div></body></html>";
} else {
    // โหมด Cron (ไม่มีผลต่อหน้าเว็บ)
    process_bot(true);
}

// --- [ 5. BOT CORE LOGIC ] ---
function process_bot($is_cron) {
    global $rss_sources, $webhook_url;
    
    foreach ($rss_sources as $name => $info) {
        $key = substr(md5($info['rss']), 0, 8);
        $rss = @simplexml_load_file($info['rss']);
        if (!$rss || !isset($rss->channel->item[0])) continue;

        $item = $rss->channel->item[0];
        $guid = (string)$item->guid;
        $last_guid = google_db("get", $key);

        if ($last_guid === "DATABASE_CONNECT_FAIL") {
            if (!$is_cron) echo "• $name: <span class='error'>❌ ฐานข้อมูลขัดข้อง (ข้ามเพื่อป้องกันสแปม)</span><br>";
            continue; 
        }

        if ($guid === $last_guid) {
            if (!$is_cron) echo "• $name: <span class='warning'>ข่าวเดิม</span><br>";
            continue;
        }

        // ดึงรูปภาพ
        $image_url = "";
        $ns = $rss->getNamespaces(true);
        if (isset($ns['media'])) {
            $media = $item->children($ns['media']);
            if (isset($media->content)) $image_url = (string)$media->content->attributes()->url;
        }

        // ส่ง Discord
        $payload = [
            "username" => $name,
            "avatar_url" => $info['avatar'],
            "embeds" => [[
                "title" => "📌 " . (string)$item->title,
                "url" => (string)$item->link,
                "description" => mb_strimwidth(strip_tags((string)$item->description), 0, 350, "..."),
                "color" => hexdec($info['color']),
                "image" => !empty($image_url) ? ["url" => $image_url] : null,
                "footer" => ["text" => "M.3/5 NR-NEWS | " . date("H:i")]
            ]]
        ];

        $ch = curl_init($webhook_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_exec($ch);
        curl_close($ch);

        google_db("set", $key, $guid);
        if (!$is_cron) echo "• $name: <span class='success'>✅ ส่งข่าวใหม่แล้ว!</span><br>";
        usleep(300000);
    }
    if ($is_cron) echo "Cron job executed at " . date("H:i:s");
}
