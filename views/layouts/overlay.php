<div class="search-overlay shadow">
    <form action="?act=search-product" method="post">
        <div class="search__box-content">
            <input type="text" id="search__box-input" name="keyword" class="fs-2" placeholder="Type to search..." required autofocus>
            <input type="submit" id="search__box-btn" name="btnSearch" class="fs-2" value="GO">
            <div class="search-close" id="search__box-close">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 1024 1024" height="1.1em" width="1.1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M563.8 512l262.5-312.9c4.4-5.2.7-13.1-6.1-13.1h-79.8c-4.7 0-9.2 2.1-12.3 5.7L511.6 449.8 295.1 191.7c-3-3.6-7.5-5.7-12.3-5.7H203c-6.8 0-10.5 7.9-6.1 13.1L459.4 512 196.9 824.9A7.95 7.95 0 0 0 203 838h79.8c4.7 0 9.2-2.1 12.3-5.7l216.5-258.1 216.5 258.1c3 3.6 7.5 5.7 12.3 5.7h79.8c6.8 0 10.5-7.9 6.1-13.1L563.8 512z">
                    </path>
                </svg>
            </div>
        </div>
    </form>
</div>

<div class="cart-overlay">
    <div class="cart-container shadow-sm">
        <div class="cart-header fs-2">
            <h3 class="fw-bold">
                Your Shopping Cart
            </h3>
            <div class="cart-close">✖</div>
        </div>

        <div class="cart-body">
            <?php $carts = getCartByCustomer('tbl_carts', $_SESSION["user"]['id'] ?? ''); ?>
            <?php if (empty($carts)) : ?>
                <div class="m-auto text-center">
                    <img src="<?= BASE_URL ?>assets/images/empty-cart.png" style="width: 180px;">
                    <h2 class="py-4">Your cart is empty</h2>
                    <a href="?act=categories" class="btn btn-outline-dark px-3 fs-4">Keep Shopping</a>
                </div>
            <?php else : ?>
                <!-- When has items -->
                <!-- Add HTML string here -->
                <?php foreach ($carts as $cart) : ?>
                    <div class="cart-item">
                        <div onclick="redirectToProductDetail(<?= $cart['id_product'] ?>)" class="cart-item-start">
                            <img src="<?= BASE_URL . $cart['thumbnail'] ?>" alt="product img" class="cart-item-img" layout="fill">
                        </div>
                        <div class="w-100 p-3 d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-column gap-3">
                                    <h4 class="fs-3 fw-bold"><?= $cart['product'] ?></h4>
                                    <span class="fs-4 fw-semibold"><?= $cart['color'] ?></span>
                                </div>
                                <a href="?act=delete-cart-item&id=<?= $cart['id_cart'] ?>" class="cart-item-remove" style="height: fit-content;">
                                    <svg width="20" height="20" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.25 5.25H3.75V9.75H5.25V5.25Z" fill="#524646"></path>
                                        <path d="M8.25 5.25H6.75V9.75H8.25V5.25Z" fill="#524646"></path>
                                        <path d="M9 0.75C9 0.3 8.7 0 8.25 0H3.75C3.3 0 3 0.3 3 0.75V2.25H0V3.75H0.75V11.25C0.75 11.7 1.05 12 1.5 12H10.5C10.95 12 11.25 11.7 11.25 11.25V3.75H12V2.25H9V0.75ZM4.5 1.5H7.5V2.25H4.5V1.5ZM9.75 3.75V10.5H2.25V3.75H9.75Z" fill="#524646"></path>
                                    </svg>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="d-flex">
                                    <div class="cart-qty-btn qty-minus"><span>−</span></div>
                                    <div class="cart-qty-number fw-bold"><?= $cart['quantity'] ?></div>
                                    <div class="cart-qty-btn qty-plus"><span>+</span></div>
                                </div>
                                <div class="fs-3 fw-bold">£<?= $cart['price'] - ($cart['price'] * $cart['discount'] / 100); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php if (!empty($carts)) : ?>
            <div class="cart-footer">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Total:</h2>
                    <?php
                    $totalPrice = 0; // Khởi tạo biến tổng giá trị của giỏ hàng
                    foreach ($carts as $cart) {
                        // Tính giá tiền của mỗi sản phẩm và cộng vào tổng
                        $productPrice = ($cart['price'] - ($cart['price'] * $cart['discount'] / 100)) * $cart['quantity'];
                        $totalPrice += $productPrice;
                    }
                    ?>
                    <span class="fs-2 fw-bold">£<?= $totalPrice ?></span>
                </div>
                <button class="btn btn-danger px-4 py-2 fs-4 float-end">Go to Checkout</button>
            </div>
        <?php endif; ?>
    </div>
</div>