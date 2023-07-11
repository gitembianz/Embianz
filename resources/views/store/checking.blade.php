<x-store-head />
<x-store-header />
<main class="details container">
    <div class="details__steps">
        <div class="details__steps--item active">1</div>
        <span class="details__steps--line activeOld"></span>
        <div class="details__steps--item active">2</div>
        <span class="details__steps--line active"></span>
        <div class="details__steps--item">3</div>
    </div>

    <h1 class="section__title">Check your details</h1>
    <div class="checking__wrapper">
        <div class="checking__cart">
            <div class="checking__cart--items">
                <div class="checking__content-item" href="#">
                    <img class="cart__list--img" src="img/bottle1.png" alt="Sustainable Sips: Reusable Bottles">
                    <span class="cart__list--text">Sustainable Sips: Reusable Bottles</span>
                    <span class="cart__list--much">x2</span>
                    <span class="cart__list--much">20$</span>
                </div>
                <div class="checking__content-item" href="#">
                    <img class="cart__list--img" src="img/bottle2.png" alt="Sustainable Sips: Reusable Bottles">
                    <span class="cart__list--text">Sustainable Sips: Reusable Bottles</span>
                    <span class="cart__list--much">x7</span>
                    <span class="cart__list--much">20$</span>
                </div>
                <div class="checking__content-item" href="#">
                    <img class="cart__list--img" src="img/bottle2.png" alt="Sustainable Sips: Reusable Bottles">
                    <span class="cart__list--text">Sustainable Sips: Reusable Bottles</span>
                    <span class="cart__list--much">x7</span>
                    <span class="cart__list--much">20$</span>
                </div>
                <div class="checking__content-item" href="#">
                    <img class="cart__list--img" src="img/bottle2.png" alt="Sustainable Sips: Reusable Bottles">
                    <span class="cart__list--text">Sustainable Sips: Reusable Bottles</span>
                    <span class="cart__list--much">x7</span>
                    <span class="cart__list--much">20$</span>
                </div>
                <div class="checking__content-item" href="#">
                    <img class="cart__list--img" src="img/bottle2.png" alt="Sustainable Sips: Reusable Bottles">
                    <span class="cart__list--text">Sustainable Sips: Reusable Bottles</span>
                    <span class="cart__list--much">x7</span>
                    <span class="cart__list--much">20$</span>
                </div>
            </div>
            <!-- Here is Card Information -->
            <div class="checking__content-complete">
                <span class="checking__content-create--text">Card Online &check;</span>
            </div>
            <div class="checking__content-price">
                <span class="checking__content-complete--text">Total Price:</span>
                <span class="checking__content-create--text">1700$</span>
            </div>
        </div>
        <div class="checking">
            <!-- here is billing contact -->
            <div class="checking__content-complete">
                <span class="checking__content-create--text">Bill contact information &check;</span>
                <span class="checking__content-complete--text">John Doe</span>
                <span class="checking__content-complete--text">+123 456 789</span>
                <span class="checking__content-complete--text">john.doe@example.com</span>
            </div>
            <!-- here is billing address -->
            <div class="checking__content-complete">
                <span class="checking__content-create--text">Billing Address &check;</span>
                <span class="checking__content-complete--text">Address 1*</span>
                <span class="checking__content-complete--text">Address 2</span>
                <span class="checking__content-complete--text">Country</span>
                <span class="checking__content-complete--text">County</span>
                <span class="checking__content-complete--text">City</span>
                <span class="checking__content-complete--text">Post Code</span>
            </div>
            <!-- here is delivery contact -->
            <div class="checking__content-complete">
                <span class="checking__content-create--text">Delivery contact information
                    &check;</span>
                <span class="checking__content-complete--text">John Doe</span>
                <span class="checking__content-complete--text">+123 456 789</span>
                <span class="checking__content-complete--text">john.doe@example.com</span>
            </div>
            <!-- Here is shipping address -->

            <div class="checking__content-complete">
                <span class="checking__content-create--text">Shipping Address &check;</span>
                <span class="checking__content-complete--text">Address 1*</span>
                <span class="checking__content-complete--text">Address 2</span>
                <span class="checking__content-complete--text">Country</span>
                <span class="checking__content-complete--text">County</span>
                <span class="checking__content-complete--text">City</span>
                <span class="checking__content-complete--text">Post Code</span>
            </div>

        </div>
    </div>

    <div class="details__btns">
        <a href="{{ route('order') }}" data-tooltip="Go to previous step">Back</a>
        <a href="{{ route('complete') }}" data-tooltip="Go to next step">Next</a>
    </div>
</main>
<x-store-footer />
