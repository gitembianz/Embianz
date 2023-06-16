<x-dashboardheader />
<x-dashboardnavbar />
{{-- Display session message --}}
@if (session()->has('message'))
    <div class="alert__session" id="alertevent">
        <Span class="alert__session-text">{!! session('message') !!}</Span>
        <button class="alert__session-btn" type="button"
            onclick="document.getElementById('alertevent').style.display='none'" data-bs-dismiss="alert"
            aria-hidden="true">
            <svg>
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    <script>
        const alertEvent = document.getElementById("alertevent");
        header.style.marginBottom = '4rem';
        alertEvent.style.opacity = '1';

        setTimeout(function() {
            alertEvent.style.opacity = '0';
            setTimeout(function() {
                alertEvent.remove();
                header.style.marginBottom = '0';
            }, 500);
        }, 2000);
    </script>
@endif
{{-- End Section session message --}}
<x-dashboardsidebar />


{{-- Page content start --}}
<section class="content">
    {{-- <div class="tab">
        <div class="tabs">
            <h3 class="tabs__page active">Tab 1</h3>
            <h3 class="tabs__page">Tab 2</h3>
        </div>
        <div class="tab__list">
            <div class="tabs__content active">
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore
                    explicabo cum dolores hic possimus aut corrupti quisquam aperiam
                    quia veniam inventore officiis nam error sunt libero, commodi
                </p>
            </div>
            <div class="tabs__content">
                <div class="accordion">
                    <button class="accordion__btn">Accordion Button</button>
                    <div class="accordion__wrapper">
                        <div class="accordion__content">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis illo accusantium beatae
                            veniam
                            quaerat? Ducimus atque, ut eaque beatae saepe aspernatur enim eveniet earum quod! Sapiente
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis illo accusantium beatae
                            veniam
                            quaerat? Ducimus atque, ut eaque beatae saepe aspernatur enim eveniet earum quod! Sapiente
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis illo accusantium beatae
                            veniam
                            quaerat? Ducimus atque, ut eaque beatae saepe aspernatur enim eveniet earum quod! Sapiente
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis illo accusantium beatae
                            veniam
                            quaerat? Ducimus atque, ut eaque beatae saepe aspernatur enim eveniet earum quod! Sapiente
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis illo accusantium beatae
                            veniam
                            quaerat? Ducimus atque, ut eaque beatae saepe aspernatur enim eveniet earum quod! Sapiente
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis illo accusantium beatae
                            veniam
                        </div>
                    </div>
                </div>
                <div class="accordion">
                    <button class="accordion__btn">Accordion Button</button>
                    <div class="accordion__wrapper">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis illo accusantium beatae veniam
                        quaerat? Ducimus atque, ut eaque beatae saepe aspernatur enim eveniet earum quod! Sapiente
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis illo accusantium beatae veniam
                        quaerat? Ducimus atque, ut eaque beatae saepe aspernatur enim eveniet earum quod! Sapiente
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis illo accusantium beatae veniam
                        quaerat? Ducimus atque, ut eaque beatae saepe aspernatur enim eveniet earum quod! Sapiente
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- livewire tabs --}}
    <livewire:show-category categoryId="{{ $data->id }}" />

    {{-- end livewire tabs --}}

</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardscript />
<x-dashboardmediahanddler />
<x-dashboardscriptcategory />
<x-dashboardfooter />
