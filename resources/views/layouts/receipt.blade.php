<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Receipt</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  <div class="container mx-auto px-4 py-8 max-w-2xl">
      
      <!-- Tombol Close -->
    <a href="{{ route('halamanutama') }}" class="absolute top-4 right-4 text-red-500 hover:text-red-700 text-2xl font-bold">
      &times;
    </a>

      
    <h1 class="text-2xl md:text-3xl font-bold text-center mb-6">Struk Pemesanan</h1>

    <div id="cart-container" class="bg-white rounded-xl shadow-md p-6 space-y-6">
      <div class="space-y-2 text-sm md:text-base">
        <div class="flex justify-between">
          <span class="font-semibold">Nama Pelanggan:</span>
          <span>{{ $customerName ?? 'Tidak diketahui' }}</span>
        </div>
        <div class="flex justify-between">
          <span class="font-semibold">Tipe Pemesanan:</span>
          <span>{{ $orderType ? ucfirst($orderType) : 'Tidak diketahui' }}</span>
        </div>
        <div class="flex justify-between">
          <span class="font-semibold">Waktu Pemesanan:</span>
          <span>{{ $createdAt ?? '-' }}</span>
        </div>
      </div>

      <div>
        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Pesanan</h3>
        @php $total = 0; @endphp
        @forelse ($orderItems as $item)
          @php
            $price = $item->price ?? 0;
            $quantity = $item->quantity ?? 0;
            $subtotal = $item->subtotal ?? ($price * $quantity);
            $total += $subtotal;
          @endphp
          <div class="border-b border-dashed py-4 text-sm md:text-base">
            <p class="font-medium">{{ $item->name ?? 'Tidak diketahui' }}</p>
            <p>Harga Satuan: <span class="font-semibold">Rp {{ number_format($price, 0, ',', '.') }}</span></p>
            <p>Jumlah: <span class="font-semibold">{{ $quantity }}</span></p>
            <p>Subtotal: <span class="font-semibold text-red-500">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></p>
            @if (!empty($item->notes))
              <p class="text-sm italic text-gray-600">Catatan: {{ $item->notes }}</p>
            @endif
          </div>
        @empty
          <p class="text-center text-gray-500">Tidak ada item dalam pesanan.</p>
        @endforelse
      </div>

      <div class="text-right text-lg font-bold text-red-600 border-t pt-4">
        Total: Rp {{ number_format($total, 0, ',', '.') }}
      </div>
    </div>

    <div class="mt-6 text-center text-sm text-gray-600">
      <p>Silakan lakukan pembayaran di kasir.</p>
      <p>Terima kasih atas kunjungannya!</p>
    </div>
  </div>

  <script>
    // Bersihin cart pas halaman receipt ditampilkan
  localStorage.removeItem('cart');

  // Deteksi tombol back dan arahkan langsung ke halaman utama
  window.addEventListener('pageshow', function(event) {
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
      window.location.href = '/'; // Atau ganti ke route halaman utama/menu
    }
  });
  </script>

</body>
</html>
