<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order</title>
  <script src="https://cdn.tailwindcss.com"></script>
  
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order</title>
  <script src="https://cdn.tailwindcss.com"></script>

 
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  </head>
<body class="bg-gray-100 text-gray-800">
  <div class="container max-w-4xl mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
      <h1 class="text-2xl md:text-3xl font-bold mb-6 text-center">Cart Anda</h1>

      <div id="cart-items" class="space-y-6"></div>

      <div class="mt-6 flex justify-between items-center text-lg font-bold text-red-500">
        <span>Total:</span>
        <span id="cart-total">Rp 0</span>
      </div>

      @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
          {{ $errors->first() }}
        </div>
      @endif

      <form id="order-form" action="{{ route('receipt.store') }}" method="POST" class="mt-6 space-y-4">
        @csrf
        <textarea name="items" id="order-data" hidden></textarea>

        <div>
          <label for="customer_name" class="block text-sm font-medium">Nama Pemesan</label>
          <input type="text" name="customer_name" id="customer_name" required maxlength="65"
            class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-red-500">
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Tipe Pemesanan</label>
          <div class="flex flex-wrap gap-4">
            <label class="inline-flex items-center">
              <input type="radio" name="order_type" value="dine in" required class="text-red-500 focus:ring-red-500">
              <span class="ml-2">Dine In</span>
            </label>
            <label class="inline-flex items-center">
              <input type="radio" name="order_type" value="take away" class="text-red-500 focus:ring-red-500">
              <span class="ml-2">Take Away</span>
            </label>
          </div>
        </div>

        <button type="submit"
          class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition duration-200">
          Checkout Semua Pesanan
        </button>
      </form>

      <a href="{{ route('halamanutama') }}"
        class="block mt-6 text-center bg-gray-300 text-gray-800 py-2 rounded-lg hover:bg-gray-400 transition duration-200">
        Back To Menu
      </a>
    </div>
  </div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalDisplay = document.getElementById('cart-total');
    const orderDataInput = document.getElementById('order-data');
    const orderForm = document.getElementById('order-form');

    const noteSection = document.createElement('div');
    noteSection.className = 'mt-6 space-y-4';
    orderForm.insertBefore(noteSection, orderForm.firstChild);

    if (cart.length === 0) {
      cartItemsContainer.innerHTML = '<p class="text-center text-gray-500">Keranjang kosong. Silakan pilih menu terlebih dahulu.</p>';
      orderForm.style.display = 'none';
      cartTotalDisplay.textContent = 'Rp 0';
      return;
    }

    function renderCart() {
      cartItemsContainer.innerHTML = '';
      noteSection.innerHTML = '';
      let total = 0;

      cart.forEach((item, index) => {
        const price = Number(item.price);
        const qty = Number(item.quantity);
        const itemTotal = price * qty;
        total += itemTotal;

        const itemElem = document.createElement('div');
        itemElem.className = 'border p-4 rounded-lg shadow-sm bg-white space-y-3';

        itemElem.innerHTML = `
          <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div class="flex-1">
              <h3 class="text-lg sm:text-xl font-semibold capitalize">${item.name}</h3>
              <p class="font-bold text-red-500">Rp ${itemTotal.toLocaleString('id-ID')}</p>
              <div class="mt-2 flex items-center gap-2">
                <button class="decrease bg-gray-200 px-3 py-1 rounded text-lg font-bold" data-index="${index}">-</button>
                <span class="font-medium">${qty}</span>
                <button class="increase bg-gray-200 px-3 py-1 rounded text-lg font-bold" data-index="${index}">+</button>
              </div>
            </div>
            <button data-index="${index}" class="delete bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
              Hapus
            </button>
          </div>
        `;

        cartItemsContainer.appendChild(itemElem);

        const noteElem = document.createElement('div');
        noteElem.innerHTML = `
          <label class="block text-sm font-medium mb-1">${item.name} - Catatan</label>
          <input
            type="text"
            placeholder="Contoh: tanpa sambal"
            value="${item.notes || ''}"
            data-index="${index}"
            class="note-input block w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white"
          />
        `;
        noteSection.appendChild(noteElem);
      });

      cartTotalDisplay.textContent = `Rp ${total.toLocaleString('id-ID')}`;
      orderDataInput.value = JSON.stringify(cart);
    }

    cartItemsContainer.addEventListener('click', (e) => {
      const idx = e.target.getAttribute('data-index');
      if (idx === null) return;

      if (e.target.classList.contains('delete')) {
        cart.splice(idx, 1);
      } else if (e.target.classList.contains('increase')) {
        cart[idx].quantity += 1;
      } else if (e.target.classList.contains('decrease')) {
        cart[idx].quantity = Math.max(1, cart[idx].quantity - 1);
      }

      localStorage.setItem('cart', JSON.stringify(cart));
      renderCart();

      if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p class="text-center text-gray-500">Keranjang kosong. Silakan pilih menu terlebih dahulu.</p>';
        orderForm.style.display = 'none';
        cartTotalDisplay.textContent = 'Rp 0';
      } else {
        orderForm.style.display = 'block';
      }
    });

    orderForm.addEventListener('input', (e) => {
      if (e.target.classList.contains('note-input')) {
        const index = e.target.getAttribute('data-index');
        cart[index].notes = e.target.value;
        localStorage.setItem('cart', JSON.stringify(cart));
        orderDataInput.value = JSON.stringify(cart);
      }
    });

    orderForm.addEventListener('submit', () => {
      orderDataInput.value = JSON.stringify(cart);
    });

    renderCart();
  });
</script>

</body>
</html>
