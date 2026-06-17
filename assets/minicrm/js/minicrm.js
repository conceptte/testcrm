 MiniCRM = {
        initialize: function() {
            this.initFlashMessages();
        },
        initQuickSearch: function(input, template) {
            this.quickSearch.template = template;

            input.addEventListener('input', function () {
                const query = this.value.trim();
                if (query.length > 2) {
                    MiniCRM.quickSearch.search(query);
                }
		    });

            document.addEventListener('click', function (event) {
                if (!template.contains(event.target) && event.target !== input) {
                    template.style.display = 'none';
                }
            });
        },
        quickSearch: {
            template: null,

            search: function(query) {                
                const searchUrl = this.template.dataset.url;
                const queryEncoded = encodeURIComponent(query.trim());

                return fetch(searchUrl + '?q=' + queryEncoded, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    this.template.innerHTML = html;
                    this.template.style.display = 'block';
                });
            }
        },
        initFlashMessages: function() {
            const scheduleFlashHide = () => {
				const flashes = document.querySelector('.snippet--flashes');

				if (!flashes) {
					return;
				}

				flashes.classList.remove('is-hidden');

				if (flashes._hideTimer) {
					clearTimeout(flashes._hideTimer);
				}

				flashes._hideTimer = setTimeout(() => {
					flashes.classList.add('is-hidden');
				}, 3000);
			};
			scheduleFlashHide();

			naja.addEventListener('complete', () => {
				scheduleFlashHide();
			});
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        naja.initialize();
        MiniCRM.initialize();
    });