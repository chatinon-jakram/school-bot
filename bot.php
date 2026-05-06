<?php
// --- [ CONFIGURATION ] ---
// 1. ใส่ URL จาก Google Apps Script ของเอิร์กที่นี่
$gas_url = "https://script.google.com/macros/s/AKfycbx5ue2dzjSFqCJ6gN-XJJOtL9j3ICMuifD5A6YDegj2oFsRRZrtzGrahPzNnVYEgxyZ/exec"; 

// 2. Webhook Discord (ตัวที่เอิร์กคัดลอกมาใหม่)
$webhook_url = "https://discord.com/api/webhooks/1501520381826043946/TIa1l2i3REl96ZStVCpKi5xveJER2jowCGJHyQX_7NySc5jYk80pZUClFjrEpJP7N9Vd";

// --- [ UI & STYLING ] ---
echo "<!DOCTYPE html><html lang='th'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>NR RSS Control Panel</title>";
echo "<style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #eceff1; padding: 20px; color: #333; }
    .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    h1 { color: #2c3e50; text-align: center; margin-bottom: 30px; }
    .toolbar { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 25px; }
    .btn { padding: 12px 25px; text-decoration: none; color: white; border-radius: 8px; font-weight: 600; transition: 0.3s; border: none; cursor: pointer; }
    .btn-blue { background: #3498db; } .btn-blue:hover { background: #2980b9; }
    .btn-red { background: #e74c3c; } .btn-red:hover { background: #c0392b; }
    .btn-green { background: #2ecc71; } .btn-green:hover { background: #27ae60; }
    .log-container { background: #263238; color: #80cbc4; padding: 20px; border-radius: 10px; font-family: 'Courier New', Courier, monospace; font-size: 14px; overflow-x: auto; min-height: 200px; }
    .status-line { margin-bottom: 5px; border-bottom: 1px solid #37474f; padding-bottom: 5px; }
    .success { color: #afffaf; } .info { color: #81d4fa; } .warning { color: #fff59d; } .error { color: #ffab91; }
</style></head><body><div class='container'>";

echo "<h1>🚀 ระบบกระจายข่าวโรงเรียนนางรอง</h1>";
echo "<div class='toolbar'>";
echo "<a href='?action=debug' class='btn btn-blue'>▶️ บังคับส่งทันที (Debug)</a>";
echo "<a href='?action=check' class='btn btn-red'>🔍 ตรวจสอบการเชื่อมต่อ</a>";
echo "<a href='index.php' class='btn btn-green'>🔄 รันโหมดปกติ</a>";
echo "</div><div class='log-container'>";

// --- [ FUNCTIONS ] ---
function google_db($action, $key, $val = "") {
    global $gas_url;
    $ch = curl_init($gas_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["action" => $action, "key" => $key, "val" => $val]));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    return $err ? "ERROR_CONN" : trim($res);
}

// --- [ RSS SOURCES ] ---
$rss_sources = [
    "งานประชาสัมพันธ์โรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/1TQl9fs4RGwFQO5u.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/324269728_743603630069476_228333852253818672_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=53a332&oh=00_Af68PNKCTkk6MHN-q2yZ6qH0cO8WR188tpti4eFbXSTzZQ&oe=6A009594",
        "color" => "4ebc00"
    ],
    "โรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/y2raHbpZnAJfIN0p.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/648873944_26574300548828725_2833658263750313445_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=53a332&oh=00_Af7yXti2unkb8AfMJUGQDRKv9NJyI29t4_660aoZZsk5Pg&oe=6A00A79B",
        "color" => "e28b00"
    ],
    "กลุ่มสาระการเรียนรู้ภาษาไทย" => [
        "rss" => "https://rss.app/feeds/gMVDMl12sQ7dgTdf.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/466078074_998896022283094_5704068485564192640_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=53a332&oh=00_Af5tmjfJE1PglgQjOBGiPcUw-UdkMqSn5ACFpm5hKnbS5w&oe=6A00BC94",
        "color" => "a200e2"
    ],
    "กลุ่มสาระการเรียนรู้คณิตศาสตร์" => [
        "rss" => "https://rss.app/feeds/4ICRUnxw1bTEqm2c.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/612040273_1401814754983784_7535017924699925573_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=1d70fc&oh=00_Af5rnCif5M7cndPWSnC4pVL3q4x5nMo8M1xijzjqamDeDw&oe=6A00AF1C",
        "color" => "e20000"
    ],
    "กลุ่มสาระการเรียนรู้วิทยาศาสตร์และเทคโนโลยี" => [
        "rss" => "https://rss.app/feeds/eaysEO9DoGTFcC6I.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/300896257_746408846681142_7293328732782702774_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=1d70fc&oh=00_Af653dU4-U1V2sHtiGwxpo1-kuo6u2SY9yczTqul8rZZlg&oe=6A009934",
        "color" => "fcdb00"
    ],
    "กลุ่มสาระการเรียนรู้สังคมศึกษาฯ" => [
        "rss" => "https://rss.app/feeds/p3XziBCnjAbksa3a.xml",
        "avatar" => "https://cdn-icons-png.flaticon.com/512/3534/3534033.png",
        "color" => "00b0fc"
    ],
    "กลุ่มสาระการเรียนรู้สุขศึกษาและพลศึกษา" => [
        "rss" => "https://rss.app/feeds/9Gev9MaiKdlz1rb5.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/452480471_122102462126421014_6871839481956866531_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=53a332&oh=00_Af6TZrnPBczjm4xZvwnYDxp7_k7KYmG65IfDIxmwWfs_iQ&oe=6A00AE60",
        "color" => "fc00eb"
    ],
    "กลุ่มสาระการเรียนรู้ศิลปะ" => [
        "rss" => "https://rss.app/feeds/2QpDynw9Qm7XRbtm.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/481299726_1129102305678515_2278776288030225200_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=53a332&oh=00_Af7cMS4DgBTSZmdxPStAzsa5tRE894ScZO5F3pO7Uqxb7A&oe=6A00B39B",
        "color" => "3200fc"
    ],
    "กลุ่มสาระการเรียนรู้การงานอาชีพ" => [
        "rss" => "https://rss.app/feeds/2FqXov6HBHJNs7Rv.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/471164713_1233258884423055_869006746446843372_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=53a332&oh=00_Af4s29tUJRh6Ivi2Lv8mEX2dHjpYGUnrkxP9UnfQHrtvVg&oe=6A00A0C0",
        "color" => "fc8600"
    ],
    "กลุ่มสาระการเรียนรู้ภาษาต่างประเทศ" => [
        "rss" => "https://rss.app/feeds/dwsJ9ElT7Rf8O8KV.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-1/353797149_3193673687444746_8123120628808621095_n.jpg?stp=dst-jpg_s200x200_tt6&_nc_cat=106&ccb=1-7&_nc_sid=2d3e12&oh=00_Af7SF4xuFv1Am8zldzAkMfmFLNTOFfwIlLrkL5oNl2MP2Q&oe=6A00A5AE",
        "color" => "fc0071"
    ],
    "คณะกรรมการสภานักเรียนโรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/cdeTCXnSfyaJguza.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/307475364_478915944282703_3356226624056089045_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=1d70fc&oh=00_Af766loH9oWJedrQSAzajyrz0bYHFycopqUSM2bqnYFs0A&oe=6A00AE08",
        "color" => "ffffff"
    ],
];

// --- [ LOGIC EXECUTION ] ---
$action = $_GET['action'] ?? 'normal';

if ($action == 'check') {
    echo "<div class='status-line info'>[SYSTEM] กำลังทดสอบการเชื่อมต่อ...</div>";
    $test = google_db("get", "test");
    echo "<div class='status-line " . ($test != "ERROR_CONN" ? "success" : "error") . "'>Google Sheets: " . ($test != "ERROR_CONN" ? "✅ เชื่อมต่อติด ($test)" : "❌ เชื่อมต่อไม่ได้ (Check Apps Script URL)") . "</div>";
    
    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "<div class='status-line " . ($code != 404 ? "success" : "error") . "'>Discord Webhook: " . ($code != 404 ? "✅ เชื่อมต่อติด (Status $code)" : "❌ URL ผิดหรือหาไม่เจอ") . "</div>";

} else {
    $is_debug = ($action == 'debug');
    echo "<div class='status-line info'>[RUN] กำลังสแกนหาข่าวใหม่ (" . ($is_debug ? "โหมดบังคับส่ง" : "โหมดปกติ") . ")...</div>";

    foreach ($rss_sources as $name => $info) {
        $key = substr(md5($info['rss']), 0, 8);
        $rss = @simplexml_load_file($info['rss']);
        
        if (!$rss) {
            echo "<div class='status-line error'>❌ $name: อ่าน RSS ไม่สำเร็จ</div>";
            continue;
        }

        $item = $rss->channel->item[0];
        $guid = (string)$item->guid;
        $last_guid = google_db("get", $key);

        $should_send = false;
        $status_msg = "";

        if ($is_debug) {
            $should_send = true;
            $status_msg = "DEBUG_SEND";
        } elseif ($last_guid == "ERROR_CONN") {
            $status_msg = "SKIPPED (Sheets Conn Error)";
        } elseif (empty($last_guid)) {
            google_db("set", $key, $guid);
            $status_msg = "INITIALIZED (Saved to Sheet)";
        } elseif ($guid !== $last_guid) {
            $should_send = true;
            $status_msg = "NEW_POST_FOUND";
        } else {
            $status_msg = "ALREADY_SENT";
        }

        if ($should_send) {
            $title = (string)$item->title;
            $link = (string)$item->link;
            $desc = strip_tags((string)$item->description);
            $image_url = "";
            $ns = $rss->getNamespaces(true);
            if (isset($ns['media'])) {
                $media = $item->children($ns['media']);
                if (isset($media->content)) { $image_url = (string)$media->content->attributes()->url; }
            }

            $payload = [
                "username" => $name,
                "avatar_url" => $info['avatar'],
                "embeds" => [[
                    "title" => "📌 " . ($title ?: "ข่าวสารโรงเรียนนางรอง"),
                    "description" => mb_strimwidth($desc, 0, 250, "..."),
                    "url" => $link,
                    "color" => hexdec($info['color']),
                    "image" => !empty($image_url) ? ["url" => $image_url] : null,
                    "footer" => ["text" => "ระบบแจ้งข่าวอัตโนมัติ M.3/5 | " . date("H:i")]
                ]]
            ];

            $ch = curl_init($webhook_url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);

            google_db("set", $key, $guid);
            echo "<div class='status-line success'>✅ $name: $status_msg (Sent to Discord)</div>";
        } else {
            echo "<div class='status-line warning'>⚪ $name: $status_msg</div>";
        }
        usleep(500000); // กันโดนแบน
    }
}

echo "</div><p style='text-align:center; color:#777; margin-top:20px;'>Last Process: " . date("H:i:s") . " | M.3/5 Admin Ek</p>";
echo "</div></body></html>";
?>
