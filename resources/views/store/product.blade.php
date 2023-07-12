<x-store-head />
<x-store-header />
<main>
    <section class="container">
        <div class="product">
            <div class="product__preview">
                <div class="product__image">
                    <button class="product__image-prev">
                        <svg>
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <img class="thumbnail-active" src="/images/store/bottle1.png" alt="Product Image" id="openModal">
                    <button class="product__image-next">
                        <svg>
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>
                <div class="product__nails">
                    <img class="thumbnail" src="/images/store/bottle-img1.jpeg" alt="Thumbnail 1">
                    <img class="thumbnail" src="/images/store/bottle-img2.webp" alt="Thumbnail 2">
                    <img class="thumbnail" src="/images/store/bottle-img3.webp" alt="Thumbnail 3">
                    <img class="thumbnail" src="/images/store/bottle-img4.png" alt="Thumbnail 1">
                    <img class="thumbnail" src="/images/store/bottle2.png" alt="Thumbnail 2">
                    <img class="thumbnail" src="/images/store/bottle3.png" alt="Thumbnail 3">
                    <img class="thumbnail" src="/images/store/bottle1.png" alt="Thumbnail 1">
                    <img class="thumbnail" src="/images/store/bottle2.png" alt="Thumbnail 2">
                    <img class="thumbnail" src="/images/store/bottle3.png" alt="Thumbnail 3">
                </div>
                <div class="product__modal" id="modal">
                  <div class="slideshow">
                    <!-- Full-width images with number and caption text -->
                    <div class="slideshow--slides">
                      <img src="/images/store/bottle-img1.jpeg">
                    </div>
                    <div class="slideshow--slides">
                      <img src="/images/store/bottle-img2.webp">
                    </div>
                    <div class="slideshow--slides">
                      <img src="/images/store/bottle-img3.webp">
                    </div>
                    <div class="slideshow--slides">
                      <img src="/images/store/bottle-img4.png">
                    </div>
                    <div class="slideshow--slides">
                      <img src="/images/store/bottle2.png">
                    </div>

                    <div class="slideshow--slides">
                      <img src="/images/store/bottle3.png">
                    </div>


                    <!-- Next and previous buttons -->
                    <a class="prev" onclick="plusSlides(-1)">
                      <svg>
                        <polyline points="15 18 9 12 15 6"></polyline>
                      </svg>
                    </a>
                    <a class="next" onclick="plusSlides(1)">
                      <svg>
                        <polyline points="9 18 15 12 9 6"></polyline>
                      </svg>
                    </a>
                    <!-- Dots buttons -->
                    <div class="dots" id="dots">
                    </div>
                    <button id="closeModal">
                      <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                      </svg>
                    </button>
                  </div>
                </div>
            </div>
            <div class="product__info">
                <h1>"HydraMax 24: The Ultimate Quenching Companion"</h1>
                <p>
                    Stay hydrated on-the-go with HydraMax 24, the perfect companion for active individuals. This
                    24-ounce water bottle combines stylish design with advanced insulation technology to keep
                    your beverages at the ideal temperature for hours. Whether you're hitting the gym, hiking, or
                    simply running errands, HydraMax 24 ensures you stay refreshed and energized throughout the day.
                </p>
                <div class="product__price">
                  <div class="product__count">
                      <button id="countDecrease">
                          <svg>
                              <line x1="5" y1="12" x2="19" y2="12"></line>
                          </svg>
                      </button>
                      <input type="number" name="count" id="count" min="1" value="1">
                      <button id="countIncrease">
                          <svg>
                              <line x1="12" y1="5" x2="12" y2="19"></line>
                              <line x1="5" y1="12" x2="19" y2="12"></line>
                          </svg>
                      </button>
                  </div>
                  <h3>50.00€</h3>
                </div>
            <div class="product__buttons">
                <button class="product__btn">Add to cart</button>
                <button class="product__btn">
                    <svg>
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                        </path>
                    </svg>
                </button>
            </div>
          </div>
        </div>

        <div class="tab">
            <div class="tab__header">
                <button class="tab__header--btn">Description</button>
                <button class="tab__header--btn">Details</button>
            </div>
            <div class="tab__content">
                <div class="tab__pane active">
                    <p>
                        Introducing the HydraMax 24: The Ultimate Quenching Companion for Active Lifestyles
                        <br>
                        The HydraMax 24 is not just an ordinary water bottle; it's a game-changer for those who
                        value staying hydrated while leading an active lifestyle. With its sleek and ergonomic
                        design, this 24-ounce water bottle is meticulously crafted to be your reliable companion
                        wherever your adventures take you.
                        <br>
                        One of the standout features of the HydraMax 24 is its advanced insulation technology.
                        Designed to keep your beverages at the perfect temperature, it ensures your drinks stay
                        refreshingly cold on scorching summer days and pleasantly warm during chilly winter
                        outings. No matter the weather, you can count on HydraMax 24 to deliver a satisfying sip
                        every time.
                        <br>
                        Crafted with durability in mind, the HydraMax 24 is built to withstand the rigors of
                        your active endeavors. Made from high-quality, BPA-free materials, it guarantees a
                        long-lasting and safe hydration experience. Its leak-proof construction provides peace
                        of mind, allowing you to toss it into your bag without worry, while the convenient carry
                        handle ensures easy transportation wherever you go.
                        <br>
                        But the HydraMax 24 doesn't stop at just functionality—it's a statement of style. The
                        sleek and modern design, available in a range of vibrant colors and patterns, ensures
                        that you can express your personal taste while staying hydrated. It effortlessly
                        complements any outfit or gear, making it a fashion-forward accessory for fitness
                        enthusiasts, outdoor adventurers, and busy professionals alike.
                    </p>
                </div>
                <div class="tab__pane">
                    <div class="table__wrapper">
                        <table class="table__info">
                            <thead>
                                <tr>
                                    <th>Specification </th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Specification</td>
                                    <td>Description</td>
                                </tr>
                                <tr>
                                    <td>Model</td>
                                    <td>HydraMax 24</td>
                                </tr>
                                <tr>
                                    <td>Capacity</td>
                                    <td>24 ounces (710 ml)</td>
                                </tr>
                                <tr>
                                    <td>Material</td>
                                    <td>BPA-free, high-quality materials</td>
                                </tr>
                                <tr>
                                    <td>Insulation</td>
                                    <td>Advanced insulation technology for temperature retention</td>
                                </tr>
                                <tr>
                                    <td>Leak-proof</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td>Carry handle</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td>Design</td>
                                    <td>Sleek and ergonomic</td>
                                </tr>
                                <tr>
                                    <td>Colors</td>
                                    <td>Multiple vibrant options available</td>
                                </tr>
                                <tr>
                                    <td>Dimensions</td>
                                    <td>Height: 9.5 inches (24 cm)<br> Diameter: 2.75 inches (7 cm)</td>
                                </tr>
                                <tr>
                                    <td>Weight</td>
                                    <td>Approximately 0.45 pounds (200 grams)</td>
                                </tr>
                                <tr>
                                    <td>Suitable for</td>
                                    <td>Active lifestyles, sports, outdoor activities, daily use</td>
                                </tr>
                                <tr>
                                    <td>Safety</td>
                                    <td>BPA-free and non-toxic</td>
                                </tr>
                                <tr>
                                    <td>Warranty</td>
                                    <td>Manufacturer's warranty included</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </section>
</main>
<x-store-footer />
