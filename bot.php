
<?php
// ปิดการแสดง Error เพื่อความสะอาดของ Log
error_reporting(0);
ini_set('display_errors', 0);

// --- [ ตั้งค่าสำคัญ ] ---
$gas_url = "https://script.google.com/macros/s/AKfycbx5ue2dzjSFqCJ6gN-XJJOtL9j3ICMuifD5A6YDegj2oFsRRZrtzGrahPzNnVYEgxyZ/exec"; 
$webhook_url = "https://discord.com/api/webhooks/1501520381826043946/TIa1l2i3REl96ZStVCpKi5xveJER2jowCGJHyQX_7NySc5jYk80pZUClFjrEpJP7N9Vd";

// --- [ รายชื่อเพจทั้งหมด ] ---
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

// --- [ ฟังก์ชันคุยกับ Google Sheets ] ---
function google_db($action, $key, $val = "") {
    global $gas_url;
    $ch = curl_init($gas_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["action" => $action, "key" => $key, "val" => $val]));
    curl_setopt($ch, CURLOPT_POST, true);
    $res = curl_exec($ch);
    curl_close($ch);
    return trim($res);
}

// --- [ เริ่มประมวลผล ] ---
foreach ($rss_sources as $source_name => $info) {
    $source_key = substr(md5($info['rss']), 0, 8);
    $rss = @simplexml_load_file($info['rss']);
    
    if ($rss === false || !isset($rss->channel->item[0])) continue;

    $item = $rss->channel->item[0];
    $guid = (string)$item->guid;

    // ดึงค่าล่าสุดจาก Google Sheets
    $last_guid = google_db("get", $source_key);

    // กรณีไม่มีข้อมูลเดิม (Initialize) ให้บันทึกไว้แล้วข้าม เพื่อไม่ให้ส่งซ้ำครั้งแรก
    if (empty($last_guid)) {
        google_db("set", $source_key, $guid);
        echo "Init: $source_name | ";
        continue;
    }

    if ($guid !== $last_guid) {
        $title = (string)$item->title;
        $link  = (string)$item->link;
        $desc  = strip_tags((string)$item->description);

        $image_url = "";
        $ns = $rss->getNamespaces(true);
        if (isset($ns['media'])) {
            $media = $item->children($ns['media']);
            if (isset($media->content)) { 
                $image_url = (string)$media->content->attributes()->url; 
            }
        }

        $data = [
            "username" => $source_name,
            "avatar_url" => $info['avatar'],
            "embeds" => [[
                "title" => "📌 " . ($title ?: "ข่าวประชาสัมพันธ์"),
                "description" => mb_strimwidth($desc, 0, 300, "..."),
                "url" => $link,
                "color" => hexdec($info['color']),
                "footer" => ["text" => "โรงเรียนนางรอง Nangrong School | ประพฤติดี เรียนเด่น กีฬาดัง สร้างงานได้"],
                "timestamp" => date('c'),
                "image" => !empty($image_url) ? ["url" => $image_url] : null
            ]]
        ];

        $ch = curl_init($webhook_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        sleep(1); // หน่วงเวลา 1 วินาทีกัน Error 429
        curl_exec($ch);
        curl_close($ch);

        // อัปเดต GUID ใหม่ลง Google Sheets
        google_db("set", $source_key, $guid);
        echo "Sent: $source_name | ";
    }
}
echo "Done: " . date("H:i:s");
?>
