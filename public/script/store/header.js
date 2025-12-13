//<--------------------------------------------------------------------->
//<---------------------------- ScrollEvent ---------------------------->
function startHeaderScripts() {
    searchBar();
    leftbar(
        "basketOpen",
        "basketClose",
        "basketList",
        "basketContent",
        "basketHidden",
    );
    leftbar("wishOpen", "wishClose", "wishList", "wishContent", "wishHidden");
    leftbar("menuOpen", "menuClose", "menuList", "menuContent", "menuHidden");
    dropmenus(".dropmenu", false);
    dropmenus(".submenu", false);
    scrollEvent();
    window.addEventListener("scroll", scrollEvent);
    window.addEventListener("resize", scrollEvent);
}

// expose globally
window.initHeaderJs = function () {
    startHeaderScripts();
};

function scrollEvent() {
  const header = document.querySelector("header");
  const banner = document.querySelector(".banner");
  const main = document.querySelector("main");
  const body = document.body;

  if (!banner) {
    const headerHeight = header.offsetHeight;
    // const bannerHeight = banner.offsetHeight;

    if (window.pageYOffset > 200) {
      // banner.style.transform = `translate3d(0px, -${bannerHeight}px, 0px)`;
      main.style.paddingTop = `${headerHeight}px`;
      header.style.paddingTop = 0;
    } else {
      // banner.style.transform = `translate3d(0px, 0px, 0px)`;
      main.style.paddingTop = `${headerHeight}px`;
      // header.style.paddingTop = `${headerHeight}px`;
    }
  } else {
    const headerHeight = header.offsetHeight;
    const bannerHeight = banner.offsetHeight;

    if (window.pageYOffset > 200) {
      banner.style.transform = `translate3d(0px, -${bannerHeight}px, 0px)`;
      main.style.paddingTop = `${headerHeight}px`;
      header.style.paddingTop = 0;
    } else {
      banner.style.transform = `translate3d(0px, 0px, 0px)`;
      main.style.paddingTop = `${60 + bannerHeight}px`;
      header.style.paddingTop = `${bannerHeight}px`;
    }
  }
}

//<-------------------------- End ScrollEvent -------------------------->
//<--------------------------------------------------------------------->
//<------------------------ DropMenu on leftbar ------------------------>
function dropmenus(menuID, setActive = false) {
  let dropmenus = document.querySelectorAll(menuID);

  dropmenus.forEach(function (menu) {
    let button = menu.querySelector(`.${menu.className}__open`);
    let list = menu.querySelector(`.${menu.className}__list`);

    if (button && list) {
      if (setActive) {
        menu.classList.add("active");
        list.classList.add("active");
      }

      button.addEventListener("click", function () {
        menu.classList.toggle("active");
        list.classList.toggle("active");

        if (menu.classList.contains("active")) {
          menu.scrollIntoView({ behavior: "smooth", block: "start" });
        }
      });
    } else {
      return;
    }
  });
}
//<---------------------- End DropMenu on leftbar ---------------------->
//<--------------------------------------------------------------------->
//<------------------------------ LeftBar ------------------------------>
function leftbar(idOpen, idClose, idList, idContent, hiddenId) {
  const buttonOpen = document.getElementById(idOpen);
  const buttonClose = document.getElementById(idClose);
  const list = document.getElementById(idList);
  const content = document.getElementById(idContent);
  const contentModal = document.getElementById(hiddenId);
  const body = document.querySelector("body");

  if (!buttonOpen || !buttonClose || !list || !content) {
    // console.log("leftbar error");
    return;
  } else {

    buttonOpen.addEventListener("click", () => {
      list.classList.add("active");
      // gtm view_cart event

      dataLayer.push({
        event: "view_minicart",
        ecommerce: {
        }
       });

      body.style.overflow = "hidden";
      scrollEvent();
    });
    buttonClose.addEventListener("click", () => {
      list.classList.remove("active");
      body.style.overflow = "auto";
    });
    contentModal.addEventListener("click", (event) => {
      list.classList.remove("active");
      body.style.overflow = "auto";
    });

    function handleKeyPress(event) {
      if (event.keyCode === 27) {
        list.classList.remove("active");
        body.style.overflow = "auto";
      }
    }

    document.addEventListener("keydown", handleKeyPress);
  }
}
//<---------------------------- End LeftBar ---------------------------->
//<--------------------------------------------------------------------->
//<----------------------------- SearchBar ----------------------------->
function searchBar() {
    const searchBtn = document.getElementById("searchOpen");
    const closeBtn = document.getElementById("searchClose");
    const input = document.getElementById("searchInput");
    const modalClose = document.getElementById("modalClose");
    const searching = document.getElementById("searching");
    const searchList = document.getElementById("searchList");
    const searchContent = document.getElementById("searchContent");

    if (!searchBtn || !closeBtn || !input || !modalClose || !searching || !searchList) {
        console.error('Search elements missing');
        return;
    }

    let searchTimeout;
    let resultsContainer = null;

    // Open search overlay
    searchBtn.addEventListener("click", function () {
        searchList.classList.add("active");
        document.body.style.overflow = "hidden";
        setTimeout(() => input.focus(), 50);
    });

    // Close search overlay
    const closeSearch = () => {
        searchList.classList.remove("active");
        document.body.style.overflow = "auto";
        input.value = "";
        
        // Remove the search__list if it exists
        if (resultsContainer && resultsContainer.parentNode) {
            resultsContainer.remove();
            resultsContainer = null;
        }
    };

    closeBtn.addEventListener("click", closeSearch);
    modalClose.addEventListener("click", closeSearch);

    // ESC key closes search
    function handleKeyPress(event) {
        if (event.keyCode === 27 && searchList.classList.contains("active")) {
            closeSearch();
        }
    }
    document.addEventListener("keydown", handleKeyPress);

    // Live search as user types (matching wire:model.live.debounce.500ms)
    input.addEventListener("input", function () {
        const query = input.value.trim();

        clearTimeout(searchTimeout);

        // Remove results if empty (matching @if ($search && $active))
        if (query.length === 0) {
            if (resultsContainer && resultsContainer.parentNode) {
                resultsContainer.remove();
                resultsContainer = null;
            }
            return;
        }

        // Debounce search - wait 500ms
        searchTimeout = setTimeout(() => {
            fetch(`/api/search?q=${encodeURIComponent(query)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    // Remove old results container if exists
                    if (resultsContainer && resultsContainer.parentNode) {
                        resultsContainer.remove();
                    }

                    // Create new <ul class="search__list"> (matching @if ($search && $active))
                    resultsContainer = document.createElement('ul');
                    resultsContainer.className = 'search__list';

                    // No results case (matching @else inside the @if count check)
                    if (data.count === 0) {
                        resultsContainer.innerHTML = `<span>${data.no_results_message}</span>`;
                        searchContent.appendChild(resultsContainer);
                        return;
                    }

                    let html = '';

                    // Render products (matching @if (count($objects) > 0))
                    if (data.products.length > 0) {
                        data.products.forEach(product => {
                            html += `
                                <li class="search__item">
                                    <a class="search__link" href="${product.url}">
                                        ${product.image ? `<img title="${product.name}" loading="eager" src="${product.image}" alt="${product.name}">` : ''}
                                        <div class="search__link--text">
                                            <div class="search__link--top">
                                                <p>${product.short_description || ''}</p>
                                            </div>
                                            <div class="search__link--bottom">
                                                <h4>${product.name}</h4>
                                                ${product.price ? `<span>${product.price} ${data.currency}</span>` : ''}
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            `;
                        });
                    }

                    // Render categories (matching @if (count($cats) > 0))
                    if (data.categories.length > 0) {
                        data.categories.forEach(category => {
                            html += `
                                <li class="search__item">
                                    <a class="search__link" href="${category.url}">
                                        ${category.image ? `<img title="${category.name.replace(/<[^>]*>/g, '')}" loading="eager" src="${category.image}" alt="${category.name.replace(/<[^>]*>/g, '')}">` : ''}
                                        <div class="search__link--text">
                                            <div class="search__link--bottom">
                                                <h4>${category.name}</h4>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            `;
                        });
                    }

                    resultsContainer.innerHTML = html;
                    searchContent.appendChild(resultsContainer);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    if (resultsContainer && resultsContainer.parentNode) {
                        resultsContainer.remove();
                    }
                    resultsContainer = document.createElement('ul');
                    resultsContainer.className = 'search__list';
                    resultsContainer.innerHTML = '<span>Eroare la căutare.</span>';
                    searchContent.appendChild(resultsContainer);
                });
        }, 500);
    });

    // Enter key or search button redirects to full search page
    const redirectToSearch = () => {
        const query = input.value.trim();
        if (query) {
            window.location.href = "/search/" + encodeURIComponent(query);
        }
    };

    input.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            redirectToSearch();
        }
    });

    searching.addEventListener("click", redirectToSearch);
}





//<--------------------------- End SearchBar --------------------------->
//<--------------------------------------------------------------------->
//<------------------------ Double Tap Redirect ------------------------>
let lastTap = 0;

function DoubleTapRedirect(link) {
  const currentTime = new Date().getTime();
  const tapLength = currentTime - lastTap;
  lastTap = currentTime;

  if (tapLength < 500 && tapLength > 0) {
    window.location.href = link;
  }
}
