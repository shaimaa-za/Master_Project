@extends('Home.layout.master')

@section('content')
<div class="container my-5" style="padding: 3% 0px 0px;">
    <!-- Navbar ثابت يظهر دائمًا بشكل أفقي -->
    <nav class="navbar navbar-light bg-light shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand font-weight-bold">Dashboard</a>
            <!-- قائمة التبويبات تظهر بشكل أفقي على جميع الشاشات -->
            <ul class="navbar-nav d-flex flex-row justify-content-center w-100" style="gap: 20px;">
                <li class="nav-item">
                    <a href="#" class="nav-link dashboard-link" data-url="{{ route('favorites.index') }}" id="favorites-tab">
                        <i class="fas fa-heart"></i> Favorites
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link dashboard-link" data-url="{{ route('orders.index') }}">
                        <i class="fas fa-shopping-cart"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link dashboard-link" data-url="{{ route('cart.index') }}" id="cart-tab">
                        <i class="fas fa-shopping-basket"></i> Cart
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- Content Area -->
    <div id="dashboard-content" class="card shadow-sm">
        <div class="card-body text-center text-muted">
            <h3>Loading...</h3>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let lastPage = localStorage.getItem("lastPage") || document.getElementById("favorites-tab").getAttribute("data-url");

        loadContent(lastPage);
        highlightActiveTab(lastPage);

        document.querySelectorAll(".dashboard-link").forEach(link => {
            link.addEventListener("click", function(e) {
                e.preventDefault();

                let url = this.getAttribute("data-url");

                document.querySelectorAll(".dashboard-link").forEach(item => item.classList.remove("active"));
                this.classList.add("active");
                localStorage.setItem("lastPage", url);

                loadContent(url);
            });
        });

        function loadContent(url) {
            fetch(url)
                .then(response => response.text())
                .then(html => document.getElementById("dashboard-content").innerHTML = html)
                .catch(error => console.error("Error loading content:", error));
        }

        function highlightActiveTab(url) {
            document.querySelectorAll(".dashboard-link").forEach(link => {
                if (link.getAttribute("data-url") === url) {
                    link.classList.add("active");
                } else {
                    link.classList.remove("active");
                }
            });
        }
    });
</script>
@endsection
