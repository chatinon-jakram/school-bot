<?php
// --- [ 1. ตั้งค่าพื้นฐาน ] ---
$rss_url     = "https://rss.app/feeds/1TQl9fs4RGwFQO5u.xml";
$webhook_url = "https://discord.com/api/webhooks/1501434651422490624/RYALnbsvjs6equEyRJNMCnLSbIkx78HH43zVttBMA9rHEZ8f45-p4U3syitYJz-qP_lV";
$cache_file  = "last_post.txt"; 

// 2. ดึงข้อมูล RSS
$rss = @simplexml_load_file($rss_url);
if ($rss === false) {
    die("Error: ไม่สามารถดึงข้อมูลจาก RSS ได้ ตรวจสอบลิงก์อีกทีนะเอิร์ก");
}

$item  = $rss->channel->item[0];
$guid  = (string)$item->guid;
$title = (string)$item->title;
$link  = (string)$item->link;
$desc  = strip_tags((string)$item->description);

// ดึงรูปภาพจาก Media Tag
$image_url = "";
$ns = $rss->getNamespaces(true);
if (isset($ns['media'])) {
    $media = $item->children($ns['media']);
    if (isset($media->content)) {
        $image_url = (string)$media->content->attributes()->url;
    }
}

// 3. ระบบเช็คโพสต์ซ้ำ
$last_guid = file_exists($cache_file) ? trim(file_get_contents($cache_file)) : "";

if ($guid !== $last_guid) {

    // เตรียม Embed
    $embed = [
        "title" => "📌 " . ($title ?: "ประชาสัมพันธ์จากโรงเรียน"),
        "description" => mb_strimwidth($desc, 0, 300, "..."), // ตัดข้อความไม่ให้ยาวเกินไป
        "url" => $link,
        "color" => hexdec("FFCC00"),
        "footer" => [
            "text" => "แจ้งเตือนอัตโนมัติโดยระบบของเอิร์ก • " . date("H:i")
        ],
        "timestamp" => date('c')
    ];

    if (!empty($image_url)) {
        $embed["image"] = ["url" => $image_url];
    }

    $data = [
        "username" => "ข่าวสารโรงเรียนนางรอง",
        "avatar_url" => "https://cdn-icons-png.flaticon.com/512/3534/3534033.png", // ไอคอนบอตสวยๆ
        "embeds" => [$embed]
    ];

    // 4. ยิง cURL แบบข้าม SSL (สำหรับแก้ปัญหา HTTP 0)
    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
        file_put_contents($cache_file, $guid);
        echo "✅ สำเร็จ: ข้อความส่งเข้า Discord แล้ว (โพสต์ใหม่)";
    } else {
        echo "❌ พลาดแล้ว: Discord ตอบกลับรหัส $httpCode";
    }

} else {
    echo "😴 สถานะ: ยังไม่มีโพสต์ใหม่ (โพสต์ล่าสุดคือ ID: $guid)";
}

// ส่วนเสริมสำหรับ Render: ป้องกัน Error เรื่องหน้าเว็บว่าง
echo "<br><hr>ระบบทำงานปกติ - Current Server Time: " . date("Y-m-d H:i:s");
?>
