<?php
// --- [ 1. ตั้งค่ารายการ RSS และโปรไฟล์แยกรายเพจ ] ---
$rss_sources = [
    "งานประชาสัมพันธ์โรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/1TQl9fs4RGwFQO5u.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/324269728_743603630069476_228333852253818672_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=53a332&_nc_eui2=AeEQVpKYxiZf0zpc7ixL1g8LecSOevxaTvB5xI56_FpO8G-kmzElyDMph-JS-QSQGiyNyDcONhQLPdPkbRPErI7o&_nc_ohc=CX1f7aSlXxcQ7kNvwESwoRP&_nc_oc=Adp2xV6Yr3NKSIKT5AMKfuHFVJ70DmJ1fWfaX1H6ruhQGUuQAl3EDibTyIQmKA3OhvG3lYjTPZPesOaD90y9DSy4&_nc_zt=23&_nc_ht=scontent.fnak2-1.fna&_nc_gid=CxKVQZGy_Rn4aSYe6-yyww&_nc_ss=7b2a8&oh=00_Af68PNKCTkk6MHN-q2yZ6qH0cO8WR188tpti4eFbXSTzZQ&oe=6A009594",
        "color" => "4ebc00" // สีเขียว
    ],
    "โรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/y2raHbpZnAJfIN0p.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/648873944_26574300548828725_2833658263750313445_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=53a332&_nc_eui2=AeHRXnnL8KYdF29eq-urr8Hk7NbQ0Ezb03rs1tDQTNvTep5rnU215_J14xMjiz9ShrfLuD-UtFSgs-RYPslNPIDT&_nc_ohc=k6FhhGf3zoAQ7kNvwGINFXR&_nc_oc=AdrDGIKPrteHTS_834qoKzuIFQx4UMUUaUL4CVwSJX8dMuzvkNHogUPO9NKDPCNNaPLwVLnJlvBW04taFj6DDE_4&_nc_zt=23&_nc_ht=scontent.fnak2-1.fna&_nc_gid=m7pP3cqH6xkUX4V3M2WFJg&_nc_ss=7b2a8&oh=00_Af7yXti2unkb8AfMJUGQDRKv9NJyI29t4_660aoZZsk5Pg&oe=6A00A79B",
        "color" => "e28b00" // สีส้ม
    ],
    "กลุ่มสาระการเรียนรู้ภาษาไทย โรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/gMVDMl12sQ7dgTdf.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/466078074_998896022283094_5704068485564192640_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=53a332&_nc_eui2=AeHyhkh8aLoUuONlbNZC1q2s3F02k0fXkjXcXTaTR9eSNSaLk4fip23gjTA0SucDVRq99tTbLx1tj5pI5DGc_y5a&_nc_ohc=Ib023dzxsjcQ7kNvwE7WxHy&_nc_oc=AdoJMCYfJTS6DTfYIPfSSiGuWtwOxj7M2yFBD7U6FR69jp8CYjBPo4IwCs3JsDNo5OCLj21R1zYkCk0MtB294HvF&_nc_zt=23&_nc_ht=scontent.fnak2-1.fna&_nc_gid=AY5sQEKO_SMxiQH-lVhWhw&_nc_ss=7b2a8&oh=00_Af5tmjfJE1PglgQjOBGiPcUw-UdkMqSn5ACFpm5hKnbS5w&oe=6A00BC94",
        "color" => "a200e2" // สีม่วง
    ],
        "กลุ่มสาระการเรียนรู้คณิตศาสตร์ โรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/4ICRUnxw1bTEqm2c.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/612040273_1401814754983784_7535017924699925573_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=1d70fc&_nc_eui2=AeFT7o7guWXsfYOx9B9pOXkD9NMVPBk2YX300xU8GTZhfePH10rdwuIQhP3gGOJ_fa68L74SymVNqOQtmQ0O-9R4&_nc_ohc=Ixkd4oyX9NEQ7kNvwGPPygP&_nc_oc=AdpiINkOw2ob6UGmImVgoWqTDnd2-79nk7Qno1B5CwXtqvr6Um1o_pHCUt3oIqDI6SdwaObWmzNGhsVabOXFOHuP&_nc_zt=23&_nc_ht=scontent.fnak2-1.fna&_nc_gid=xW2XdKuNOgNCmPV-whw7zA&_nc_ss=7b2a8&oh=00_Af5rnCif5M7cndPWSnC4pVL3q4x5nMo8M1xijzjqamDeDw&oe=6A00AF1C",
        "color" => "e20000" // สีแดง
    ],
        "กลุ่มสาระการเรียนรู้วิทยาศาสตร์และเทคโนโลยี โรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/eaysEO9DoGTFcC6I.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/300896257_746408846681142_7293328732782702774_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=1d70fc&_nc_eui2=AeG06LwHnTI6sTHgTAqipHkY9RT5DInYSO71FPkMidhI7tL91ilQBIfgf9p9Uocuvd3r5JV5q6oeGlNNjWHfo5Rk&_nc_ohc=PuQBxNuLMxgQ7kNvwH1r2Ls&_nc_oc=Adpx82n9AjI3kF-hMHrfhBXWf-Is0GRW22QfcA51aJomye86vrwK6WYPxXqMZ2-THJ2biGqFkM_RxTXIW03dSMWJ&_nc_zt=23&_nc_ht=scontent.fnak2-1.fna&_nc_gid=iaVgOPPeIW5NWHKBcKnZGg&_nc_ss=7b2a8&oh=00_Af653dU4-U1V2sHtiGwxpo1-kuo6u2SY9yczTqul8rZZlg&oe=6A009934",
        "color" => "fcdb00" // สีเหลือง
    ],
        "กลุ่มสาระการเรียนรู้สังคมศึกษา ศาสนา และวัฒนธรรม โรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/p3XziBCnjAbksa3a.xml",
        "avatar" => "https://cdn-icons-png.flaticon.com/512/3534/3534033.png",
        "color" => "00b0fc" // สีฟ้า
    ],
        "กลุ่มสาระการเรียนรู้สุขศึกษาและพลศึกษา โรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/9Gev9MaiKdlz1rb5.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/452480471_122102462126421014_6871839481956866531_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=53a332&_nc_eui2=AeFw65s4OUvFygpJBJqQT80qfiigZA6DUMd-KKBkDoNQx5-OeFQP5-kC4DAi8OjjqtWgFrXrRk1b57Eo-9yPrlQr&_nc_ohc=E1OmVK6KLAMQ7kNvwErUiJ0&_nc_oc=AdpfLPwS9PMrXUek3J9KMdIGMECvaMgGQMMJcRUHA33DiX9YdPb9YwSVV5bOtqgH-cIRUp8yQ718XmSBsJmJARb2&_nc_zt=23&_nc_ht=scontent.fnak2-1.fna&_nc_gid=q0owvjHnLODA1uvQ0pZ7KQ&_nc_ss=7b2a8&oh=00_Af6TZrnPBczjm4xZvwnYDxp7_k7KYmG65IfDIxmwWfs_iQ&oe=6A00AE60",
        "color" => "fc00eb" // สีชมพู
    ],
        "กลุ่มสาระการเรียนรู้ศิลปะ โรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/2QpDynw9Qm7XRbtm.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/481299726_1129102305678515_2278776288030225200_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=53a332&_nc_eui2=AeEWQHLcqeqd88jN-RksY4IFq7ykPSxp30OrvKQ9LGnfQwsqumkjGVi623YxSElO3isyLqox4t7M2_vWyRejtpxW&_nc_ohc=mmmzBsaFy5EQ7kNvwHCNbrg&_nc_oc=AdoUJ9CC3xV2RBOOnZKGhpY19XFdqcq1dZTz1OKYtKwq53xV8bVJyBlrPYsMNxrA8I4JhfElsyLP0mKvGCB8zIMw&_nc_zt=23&_nc_ht=scontent.fnak2-1.fna&_nc_gid=U0_PwnrByXJOygBiPGo6QA&_nc_ss=7b2a8&oh=00_Af7cMS4DgBTSZmdxPStAzsa5tRE894ScZO5F3pO7Uqxb7A&oe=6A00B39B",
        "color" => "3200fc" // สีน้ำเงิน
    ],
        "กลุ่มสาระการเรียนรู้การงานอาชีพ โรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/2FqXov6HBHJNs7Rv.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/471164713_1233258884423055_869006746446843372_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=53a332&_nc_eui2=AeFL3FN3Y6lBWMgW2MZi_4pEYoeXqHrvohBih5eoeu-iELvxqROJf6j5PIkFBnxwbePJ2GPmaB_2YkrsyoHcet2H&_nc_ohc=2WJTmnnNZ4EQ7kNvwGeMd-I&_nc_oc=Ado7I2-7EkiIeFRCJEdK68N_7vFy7ruVJgJ8OyXzhgErTtGuKPxuHNrgNI9_eOGmKNRhKsXHqzJ7O3hXwr-H7uUn&_nc_zt=23&_nc_ht=scontent.fnak2-1.fna&_nc_gid=i0QyGALau1aEAOtfCyj3Jg&_nc_ss=7b2a8&oh=00_Af4s29tUJRh6Ivi2Lv8mEX2dHjpYGUnrkxP9UnfQHrtvVg&oe=6A00A0C0",
        "color" => "fc8600" // สีน้ำตาล
    ],
        "กลุ่มสาระการเรียนรู้ภาษาต่างประเทศ โรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/dwsJ9ElT7Rf8O8KV.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-1/353797149_3193673687444746_8123120628808621095_n.jpg?stp=dst-jpg_s200x200_tt6&_nc_cat=106&ccb=1-7&_nc_sid=2d3e12&_nc_eui2=AeFJ1aZnJvj_8Du5M9BTAMHSFP8pjvmzaHYU_ymO-bNodgR7q2NX_w1LpGLzZ1o3RERp-MRIXee9RWzsIHzYJdv5&_nc_ohc=DuCfTaCqlnYQ7kNvwHMVeP2&_nc_oc=Ado4govM8ZTEyUhM5lI8tnE6f4bJiM0My_N-rOmPRsbIMA0vTxLeXiunGwBxopukYX5VzfNi1_Q7GSblf5xPCCGb&_nc_zt=24&_nc_ht=scontent.fnak2-1.fna&_nc_gid=VR83NLL7oyq1Zugm6B_fHQ&_nc_ss=7b2a8&oh=00_Af7SF4xuFv1Am8zldzAkMfmFLNTOFfwIlLrkL5oNl2MP2Q&oe=6A00A5AE",
        "color" => "fc0071" 
    ],

        "คณะกรรมการสภานักเรียนโรงเรียนนางรอง" => [
        "rss" => "https://rss.app/feeds/cdeTCXnSfyaJguza.xml",
        "avatar" => "https://scontent.fnak2-1.fna.fbcdn.net/v/t39.30808-6/307475364_478915944282703_3356226624056089045_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=1d70fc&_nc_eui2=AeHi4xh-ZTKAoNl63OH5P5K1QDRb11jdcDpANFvXWN1wOrfKk2-V_yxHvXG7voX80nxQz7PsugDsEOSqZl95rjqa&_nc_ohc=Ejdl-h-KwkoQ7kNvwG1U6db&_nc_oc=AdrEkwvz699_sN7z6uMvHJenTqwEv7mH4WDbVqMkniUNMacPnW7kUVRaO7WCuRk7qC1QF2yOI-ysQUEow32KiqM2&_nc_zt=23&_nc_ht=scontent.fnak2-1.fna&_nc_gid=Sov029jOklbzPQZzvFiPlQ&_nc_ss=7b2a8&oh=00_Af766loH9oWJedrQSAzajyrz0bYHFycopqUSM2bqnYFs0A&oe=6A00AE08",
        "color" => "fffff" //
    ],
];

$webhook_url = "https://discord.com/api/webhooks/1501434651422490624/RYALnbsvjs6equEyRJNMCnLSbIkx78HH43zVttBMA9rHEZ8f45-p4U3syitYJz-qP_lV";

// --- [ 2. เริ่มทำงาน ] ---
foreach ($rss_sources as $source_name => $info) {
    
    $cache_file = "last_post_" . substr(md5($info['rss']), 0, 8) . ".txt";

    $rss = @simplexml_load_file($info['rss']);
    if ($rss === false || !isset($rss->channel->item[0])) continue;

    $item  = $rss->channel->item[0];
    $guid  = (string)$item->guid;
    $last_guid = file_exists($cache_file) ? trim(file_get_contents($cache_file)) : "";

    if ($guid !== $last_guid) {
        $title = (string)$item->title;
        $link  = (string)$item->link;
        $desc  = strip_tags((string)$item->description);

        // ดึงรูปภาพประกอบข่าว
        $image_url = "";
        $ns = $rss->getNamespaces(true);
        if (isset($ns['media'])) {
            $media = $item->children($ns['media']);
            if (isset($media->content)) { $image_url = (string)$media->content->attributes()->url; }
        }

        $data = [
            "username" => $source_name, // ชื่อบอตจะเปลี่ยนตามชื่อเพจ
            "avatar_url" => $info['avatar'], // รูปบอตจะเปลี่ยนตามรูปเพจ
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
        curl_exec($ch);
        curl_close($ch);

        file_put_contents($cache_file, $guid);
        echo "✅ ส่งข่าวจาก [$source_name] แล้ว!<br>";
    } else {
        echo "😴 [$source_name] ไม่มีข่าวใหม่<br>";
    }
}
?>
