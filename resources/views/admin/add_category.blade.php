<x-dashboardheader />
<x-dashboardnavbar />
<x-alert />
<x-dashboardsidebar :active="__('category')" />

<form class="content" method="POST" enctype="multipart/form-data" action="{{ route('add_category') }}">

    {{-- Navigation --}}
    <nav class="nav--controls">
        <h1 class="table--name">{{ __('New category') }}</h1>
        {{-- Refresh Button --}}
        <a class="button button--primary button--centered" tooltip="Back to Categories" tooltip-top
            href="{{ route('category') }}">
            <svg>
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </a>
        <button class="button button--primary button--centered" tooltip="Save Category" tooltip-left type="submit">
            <svg>
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                <path d="M9 15l2 2l4 -4" />
            </svg>
        </button>
        <button class="button button--primary button--centered" tooltip="Reset Category" tooltip-left type="reset">
            <svg>
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
            </svg>
        </button>
    </nav>

    {{-- Tabs Body (Details) --}}
    <section style="height: calc(100% - 107.5px);" class="tabs__content details__view active">
        @csrf

        {{-- Category Name --}}
        <div class="input__tabs">
            <input type="text" name="category" placeholder=" " required value="{{ old('category') }}">
            <label>Name</label>
        </div>

        {{-- Category Active && Displayed on Store --}}
        <div class="details__checkboxes">
            {{-- Category Active --}}
            <div class="checkbox__details ">
                <input type="checkbox" id="active" name="active" value="{{ old('active') }}" />
                <label for="active">Active</label>
            </div>
            {{-- Category Displayed on Store Tab --}}
            <div class="checkbox__details ">
                <input type="checkbox" id="visible" name="visible" value="{{ old('visible') }}" />
                <label for="visible">Displayed on Store Tab?</label>
            </div>
        </div>

        <div class="details__checkboxes">
            {{-- Category Active --}}
            <div class="checkbox__details ">
                <input type="checkbox" id="preload_image" name="preload_image" value="{{ old('preload_image') }}" />
                <label for="preload_image">Preload image</label>
            </div>
            {{-- Category Displayed on Store Tab --}}
            <div class="checkbox__details ">
                <input type="checkbox" id="display_variant_price" name="display_variant_price"
                    value="{{ old('display_variant_price') }}" />
                <label for="display_variant_price">Displayed variant price</label>
            </div>
        </div>
        <div class="details__checkboxes">
            {{-- Category Active --}}
            <div class="checkbox__details ">
                <input type="checkbox" id="one_product_page_category" name="one_product_page_category"
                    value="{{ old('one_product_page_category') }}" />
                <label for="one_product_page_category">Is one product page system category?</label>
            </div>
        </div>
        <div class="details__checkboxes">

            {{-- Category Start Date --}}
            <div class="input__tabs">
                <input type="date" id="start_date" placeholder=" " name="start_date" value="{{ old('start_date') }}">
                <label>Start Date</label>
            </div>

            {{-- Category End Date --}}
            <div class="input__tabs">
                <input type="date" id="end_date" placeholder=" " name="end_date" value="{{ old('end_date') }}">
                <label>End Date</label>
            </div>
        </div>
        <div class="details__checkboxes">

            {{-- Category Sequence --}}
            <div class="input__tabs">
                <input type="number" min="0" name="sequence" placeholder=" " required
                    value="{{ old('sequence') }}">
                <label>Sequence</label>
            </div>
            <div class="input__tabs">
                <input type="number" min="0" name="slider_sequence" placeholder=" " required
                    value="{{ old('slider_sequence') }}">
                <label>Slider sequence</label>
            </div>
        </div>

        {{-- Category Meta Description --}}
        <div class="input__tabs details__long">
            <input type="text" name="meta_description" placeholder=" " value="{{ old('meta_description') }}">
            <label>Meta Description</label>
        </div>

        {{-- Category Short Description --}}
        <div class="input__tabs">
            <input type="text" name="short_description" placeholder=" " value="{{ old('short_description') }}">
            <label>Short Description</label>
        </div>

        <div class="input__tabs">
            <select name="accepted_items" value="{{ old('accepted_items') }}">
                <option selected value="standard">default</option>
                <option value="parent">parents</option>
            </select>
            <label>Accepted items</label>
        </div>

        {{-- Category Long Description --}}

<div class="textarea__tabs details__long">
  <div id="ck-host"></div>
  <input type="hidden" name="long_description" id="long_description_input"
         value="{!! old('long_description', $category->long_description ?? '') !!}">
  <label style="top:-25px; transform:none; color: #bbfcde;">Long Description</label>
</div>
{{-- CKEditor styles + your editor-only styles --}}
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.css">
<style>
  /* Reset look ONLY inside the editable area */
  .ck-content {
    font: 400 16px/1.6 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    color: #111;
    /* optional: give it some height */
    min-height: 280px;
  }

  /* Prevent weird box/paddings from your global CSS */
  .ck-content * { box-sizing: border-box; }

  /* Basic, clean defaults */
  .ck-content p { margin: .8em 0; }
  .ck-content h1, .ck-content h2, .ck-content h3,
  .ck-content h4, .ck-content h5, .ck-content h6 {
    margin: 1.2em 0 .6em;
    line-height: 1.25;
    font-weight: 700;
  }
  .ck-content ul, .ck-content ol { margin: .8em 0; padding-left: 1.5em; }
  .ck-content ul { list-style: disc outside; }
  .ck-content ol { list-style: decimal outside; }
  .ck-content a { text-decoration: underline; color: inherit; }
  .ck-content img { max-width: 100%; height: auto; }
  .ck-content table { border-collapse: collapse; width: 100%; }
  .ck-content th, .ck-content td { border: 1px solid #ddd; padding: .5em; }
  .ck-content blockquote { margin: 1em 0; padding-left: 1em; border-left: 3px solid #ddd; }
  .ck-content hr { border: 0; border-top: 1px solid #e5e5e5; margin: 1.5em 0; }
</style>
<script>
(function () {
  // The initial HTML to load in the iframe editor
  const initialHtml = @json(old('long_description', $category->long_description ?? '<p></p>'));

  const srcdoc = `
<!doctype html><html><head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.css">
  <style>
    html,body{margin:0;height:100%}
    .ck-content{min-height:280px; line-height:1.6;}
    .ck-editor{max-width:100%}
  </style>
</head><body>
  <div id="editor">${initialHtml}</div>

  <script src="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.umd.js"><\/script>
  <script>
  (function(){
    var CK = window.CKEDITOR;
    CK.ClassicEditor.create(document.querySelector('#editor'), {
      licenseKey: '{{ app('global_ckeditor_license') }}',
      plugins: [
        CK.Essentials, CK.Paragraph, CK.Autoformat, CK.PasteFromOffice, CK.RemoveFormat,
        CK.Bold, CK.Italic, CK.Underline, CK.Strikethrough, CK.Font, CK.Alignment,
        CK.Heading, CK.List, CK.ListProperties, CK.Indent, CK.IndentBlock,
        CK.BlockQuote, CK.HorizontalLine, CK.CodeBlock,
        CK.Link,
        CK.Table, CK.TableToolbar,
        CK.SourceEditing
      ],
      toolbar: [
        'sourceEditing','|',
        'undo','redo','|',
        'heading','|',
        'bold','italic','underline','strikethrough','removeFormat','|',
        'link','blockQuote','codeBlock','horizontalLine','|',
        'bulletedList','numberedList','outdent','indent','|',
        'alignment','|',
        'fontSize','fontFamily','fontColor','fontBackgroundColor','|',
        'insertTable'
      ],
      list: { properties: { styles:true, startIndex:true, reversed:true } },
      table: { contentToolbar: ['tableColumn','tableRow','mergeTableCells'] }
    }).then(function(editor){
      // Expose to parent
      window.editor = editor;

      // Tell parent our height (for auto-resize)
      function pingHeight(){
        var h = document.documentElement.scrollHeight || document.body.scrollHeight || 320;
        parent.postMessage({ type:'ck-height', h: h }, '*');
      }
      pingHeight();
      editor.model.document.on('change:data', pingHeight);
      window.addEventListener('resize', pingHeight);
      setTimeout(pingHeight, 50);
    }).catch(function(e){ console.error(e); });
  })();
  <\/script>
</body></html>`;

  // Create the iframe
  const iframe = document.createElement('iframe');
  iframe.id = 'ckframe';
  iframe.srcdoc = srcdoc;
  iframe.style.width = '100%';
  iframe.style.minHeight = '320px';
  iframe.style.border = '1px solid #ddd';
  iframe.style.borderRadius = '8px';
  iframe.style.background = '#fff';

  document.getElementById('ck-host').appendChild(iframe);

  // Auto-resize when the iframe reports new height
  window.addEventListener('message', function (e) {
    if (e.source === iframe.contentWindow && e.data && e.data.type === 'ck-height') {
      iframe.style.height = Math.max(320, e.data.h) + 'px';
    }
  });

  // Sync HTML to hidden input on submit
  const form = document.getElementById('long_description_input')?.closest('form');
  form?.addEventListener('submit', function () {
    const ed = iframe.contentWindow && iframe.contentWindow.editor;
    if (ed) document.getElementById('long_description_input').value = ed.getData();
  });
})();
</script>
        
        <div class="textarea__tabs details__long">
            <textarea name="long_description_bottom" placeholder=" ">{{ old('long_description_bottom') }}</textarea>
            <label>Long Description Bottom</label>
        </div>

<div class="textarea__tabs details__long">
  <div id="ck-host2"></div>
  <input type="hidden" name="long_description_bottom" id="long_description_input_bottom"
         value="{!! old('long_description_bottom', $category->long_description_bottom ?? '') !!}">
  <label style="top:-25px; transform:none; color: #bbfcde;">Long Description Bottom</label>
</div>
{{-- CKEditor styles + your editor-only styles --}}
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.css">
<style>
  /* Reset look ONLY inside the editable area */
  .ck-content {
    font: 400 16px/1.6 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    color: #111;
    /* optional: give it some height */
    min-height: 280px;
  }

  /* Prevent weird box/paddings from your global CSS */
  .ck-content * { box-sizing: border-box; }

  /* Basic, clean defaults */
  .ck-content p { margin: .8em 0; }
  .ck-content h1, .ck-content h2, .ck-content h3,
  .ck-content h4, .ck-content h5, .ck-content h6 {
    margin: 1.2em 0 .6em;
    line-height: 1.25;
    font-weight: 700;
  }
  .ck-content ul, .ck-content ol { margin: .8em 0; padding-left: 1.5em; }
  .ck-content ul { list-style: disc outside; }
  .ck-content ol { list-style: decimal outside; }
  .ck-content a { text-decoration: underline; color: inherit; }
  .ck-content img { max-width: 100%; height: auto; }
  .ck-content table { border-collapse: collapse; width: 100%; }
  .ck-content th, .ck-content td { border: 1px solid #ddd; padding: .5em; }
  .ck-content blockquote { margin: 1em 0; padding-left: 1em; border-left: 3px solid #ddd; }
  .ck-content hr { border: 0; border-top: 1px solid #e5e5e5; margin: 1.5em 0; }
</style>
<script>
(function () {
  // The initial HTML to load in the iframe editor
  const initialHtml = @json(old('long_description', $category->long_description ?? '<p></p>'));

  const srcdoc = `
<!doctype html><html><head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.css">
  <style>
    html,body{margin:0;height:100%}
    .ck-content{min-height:280px; line-height:1.6;}
    .ck-editor{max-width:100%}
  </style>
</head><body>
  <div id="editor">${initialHtml}</div>

  <script src="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5.umd.js"><\/script>
  <script>
  (function(){
    var CK = window.CKEDITOR;
    CK.ClassicEditor.create(document.querySelector('#editor'), {
      licenseKey: '{{ app('global_ckeditor_license') }}',
      plugins: [
        CK.Essentials, CK.Paragraph, CK.Autoformat, CK.PasteFromOffice, CK.RemoveFormat,
        CK.Bold, CK.Italic, CK.Underline, CK.Strikethrough, CK.Font, CK.Alignment,
        CK.Heading, CK.List, CK.ListProperties, CK.Indent, CK.IndentBlock,
        CK.BlockQuote, CK.HorizontalLine, CK.CodeBlock,
        CK.Link,
        CK.Table, CK.TableToolbar,
        CK.SourceEditing
      ],
      toolbar: [
        'sourceEditing','|',
        'undo','redo','|',
        'heading','|',
        'bold','italic','underline','strikethrough','removeFormat','|',
        'link','blockQuote','codeBlock','horizontalLine','|',
        'bulletedList','numberedList','outdent','indent','|',
        'alignment','|',
        'fontSize','fontFamily','fontColor','fontBackgroundColor','|',
        'insertTable'
      ],
      list: { properties: { styles:true, startIndex:true, reversed:true } },
      table: { contentToolbar: ['tableColumn','tableRow','mergeTableCells'] }
    }).then(function(editor){
      // Expose to parent
      window.editor = editor;

      // Tell parent our height (for auto-resize)
      function pingHeight(){
        var h = document.documentElement.scrollHeight || document.body.scrollHeight || 320;
        parent.postMessage({ type:'ck-height', h: h }, '*');
      }
      pingHeight();
      editor.model.document.on('change:data', pingHeight);
      window.addEventListener('resize', pingHeight);
      setTimeout(pingHeight, 50);
    }).catch(function(e){ console.error(e); });
  })();
  <\/script>
</body></html>`;

  // Create the iframe
  const iframe = document.createElement('iframe');
  iframe.id = 'ckframe2';
  iframe.srcdoc = srcdoc;
  iframe.style.width = '100%';
  iframe.style.minHeight = '320px';
  iframe.style.border = '1px solid #ddd';
  iframe.style.borderRadius = '8px';
  iframe.style.background = '#fff';

  document.getElementById('ck-host2').appendChild(iframe);

  // Auto-resize when the iframe reports new height
  window.addEventListener('message', function (e) {
    if (e.source === iframe.contentWindow && e.data && e.data.type === 'ck-height') {
      iframe.style.height = Math.max(320, e.data.h) + 'px';
    }
  });

  // Sync HTML to hidden input on submit
  const form = document.getElementById('long_description_input_bottom')?.closest('form');
  form?.addEventListener('submit', function () {
    const ed = iframe.contentWindow && iframe.contentWindow.editor;
    if (ed) document.getElementById('long_description_input_bottom').value = ed.getData();
  });
})();
</script>

        {{-- Category SEO Title --}}
        <div class="input__tabs">
            <input type="text" name="seo_title" placeholder=" " value="{{ old('seo_title') }}">
            <label>SEO Title</label>
        </div>

        {{-- Category Friendly URL --}}
        <div class="input__tabs">
            <input type="text" name="seo_id" placeholder=" " value="{{ old('seo_id') }}">
            <label>Friendly URL</label>
        </div>

        {{-- Save Button --}}
        <input class="button button--fill button--secondary details__long" type="submit" value="Add New"
            name="submit">
    </section>

</form>

<x-dashboardfooter />
