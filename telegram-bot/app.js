// build-in
const path = require('path');
const fs = require('fs');
const url = require('url');

// package program
const packageJson = require('./package.json');
console.log(`\n=== Sell Logs Bot ${packageJson.version} ===\n`);

// config
const configFile = path.join(__dirname + '/config','config.main.yml');

// plugins
const yaml = require('yaml');
const request = require('request');
const mongoose = require('mongoose');
const Telegraf = require('telegraf');

const Markup = require('telegraf/markup');
const Stage = require("telegraf/stage");
const Scene = require('telegraf/scenes/base')
const session = require("telegraf/session");
const WizardScene = require("telegraf/scenes/wizard");
const { enter, leave } = Stage
const agent = require('socks5-https-client/lib/Agent')
const socksAgent = new agent({ socksHost: '178.197.248.49', socksPort: '1080' });
const config = yaml.parse(fs.readFileSync(configFile, 'utf8').replace(/\${__dirname}/g,__dirname.replace(/\\/g,'/')));
const bot = new Telegraf(config.bot.token);
// const xPub = 'xpub6DJgewN97wB2V52Sfmm6261runiSpsbkgrD5gsbR4MLMRvdWqwMpzT4sz4dsrUvXoTZTLDafwvXdbrQigwWS4S8CGzWV7KhrxdVzX2BhQw4';
// const openExchangeRatesAppID = 'a8a979c7e16a44b28b5eba758d6ab11c'; // to automatically convert USD amounts to BTC at real time rates
// const BitcoinGateway = require('bitcoin-receive-payments');

mongoose.connect('mongodb://localhost/shop',  { useNewUrlParser: true },  function (err) {
   if (err) throw err;
   console.log('[INFO] Database successfully connected');
});
mongoose.set('useFindAndModify', false);

const userSchema = new mongoose.Schema({
	_id: mongoose.Schema.Types.ObjectId,
	tgId: String,
	name: String,
	username: String,
	balance: Number
});
const logSchema = new mongoose.Schema({
	_id: mongoose.Schema.Types.ObjectId,
	values: Array,
	url: String,
	price: Number,
	country: String
});
const orderSchema = new mongoose.Schema({
	_id: mongoose.Schema.Types.ObjectId,
	buyerId: String,
	buyerUsername: String,
	query: String,
	url: String,
	price: Number,
	country: String
});
const transactionSchema = new mongoose.Schema({
	_id: mongoose.Schema.Types.ObjectId,
	userId: String,
	btcWallet: String,
	btcAmount: Number,
	dollarsAmount: Number
});
const paymentSchema = new mongoose.Schema({
	_id: mongoose.Schema.Types.ObjectId,
	userId: String,
	pcs: Number,
	country: String,
	price: Number,
	priceBtc: Number,
	logUrl: String
});

// params
let User = mongoose.model('users', userSchema);
let Log = mongoose.model('logs', logSchema);
let Order = mongoose.model('orders', orderSchema);
let lastUpdate = `${(new Date).getFullYear()}-${(new Date).getMonth()}-${(new Date).getDate()} ${(new Date).getHours()}:${(new Date).getMinutes()}`;
let logsAvailable = 0;

// Emoji: 💲💸₿📅📅📩📦🔍

async function syncLogs() {
	let logs = fs.readFileSync('logs.txt', 'utf8').split('\r\n');
	logs.forEach(log => {
		log = log.split('|');
		let newLog = new Log ({
			_id: new mongoose.Types.ObjectId(),
			values: log[0].split(';'),
			url: log[1],
			price: log[2],
			country: log[3]
		});
		newLog.save(function(err) {
			if (err) throw console.log(`[ERROR] Can't add new user to database: ${err}`);
			console.log(`[INFO] New log added`);
		});
	})
}

const welcomeScene = new WizardScene(
	'welcome',
	async ctx => {
		try {
			ctx.editMessageText(
				`Добро пожаловать. Снова.\n` +
				`Здесь ты можешь купить логи по запросам.\n\n` +
				`📥 <b>Последнее пополнение базы:</b> <code>${ctx.session.lastUpdate}\n</code>` +
				`🗂 <b>Всего логов в базе:</b> <code>${ctx.session.logsAvailable}\n</code>` +
				`💳 <b>Баланс кошелька:</b> <code>\$${ctx.session.balance}</code>`, {
					parse_mode: 'html',
					reply_markup: JSON.stringify({
						inline_keyboard: [
							[{ text: 'Кошелек', callback_data: 'wallet_deposit' }],
							[{ text: 'Покупка логов', callback_data: 'buy_logs' }],
							[{ text: 'Связаться с нами', callback_data: 'get_contact' }]
						]
					})
				}
			)
		} catch (err) {
			if (ctx.session.userId == undefined) {
				await User.findOne({ tgId: `${ctx.update.message.from.id}` }, function(err, user) {
					if (user == null) {
						let newUser = new User ({
							_id: new mongoose.Types.ObjectId(),
							tgId: ctx.update.message.from.id,
							name: ctx.update.message.from.first_name,
							username: ctx.update.message.from.username,
							balance: 0
						});
						newUser.save(function(err) {
							if (err) throw console.log(`[ERROR] Can't add new user to database: ${err}`);
							console.log(`[INFO] <${newUser.username}> registered`);
						});
						ctx.session.userId = newUser._id.toString();
						ctx.session.tgId = ctx.update.message.from.id;
						ctx.session.name = newUser.name;
						ctx.session.username = newUser.username;
						ctx.session.balance = newUser.balance;
					} else {
						if (err) throw console.log(`[ERROR] Can't find user in database: ${err}`);
						ctx.session.userId = user._id.toString();
						ctx.session.tgId = user.tgId;
						ctx.session.name = user.name;
						ctx.session.username = user.username;
						ctx.session.balance = user.balance;
						console.log(`[INFO] <${ctx.session.username}> logged`);
					}
				});
				await Log.countDocuments({}, function(err, count) {
					if (err) throw console.log(`[ERROR] Can't get logs count: ${err}`);
					ctx.session.logsAvailable = count;
				});
				
				// TODO: Get <logs> last modified date
				// ...
				
			};
			ctx.reply(
				`Добро пожаловать!\n` +
				`Здесь ты можешь купить логи по запросам.\n\n` +
				`📥 <b>Последнее пополнение базы:</b> <code>${ctx.session.lastUpdate}\n</code>` +
				`🗂 <b>Всего логов в базе:</b> <code>${ctx.session.logsAvailable}\n</code>` +
				`💳 <b>Баланс кошелька:</b> <code>\$${ctx.session.balance}</code>`, {
					parse_mode: 'html',
					reply_markup: JSON.stringify({
						inline_keyboard: [
							[{ text: 'Кошелек', callback_data: 'wallet_deposit' }],
							[{ text: 'Покупка логов', callback_data: 'buy_logs' }],
							[{ text: 'Связаться с нами', callback_data: 'get_contact' }]
						]
					})
				}
			)
		}
		return ctx.scene.leave();
	}
);

const logsScene = new WizardScene(
	'logs',
	ctx => {
		console.log(`[INFO] <${ctx.session.username}> open Logs Page`);
		ctx.editMessageText(
			`Выберите нужный вам запрос для поиска!`,
			Markup.inlineKeyboard([
				[Markup.callbackButton('Amazon', 'amazon')],
				[Markup.callbackButton('eBay', 'ebay')],
				[Markup.callbackButton('PayPal', 'paypal')],
				[Markup.callbackButton('Crypto', 'crypto')],
				[Markup.callbackButton('Назад', 'back')]
			]).extra()
		);
		return ctx.wizard.next();
	},
	async ctx => {
		if (ctx.update.callback_query != undefined) {
			ctx.wizard.state.query = ctx.update.callback_query.data;
			//await syncLogs();
			// TODO: Search <logs> values in database by query
			// ...
			
			
			Log.find({
				values: { $all: [ `${ctx.wizard.state.query}.com` ] }
			}).exec(function(err, logs) {
				if (err) throw err;
				
				ctx.wizard.state.urlList = [];
				ctx.wizard.state.urls = ``;
				let results = ``;
				let outputList = {};
				let countries = [];
				let count = [];
				logs.forEach( function( item ) { 
					let country = outputList[item.country] = outputList[item.country] || {} 
					ctx.wizard.state.urlList.push(item.url);
				});
				for( var country in outputList ) { countries.push({ country: country }) };
				
				for (let i = 0; i < countries.length; i++) {
					countries[i] = countries[i].country;
					count[i] = logs.filter(value => value.country === countries[i]).length;
					results += `<b>${countries[i]}:</b> ${count[i]} · `;
				}
				
				if (logs.length == 0) {
					ctx.editMessageText(
						`По указанному запросу логи не найдены. Попробуйте другой запрос или вернитесь позднее.`, {
							parse_mode: 'html',
							reply_markup: JSON.stringify({
								inline_keyboard: [
									[{ text: 'Вернуться', callback_data: 'back' }]
								]
							})
						}
					);
					ctx.wizard.back();
				} else {
					ctx.editMessageText(
						`Найдено ${logs.length} логов по запросу <code>${ctx.wizard.state.query}.com</code>\n\n${results.substring(0,results.length-3)}\n\n💎 <b>Цена 1 лога:</b> <code>\$${logs[0].price}</code>\n💰 <b>Ваш баланс:</b> <code>\$${ctx.session.balance}</code>\n\nВведите нужное число логов и страну для покупки.\nПример: <b>5 US</b>`, {
							parse_mode: 'html',
							reply_markup: JSON.stringify({
								inline_keyboard: [
									[{ text: 'Вернуться', callback_data: 'back' }]
								]
							})
						}
					);
					ctx.wizard.state.countries = countries;
					ctx.wizard.state.countriesCount = count;
				}
			});
			
			return ctx.wizard.next();
		} else if (ctx.message.text) {
		
			ctx.reply(`Функционал ручного поиска на данный момент отключён.`);
			return ctx.scene.leave();
			
			ctx.wizard.state.query = ctx.message.text;
			// TODO: Search <logs> values in database by query (manual input)
			// ...
			
			ctx.reply(
				`Найдено ${logs.length} логов по запросу <code>${ctx.wizard.state.query}</code>\n\n${results.substring(0,results.length-3)}\n\n💎 <b>Цена 1 лога:</b> <code>$5</code>\n💰 <b>Ваш баланс:</b> <code>\$${ctx.session.balance}</code>\n\nВведите нужное число логов и страну для покупки.\nПример: <b>5 US</b>`, {
					parse_mode: 'html',
					reply_markup: JSON.stringify({
						inline_keyboard: [
							[{ text: 'Вернуться', callback_data: 'back' }]
						]
					})
				}
			);
			
		};
		
	},
	ctx => {
		ctx.wizard.state.pcs = ctx.update.message.text.split(' ')[0];
		ctx.wizard.state.country = ctx.update.message.text.split(' ')[1];
		
		let correctCountry = false;
		let correctCount = false;
		console.log(`urlList: ${ctx.wizard.state.urlList}`);
		for (let i = 0; i < ctx.wizard.state.urlList.length; i++) {
			if (ctx.wizard.state.countries[i] == ctx.wizard.state.country) {
				correctCountry = true;
				if (ctx.wizard.state.pcs <= ctx.wizard.state.countriesCount[i]) {
					
					correctCount = true;
					ctx.wizard.state.urls += `${ctx.wizard.state.urlList[i]}\n`;
					console.log(`added to cart: ${ctx.wizard.state.urlList[i]}`);
					//continue;
				}
			}
			console.log(`url: ${ctx.wizard.state.urlList[i]}`);
		}
		
		if ((ctx.wizard.state.pcs == undefined) || (ctx.wizard.state.country == undefined)) {
			ctx.reply(`⚠ Неверный формат данных. Пожалуйста, повторите запрос.`);
			ctx.wizard.back();
		} else if (!correctCountry) {
			ctx.reply(`⚠ Указанной страны нет в списке. Пожалуйста, повторите запрос.`);
			ctx.wizard.back();
		} else if (!correctCount) {
			ctx.reply(`⚠ Указанное количество логов недоступно по этой стране. Пожалуйста, повторите запрос.`);
			ctx.wizard.back();
		} else {
			ctx.wizard.state.price = ctx.wizard.state.pcs * 5;
			if ((ctx.session.balance - ctx.wizard.state.price) >= 0) {
				ctx.reply(
					`Заказ оформлен. \n\nЗапрос: ${ctx.wizard.state.query}\nСтрана: ${ctx.wizard.state.country}\nКоличество: ${ctx.wizard.state.pcs} шт.\nИтоговая стоимость: \$${ctx.wizard.state.price}\n\nПодтвердите покупку.`, {
						parse_mode: 'html',
						reply_markup: JSON.stringify({
							inline_keyboard: [
								[{ text: 'Отмена', callback_data: 'cancel' }],
								[{ text: 'Ок', callback_data: 'ok' }]
							]
						})
					}
				);
			} else {
				ctx.reply(
					`Недостаточно средств на балансе. Не хватает \$${ctx.wizard.state.price - ctx.session.balance} для проведения оплаты. Пополните счёт и возвращайтесь!`, {
						parse_mode: 'html',
						reply_markup: JSON.stringify({
							inline_keyboard: [
								[{ text: 'Вернуться', callback_data: 'back' }],
							]
						})
					}
				);
				ctx.wizard.back();
				//return ctx.scene.leave();
				}
		}
		return ctx.wizard.next();
	},
	async ctx => {
		if (ctx.update.callback_query.data == 'ok') {
			ctx.session.balance = ctx.session.balance - ctx.wizard.state.price;
			
			await User.findById(ctx.session.userId, function(err, user) {
				if (err) throw console.log(`[ERROR] Can't find user in database: ${err}`);				
				user.balance = ctx.session.balance;
				user.save(function(err) {
					if (err) throw console.log(`[INFO] <${ctx.session.username}> payment error`);;
					console.log(`[INFO] <${ctx.session.username}> completed payment: ${ctx.wizard.state.pcs} ${ctx.wizard.state.country}`);
					
					// TODO: Get log url for output
					// ...
					
					ctx.editMessageText(
						`Ваш заказ оплачен!\n\nЗапрос: ${ctx.wizard.state.query}\nСтрана: ${ctx.wizard.state.country}\nКоличество: ${ctx.wizard.state.pcs} шт.\n\nСкачать: ${ctx.wizard.state.urls}`, {
							parse_mode: 'html',
							reply_markup: JSON.stringify({
								inline_keyboard: [
									[{ text: 'Вернуться', callback_data: 'back' }]
								]
							})
						}
					);
				});
			});
			ctx.wizard.state.urls = ctx.wizard.state.urls.substring(0,ctx.wizard.state.urls.length-1);
			//console.log(`${ctx.wizard.state.urls}`);
			//console.log(`${ctx.wizard.state.urls.split(/\n/).length} urls = logs`);
			//console.log(`${ctx.wizard.state.urls.split(/\n/)}.toString()`);
			//process.exit();
			for (let i = 0; i < ctx.wizard.state.urls.split(/\n/).length; i++) {
				Log.findOneAndRemove({'url' : ctx.wizard.state.urls.split(/\n/)[i]}, function (err){ 
					let order = new Order ({
						_id: new mongoose.Types.ObjectId(),
						buyerId: ctx.session.tgId,
						buyerUsername: ctx.session.username,
						query: ctx.wizard.state.query,
						url: ctx.wizard.state.urls.split(/\n/).toString(),
						price: ctx.wizard.state.price,
						country: ctx.wizard.state.country
					});
					order.save(function(err) {
						if (err) throw console.log(`[ERROR] Can't save order to database: ${err}`);
					});
					console.log(order);
				});
				
				
			}
		} else if (ctx.update.callback_query.data == 'cancel') {
			ctx.editMessageText(
				`Заказ аннулирован\n\nЗапрос: ${ctx.wizard.state.query}\nСтрана: ${ctx.wizard.state.country}\nКоличество: ${ctx.wizard.state.pcs} шт.\n\n⚠ Покупка отменена!`, {
					parse_mode: 'html',
					reply_markup: JSON.stringify({
						inline_keyboard: [
							[{ text: 'Вернуться', callback_data: 'back' }]
						]
					})
				}
			);
		}
	return ctx.scene.leave();
	}
);

// Making Deposit Wizard
const walletScene = new WizardScene(
	'wallet',
	ctx => {
		console.log(`[INFO] <${ctx.session.username}> open Wallet Page`);
		ctx.editMessageText(
			`💰 <b>Ваш баланс:</b> <code>\$${ctx.session.balance}</code>\n\nДля пополнения счета отправьте боту желаемую сумму пополнения в USD (числом в данный чат).`, {
				parse_mode: 'html',
				reply_markup: JSON.stringify({
					inline_keyboard: [
						[{ text: 'Назад', callback_data: 'back' }]
					]
				})
			}
		);
		return ctx.wizard.next();
	},
	ctx => {
		ctx.wizard.state.amount = ctx.update.message.text;

		if ((ctx.wizard.state.amount == undefined) || (isNaN(ctx.wizard.state.amount))) {
			ctx.reply(`⚠ Неверный формат. Попробуйте снова`);
			ctx.wizard.back();
		} else {
			
			// TODO: Generating BTC wallet
			// API ID 56776fa3-a99f-4e65-9f9a-132f70c447ba 
			// opennode.setCredentials('56776fa3-a99f-4e65-9f9a-132f70c447ba ');
			// TODO: Calculating currency rate BTC-USD
			// ...
			
			ctx.reply(
				`Для вас сгенерирован Bitcoin кошелек:\n<code>%wallet%</code>\nДля пополнения счета на <code>\$${ctx.wizard.state.amount}</code>, переведите <code>%btc% BTC</code> на указанный кошелек.\n\nЦена Bitcoin (по данным с биржи):\n<code>%currency_rate%</code>\n\n⚠ Убедительная просьба переводить средства за одну транзакцию для автоматического зачисления.`, {
					parse_mode: 'html',
					reply_markup: JSON.stringify({
						inline_keyboard: [
							[{ text: 'Проверить платеж', callback_data: 'check_payment' }],
							[{ text: 'Отменить и вернуться', callback_data: 'back' }]
						]
					})
				}
			);
			return ctx.wizard.next();
		}		
	},
	ctx => {
		return ctx.scene.leave();
	}
	
);

const contactScene = new WizardScene(
	'contact',
	ctx => {
		console.log(`[INFO] <${ctx.session.username}> open Contact Page`);
		ctx.editMessageText(
			`Задайте интересующий вас вопрос в этом диалоге и он будет перенаправлен оператору.\n\nПосле рассмотрения обращения с вами свяжутся при необходимости.`, 
			Markup.inlineKeyboard([
				Markup.callbackButton('Назад', 'back'),
			]).extra()
		);
		return ctx.wizard.next();
	},
	ctx => {
		console.log(`[MESSAGE] <${ctx.session.username}> ${ctx.update.message.text}`);
		ctx.reply(
			`Обращение зафиксировано`, 
			Markup.inlineKeyboard([
				Markup.callbackButton('Вернуться', 'back'),
			]).extra()
		);
		return ctx.scene.leave();
	}
);

// Scene registration
const stage = new Stage([walletScene, logsScene, welcomeScene, contactScene], {ttl: 300});
stage.action('back', enter('welcome'));

bot.use(session());
bot.use(stage.middleware());
bot.start(enter('welcome'));

bot.action('wallet_deposit', enter('wallet'));
bot.action('buy_logs', enter('logs'));
bot.action('get_contact', enter('contact'));

bot.startPolling();