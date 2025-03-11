<nav class="bg-white shadow">
    <div class="container mx-auto px-4 py-6 flex justify-between items-center">
        <a href="/" class="text-2xl font-bold text-gray-800">GDSC Merch Haven</a>

        <ul class="flex space-x-4">
            <li><a href="/" class="text-gray-600 hover:text-gray-900">Home</a></li>
            <li><a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900">Products</a></li>
            <li><a href="#about" class="text-gray-600 hover:text-gray-900">About</a></li>
            <li><a href="#contact" class="text-gray-600 hover:text-gray-900">Contact</a></li>
        </ul>

        <div class="flex items-center space-x-6"> 
            <!-- Cart Icon -->
            <div class="relative">
                <button id="cartButton" class="relative">
                    🛒
                    <span id="cartCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full hidden">0</span>
                </button>
            </div>

            <!-- User Dropdown -->
            <div class="relative">
                @auth
                    <!-- Dropdown Toggle Button -->
                    <button id="dropdownAvatarNameButton" class="flex items-center gap-x-2 text-sm pe-1 font-medium text-gray-900 rounded-full hover:text-blue-600 focus:ring-4 focus:ring-gray-100">
                        <span class="sr-only">Open user menu</span>
                        <x-fas-user />
                        {{ auth()->user()->name }}
                        <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="dropdownAvatarName" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 absolute right-0 mt-2">
                        <div class="px-4 py-3 text-sm text-gray-900">
                            <div class="font-medium">Email</div>
                            <div class="truncate">{{ auth()->user()->email }}</div>
                        </div>
                        <ul class="py-2 text-sm text-gray-700">
                            <li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">About</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Contact</a>
                            </li>
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'ADMIN')
                                <li>
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100 text-red-600 font-semibold">Admin Panel</a>
                                </li>
                            @endif
                        </ul>
                        <div class="py-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">Sign out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-blue-500 hover:underline mr-4">Login</a>
                    <a href="{{ route('register.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Cart Modal -->
<div id="cartModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <div class="flex justify-between items-center border-b pb-2">
            <h2 class="text-lg font-semibold">Cart Summary</h2>
            <button id="closeCart" class="text-gray-600 hover:text-red-500">&times;</button>
        </div>
        <ul id="cartItems" class="mt-4 space-y-4"></ul>
        <p class="mt-4 font-semibold">Total Price: <span id="cartTotalPrice">₱0.00</span></p>
        <button class="mt-4 w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600">Checkout</button>
    </div>
</div>

<!-- JavaScript for Dropdown & Cart Modal -->   
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Dropdown Modal Toggle
        const dropdownButton = document.getElementById("dropdownAvatarNameButton");
        const dropdownMenu = document.getElementById("dropdownAvatarName");

        if (dropdownButton) {
            dropdownButton.addEventListener("click", function(event) {
                event.stopPropagation();
                dropdownMenu.classList.toggle("hidden");
            });
        }

        document.addEventListener("click", function(event) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add("hidden");
            }
        });

        const cartButton = document.getElementById("cartButton");
        const cartModal = document.getElementById("cartModal");
        const closeCart = document.getElementById("closeCart");
        const cartItems = document.getElementById("cartItems");
        const cartTotalPrice = document.getElementById("cartTotalPrice");
        const cartCount = document.getElementById("cartCount");

        let cart = [];

        function fetchCart() {
            fetch('/cart')
                .then(response => response.json())
                .then(data => {
                    cart = data.map(item => ({
                        id: item.id,
                        name: item.product.name,
                        price: item.product.price,
                        quantity: item.quantity
                    }));
                    updateCart();
                })
                .catch(error => console.error('Error fetching cart:', error));
        }

        function updateCart() {
            cartItems.innerHTML = "";
            let total = 0;

            cart.forEach((item, index) => {
                total += item.price * item.quantity;
                cartItems.innerHTML += `
                    <li class="flex justify-between items-center border-b pb-2">
                        <span>${item.name} x${item.quantity}</span>
                        <div class="flex items-center">
                            <button class="decreaseQty px-2 py-1 bg-gray-300 rounded-l" data-index="${index}">-</button>
                            <input type="text" class="w-10 text-center border-t border-b" value="${item.quantity}" readonly>
                            <button class="increaseQty px-2 py-1 bg-gray-300 rounded-r" data-index="${index}">+</button>
                        </div>
                        <button class="removeFromCart px-2 py-1 bg-red-500 text-white rounded" data-index="${index}">X</button>
                    </li>
                `;
            });

            cartTotalPrice.textContent = `₱${total.toFixed(2)}`;
            cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCount.classList.toggle("hidden", cart.length === 0);
        }

        document.addEventListener("click", function (event) {
            if (event.target.classList.contains("addToCart")) {
                const productId = event.target.dataset.id;

                fetch('/cart', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ product_id: productId, quantity: 1 })
                })
                .then(() => fetchCart());
            }
        });

        document.addEventListener("click", function (event) {
            let index = event.target.dataset.index;
            let cartItem = cart[index];

            if (event.target.classList.contains("decreaseQty") && cartItem.quantity > 1) {
                fetch(`/cart/${cartItem.id}`, {
                    method: "PATCH",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content") },
                    body: JSON.stringify({ quantity: cartItem.quantity - 1 })
                }).then(() => fetchCart());
            }

            if (event.target.classList.contains("increaseQty")) {
                fetch(`/cart/${cartItem.id}`, {
                    method: "PATCH",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content") },
                    body: JSON.stringify({ quantity: cartItem.quantity + 1 })
                }).then(() => fetchCart());
            }

            if (event.target.classList.contains("removeFromCart")) {
                fetch(`/cart/${cartItem.id}`, {
                    method: "DELETE",
                    headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content") }
                }).then(() => fetchCart());
            }
        });

        cartButton.addEventListener("click", function () {
            cartModal.classList.toggle("hidden");
            fetchCart();
        });

        closeCart.addEventListener("click", function () {
            cartModal.classList.add("hidden");
        });

        fetchCart();
    });
</script>
