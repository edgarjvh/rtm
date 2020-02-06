$(document).ready(function () {
   $(document).on('click', '.add-invitation-btn', function (e) {
        let form = $(this).closest('form');
        let partnersContainer = form.find('.partners-container');
        let count = partnersContainer.find('.form-group').length;

        let newFormGroup = `
            <div class="form-group">
                <input id="partner`+ (count+1) +`" type="email"
                       class="form-control partner" name="partners[]"
                       required autofocus>
            </div>
        `;

        partnersContainer.append(newFormGroup);
   });
});