<?php
/**
 * NR RSS BOT - Version: Silent Cron (แก้ปัญหา Output file too large)
 */

$gas_url = "https://script.google.com/macros/s/AKfycbx5ue2dzjSFqCJ6gN-XJJOtL9j3ICMuifD5A6YDegj2oFsRRZrtzGrahPzNnVYEgxyZ/exec"; 
$webhook_url = "https://discord.com/api/webhooks/1501520381826043946/TIa1l2i3REl96ZStVCpKi5xveJER2jowCGJHyQX_7NySc5jYk80pZUClFjrEpJP7N9Vd";

// ตรวจสอบว่าเป็นคนรันเองผ่านหน้าเว็บ หรือ Cron เป็นคนรัน
// ถ้าไม่มี action=run แสดงว่าเป็นโหมดเงียบ (Cron)
$is_web = isset($_GET['action']); 
$action = $_GET['action'] ?? 'cron';

function google_db($action, $key, $val = "") {
    global $gas_url;
    $ch = curl_init($gas_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["action" => $action, "key" => $key, "val" => $val]));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $res = curl_exec($ch);
    curl_close($ch);
    return trim($res);
}

// รายชื่อเพจ (ใส่รวมไว้ในตัวแปรเดียว)
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
    "คณะกรรมการสภานักเรียนโรงเรียนนางรอง" => ["rss" => "https://rss.app/feeds/cdeTCXnSfyaJguza.xml", "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/307475364_478915944282703_3356226624056089045_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=1d70fc&oh=00_Af766loH9oWJedrQSAzajyrz0bYHFycopqUSM2bqnYFs0A&oe=6A00AE08", "color" => "ffffff"],
];

// ถ้าเข้าผ่าน Web ให้โชว์หน้าจอสวยงาม
if ($is_web) {
    echo "<html><body style='font-family:sans-serif; background:#f0f0f0; padding:20px;'>";
    echo "<div style='background:white; padding:20px; border-radius:10px; max-width:600px; margin:auto;'>";
    echo "<h2>NR Bot Status</h2><hr>";
}

foreach ($rss_sources as $name => $info) {
    $key = substr(md5($info['rss']), 0, 8);
    $rss = @simplexml_load_file($info['rss']);
    
    if (!$rss) continue;

    $item = $rss->channel->item[0];
    $guid = (string)$item->guid;
    $last_guid = google_db("get", $key);

    if ($guid !== $last_guid) {
        // ส่ง Discord
        $ns = $rss->getNamespaces(true);
        $image_url = "";
        if (isset($ns['media'])) {
            $media = $item->children($ns['media']);
            if (isset($media->content)) { $image_url = (string)$media->content->attributes()->url; }
        }

        $payload = [
            "username" => $name,
            "avatar_url" => $info['avatar'],
            "embeds" => [[
                "title" => "📌 " . (string)$item->title,
                "url" => (string)$item->link,
                "description" => mb_strimwidth(strip_tags((string)$item->description), 0, 200, "..."),
                "color" => hexdec($info['color']),
                "image" => !empty($image_url) ? ["url" => $image_url] : null,
                "footer" => ["text" => "NR RSS System | " . date("H:i")]
            ]]
        ];

        $ch = curl_init($webhook_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_exec($ch);
        curl_close($ch);

        google_db("set", $key, $guid);
        if ($is_web) echo "✅ $name: ส่งข่าวใหม่แล้ว<br>";
    } else {
        if ($is_web) echo "⚪ $name: ไม่มีข่าวใหม่<br>";
    }
}

if ($is_web) {
    echo "<hr><p>สแกนเสร็จแล้ว: ".date("H:i:s")."</p>";
    echo "<a href='bot.php?action=run'>คลิกเพื่อรันใหม่</a>";
    echo "</div></body></html>";
} else {
    // ถ้า Cron รัน ให้พ่นคำสั้นๆ ออกมาพอ ไม่ให้ไฟล์ใหญ่เกิน
    echo "Cron Job Success: " . date("H:i:s");
}
?>
