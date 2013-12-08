var express = require('express');
var app = express();
var server = require('http').createServer(app)
var io = require('socket.io').listen(server);
var cookie = require('cookie');
var serverConfiguration = require('./server.config.js');
var storeProvider = express.session.MemoryStore;
var sessionStorage = new storeProvider();

var componentManager = require('./component.manager.js');
var eventManager = require('./event.manager.js');

componentManager.set('io', io);
componentManager.set('eventManager', eventManager);
componentManager.set('config', serverConfiguration);

server.listen(serverConfiguration.port, serverConfiguration.host);

//  accept all connections from local server
io.set("origins", serverConfiguration.origin);

//  client
io.of('/client').authorization(function (handshakeData,accept) {

	if (!handshakeData.headers.cookie) {
		return accept('NO COOKIE TRANSMITTED', false);
	}

	handshakeData.cookie = cookie.parse(handshakeData.headers.cookie);

	var sid = handshakeData.cookie['PHPSESSID'];
	if (!sid) {
		return accept('Have no session id', false);
	}

	handshakeData.sid = sid;

	//  create write method
	handshakeData.writeSession = function (fn) {
		sessionStorage.set(sid, handshakeData.session, function () {
			fn();
		});
	};

	//  trying to get session
	sessionStorage.get(sid, function (err, session) {

		//  create session handler
		var createSession = function () {
			var sessionData = {
				sid : sid,
				cookie : handshakeData.cookie,
				user : {
					role : 'guest',
					id : null
				}
			};

			//  store session in session storage
			sessionStorage.set(sid, sessionData, function () {

				//  authenticate and authorise client
				handshakeData.session = sessionData;
				accept(null, true);
			});
		};

		//  check on errors or empty session
		if (err || !session) {
			if (!session) {

				//  create new session
				createSession();
			} else {

				//  not authorise client if errors occurred
				accept('ERROR: ' + err, false);
			}
		} else {
			if (!session) {
				createSession();
			} else {

				//  authorize client
				handshakeData.session = session;
				accept(null, true);
			}
		}
	});

}).on('connection', function (socket) {

	//  bind events to socket
	eventManager.client.bind(socket);
});

//  server
io.of('/server').authorization(function (data, accept) {
	if (data && data.address) {
		var found = false;
		for (var i in serverConfiguration.allowedServers) {
			if (serverConfiguration.allowedServers[i] == data.address.address) {
				found = true;
				break;
			}
		}
		if (found) {
			accept(null, true);
		} else {
			accept('INVALID SERVER: server host ' + data.address.address + ' not allowed');
		}
		return
	} else {
		accept('NO ADDRESS TRANSMITTED.', false);
		return false;
	}
}).on('connection', function (socket) {

	//  bind events
	eventManager.server.bind(socket);
});

