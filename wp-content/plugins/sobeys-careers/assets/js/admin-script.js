// Get Api Data Ajax Function
jQuery(document).on('click', '.updateJsonData', function () {
    jQuery('.updateJsonData').attr('disabled', 'disabled');
    jQuery.ajax({
        url: Ajax.url,
        type: 'post',
        dataType: 'json',
        data: { action: 'Sobeys_Api_Json_Filter', nonce: Ajax.nonce },
        success: function (response) {
            if (response.data.result === 'success') {
                jQuery('.updated').slideToggle();
                jQuery('.updateJsonData').attr('disabled', false);
                setTimeout(function () {
                    jQuery('.updated').slideToggle();
                }, 4000);
            }
        }
    });
});
// Slider textarea Editor & Add/Remove fileds code
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#slider-entries-table');
    let editorCount = document.querySelectorAll('textarea[name="slider_descriptions[]"]').length;
    // Handle Add Slide
    document.querySelector('.add-row').addEventListener('click', function () {
        editorCount++;
        const newEditorId = 'slider_description_' + editorCount;
        const newRow = document.createElement('tbody');
        newRow.classList.add('slider-row');
        newRow.innerHTML = `
            <tr>
                <th scope="row">
                    <label for="slider_title_${editorCount}">Title</label>
                </th>
                <td>
                    <input type="text" name="slider_titles[]" id="slider_title_${editorCount}" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="${newEditorId}">Description</label>
                </th>
                <td>
                    <textarea name="slider_descriptions[]" id="${newEditorId}" rows="5" class="large-text"></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="slider_link_${editorCount}">Link</label>
                </th>
                <td>
                    <input type="text" name="slider_link[]" id="slider_link_${editorCount}" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label>Upload Image</label>
                </th>
                <td>
                    <input type="hidden" name="slider_images[]" class="regular-text slider-image-url" />
                    <button type="button" class="button upload-image-button">Upload</button>
                    <br><br>
                    <img src="" class="slider-preview" style="width:100px;display:none;" />
                    <br><br>
                    <button type="button" class="button remove-row">Remove Row</button>
                </td>
            </tr>
        `;

        tableBody.appendChild(newRow);
        // TinyMCE init for new editor
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: `#${newEditorId}`,
                menubar: false,
                plugins: 'lists link fullscreen',
                toolbar: 'bold italic underline strikethrough | bullist numlist | alignleft aligncenter alignright | link unlink | undo redo',
                quicktags: true,
                height: 150,
                branding: false
            });
        }
    });

    // Handle Remove + Upload Image Buttons
    tableBody.addEventListener('click', function (e) {
        // Remove Row
        if (e.target.classList.contains('remove-row')) {
            e.preventDefault();
            const tbody = e.target.closest('tbody.slider-row');
            if (tbody) tbody.remove();
        }
        // Upload Image
        if (e.target.classList.contains('upload-image-button')) {
            e.preventDefault();

            const button = e.target;
            const td = button.closest('td');
            const input = td.querySelector('.slider-image-url');
            const preview = td.querySelector('.slider-preview');

            const custom_uploader = wp.media({
                title: 'Select or Upload Image',
                button: { text: 'Use this image' },
                multiple: false
            });

            custom_uploader.on('select', function () {
                const attachment = custom_uploader.state().get('selection').first().toJSON();
                input.value = attachment.url;
                preview.src = attachment.url;
                preview.style.display = 'block';
            });

            custom_uploader.open();
        }
    });
});
