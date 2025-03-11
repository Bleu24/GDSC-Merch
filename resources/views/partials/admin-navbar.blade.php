<nav class="bg-gray-800 shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('admin.dashboard') }}" class="text-white text-2xl font-bold">Admin Panel</a>

        <ul class="flex space-x-6">
            <li><a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white">Dashboard</a></li>
            <li><a href="{{ route('admin.products.index') }}" class="text-gray-300 hover:text-white">Manage Products</a></li>
            <li>
            <li><a href="/" class="text-gray-300 hover:text-white">Back to Site</a></li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-white">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</nav>
