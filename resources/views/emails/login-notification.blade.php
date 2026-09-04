<h2>Merhaba {{ $user->name }}</h2>

<p>Hesabınıza yeni bir giriş yapıldı.</p>

<ul>
    <li><strong>Tarih:</strong> {{ $loginAt }}</li>
    <li><strong>IP:</strong> {{ $ip ?? 'Bilinmiyor' }}</li>
    <li><strong>Cihaz / Tarayıcı:</strong> {{ $userAgent ?? 'Bilinmiyor' }}</li>
    <li><strong>Konum:</strong> {{ $location }}</li>
</ul>

<p>
    Eğer bu giriş size ait değilse hesabınızı kontrol etmenizi öneririz.
</p>
