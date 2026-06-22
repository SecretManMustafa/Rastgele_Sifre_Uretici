<?php

function generatePassword(
    int $length,
    bool $lowercase,
    bool $uppercase,
    bool $numbers,
    bool $specialChars
): string {

    $charSets = [];

    if ($lowercase) {
        $charSets[] = 'abcdefghijklmnopqrstuvwxyz';
    }

    if ($uppercase) {
        $charSets[] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }

    if ($numbers) {
        $charSets[] = '0123456789';
    }

    if ($specialChars) {
        $charSets[] = '!@#$%^&*()-_=+[]{};:,.<>?';
    }

    if (empty($charSets)) {
        return 'En az bir karakter türü seçmelisiniz.';
    }

    if ($length < count($charSets)) {
        return 'Parola uzunluğu seçilen karakter türü sayısından küçük olamaz.';
    }

    $password = [];

    // Her seçilen karakter grubundan en az 1 karakter ekle
    foreach ($charSets as $set) {
        $password[] = $set[random_int(0, strlen($set) - 1)];
    }

    // Tüm karakter havuzunu oluştur
    $allChars = implode('', $charSets);

    // Kalan karakterleri doldur
    while (count($password) < $length) {
        $password[] = $allChars[random_int(0, strlen($allChars) - 1)];
    }

    // Karıştır
    shuffle($password);

    return implode('', $password);
}

$generatedPassword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $length = (int)($_POST['length'] ?? 12);

    $generatedPassword = generatePassword(
        $length,
        isset($_POST['lowercase']),
        isset($_POST['uppercase']),
        isset($_POST['numbers']),
        isset($_POST['special'])
    );
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Güvenli Şifre Üretici</title>

<style>
body{
    font-family:Arial,sans-serif;
    background:#f4f6f9;
    padding:40px;
}

.container{
    max-width:500px;
    margin:auto;
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,.1);
}

h2{
    text-align:center;
}

.form-group{
    margin-bottom:15px;
}

input[type=number]{
    width:100%;
    padding:10px;
}

button{
    width:100%;
    padding:12px;
    border:none;
    background:#007bff;
    color:#fff;
    cursor:pointer;
    border-radius:5px;
}

button:hover{
    background:#0056b3;
}

.result{
    margin-top:20px;
    padding:15px;
    background:#e9f7ef;
    border:1px solid #28a745;
    border-radius:5px;
    word-break:break-all;
    font-size:18px;
}
</style>
</head>
<body>

<div class="container">

    <h2>Rastgele Şifre Üretici</h2>

    <form method="post">

        <div class="form-group">
            <label>Şifre Uzunluğu</label>
            <input type="number" name="length" min="4" max="128" value="12">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="lowercase" checked>
                Küçük Harf (a-z)
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="uppercase" checked>
                Büyük Harf (A-Z)
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="numbers" checked>
                Rakam (0-9)
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="special" checked>
                Özel Karakter (!@#$...)
            </label>
        </div>

        <button type="submit">Şifre Üret</button>

    </form>

    <?php if ($generatedPassword): ?>
        <div class="result">
            <strong>Oluşturulan Şifre:</strong><br>
            <?php echo htmlspecialchars($generatedPassword); ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>