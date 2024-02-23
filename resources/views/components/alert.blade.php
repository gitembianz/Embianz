@if (session()->has("notification"))
	{{-- aici se afla notificarea noua --}}
	<div class="notifications">
		<div class="toast {{ session("notification.type", "info") }}" id="alertevent">
			@php
				// Determine the SVG code based on the type
				$svg = "";
				switch (session("notification.type", "info")) {
				    case "success":
				        $svg = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 50.00 50.00" xml:space="preserve" width="64px"
                    height="64px" fill="#000000">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC"
                        stroke-width="0.3"></g>
                    <g id="SVGRepo_iconCarrier">
                        <circle style="fill:#25AE88;" cx="25" cy="25" r="25"></circle>
                        <polyline
                            style="fill:none;stroke:#FFFFFF;stroke-width:5;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
                            points=" 38,15 22,33 12,25 "></polyline>
                    </g>
                </svg>';
				        break;
				    case "warning":
				        $svg = '<svg style="fill: yellow;stroke: black;" viewBox="0 0 32 32">
                                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                  <g id="SVGRepo_iconCarrier">
                                    <path d="M30.555 25.219l-12.519-21.436c-1.044-1.044-2.738-1.044-3.782 0l-12.52 21.436c-1.044 1.043-1.044 2.736 0 3.781h28.82c1.046-1.045 1.046-2.738 0.001-3.781zM14.992 11.478c0-0.829 0.672-1.5 1.5-1.5s1.5 0.671 1.5 1.5v7c0 0.828-0.672 1.5-1.5 1.5s-1.5-0.672-1.5-1.5v-7zM16.501 24.986c-0.828 0-1.5-0.67-1.5-1.5 0-0.828 0.672-1.5 1.5-1.5s1.5 0.672 1.5 1.5c0 0.83-0.672 1.5-1.5 1.5z"></path>
                                  </g>
                                </svg>';
				        break;
				    case "error":
				        $svg = '<svg style="fill: red;stroke: black;" viewBox="0 0 1200 1200">
                                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                  <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                  <g id="SVGRepo_iconCarrier">
                                  <sodipodi:namedview inkscape:cy="585.53943" inkscape:cx="642.76503" inkscape:zoom="0.52678571" showgrid="false" id="namedview30" guidetolerance="10" gridtolerance="10" objecttolerance="10" borderopacity="1" bordercolor="#666666" pagecolor="#ffffff" inkscape:current-layer="svg2" inkscape:window-maximized="1" inkscape:window-y="24" inkscape:window-height="876" inkscape:window-width="1535" inkscape:pageshadow="2" inkscape:pageopacity="0" inkscape:window-x="65">
                                  </sodipodi:namedview>
                                  <path id="path6448" inkscape:connector-curvature="0" d="M600,0C268.629,0,0,268.629,0,600s268.629,600,600,600s600-268.629,600-600 S931.371,0,600,0z M197.314,439.453h805.371v321.094H197.314V439.453z"></path>
                                  </g>
                                </svg>';
				        break;
				    default:
				        // If the type is not recognized, use default success SVG
				        $svg = "";
				        break;
				}
			@endphp
			{!! $svg !!}

			<div class="toast__text">
				<h3><strong>{{ session("notification.title", "Info") }}</h3>
				<span>{!! session("notification.message") !!}</span>
			</div>
			<button type="button" class="close-button cursor-p" data-bs-dismiss="alertevent" aria-hidden="true" style="border: none">
				<svg style="stroke:black">
					<line x1="18" y1="6" x2="6" y2="18">
					</line>
					<line x1="6" y1="6" x2="18" y2="18">
					</line>
				</svg>
			</button>
		</div>
	</div>
	<script>
		var element = document.getElementById('alertevent');
		if (element) {
			element.style.transition = 'opacity 0.6s ease';
			setTimeout(function() {
				element.style.opacity = '0';
				setTimeout(function() {
					element.remove();
				}, 600);
			}, 5000);
			var closeButton = element.querySelector('.close-button');
			if (closeButton) {
				closeButton.addEventListener('click', function() {
					element.style.opacity = '0';
					setTimeout(function() {
						element.remove();
					}, 600);
				});
			}
		}
	</script>
@endif
