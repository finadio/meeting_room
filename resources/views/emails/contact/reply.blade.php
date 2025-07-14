@component('mail::message')
# Halo, {{ $userName }}

Terima kasih telah menghubungi kami melalui form Contact Us.

Berikut adalah balasan dari admin kami:

---

{!! $replyMessage !!}

---

Jika Anda memiliki pertanyaan lebih lanjut, silakan balas email ini atau hubungi kami melalui kontak resmi website.

Hormat kami,<br>
<strong>{{ $adminName }}</strong><br>
Tim Meeting Room
@endcomponent
