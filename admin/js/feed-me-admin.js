var UNIR = UNIR || {};
var sending_suprise_message = [
	'Estamos enviando este caviar digital ...',
	'Transformando tu feedback en ideas ... enviando información',
	'Estás ayudando a mejorar UNIR ... estamos trasladando tu petición a nuestro equipo de superhéroes ... ',
	'Este proyecto tiene un poquito de ti ... enviando tu feedback',
];

UNIR.Feedme = function () {
	var instance = this;
	this.wrapper = null;
	this.launcher = null;
	this.close_button = null;
	this.form = null;
	this.attachment_button_text = jQuery("#attachment_button").html();

	this.init = function () {
		this.wrapper = document.querySelector('#feed-me .wrapper');
		this.launcher = document.querySelector('#feed-me .launcher');
		this.close_button = this.wrapper.getElementsByClassName('close')[0];
		this.form = this.wrapper.querySelector('form');

		this.launcher.addEventListener('click', function (event) {
			return this.open(event)
		}.bind(this), true);
		this.close_button.addEventListener('click', function (event) {
			return this.close(event)
		}.bind(this), true);
		this.wrapper.addEventListener('click', function (event) {
			return this.close(event)
		}.bind(this), true);
		this.form.addEventListener('submit', function (event) {
			return this.pre_submit(event)
		}.bind(this));
		this.form.attachment_button.addEventListener('click', function (event) {
			return this._attach_button_clicked(event);
		}.bind(this));
		this.form.attachment.addEventListener('change', function (event) {
			return this._attachment_changed(event);
		}.bind(this));

		window.document.addEventListener('keyup', function (event) {
			event.closeForm = true;
			if (event.keyCode == 27) {
				return this.close(event);
			}
		}.bind(this));


		var tags = document.getElementById('feed-me-tags');
		$(tags).select2({multiple: true});
	};

	this.open = function (event) {
		if (this.wrapper) {
			this.wrapper.classList.add('activate');
		}

		event.preventDefault();
		return false;
	};

	this.close = function (event) {
		if (event.target !== this.wrapper && event.target !== this.close_button && typeof event.closeForm == 'undefined') {
			return;
		}

		this.closeIt();

		event.preventDefault();
		return false;
	};

	this.closeIt = function () {
		this._reset_attach_button();
		this.wrapper.classList.remove('activate');
	};

	this.hideNotify = function (event) {
		jQuery('#feed-me .feedme-notify').hide().removeClass('success error info').find('p').text('');

		if (typeof event.target.reset == 'function') {
			event.target.reset();
		}

		instance.close(event);
	}

	this.showNotify = function (notify_class, notify_message) {
		jQuery('#feed-me .feedme-notify').removeClass('success error info').addClass(notify_class).show().find('p').text(notify_message);
	}

	this.pre_submit = function (event) {
		this.showNotify('info', sending_suprise_message[Math.floor(Math.random() * sending_suprise_message.length)]);

		var ajax = new XMLHttpRequest();

		ajax.open(this.form.getAttribute('method'), this.form.getAttribute('data-ajax-url'), true);

		ajax.onreadystatechange = function () {

			if (ajax.readyState == ajax.DONE) {
				if (ajax.status == 200) {
					try {
						var response = JSON.parse(ajax.responseText);
					} catch (e) {
						console.log('error parsing', ajax.responseText);
					}

					if (!response) {
						return;
					}

					if (response['ok']) {
						this.showNotify('success', '¡Gracias por tu tiempo. hemos recibido tu feedback correctamente!');
						event.closeForm = true;
						setTimeout(this.hideNotify.bind(null, event), 3000);
					} else {
						alert(response['msg']);
					}
				} else {
					this.showNotify('error', "Error en la conexión. Inténtelo de nuevo en unos minutos");
					setTimeout(this.hideNotify.bind(null, event), 3000);
				}
			}
		}.bind(this);

		var form_data = new FormData(this.form);

		unir_feedme_enviorment = "\n" +
			'* JS currentURL: ' + document.location.href +
			'* userAgent: ' + navigator.userAgent +
			'* platform: ' + navigator.platform +
			'* language: ' + navigator.language +
			'* cookies?: ' + navigator.cookieEnabled;

		form_data.append('feed-me[enviroment]', unir_feedme_enviorment.toString());
		form_data.append('feed-me[jsbacktrace]', unir_feedme_errors.toString());
		ajax.send(form_data);

		event.preventDefault();
		return false;
	};

	this._attach_button_clicked = function (event) {
		event.preventDefault();
		if (this.form.attachment.value) {
			this._reset_attach_button();
		} else {
			this.form.attachment.click()
		}
	};

	this._attachment_changed = function (event) {
		event.preventDefault();
		if (this.form.attachment.value) {
			this.attachment_button_text = jQuery("#attachment_button").html();
			jQuery("#attachment_button").html(this.form.attachment.value.replace(/^.*[\\\/]/, '') + "  | Eliminar");
		} else {
			jQuery("#attachment_button").html(this.attachment_button_text)
		}
	};

	this._reset_attach_button = function () {
		this.form.attachment.value = "";
		this.form.attachment.dispatchEvent(new Event('change'));
	};
};


window.addEventListener('DOMContentLoaded', function () {
	var feedme = new UNIR.Feedme;
	feedme.init();
});

var unir_feedme_errors = '';
window.onerror = function (msg, url, lineNo, columnNo) {
	unir_feedme_errors = unir_feedme_errors +
		"\n" +
		'\n message error: ' + msg +
		'\n url error: ' + url +
		'\n lineNo error: ' + lineNo +
		'\n columnNo error: ' + columnNo + '\n';
};

