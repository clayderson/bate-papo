$(document).ready(function() {
	$.cookie('user') === undefined ? userRegister() : userLogin();

	btnSubmit.click(function() {
		const messsage = inputMessage.val().replace(/^\s+/g, '');
		if (messsage.length) {
			inputMessage.val('');
			sendMessage(messsage);
		}
	});

	inputMessage.on('keyup', function(event) {
		if (event.keyCode == 13) {
			btnSubmit.click();
		}
	});

	function fatalError(errorMessage) {
		$.removeCookie('user');
		window.location.reload(true);
	}

	function sendMessage(message) {
		const formData = {
			roomId: runtime.room.id,
			userToken: runtime.user.token,
			message: message
		};

		console.log(formData);

		$.ajax({
			url: `${runtime.api.url}/v1/messages`,
			method: 'POST',
			dataType: 'json',
			data: formData,
			success: function(response) {},
			error: function(jqXHR) {
				if (jqXHR.status !== 400) {
					setTimeout(function() {
						sendMessage(message);
					}, 2000);
					alertContainer.show(300);
				}
			}
		});
	}

	function getMessages() {
		$.ajax({
			url: `${runtime.api.url}/v1/messages/${runtime.room.id}`,
			contentType: 'application/json; charset=UTF-8',
			method: 'GET',
			dataType: 'json',
			success: function(roomMessages) {
				let youOrHe = null;
				for(let id in roomMessages) {
					runtime.lastId = id;
					youOrHe = roomMessages[id]['userId'] === runtime.user.id ? 'you' : 'he';
					messagesContainer.append(`<div class="${youOrHe}"><p title="${roomMessages[id]['createdAt']}"><span class="nickname">${roomMessages[id]['userNickname']}</span>${roomMessages[id]['message']}</p></div>`);
				}

				messagesContainer.append(`<div class="system"><p>Este é um projeto experimental e <strong>suas mensagens poderão ser lidas por qualquer pessoa que possuir o link desta sala</strong>. Novos visitantes só poderão ler mensagens enviadas nos últimos 15 minutos.</p></div>`);
				messagesContainer.append(`<div class="system"><p>Chame seus amigos pra bater um papo enviando esse link para eles &#187; <a href="${runtime.room.url}" style="word-wrap: break-word;">${runtime.room.url}</a> ou inicie um novo chat clicando <a href="/" target="_blank" title="Abrir um novo chat em uma aba separada">aqui</a>.</p></div>`);

				messagesContainer.show();
				messagesLoading.hide();
				inputMessage.prop('disabled', false);

				viewportContainer.animate({ scrollTop: viewportContainer.prop('scrollHeight') }, 500);

				setInterval(function() {
					getNewMessages();
				}, 1300);
			},
			error: function(jqXHR) {
				setTimeout(function() {
					getMessages(runtime.room.id);
				}, 2000);
			}
		});
	}

	function getNewMessages() {
		if (runtime.getingNewMessage === false) {
			$.ajax({
				url: `${runtime.api.url}/v1/messages/${runtime.room.id}/${runtime.lastId}/20`,
				contentType: 'application/json; charset=UTF-8',
				method: 'GET',
				dataType: 'json',
				beforeSend: function() {
					runtime.getingNewMessage = true;
				},
				complete: function() {
					runtime.getingNewMessage = false;
				},
				success: function(roomMessages) {
					let forceScroll = false;
					if (viewportContainer.scrollTop() + viewportContainer.prop('clientHeight') === viewportContainer.prop('scrollHeight')) {
						forceScroll = true;
					}

					let youOrHe = null;
					for(let id in roomMessages) {
						runtime.lastId = id;
						youOrHe = roomMessages[id]['userId'] === runtime.user.id ? 'you' : 'he';
						messagesContainer.append(`<div class="${youOrHe}"><p title="${roomMessages[id]['createdAt']}"><span class="nickname">${roomMessages[id]['userNickname']}</span>${roomMessages[id]['message']}</p></div>`);
					}

					if (forceScroll) {
						viewportContainer.animate({ scrollTop: viewportContainer.prop('scrollHeight') }, 500);
					}

					alertContainer.hide(300);
				},
				error: function(jqXHR) {
					alertContainer.show(300);
				}
			});
		}
	}

	function userRegister() {
		$.ajax({
			url: `${runtime.api.url}/v1/users`,
			method: 'POST',
			dataType: 'json',
			success: function(response) {
				if (setUserCredentials(response)) {
					getMessages();
				}
			},
			error: function(jqXHR) {
				fatalError('Falha ao tentar registrar usuário. A página será atualizada para uma nova tentativa.');
			}
		});
	}

	function userLogin() {
		if (JSON.parse($.cookie('user')).token === undefined) {
			fatalError('Falha ao tentar obter token do usuário. A página será atualizada.');
			return;
		}

		$.ajax({
			url: `${runtime.api.url}/v1/users/${JSON.parse($.cookie('user')).token}`,
			contentType: 'application/json; charset=UTF-8',
			method: 'GET',
			dataType: 'json',
			success: function(response) {
				if (setUserCredentials(response)) {
					getMessages();
				}
			},
			error: function(jqXHR) {
				fatalError('Falha ao tentar obter informações do usuário. A página será atualizada para uma nova tentativa.');
			}
		});
	}

	function setUserCredentials(data) {
		if (data.id !== undefined && data.token !== undefined) {
			$.cookie('user', JSON.stringify({ token: data.token }), { path: '/' });

			runtime.user.id = data.id;
			runtime.user.token = data.token;

			return true;
		}

		fatalError('Falha ao tentar definir cookie de credenciais do usuário. A página será atualizada.');
	}
});
