@component('mail::message')
    # Perpanjangan Masa Aktif Berhasil 🎉

    Halo **{{ $pricing->email }}**,

    Langganan paket **{{ $pricing->namapaket }}** Anda telah berhasil diperpanjang selama **{{ $months }} bulan**.

    **Masa aktif baru:**
    - Mulai: {{ $pricing->start_date->format('d M Y') }}
    - Berakhir: {{ $pricing->end_date->format('d M Y') }}

    Terima kasih telah terus menggunakan layanan kami!
    Jika ada pertanyaan, silakan hubungi admin.

    @component('mail::button', ['url' => url('/')])
        Kunjungi Website
    @endcomponent

    Salam hangat,
    **Tim Admin**
@endcomponent
