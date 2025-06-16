<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Menu Restoran</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .cart-item-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      font-size: 0.9rem;
      gap: 0.5rem;
    }
    .cart-item-info {
      flex-grow: 1;
    }
    .cart-item-name {
      font-weight: 600;
      margin-bottom: 0.2rem;
    }
    .cart-item-qty {
      font-size: 0.85rem;
      color: #4B5563;
    }
    .cart-item-price {
      white-space: nowrap;
      font-weight: 600;
      color: #dc2626;
    }
  </style>
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<div class="md:hidden flex justify-between items-center bg-[#d12626] text-white p-4 sticky top-0 z-50">
  <div class="flex items-center">
    <img src="{{ asset('images/logocgp.png') }}" alt="Logo" class="w-8 h-8 mr-2">
    <span class="text-xl font-semibold">Menu</span>
  </div>
  <button id="toggleSidebar" class="text-white text-2xl">&#9776;</button>
</div>

<!-- Sidebar -->
<div id="sidebar" class="w-64 bg-[#d12626] text-white md:h-screen fixed md:top-0 md:left-0 md:block hidden md:block z-40 p-6 shadow-lg overflow-y-auto transition-all duration-300">
  <div class="flex items-center justify-center mb-8">
    <img src="{{ asset('images/logocgp.png') }}" alt="Logo Perusahaan" class="w-10 h-10 mr-2" />
    <h2 class="text-2xl font-semibold">Menu</h2>
  </div>
  <ul class="space-y-4">
    @foreach (['coffee','non-coffee','mocktail','other','dessert','burger','breakfast','pasta','snack','soup','signature'] as $k)
      <li><a href="#{{ $k }}" class="block py-3 px-4 rounded-lg hover:bg-red-500 capitalize">{{ str_replace('-', ' ', $k) }}</a></li>
    @endforeach
  </ul>
</div>

<!-- Main Content -->
<div class="md:ml-64 p-4 sm:p-6">
  @foreach (['coffee','non-coffee','mocktail','other','dessert','burger','breakfast','pasta','snack','soup','signature'] as $kategori)
    <div id="{{ $kategori }}" class="my-16 px-2 sm:px-6">
      <h2 class="text-2xl sm:text-4xl font-bold text-gray-800 mb-8 border-b-4 border-yellow-500 inline-block capitalize">
        {{ str_replace('-', ' ', $kategori) }}
      </h2>
      <div id="{{ $kategori }}-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8"></div>
    </div>
  @endforeach
</div>

<!-- Cart Toggle Button -->
<button id="cart-toggle-btn" class="fixed bottom-6 right-4 sm:right-6 bg-green-600 text-white p-3 rounded-full shadow-lg z-40 hidden">🛒</button>

<!-- Cart Popup -->
<div id="cart-popup" class="fixed bottom-6 right-4 sm:right-6 bg-white border border-gray-300 shadow-lg rounded-lg p-4 w-full max-w-sm hidden z-50">
  <h3 class="text-lg font-semibold mb-4">Keranjang Pesanan</h3>
  <div id="cart-items" class="max-h-48 overflow-y-auto mb-4 space-y-4"></div>
  <div id="cart-total" class="text-right text-lg font-bold text-red-600 mb-4">Rp 0</div>
  <a id="checkout-btn" href="/order" class="block bg-green-600 text-white py-2 px-4 rounded text-center hover:bg-green-700 transition">Checkout</a>
  <button id="close-cart-btn" class="mt-3 w-full bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 rounded transition">Close</button>
</div>

<!-- Sidebar toggle -->
<script>
  document.getElementById('toggleSidebar').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('hidden');
  });
</script>

<!-- Menu & Cart Script -->
<script>
fetch('/menus')
.then(res => {
  if (!res.ok) throw new Error('Gagal ambil data dari /menus');
  return res.json();
})
.then(data => {
  const kategoriMap = {
    'coffee': 'coffee-list',
    'non coffee': 'non-coffee-list',
    'mocktail': 'mocktail-list',
    'other': 'other-list',
    'dessert': 'dessert-list',
    'burger sandwich': 'burger-list',
    'breakfast': 'breakfast-list',
    'pasta': 'pasta-list',
    'snack': 'snack-list',
    'soup': 'soup-list',
    'signature': 'signature-list'
  };

  const findContainerId = (kategori) => {
    kategori = kategori.toLowerCase().trim();
    if (kategori.includes('non') && kategori.includes('coffee')) return 'non-coffee-list';
    if (kategori.includes('coffee')) return 'coffee-list';
    if (kategori.includes('mocktail')) return 'mocktail-list';
    if (kategori.includes('dessert')) return 'dessert-list';
    if (kategori.includes('burger') || kategori.includes('sandwich')) return 'burger-list';
    if (kategori.includes('breakfast')) return 'breakfast-list';
    if (kategori.includes('pasta')) return 'pasta-list';
    if (kategori.includes('snack')) return 'snack-list';
    if (kategori.includes('soup') || kategori.includes('soto')) return 'soup-list';
    if (kategori.includes('signature')) return 'signature-list';
    if (kategori.includes('other') || kategori.includes('lain') || kategori === '') return 'other-list';
    return null;
  };

  data.forEach(item => {
    const kategori = item.category || '';
    const containerId = kategoriMap[kategori.toLowerCase().trim()] || findContainerId(kategori);
    if (!containerId) return;
    const container = document.getElementById(containerId);
    if (!container) return;

    const price = Number(item.price) || 0;
    const card = document.createElement('div');
    card.className = 'bg-white border border-gray-200 rounded-xl overflow-hidden shadow hover:shadow-lg transition-all duration-300 cursor-pointer';
    card.innerHTML = `
      <img src="${item.photo_url}" alt="${item.name}" class="w-full h-48 object-cover" />
      <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-800">${item.name}</h3>
        <p class="text-sm text-gray-500 mt-1 capitalize">${item.category}</p>
        <p class="text-red-500 font-bold mt-2">Rp ${price.toLocaleString()}</p>
        <button class="mt-3 w-full bg-[#d12626] text-white py-2 rounded hover:bg-red-600 add-to-cart-btn" 
          data-id="${item.id}" data-name="${item.name}" data-price="${price}">Tambah ke Keranjang</button>
      </div>
    `;
    container.appendChild(card);
  });

  const cartPopup = document.getElementById('cart-popup');
  const cartItemsContainer = document.getElementById('cart-items');
  const cartTotalElem = document.getElementById('cart-total');
  const closeCartBtn = document.getElementById('close-cart-btn');
  const checkoutBtn = document.getElementById('checkout-btn');
  const cartToggleBtn = document.getElementById('cart-toggle-btn');

  let cart = JSON.parse(localStorage.getItem('cart')) || [];

  function renderCart() {
    cartItemsContainer.innerHTML = '';
    let total = 0;

    if (cart.length === 0) {
      cartPopup.classList.add('hidden');
      cartToggleBtn.classList.add('hidden');
      checkoutBtn.classList.add('disabled');
      checkoutBtn.setAttribute('aria-disabled', 'true');
      cartTotalElem.textContent = 'Rp 0';
      return;
    }

    cartPopup.classList.remove('hidden');
    cartToggleBtn.classList.add('hidden');
    checkoutBtn.classList.remove('disabled');
    checkoutBtn.removeAttribute('aria-disabled');

    cart.forEach(item => {
      const price = Number(item.price) || 0;
      const quantity = Number(item.quantity) || 0;
      const itemTotal = price * quantity;

      const itemElem = document.createElement('div');
      itemElem.className = 'cart-item-row';
      itemElem.innerHTML = `
        <div class="cart-item-info">
          <div class="cart-item-name">${item.name}</div>
          <div class="cart-item-qty">
            <button class="text-red-600 font-bold px-2 decrement-qty" data-id="${item.id}">−</button>
            <span class="mx-1">${quantity}</span>
            <button class="text-green-600 font-bold px-2 increment-qty" data-id="${item.id}">+</button>
            <button class="text-red-600 font-bold ml-2 remove-item" data-id="${item.id}">&times;</button>
          </div>
        </div>
        <div class="cart-item-price">Rp ${itemTotal.toLocaleString()}</div>
      `;
      cartItemsContainer.appendChild(itemElem);
      total += itemTotal;
    });

    cartTotalElem.textContent = `Rp ${total.toLocaleString()}`;
  }

  function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
  }

  function addToCart(id, name, price) {
    const parsedPrice = Number(price) || 0;
    const existing = cart.find(i => i.id === id);
    if (existing) {
      existing.quantity = Number(existing.quantity) + 1;
    } else {
      cart.push({ id, name, price: parsedPrice, quantity: 1 });
    }
    saveCart();
    renderCart();
  }

  function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    saveCart();
    renderCart();
  }

  function incrementQty(id) {
    const item = cart.find(i => i.id === id);
    if (item) {
      item.quantity = Number(item.quantity) + 1;
      saveCart();
      renderCart();
    }
  }

  function decrementQty(id) {
    const item = cart.find(i => i.id === id);
    if (item) {
      if (item.quantity > 1) {
        item.quantity = Number(item.quantity) - 1;
      } else {
        removeFromCart(id);
        return;
      }
      saveCart();
      renderCart();
    }
  }

  document.body.addEventListener('click', e => {
    if (e.target.classList.contains('add-to-cart-btn')) {
      const btn = e.target;
      addToCart(btn.dataset.id, btn.dataset.name, btn.dataset.price);
    } else if (e.target.classList.contains('remove-item')) {
      removeFromCart(e.target.dataset.id);
    } else if (e.target.classList.contains('increment-qty')) {
      incrementQty(e.target.dataset.id);
    } else if (e.target.classList.contains('decrement-qty')) {
      decrementQty(e.target.dataset.id);
    }
  });

  closeCartBtn.addEventListener('click', () => {
    cartPopup.classList.add('hidden');
    cartToggleBtn.classList.remove('hidden');
  });

  cartToggleBtn.addEventListener('click', () => {
    cartPopup.classList.remove('hidden');
    cartToggleBtn.classList.add('hidden');
  });

  checkoutBtn.addEventListener('click', (e) => {
    e.preventDefault();
    if (cart.length === 0) return alert('Keranjang kosong!');
    localStorage.setItem('cart', JSON.stringify(cart));
    const orderData = encodeURIComponent(JSON.stringify(cart));
    window.location.href = `/order?order_data=${orderData}`;
  });

  renderCart();
})
.catch(err => console.error('Gagal ambil menu dari backend:', err));
</script>

<!-- Smooth Scroll -->
<script>
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        window.scrollTo({
          top: target.offsetTop - 70,
          behavior: 'smooth'
        });
      }
    });
  });
</script>

</body>
</html>
